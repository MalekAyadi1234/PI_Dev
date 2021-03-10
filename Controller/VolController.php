<?php

namespace App\Controller;


use App\Form\VolType;
use App\Entity\Vol;
use App\Repository\VolRepository;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class VolController extends AbstractController
{
    /**
     * @Route("/vol", name="vol")
     */
    public function index(): Response
    {
        return $this->render('vol/index.html.twig', [
            'controller_name' => 'VolController',
        ]);
    }

    /**
     * @param VolRepository $repository
     * @return Response
     * @Route ("/affiche",name="affiche")
     */
    public function afficheVol(VolRepository $repository){

        $vol=$repository->findAll();
        return $this->render('vol/affiche.html.twig',['vol'=>$vol]);
    }

    /**
     * @param $id
     * @param VolRepository $repository
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     * @Route ("/supp/{id}",name="dR")
     */
    public function supprimer(VolRepository $repository,$id){

        $Vol=$repository->find($id);
        $em=$this->getDoctrine()->getManager();
        $em->remove($Vol);
        $em->flush();
        return$this->redirectToRoute('affiche');
    }


    /**
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|Response
     *  @Route ("vol/ajouterVol", name="ajouter")
     */
    public function ajouter(Request $request){
        $vol=new vol();
        $form=$this->createForm(volType::class,$vol);
        $form->add('ajouter',submitType::class);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            $em=$this->getDoctrine()->getManager();
            $em->persist($vol);
            $em->flush();
            return $this->redirectToRoute('affiche');
        }
        return $this->render('vol/ajouterVol.html.twig',['form'=>$form->createView()

        ]);
    }

    /**
     * @param VolRepository $repository
     * @param $id
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|Response
     *  @Route ("Vol/update.twig.html/{id}", name="update")
     */
    public function modifier( VolRepository $repository,$id,Request $request){
        $Vol=$repository->find($id);
        $form=$this->createForm(VolType::class,$Vol);
        $form->add('Update',SubmitType::class);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            $em=$this->getDoctrine()->getManager();
            $em->flush();
            return $this->redirectToRoute('affiche');
        }
        return $this->render('Vol/update.html.twig',['form'=>$form->createView()

        ]);



    }

}
