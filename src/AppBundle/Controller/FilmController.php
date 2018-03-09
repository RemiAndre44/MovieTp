<?php

namespace AppBundle\Controller;

use AppBundle\Entity\app_user;
use AppBundle\Entity\critique;
use AppBundle\Entity\Genre;
use AppBundle\Entity\Movie;
use AppBundle\Entity\People;
use AppBundle\Form\app_userType;
use AppBundle\Form\critiqueType;
use AppBundle\Repository\MovieRepository;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class FilmController extends Controller
{
    public function indexAction($name)
    {
        return $this->render('', array('name' => $name));
    }

    public function homeAction(Request $request)
    {
        $repo= $this->getDoctrine()->getRepository(Movie::class);

        $movieRepo=$repo->findAll();

        $reGenre=$this->getDoctrine()->getRepository(Genre::class);
        $listeGenre=$reGenre->findAll();
        $yearRepo=$repo->findYear();

        if ($request->isMethod("POST") && $request->request->get("recherche")) {
            $recherche=$request->request->get("recherche");
            $repo=$this->getDoctrine()->getRepository(Movie::class);
            $movieRepo=$repo->triparRecherche($recherche);
            //var_dump($movieRepo);

        }


        else if ($request->isMethod("POST")) {
            $categorie= $request->request->get("cat");
            $anneeMin=$request->request->get("anneemin");
            //var_dump($anneeMin);
            $anneemax=$request->request->get("anneemax");
            $repo = $this->getDoctrine()->getRepository(Movie::class);

            $movieRepo = $repo->triParCatégorie($categorie,$anneeMin,$anneemax);

        }



        return $this->render('default/home.html.twig', [
            "movies"=>$movieRepo,
            "listeGenre"=>$listeGenre,
            "listeYear"=>$yearRepo
        ]);

    }

    public function detailAction($id, Request $request){
        $repo= $this->getDoctrine()->getRepository(Movie::class);

        $detailRepo= $repo->findOneByImdbId($id);

        $critique = new critique();
        $critique->setDateCreated(new\DateTime());
        $critique->setMovie($detailRepo);
        $critique->setUsers($this->getUser());
        $username=$this->getUser()->getUsername();

        $reCritique= $this->getDoctrine()->getRepository(critique::class);
        $listeCritique=$reCritique->findBy(["movie"=> $detailRepo],["datecreated"=>"DESC"],10,0);

        $commentForm = $this->createForm(critiqueType::class, $critique);

        $commentForm->handleRequest($request);

        if ($commentForm->isSubmitted() && $commentForm->isValid()){

            $em = $this->getDoctrine()->getManager();
            $em->persist($critique);
            $em->flush();

            $this->addFlash("success", "Merci pour votre critique !");

            return $this->redirectToRoute("home"/*, [
                $id
            ]*/);
        }

        return $this->render("default/detail.html.twig", [
            $id,
            "movies"=>$detailRepo,
            "commentForm"=>$commentForm->createView(),
            "critique"=>$listeCritique,
            "username"=>$username,

        ]);
    }

    public function registerAction(Request $request){
        $encoder=$this->get("security.password_encoder");
        //créer un nouveau user vide
        $user=new app_user();
        //créer le formulaire en l'associant à l'user vide
        $registerForm= $this->createForm(app_userType::class, $user);

        //prend les données de la requete et les injecte dans notre user vide
        $registerForm->handleRequest($request);
        //si le formulaire est soumis et valide
        if($registerForm->isSubmitted()&& $registerForm->isValid()) {
            //hash le mot de passe
            $hash=$encoder->encodePassword($user, $user->getPassword());
            //remplace le mot de passe par le hash
            $user->setPassword($hash);

            $em= $this->getDoctrine()->getManager();
            $em->persist($user);
            $em->flush();

            $this->addFlash("success", "Bienvenue");

            return $this->redirectToRoute("home");

        }

        //affiche la page
        return $this->render("default/register.html.twig", [
            //passe le formulaire vide à twig pour l'affichage
            "registerForm"=>$registerForm->createView()
        ]);

    }


    public function loginAction(AuthenticationUtils $authUtils)
    {
        $authenticationUtils = $this->get('security.authentication_utils');

        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();

        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render(
            'default/login.html.twig',
            array(
                // last username entered by the user
                'last_username' => $lastUsername,
                'error'         => $error,
            )
        );
    }

    public function peopleAction($id,Request $request){
        $repo= $this->getDoctrine()->getRepository(Movie::class);
        //var_dump($id);

        $reGenre=$this->getDoctrine()->getRepository(Genre::class);
        $listeGenre=$reGenre->findAll();
        $yearRepo=$repo->findYear();

        $repo= $this->getDoctrine()->getRepository(Movie::class);

        $movieRepo=$repo->lienPeople($id);

        //var_dump($movieRepo);

        if ($request->isMethod("POST") && $request->request->get("recherche")) {
            $recherche=$request->request->get("recherche");
            $repo=$this->getDoctrine()->getRepository(Movie::class);
            $movieRepo=$repo->triparRecherche($recherche);
            var_dump($movieRepo);

        }


        else if ($request->isMethod("POST")) {
            $categorie= $request->request->get("cat");
            $anneeMin=$request->request->get("anneemin");
            //var_dump($anneeMin);
            $anneemax=$request->request->get("anneemax");
            $repo = $this->getDoctrine()->getRepository(Movie::class);

            $movieRepo = $repo->triParCatégorie($categorie,$anneeMin,$anneemax);

        }

        return $this->render('default/home.html.twig', [
            "movies"=>$movieRepo,
            "listeGenre"=>$listeGenre,
            "listeYear"=>$yearRepo
        ]);

    }
}
