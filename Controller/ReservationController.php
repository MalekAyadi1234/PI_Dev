<?php

namespace App\Controller;

use App\Form\ReservationType;
use App\Entity\Reservation;
use App\Entity\Vol;
use App\Repository\VolRepository;

use App\Repository\ReservationRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


class ReservationController extends AbstractController
{
    /**
     * @Route("/reservation", name="reservation")
     */
    public function index(): Response
    {
        return $this->render('reservation/index.html.twig', [
            'controller_name' => 'ReservationController',
        ]);
    }

    /**
     * @param ReservationRepository $repository
     * @return Response
     * @Route ("/afficheR",name="afficheR")
     */
    public function afficheR(ReservationRepository $repository){

        $res=$repository->findAll();
        return $this->render('reservation/afficheR.html.twig',['Reservation'=>$res]);
    }

    /**
     * @param $id
     * @param ReservationRepository $repository
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     * @Route ("/suppR/{id}",name="d")
     */
    public function supprimerR ($id,ReservationRepository $repository){
        $res=$repository->find($id);
        $em=$this->getDoctrine()->getManager();
        $em->remove($res);
        $em->flush();
        return$this->redirectToRoute('afficheR');
    }

    /**
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|Response
     * @Route ("reservation/ajouterRes",name="ajouterR")
     */
    public function ajouterR(Request $request,\Swift_Mailer $mailer,VolRepository $repository){
        $res=new Reservation();
        $form=$this->createForm(ReservationType::class,$res);
        $form->add('ajouter',submitType::class);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $em=$this->getDoctrine()->getManager();

            $id=$res->getVol();
            $Vol=$repository->find($id);
            $Vol->setPlaceD($id->getPlaceD()-1);
            $em->persist($Vol);
            $em->persist($res);
            $em->flush();
            // On crée le message
                $message = (new \Swift_Message('Confermation Reservation'))
                // On attribue l'expéditeur
                ->setFrom('jihenedorgham72@gmail.com')
                // On attribue le destinataire
                ->setTo($res->getEmail())
                // On crée le texte avec la vue
                ->setBody('Votre Reservation a ete bien inscrit'

                )
            ;
            $mailer->send($message);

            $this->addFlash('message', 'Votre message a été transmis, nous vous répondrons dans les meilleurs délais.');

            return $this->redirectToRoute('afficheR');
        }
        return $this->render('reservation/ajouterRes.html.twig',['form'=>$form->createView()

        ]);
    }

    /**
     * @param ReservationRepository $repository
     * @param $id
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|Response
     *  @Route ("reservation/updateRes.html.twig/{id}", name="updateR")
     */

    public function modifier( ReservationRepository $repository,$id,Request $request){
        $Res=$repository->find($id);
        $form=$this->createForm(ReservationType::class,$Res);
        $form->add('Update',SubmitType::class);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            $em=$this->getDoctrine()->getManager();
            $em->flush();
            return $this->redirectToRoute('afficheR');
        }
        return $this->render('reservation/updateRes.html.twig',['form'=>$form->createView()

        ]);



    }

    /**
     * @param ReservationRepository $repository
     * @param Request $request
     * @return Response
     * @Route ("/search_ajax",name="search_ajax")
     */
    public function searchAction(Request $request,ReservationRepository $repository)
    {
        $em = $this->getDoctrine()->getManager();
        $requestString = $request->get('q');
        $entities =  $em->getRepository(Reservation::class)->findEntitiesByString($requestString);

        if(!$entities)
        {
            $result['entities']['error'] = "there is no demande with this titre";

        }
        if(strlen($requestString)==1)
        {

            $entities=$repository->findAll();
            $result['entities']=$this->getRealEntities($entities);
        }
        else
        {

            $result['entities'] = $this->getRealEntities($entities);
        }

        return new JsonResponse($result, 200);
    }
    public function getRealEntities($entities){


        foreach ($entities as $entity)
        {
            $realEntities[$entity->getId()] = [$entity->getIdR(), $entity->getCin(),$entity->getPrix(),$entity->getId(),$entity->getDateV(),$entity->getNumP(),$entity->getEmail(),$entity->getVol()];
        }


        return $realEntities;
    }
}
