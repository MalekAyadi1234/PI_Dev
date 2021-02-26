<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\User;
use App\Form\UserType;

class HomeController extends AbstractController
{
    /**
     * @Route("/home", name="home")
     */
    public function index(): Response
    {
        return $this->render('home/index.html.twig', [
            'controller_name' => 'HomeController',
        ]);
    }





    /**
     * @Route("/signin", name="signin")
     */
    public function signAction()
    {
        return $this->render('home/signin.html.twig');
        
    }


    /**
     * @Route("/addUser", name="addUser")
     */
    public function addUser(Request $request)
    {
        $user=new User();
        $form=$this->createForm(UserType::class , $user);
        $form=$form->handleRequest($request);
        if($form->isSubmitted() and $form->isValid()){
            $em=$this->getDoctrine()->getManager();
            $em->persist($user);
            $em->flush();
        }
        return $this->render('user/addUser.html.twig',array(
          'form'=>$form->createView()  
        ));
        
    }



    
}
