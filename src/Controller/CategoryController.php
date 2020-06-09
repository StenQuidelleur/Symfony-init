<?php

namespace App\Controller;

use App\Entity\Category;
use App\Form\CategoryType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CategoryController extends AbstractController
{
    /**
     * @Route("category/add", name="addCategory")
     * @IsGranted("ROLE_ADMIN")
     * @param Request $request
     * @return Response
     */
    public function add (Request $request) :Response {

        $category = new Category();
        $form = $this->createForm(CategoryType::class, $category);
        $form->handleRequest($request);

        if ($form->isSubmitted()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($category);
            $em->flush();
        }
        return $this->render('wild/add.html.twig', [
            'form' => $form->createView()
        ]);
    }

}