<?php

namespace App\Controller\Api;

use App\Entity\Charges;
use App\Entity\User;
use App\Repository\CategorieChargeRepository;
use App\Repository\ChargesRepository;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\Security;

class ApiChargesController extends AbstractController
{
    private $chargesRepository;
    private $normalizerInterface;
    private $categorieChargeRepository;
    private $entityManager;
    private $tokenStorage;
    private $user;

    public function __construct(ChargesRepository $chargesRepository, 
                                CategorieChargeRepository $categorieChargeRepository, 
                                NormalizerInterface $normalizerInterface,
                                EntityManagerInterface $entityManager,
                                TokenStorageInterface $tokenStorage,
                                Security $security)
    {
        $this->chargesRepository = $chargesRepository;
        $this->categorieChargeRepository = $categorieChargeRepository;
        $this->normalizerInterface = $normalizerInterface;
        $this->entityManager = $entityManager;
        $this->tokenStorage = $tokenStorage;        
        $this->user = $security->getUser();
    }
    
    public function index(): ?JsonResponse
    {
        if ($this->user instanceof User) {
            // Get all items of this mounth        
            $categorieVariables = $this->categorieChargeRepository->findOneBy(['id' => 2]);            
            $chargesMensuelles = $this->chargesRepository->getAllChargesVariablesByMonth($categorieVariables, $this->user);        

            // Get all <Charges fixes>
            $categorieFixes = $this->categorieChargeRepository->findOneBy(['id' => 1]);                
            $chargesFixes = $this->chargesRepository->getAllChargesFixes($categorieFixes, $this->user);

            // Json
            $union = array_merge($chargesMensuelles, $chargesFixes);
            $chargesNormalises = $this->normalizerInterface->normalize(
                $union, 
                'json', 
                ['groups' => ['charge:read', 'CategorieCharge:read']]
            );

            // Json response
            $response = new JsonResponse(json_encode($chargesNormalises), 200, [], true);
            
            return $response;

        } else {
            $this->killUserSessionAndRedirectToLogin();      
        }
        
        return null;
    }

    public function listByDate($annee, $mois): ?JsonResponse
    {
        if ($this->user instanceof User) {
            // Get all items of this mounth        
            $categorieVariables = $this->categorieChargeRepository->findOneBy(['id' => 2]);
            $chargesMensuelles = $this->chargesRepository->getAllChargesVariablesByMonth($categorieVariables, $this->user, $mois, $annee);

            // Get all <Charges fixes>
            $categorieFixes = $this->categorieChargeRepository->findOneBy(['id' => 1]);
            $chargesFixes = $this->chargesRepository->getAllChargesFixes($categorieFixes, $this->user);

            // Json
            $union = array_merge($chargesMensuelles, $chargesFixes);

            $chargesNormalises = $this->normalizerInterface->normalize(
                $union, 
                'json', 
                ['groups' => ['charge:read', 'CategorieCharge:read']]
            );

            // Json response
            $response = new JsonResponse(json_encode($chargesNormalises), 200, [], true);
            
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
            $arrCharge = $arrObject['charge'];

            // Charge
            $charge = $this->chargesRepository->findOneBy([
                'id' => $id
            ]);

            // Seul le titulaire peut modifier
            if (!$this->user instanceof User || $charge->getUser() != $this->user) {
                $this->killUserSessionAndRedirectToLogin();
            }

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
            $commentaires = $arrCharge['commentaires'];

            // Catégorie
            $idCategorie = $arrCharge['categorie'] == true ? 1 : 2;
            $categorie = $this->categorieChargeRepository->findOneBy(['id' => $idCategorie]);
            $charge->setCategorie($categorie);

            // Set data
            $charge->setUpdatedAt($updatedAt);
            $charge->setLibelle($libelle);
            $charge->setMontant($montant);
            $charge->setCommentaires($commentaires);

            // Update
            $this->entityManager->flush();

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
        if ($request->getContent()) {
            $object = $request->getContent();
            $arrObject = json_decode($object, true);
            $arrCharge = $arrObject['charge'];

            // Posted values
            $createdAt = new DateTime($arrCharge['createdAt']);
            $libelle = $arrCharge['libelle'];
            $montant = $arrCharge['montant'];
            $commentaires = $arrCharge['commentaires'];

            if (!$this->user instanceof User) {
                $this->killUserSessionAndRedirectToLogin();
            }

            // Object
            $charge = new Charges();
            $charge->setCreatedAt($createdAt);
            $charge->setUpdatedAt($createdAt);
            $charge->setLibelle($libelle);
            $charge->setMontant($montant);
            $charge->setCommentaires($commentaires);
            $charge->setUser($this->user);

            // Catégorie
            $idCategorie = $arrCharge['categorie'] == true ? 1 : 2;
            $categorie = $this->categorieChargeRepository->findOneBy(['id' => $idCategorie]);
            $charge->setCategorie($categorie);

            // Save
            $this->entityManager->persist($charge);
            $this->entityManager->flush();

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

    public function delete($id) 
    {
        // Get charge by id
        $charge = $this->chargesRepository->findOneBy(['id' => $id]);
        
        // Suppression
        if ($charge && $charge instanceof Charges) {

            // Seul le titulaire peut modifier
            if (!$this->user instanceof User || $charge->getUser() != $this->user) {
                $this->killUserSessionAndRedirectToLogin();
            }

            $this->entityManager->remove($charge);
            $this->entityManager->flush();
        } else {
            // KO
            throw $this->createNotFoundException('Aucune charge trouvée avec cet id : ' . $id);
        }

        // Deleted
        $response = new JsonResponse(json_encode([
            'message' => 'delete_charge_ok'
        ]), 200, [], true);             

        return $response;
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
