<?php

namespace AppBundle\Controller;

use AppBundle\Entity\app_user;
use AppBundle\Entity\Movie;
use AppBundle\Entity\People;
use AppBundle\Form\app_userType;
use AppBundle\Repository\MovieRepository;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class FilmController extends Controller
{
    public function indexAction($name)
    {
        return $this->render('', array('name' => $name));
    }

    public function homeAction()
    {
        $repo= $this->getDoctrine()->getRepository(Movie::class);

        $movieRepo=$repo->findAll();



        return $this->render('default/home.html.twig', [
            "movies"=>$movieRepo,
        ]);

    }

    public function detailAction($id, Request $request){
        $repo= $this->getDoctrine()->getRepository(Movie::class);

        $detailRepo= $repo->findOneByImdbId($id);

        return $this->render('default/detail.html.twig', [
            "movies"=>$detailRepo,
        ]);
    }


    public function loginAction(){
        return $this->render('default/login.html.twig');
    }

    public function registerAction(Request $request, UserPasswordEncoderInterface $encoder){
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
}
