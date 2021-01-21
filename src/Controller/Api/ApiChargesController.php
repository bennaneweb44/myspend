<?php

namespace App\Controller\Api;

use App\Repository\ChargesRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

class ApiChargesController extends AbstractController
{
    private $chargesRepository;
    private $normalizerInterface;

    public function __construct(ChargesRepository $chargesRepository, NormalizerInterface $normalizerInterface)
    {
        $this->chargesRepository = $chargesRepository;
        $this->normalizerInterface = $normalizerInterface;
    }
    
    public function index(): JsonResponse
    {
        // Get current mounth
        $month = date('m');        

        // Get all items of this mounth        
        $charges = $this->chargesRepository->getAllByMonth($month);        

        // Json
        $chargesNormalises = $this->normalizerInterface->normalize($charges, 'json', ['groups' => 'charge:read']);        

        // Json response
        $response = new JsonResponse(json_encode($chargesNormalises), 200, [], true);
        
        return $response;
    }
}
