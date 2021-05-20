<?php

namespace App\Controller\Api;

use App\Entity\Alimentation;
use App\Entity\User;
use App\Entity\CategorieAlimentation;
use App\Repository\CategorieAlimentationRepository;
use App\Repository\AlimentationRepository;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\Security;

class ApiAlimentationController extends AbstractController
{
    private $alimentationRepository;
    private $normalizerInterface;
    private $categorieAlimentationRepository;
    private $entityManager;
    private $tokenStorage;
    private $user;

    public function __construct(AlimentationRepository $alimentationRepository, 
                                CategorieAlimentationRepository $categorieAlimentationRepository, 
                                NormalizerInterface $normalizerInterface,
                                EntityManagerInterface $entityManager,
                                TokenStorageInterface $tokenStorage,
                                Security $security)
    {
        $this->alimentationRepository = $alimentationRepository;
        $this->categorieAlimentationRepository = $categorieAlimentationRepository;
        $this->normalizerInterface = $normalizerInterface;
        $this->entityManager = $entityManager;
        $this->tokenStorage = $tokenStorage;        
        $this->user = $security->getUser();
    }
    
    public function index(): ?JsonResponse
    {
        if ($this->user instanceof User) {
            // Get current mounth
            $month = date('m');        

            // Get all items of this mounth   
            $alimentations = $this->alimentationRepository->getAllByMonth($this->user, $month);        

            // Json        
            $data = [];

            $alimentationsNormalises = $this->normalizerInterface->normalize(
                $alimentations, 
                'json', 
                ['groups' => ['alimentation:read', 'CategorieAlimentation:read']]
            );
            $data['alimentations'] = $alimentationsNormalises;

            // Toutes les catégories
            $allCategories = $this->categorieAlimentationRepository->findAll();

            $allCategoriesNormalises = $this->normalizerInterface->normalize(
                $allCategories, 
                'json', 
                ['groups' => ['CategorieAlimentation:read']]
            );

            $data['allCategories'] = $allCategoriesNormalises;

            // Json response
            $response = new JsonResponse(json_encode($data), 200, [], true);
            
            return $response;

        } else {
            $this->killUserSessionAndRedirectToLogin();
        }

        return null;        
    }

    public function listByDate($annee, $mois) : ?JsonResponse
    {
        if ($this->user instanceof User) {
            // Get all items of this mounth   
            $alimentations = $this->alimentationRepository->getAllByMonth($this->user, $mois, $annee);   

            // Json        
            $alimentationsNormalises = $this->normalizerInterface->normalize(
                $alimentations, 
                'json', 
                ['groups' => ['alimentation:read', 'CategorieAlimentation:read']]
            );

            // Json response
            $response = new JsonResponse(json_encode($alimentationsNormalises), 200, [], true);
            
            return $response;

        } else {
            $this->killUserSessionAndRedirectToLogin();
        }

        return null;        
    }

    public function update($id, Request $request)
    {
        if ($request->getContent() && $id && is_numeric($id) && $id > 0) {
            $object = $request->getContent();
            
            $arrObject = json_decode($object, true);
            $arrAlimentation = $arrObject['alimentation'];

            // Alimentation
            $alimentation = $this->alimentationRepository->findOneBy([
                'id' => $id
            ]);

            // Seul le titulaire peut modifier
            if (!$this->user instanceof User || $alimentation->getUser() != $this->user) {
                $this->killUserSessionAndRedirectToLogin();
            }

            // Error
            if (!$alimentation || !($alimentation instanceof Alimentation)) {
                throw $this->createNotFoundException(
                    'Aucune alimentation trouvée pour l\'id : ' . $id
                );
            }
                
            // Posted values
            $updatedAt = new DateTime($arrAlimentation['updatedAt']);
            $libelle = $arrAlimentation['libelle'];
            $montant = $arrAlimentation['montant'];
            $commentaires = $arrAlimentation['commentaires'];

            // Catégorie
            $idCategorie = $arrAlimentation['categorie']['id'];
            $categorie = $this->categorieAlimentationRepository->findOneBy(['id' => $idCategorie]);
            $alimentation->setCategorie($categorie);

            // Set data
            $alimentation->setUpdatedAt($updatedAt);
            $alimentation->setLibelle($libelle);
            $alimentation->setMontant($montant);
            $alimentation->setCommentaires($commentaires);

            // Update
            $this->entityManager->flush();

            // OK
            $response = new JsonResponse(json_encode([
                'message' => 'save_alimentation_ok'
            ]), 200, [], true);             

            return $response;
        }

        // KO
        $response = new JsonResponse(json_encode([
            'message' => 'entity_error'
        ]), 500, [], true); 

        return $response;
    }
    
