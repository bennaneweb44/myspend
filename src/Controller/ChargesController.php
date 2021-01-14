<?php

namespace App\Controller;

use App\Repository\CategorieChargeRepository;
use App\Repository\ChargesRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
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
     * @Route("/charges", name="charges")
     */
    public function index(): Response
    {
        // Get current mounth and year
        $month = date('m');
        $year = date('y');

        // Get all items of this mounth        
        $charges = $this->chargesRepository->getAllByMonth($month, $year);

        // Send data to view
        return $this->render('charges/index.html.twig', [
            'charges' => $charges
        ]);
    }
}
