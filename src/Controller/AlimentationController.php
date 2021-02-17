<?php

namespace App\Controller;

use App\Entity\CategorieAlimentation;
use App\Repository\AlimentationRepository;
use App\Repository\CategorieAlimentationRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AlimentationController extends AbstractController
{
    private $alimentationRepository;
    private $categorieAlimentationRepository;

    public function __construct(AlimentationRepository $alimentationRepository, CategorieAlimentationRepository $categorieAlimentationRepository)
    {
        $this->alimentationRepository = $alimentationRepository;
        $this->categorieAlimentationRepository = $categorieAlimentationRepository;
    }

    /**
     * @Route("/alimentation", name="alimentation_list")
     */
    public function index(): Response
    {
        // Generate empty view for Vue.js
        return $this->render('alimentation/index.html.twig');
    }
}
