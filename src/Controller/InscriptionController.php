<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\InscriptionType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class InscriptionController extends AbstractController
{

    private $entityManager;
    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager =$entityManager;
    }


    /**
     * @Route("/inscription", name="inscription")
     */
    public function index(Request $request,UserPasswordEncoderInterface $encoder)
    {
        $user= new User();
        $form=$this->createForm(InscriptionType::class,$user);
        $form->handleRequest($request);
        if($form->isSubmitted()&&$form->isValid()){
            $user = $form->getData();



            $password = $encoder->encodePassword($user,$user->getPassword());


            $user->setPassword($password);
            $this->entityManager->persist($user);
            $this->entityManager->flush();

        }

        return $this->render('inscription/index.html.twig',
            ['form' => $form->createView() ]

        );
    }
}
