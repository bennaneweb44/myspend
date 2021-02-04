<?php

namespace App\Controller\Api;

use App\Entity\Charges;
use App\Repository\CategorieChargeRepository;
use App\Repository\ChargesRepository;
use DateTime;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

class ApiChargesController extends AbstractController
{
    private $chargesRepository;
    private $normalizerInterface;
    private $categorieChargeRepository;

    public function __construct(ChargesRepository $chargesRepository, CategorieChargeRepository $categorieChargeRepository, NormalizerInterface $normalizerInterface)
    {
        $this->chargesRepository = $chargesRepository;
        $this->categorieChargeRepository = $categorieChargeRepository;
        $this->normalizerInterface = $normalizerInterface;
    }
    
    public function index(): JsonResponse
    {
        // Get current mounth
        $month = date('m');        

        // Get all items of this mounth        
        $chargesMensuelles = $this->chargesRepository->getAllByMonth($month);        

        // Get all <Charges fixes>
        $categorie = $this->categorieChargeRepository->findOneBy(['id' => 1]);                
        $chargesFixes = $this->chargesRepository->getAllChargesFixes($categorie);

        // Json
        $chargesNormalises = $this->normalizerInterface->normalize(
            array_merge($chargesMensuelles, $chargesFixes), 
            'json', 
            ['groups' => ['charge:read', 'CategorieCharge:read']]
        );

        // Json response
        $response = new JsonResponse(json_encode($chargesNormalises), 200, [], true);
        
        return $response;
    }
    
    public function update($id, Request $request)
    {
        $entityManager = $this->getDoctrine()->getManager();        

        if ($request->getContent() && $id && is_numeric($id) && $id > 0) {
            $object = $request->getContent();
            $arrObject = json_decode($object, true);
            $arrCharge = $arrObject['charge'];

            // Charge
            $charge = $this->chargesRepository->findOneBy([
                'id' => $id
            ]);

            // Error
            if (!$charge || !($charge instanceof Charges)) {
                throw $this->createNotFoundException(
                    'Aucune charge trouvée pour l\'id : ' . $id
                );
            }
                
            // Posted values
            $updatedAt = new DateTime($arrCharge['updatedAt']);
            $libelle = $arrCharge['libelle'];
            $montant = $arrCharge['montant'];

            // Set data
            $charge->setUpdatedAt($updatedAt);
            $charge->setLibelle($libelle);
            $charge->setMontant($montant);

            // Update
            $entityManager->flush();

            // OK
            $response = new JsonResponse(json_encode([
                'message' => 'save_charge_ok'
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
            $arrCharge = $arrObject['charge'];

            // Posted values
            $createdAt = new DateTime($arrCharge['createdAt']);
            $libelle = $arrCharge['libelle'];
            $montant = $arrCharge['montant'];

            // Object
            $charge = new Charges();
            $charge->setCreatedAt($createdAt);
            $charge->setUpdatedAt($createdAt);
            $charge->setLibelle($libelle);
            $charge->setMontant($montant);
            // Catégorie
            $categorie = $this->categorieChargeRepository->findOneBy(['id' => 1]);
            $charge->setCategorie($categorie);

            // Save
            $entityManager->persist($charge);
            $entityManager->flush();

            // OK
            $response = new JsonResponse(json_encode([
                'message' => 'save_charge_ok'
            ]), 201, [], true);             

            return $response;
        }

        // KO
        $response = new JsonResponse(json_encode([
            'message' => 'entity_error'
        ]), 500, [], true); 

        return $response;
    }
}
