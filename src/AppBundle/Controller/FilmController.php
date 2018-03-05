<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Movie;
use AppBundle\Repository\MovieRepository;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

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
}
