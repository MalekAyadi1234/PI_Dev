<?php

namespace App\Controller;

use App\Entity\Category;
use App\Form\CategoryType;
use App\Repository\CarrierRepository;
use App\Repository\CategoryRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CategoryController extends AbstractController
{
    /**
     * @Route("admin/category", name="category")
     */
    public function index(): Response
    {
        return $this->render('admin/category/index.html.twig');
    }


    /**
     * @param CategoryRepository $categoryRepository
     * @return Response
     * @Route ("admin/category/affiche", name="afficheC")
     */
    public function Affiche(CategoryRepository $categoryRepository)
    {
        $category=$categoryRepository->findAll();
        return $this->render('admin/category/affiche.html.twig',
            ['category' => $category]);
    }

    /**
     * @param $id
     * @param CategoryRepository $categoryRepository
     * @Route ("admin/category/delete/{id}", name="deleteC")
     */

    public function delete($id,CategoryRepository $categoryRepository){
        $category= $categoryRepository->find($id);
        $em=$this->getDoctrine()->getManager();
        $em->remove($category);
        $em->flush();
        return $this->redirectToRoute("afficheC");
    }

    /**
     * @param Request $request
     * @return Response
     * @Route("admin/category/ajouter", name="ajoutC")
     */
    public function Add(Request $request){
        $category = new Category();
        $form=$this->createForm(CategoryType::class,$category);
        $form->add('Ajouter',SubmitType::class);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            $em=$this->getDoctrine()->getManager();
            $em->persist($category);
            $em->flush();
            return $this->redirectToRoute("afficheC");
        }
        return $this->render('admin/category/ajouter.html.twig',
            ['form' => $form->createView()]);
    }

    /**

     * @param CategoryRepository $repository
     * @param $id
     * @param Request $request
     * @return RedirectResponse|Response
     * @Route("admin/category/update/{id}", name="updateC")
     */
    public function Update(CategoryRepository $repository, $id, Request $request){

        $category= $repository->find($id);
        $form=$this->createForm(CategoryType::class,$category);
        $form->add('Update',SubmitType::class);

        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            $em=$this->getDoctrine()->getManager();
            $em->flush();
            return $this->redirectToRoute('afficheC');
        }
        return $this->render('admin/category/update.html.twig',
            ['form' => $form->createView()]
        );


    }




}
