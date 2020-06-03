<?php
// src/Controller/WildController.php
namespace App\Controller;

use App\Entity\Actor;
use App\Entity\Category;
use App\Entity\Episode;
use App\Entity\Program;
use App\Entity\Season;
use App\Form\CategoryType;
use App\Form\ProgramSearchType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;

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
        /*$form = $this->createForm(
            ProgramSearchType::class,
            null,
            ['method' => Request::METHOD_GET]
        );
        $category = new Category();
        $form = $this->createForm(CategoryType::class, $category);*/

        return $this->render('wild/index.html.twig', [
            'programs' => $programs,
            //'form' => $form->createView()
        ]);
    }

    /**
     * @Route("wild/showCateg", name="wild_category")
     * @return Response
     */
    public function showCateg() :Response {
        $category = $this->getDoctrine()
            ->getRepository(Category::class)
            ->findAll();
        return $this->render('wild/category.html.twig', [
            'category' => $category
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
    public function showByCategory(string $categoryName) :Response
    {
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
        $categorys = $this->getDoctrine()
            ->getRepository(Category::class)
            ->findAll();

        return $this->render('wild/category.html.twig', [
            'programs' => $programs,
            'categoryName'  => $categoryName,
            'categorys' => $categorys
        ]);
    }

    /**
     * @param $slug
     * @Route("wild/program/{slug}", defaults={"slug" = null}, name="show_program")
     * @return Response
     */
    public function showByProgram($slug):Response
    {
        $slug = preg_replace(
            '/-/',
            ' ', ucwords(trim(strip_tags($slug)), "-")
        );

        $program = $this->getDoctrine()->getRepository(Program::class)->findOneBy(['title' => mb_strtolower($slug)]);
        $season = $program->getSeasons();

        return $this->render('wild/program.html.twig', [
            'program' => $program,
            'seasons' => $season,
            'slug'  => $slug,
        ]);
    }

    /**
     * @Route("wild/showBySeason/{id}", name="wild_season")
     * @param Season $season
     * @return Response
     */
    public function showBySeason(Season $season) :Response {
        $program = $season->getProgram();
        $episodes = $season->getEpisodes();

        return $this->render('wild/season.html.twig', [
            'program' => $program,
            'season'  => $season,
            'episodes'  => $episodes
        ]);
    }

    /**
     * @Route("wild/showEpisode/{id}", name="wild_episode")
     * @param Episode $episode
     * @return Response
     */
    public function showEpisode (Episode $episode) :Response {

        $season = $episode->getSeason();
        $program = $season->getProgram();

        return $this->render('wild/episode.html.twig', [
            'episode' => $episode,
            'season' => $season,
            'program' => $program

        ]);
    }

    /**
     * @Route("wild/actor/{id}", name="wild_actor")
     * @param Actor $actor
     * @return Response
     */
    public function actor (Actor $actor) :Response {

        $program = $actor->getPrograms();

        return $this->render('wild/actor.html.twig', [
            'programs' => $program,
            'actor' => $actor
        ]);
    }
}