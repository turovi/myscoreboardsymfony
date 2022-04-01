<?php

namespace App\Controller;

use stdClass;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class TestController extends AbstractController
{
    #[Route('/test', name: 'app_test')]

    public function index(): Response
    {
        return $this->render('test/index.html.twig', [
            'controller_name' => 'Bonjour votre magnificence', 'texte' => 'Le texte que je veux afficher'

        ]);
    }

    /* EXERCICES
    Ajouter une route pour le chemin "/test/calcul" qui utilise le fichier test/index.html.twig et qui affiche le résultat de 12 + 7 */

    #[Route('/test/calcul', name: 'app_calcul')]
    public function calcul()
    {
        $a = 12;
        $b = 7;
        return $this->render('test/index.html.twig', [
            'controller_name' => 'Bonjour votre magnificence', 'texte' => 'Le texte que je veux afficher',
            'calcul' => $a + $b
        ]);
    }

    #[Route('/test/salut')]
    public function salut()
    {
        return $this->render('test/salut.html.twig', ['prenom' => 'Matthieu']);
    }

    #[Route('/test/tableau')]
    public function tableau()
    {
        $tableau = ["bonjour", "je m'appelle", 789, true, 12, 38];
        return $this->render("test/tableau.html.twig", ["tableau" => $tableau]);
    }

    #[Route('/test/tableau-assoc')]
    public function tab()
    {
        $p = [
            "nom" => "Ienne",
            "prenom" => "César",
            "age" => 26
        ];

        return $this->render("test/assoc.html.twig", ["personne" => $p]);
    }

    #[Route('/test/objet')]
    public function objet()
    {
        $objet = new \stdClass;
        $objet->prenom = "Nordine";
        $objet->nom = "Ateur";
        $objet->age = 40;
        return $this->render("test/assoc.html.twig", ["personne" => $objet]);
    }
}
