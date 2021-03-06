<?php

namespace App\Controller\Admin;

use App\Repository\GameRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Game;
use App\Form\GameType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;

class GameController extends AbstractController
{
    #[Route('/admin/game', name: 'app_admin_game')]
    public function index(GameRepository $gameRepository): Response
    {
        /* On ne PEUT PAS instancier d'objets d'une classe Repository, on doit les passer dans les arguments d'une méthode et d'un contrôle 
            NB : Pour chaque classe Entity crée, il y a une classe Repository
            qui correspond et qui permet de faire des requêtes SELECT sur la table correspondante */
        // $gameDepository = new GameRepository;
        return $this->render('admin/game/index.html.twig', [
            "games" => $gameRepository->findAll()
        ]);
    }

    #[Route('/admin/game/new', name: 'app_admin_game_new')]
    /*
    * La classe Request permet d'instancier un objet * qui contient toutes les valeurs des variables * superglobales de PHP.
    * Ces valeurs sont dans des propriétés (qui sont * des objets).
    * $request->query contient $_GET
    * $request->request contient $_POST
    * $request->server contient $_SERVER
    * ... etc
    * Pour accéder aux valeurs, on utilisera la 
    * méthode ->get('indice')
    * La classe EntityMangager va permettre d'exécuter les requêtes
     *  qui modifient les données (INSERT, UPDATE, DELETE).
     *  L'EntityManager va toujours utiliser des objets Entity pour 
     *  modifier les données.
    */
    public function new(Request $request, EntityManagerInterface $em)
    {

        $jeu = new Game;
        // On crée un objet $form pour gérer le formulaire. Il est crée à partir de la classe GameType. On relie ce formulaire à l'objet $jeu
        $form = $this->createForm(GameType::class, $jeu);
        // L'objet $form va gérer ce qui vient de la requête HTTP (avec l'objet $request)
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            // La méthode persist() prépare la requête INSERT avec les données de l'objet passé en argument
            $em->persist($jeu);

            // La méthode flush() exécute les requêtes en attente et donc modifie la base de données
            $em->flush();

            // Redirection vers une route du projet
            return $this->redirectToRoute("app_admin_game");
        }


        return $this->render("admin/game/form.html.twig", [
            "formGame" => $form->createView()
        ]);
    }

    #[Route('/admin/game/edit/{id}', name: 'app_admin_game_edit')]
    public function edit(Request $rq, EntityManagerInterface $em, GameRepository $gameRepository, $id)
    {
        $jeu = $gameRepository->find($id);
        $form = $this->createForm(GameType::class, $jeu);
        $form->handleRequest($rq);
        if ($form->isSubmitted() && $form->isValid()) {
            $em->flush();
            return $this->redirectToRoute("app_admin_game");
        }

        return $this->render("admin/game/form.html.twig", ["formGame" => $form->createView()]);
    }

    #[Route('/admin/game/modifier/{title}', name: 'app_admin_game_modifier')]
    // Si le chemin de la route contient une partie variable (donc entre {}), on peut récupérer une objet entité
    //  directement avec la valeur de cette partie de l'URL. Il faut que le nom de ce paramètre soit le nom d'une
    //  propriété de la classe Entity.
    //  Par exemple, le paramètre est {title}, parce que dans l'entité Game il y a une propriété title.
    //  Dans les arguments de la méthode, on peut alors utiliser un objet de la classe Game ($jeu dans l'exemple)
    public function modifier(Request $rq, EntityManagerInterface $em, Game $jeu)
    {
        // $jeu = $gameRepository->find($id);
        $form = $this->createForm(GameType::class, $jeu);
        $form->handleRequest($rq);
        if ($form->isSubmitted() && $form->isValid()) {
            $em->flush();
            return $this->redirectToRoute("app_admin_game");
        }

        return $this->render("admin/game/form.html.twig", ["formGame" => $form->createView()]);
    }

    /**
     * EXO 
     * 1. Créer une route app_admin_game_delete, qui prend l'id comme paramètre 
     * 2. Afficher les informations du jeu à supprimer avec une nouvelle vue
     *          Confirmation de suppression du jeu suivant :
     *              . titre
     *              . Entre nb_min et nb_max joueurs
     */


    #[Route('/admin/game/delete/{id}', name: 'app_admin_game_delete')]
    public function delete($id, GameRepository $gameRepository, Request $rq, EntityManagerInterface $em)
    {
        $jeu = $gameRepository->find($id);
        if ($rq->isMethod("POST")) {
            $em->remove($jeu);
            $em->flush();
            return $this->redirectToRoute("app_admin_game");
        }
        return $this->render("admin/game/delete.html.twig", ["game" => $jeu]);
    }
}
