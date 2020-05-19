<?php
// src/Controller/WildController.php
namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

Class WildController extends AbstractController
{
    /**
     * @Route("wild/show/{slug}", name="show_slug", requirements={"slug"="[a-z0-9-]+$"}, defaults={"sulg"= null})
     * @param string $slug
     * @return Response
     */
    public function show(string $slug) : Response
    {
        if ($slug != null) {
            $replace = ucwords(str_replace('-', ' ',$slug));
        } else {
            $replace = "Aucune série sélectionnée, veuillez choisir une série !";
        }

        return $this->render('wild/show.html.twig', [
            'slug' => $replace
        ]);
    }

}