<?php
// src/Controller/WildController.php
namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

Class WildController extends AbstractController
{
    /**
     * @Route("/", name="app_base")
     */
    public function index() : Response
    {
        return $this->render('wild/base.html.twig', [
            'title' => 'Bienvenue sur Wild Series !!!',
        ]);
    }

}