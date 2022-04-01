<?php

namespace App\Controller;

use App\Entity\Contest;
use App\Entity\Game;
use App\Form\CommencerPartieType;
use App\Repository\GameRepository;
use App\Repository\PlayerRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    #[Route('/', name: 'app_home')]
    public function affMenu(GameRepository $gameRepository, PlayerRepository $playerRepository, ): Response
    {   
        $winner = $playerRepository->findWinners();

        return $this->render('/home/index.html.twig', [
            "games" => $gameRepository->findAll(),
            "nb_joueurs" => count($playerRepository->findAll()),
            'winners' => $winner
        ]);
    }

    #[Route('/commencer-une-partie-de-{title}', name: 'app_home_contest')]
    public function commencer(Game $game, EntityManagerInterface $em, Request $rq)
    {
        $partie = new Contest;
        $partie->setGameId($game);
        $form = $this->createForm(CommencerPartieType::class, $partie);
        $form->handleRequest($rq);
        if ($form->isSubmitted() && $form->isValid()) {
            // $em = $this->getDoctrine()->getManager(); PEUT SERVIR A RECUPERER L'ENTITYMANAGER. MAINTENANT ON NE PASSE QUE PAR ENTITYMANAGERINTERFACE
            $em->persist($partie);
            $em->flush();
            $this->addFlash("success", "La nouvelle partie a bien été enregistrée");
            // $this->addFlash("success", "succès");
            // $this->addFlash("danger", "Message d'erreur");
            // $this->addFlash("info", "Message d'info");
            return $this->redirectToRoute("app_home");
        }
        return  $this->render("home/commencer.html.twig", [
            "form" => $form->createView()
        ]);
    }
}
