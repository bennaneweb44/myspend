<?php

namespace App\Controller\Api;

use App\Entity\Alimentation;
use App\Entity\CategorieAlimentation;
use App\Repository\CategorieAlimentationRepository;
use App\Repository\AlimentationRepository;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

class ApiAlimentationController extends AbstractController
{
    private $alimentationRepository;
    private $normalizerInterface;
    private $categorieAlimentationRepository;
    private $entityManager;

    public function __construct(AlimentationRepository $alimentationRepository, 
                                CategorieAlimentationRepository $categorieAlimentationRepository, 
                                NormalizerInterface $normalizerInterface,
                                EntityManagerInterface $entityManager)
    {
        $this->alimentationRepository = $alimentationRepository;
        $this->categorieAlimentationRepository = $categorieAlimentationRepository;
        $this->normalizerInterface = $normalizerInterface;
        $this->entityManager = $entityManager;
    }
    
    public function index(): JsonResponse
    {
        // Get current mounth
        $month = date('m');        

        // Get all items of this mounth   
        $alimentations = $this->alimentationRepository->getAllByMonth($month);        

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
    }

    public function listByDate($annee, $mois) : JsonResponse
    {
        // Get all items of this mounth   
        $alimentations = $this->alimentationRepository->getAllByMonth($mois, $annee);   

        // Json        
        $alimentationsNormalises = $this->normalizerInterface->normalize(
            $alimentations, 
            'json', 
            ['groups' => ['alimentation:read', 'CategorieAlimentation:read']]
        );

        // Json response
        $response = new JsonResponse(json_encode($alimentationsNormalises), 200, [], true);
        
        return $response;
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

            // Object
            $alimentation = new Alimentation();
            $alimentation->setCreatedAt($createdAt);
            $alimentation->setUpdatedAt($createdAt);
            $alimentation->setLibelle($libelle);
            $alimentation->setMontant($montant);
            $alimentation->setCommentaires($commentaires);

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
}
