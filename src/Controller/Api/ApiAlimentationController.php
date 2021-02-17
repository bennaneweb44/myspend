<?php

namespace App\Controller\Api;

use App\Entity\Alimentation;
use App\Repository\CategorieAlimentationRepository;
use App\Repository\AlimentationRepository;
use DateTime;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

class ApiAlimentationController extends AbstractController
{
    private $alimentationRepository;
    private $normalizerInterface;
    private $categorieAlimentationRepository;

    public function __construct(AlimentationRepository $alimentationRepository, CategorieAlimentationRepository $categorieAlimentationRepository, NormalizerInterface $normalizerInterface)
    {
        $this->alimentationRepository = $alimentationRepository;
        $this->categorieAlimentationRepository = $categorieAlimentationRepository;
        $this->normalizerInterface = $normalizerInterface;
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
        $entityManager = $this->getDoctrine()->getManager();        

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

            // Catégorie
            $idCategorie = $arrAlimentation['categorie']['id'];
            $categorie = $this->categorieAlimentationRepository->findOneBy(['id' => $idCategorie]);
            $alimentation->setCategorie($categorie);

            // Set data
            $alimentation->setUpdatedAt($updatedAt);
            $alimentation->setLibelle($libelle);
            $alimentation->setMontant($montant);

            // Update
            $entityManager->flush();

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
        $entityManager = $this->getDoctrine()->getManager();        

        if ($request->getContent()) {
            $object = $request->getContent();

            $arrObject = json_decode($object, true);
            $arrAlimentation = $arrObject['alimentation'];

            // Posted values
            $createdAt = new DateTime($arrAlimentation['createdAt']);
            $libelle = $arrAlimentation['libelle'];
            $montant = $arrAlimentation['montant'];

            // Object
            $alimentation = new Alimentation();
            $alimentation->setCreatedAt($createdAt);
            $alimentation->setUpdatedAt($createdAt);
            $alimentation->setLibelle($libelle);
            $alimentation->setMontant($montant);

            // Catégorie
            $idCategorie = $arrAlimentation['idCategorie'];
            $categorie = $this->categorieAlimentationRepository->findOneBy(['id' => $idCategorie]);
            $alimentation->setCategorie($categorie);

            // Save
            $entityManager->persist($alimentation);
            $entityManager->flush();

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

    public function delete($id) 
    {
        $entityManager = $this->getDoctrine()->getManager();       

        // Get alimentation by id
        $alimentation = $this->alimentationRepository->findOneBy(['id' => $id]);
        
        // Suppression
        if ($alimentation && $alimentation instanceof Alimentation) {
            $entityManager->remove($alimentation);
            $entityManager->flush();
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

    public function getAllCategories() : JsonResponse
    {
        // Toutes les catégories
        $allCategories = $this->categorieAlimentationRepository->findAll();

        $allCategoriesNormalises = $this->normalizerInterface->normalize(
            $allCategories, 
            'json', 
            ['groups' => ['CategorieAlimentation:read']]
        );

        $allCategories = $allCategoriesNormalises;

        // Json response
        $response = new JsonResponse(json_encode($allCategories), 200, [], true);
        
        return $response;
    }
}
