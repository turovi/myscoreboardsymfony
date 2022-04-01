<?php

namespace App\Controller;

use App\Repository\PlayerRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ProfilController extends AbstractController
{
    #[Route('/profil', name: 'app_profil')]
    public function index(PlayerRepository $pr): Response
    {


        return $this->render('profil/index.html.twig', [
            'controller_name' => 'ProfilController',
            
        ]);
    }


    
}
