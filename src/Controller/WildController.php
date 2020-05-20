<?php
// src/Controller/WildController.php
namespace App\Controller;

use App\Entity\Category;
use App\Entity\Episode;
use App\Entity\Program;
use App\Entity\Season;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

Class WildController extends AbstractController
{
    /**
     * @Route("/wild", name="wild_index")
     * @return Response A response instance
     */
    public function index() :Response
    {
        $programs = $this->getDoctrine()
            ->getRepository(Program::class)
            ->findAll();
        if (!$programs) {
            throw $this->createNotFoundException(
                'No program found in program\'s table.'
            );
        }

        return $this->render('wild/index.html.twig', [
            'programs' => $programs
        ]);
    }

    /**
     * @Route("wild/show/{slug<^[a-z0-9-]+$>}", name="wild_show", defaults={"slug"= null})
     * @param $slug
     * @return Response
     */
    public function show($slug) : Response
    {
        /* === Quête 05 Symfony ===
        if ($slug != null) {
            $replace = ucwords(str_replace('-', ' ',$slug));
        } else {
            $replace = "Aucune série sélectionnée, veuillez choisir une série !";
        }*/

        if (!$slug) {
            throw $this
            ->createNotFoundException('No slug has been sent to find a program in program\'s table .');
        }
        $slug = preg_replace(
            '/-/',
            ' ', ucwords(trim(strip_tags($slug)), "-")
        );
        $program = $this->getDoctrine()
            ->getRepository(Program::class)
            ->findOneBy(['title' => mb_strtolower($slug)]);
        if (!$program) {
            throw $this->createNotFoundException(
                'No program with '.$slug.' title, found in program\'s table.'
            );
        }

        return $this->render('wild/show.html.twig', [
            //'slug' => $replace  === Quête 05 Symfony ===
            'program' => $program,
            'slug'  => $slug
        ]);
    }

    /**
     * @Route("wild/showByCategory/{categoryName}", name="wild_category")
     * @param $categoryName
     * @return Response
     */
    public function showByCategory(string $categoryName) :Response {
        $category = $this->getDoctrine()
            ->getRepository(Category::class)
            ->findOneBy(['name' => mb_strtolower($categoryName)]);
        $programs = $this->getDoctrine()
            ->getRepository(Program::class)
            ->findBy(['category'=> $category], ['id' => 'DESC'], 3);
        if (!$category) {
            throw $this->createNotFoundException(
                'No programs with category ' . $categoryName . ', found in program table.'
            );
        }

        return $this->render('wild/category.html.twig', [
            'programs' => $programs,
            'categoryName'  => $categoryName
        ]);
    }

    /**
     * @Route("wild/showBySeason/{id}", name="wild_season")
     * @param $id
     * @return Response
     */
    public function showBySeason(int $id) :Response {
        $program = $this->getDoctrine()
            ->getRepository(Program::class)
            ->findOneBy(['id' => $id]);
        if (!$program) {
            throw $this->createNotFoundException(
                'No programs found in season table.'
            );
        }
        $seasons = $this->getDoctrine()
            ->getRepository(Season::class)
            ->findBy(['program' => $program]);
        $episodes = $this->getDoctrine()
            ->getRepository(Episode::class)
            ->findBy(['season' => $seasons]);

        return $this->render('wild/season.html.twig', [
            'program' => $program,
            'seasons'  => $seasons,
            'episodes'  => $episodes
        ]);
    }
}