    public function create(Request $request)
    {
        if ($request->getContent()) {
            $object = $request->getContent();

            $arrObject = json_decode($object, true);
            $arrAlimentation = $arrObject['alimentation'];

            // Posted values
            $createdAt = new DateTime($arrAlimentation['createdAt']);
            $libelle = $arrAlimentation['libelle'];
            $montant = $arrAlimentation['montant'];
            $commentaires = $arrAlimentation['commentaires'];

            if (!$this->user instanceof User) {
                $this->killUserSessionAndRedirectToLogin();
            }

            // Object
            $alimentation = new Alimentation();
            $alimentation->setCreatedAt($createdAt);
            $alimentation->setUpdatedAt($createdAt);
            $alimentation->setLibelle($libelle);
            $alimentation->setMontant($montant);
            $alimentation->setCommentaires($commentaires);
            $alimentation->setUser($this->user);

            // Catégorie
            $idCategorie = $arrAlimentation['idCategorie'];
            $categorie = $this->categorieAlimentationRepository->findOneBy(['id' => $idCategorie]);
            $alimentation->setCategorie($categorie);

            // Save
            $this->entityManager->persist($alimentation);
            $this->entityManager->flush();

            // OK
            $response = new JsonResponse(json_encode([
                'message' => 'save_alimentation_ok'
            ]), 201, [], true);             

            return $response;
        }

        // KO
        $response = new JsonResponse(json_encode([
            'message' => 'entity_error'
        ]), 500, [], true); 

        return $response;
    }

    public function delete($id): ?JsonResponse
    {
        if ($id && is_numeric($id) && $id > 0) {
            // Get alimentation by id
            $alimentation = $this->alimentationRepository->findOneBy(['id' => $id]);
            
            // Suppression
            if ($alimentation && $alimentation instanceof Alimentation) {

                // Seul le titulaire peut modifier
                if (!$this->user instanceof User || $alimentation->getUser() != $this->user) {
                    $this->killUserSessionAndRedirectToLogin();
                }

                $this->entityManager->remove($alimentation);
                $this->entityManager->flush();
            } else {
                // KO
                throw $this->createNotFoundException('Aucune alimentation trouvée avec cet id : ' . $id);
            }

            // Deleted
            $response = new JsonResponse(json_encode([
                'message' => 'delete_alimentation_ok'
            ]), 200, [], true);             

            return $response;
        }
        
        return null;
    }

    public function getAllCategories(): ?JsonResponse
    {
        // Toutes les catégories
        $allCategories = $this->categorieAlimentationRepository->findAll();

        if ($allCategories && count($allCategories)) {
            $allCategoriesNormalises = $this->normalizerInterface->normalize(
                $allCategories, 
                'json', 
                ['groups' => ['CategorieAlimentation:read']]
            );
    
            // Json response
            $response = new JsonResponse(json_encode($allCategoriesNormalises), 200, [], true);
            
            return $response;
        }

        return null;
    }

    /**************************************************
    ************ PRIVATE FUNCTIONS ********************
    ***************************************************/
    private function killUserSessionAndRedirectToLogin()
    {
        $this->tokenStorage->setToken(null);
        $this->redirectToRoute('app_login');
    }
}
