<?php

namespace App\Controller;

use App\Repository\CategorieChargeRepository;
use App\Repository\ChargesRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ChargesController extends AbstractController
{
    private $chargesRepository;
    private $categorieChargesRepository;

    public function __construct(ChargesRepository $chargesRepository, CategorieChargeRepository $categorieChargeRepository)
    {
        $this->chargesRepository = $chargesRepository;
        $this->categorieChargesRepository = $categorieChargeRepository;
    }

    /**
     * @Route("/charges", name="charges_list")
     */
    public function index(): Response
    {
        // Generate empty view for Vue.js
        return $this->render('charges/index.html.twig');
    }
}
