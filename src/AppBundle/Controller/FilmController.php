<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Movie;
use AppBundle\Entity\People;
use AppBundle\Repository\MovieRepository;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

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





}
