<?php

namespace App\Controller;

use App\Classe\Cart;
use App\Entity\Order;
use App\Entity\OrderDetails;
use App\Form\OrderType;
use App\Repository\OrderDetailsRepository;
use App\Repository\OrderRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


class OrderController extends AbstractController
{
    
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager){
       $this->entityManager=$entityManager;

    }
    
    
    
    /**
     * @Route("/commande", name="order")
     * @param Cart $cart
     * @param Request $request
     * @return Response
     */
    public function index(Cart $cart): Response
    {



        $form = $this->createForm(OrderType::class ,null,
            ['user' => $this->getUser() ]
        );



        return $this->render('order/index.html.twig',[
            'form' => $form->createView(),
            'cart' => $cart->getFull()
        ]);
    }
    /**
     * @Route("/commande/recap", name="order_recap" , methods={"POST"})
     * @param Cart $cart
     * @param Request $request
     * @return Response
     */
    public function add(Cart $cart ,Request $request): Response
    {



        $form = $this->createForm(OrderType::class ,null,
            ['user' => $this->getUser() ]
        );
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){



            $date = new \DateTime();
            $order = new Order();
            $order->setUser($this->getUser());
            $order->setCreatedAt($date);


            $order->setIsPaid(0);
            $this->entityManager->persist($order);

            foreach ($cart->getFull() as $produit){
                $orderDetails = new OrderDetails();
                $orderDetails->setMyOrder($order);
                $orderDetails->setProduct($produit['produit']->getName());
                $orderDetails->setQuantity($produit['quantity']);
                $orderDetails->setPrice($produit['produit']->getPrix());
                $orderDetails->setTotal($produit['produit']->getPrix() * $produit['quantity']);
                $this->entityManager->persist($orderDetails);

            }
            $this->entityManager->flush();
            return $this->render('order/add.html.twig',[

                'cart' => $cart->getFull() ,

            ]);



        }
        return $this->redirectToRoute('cart');



    }

    /**
     * @Route("/admin/order", name="afficheOrder" )
     */
    public function affiche(OrderRepository $orderRepository){
        $order = $orderRepository->findAll();

        return $this->render('admin/order/affiche.html.twig',
            ['order' => $order]);
    }




    /**
     * @param $firstname
     * @param $lastname
     * @param $id
     * @Route("/admin/mail/confirm/{firstname}/{lastname}/{id}", name="confirmerOrder" )
     */
    public function affiche2(\Swift_Mailer $mailer, OrderRepository $orderRepository, $firstname,$lastname,$id,Order $order){

        $e=($firstname.'.'.$lastname.'@esprit.tn');

        $order= $orderRepository->find($id)->setIsPaid("1");

        $order->setIsPaid("1");


        $message = (new \Swift_Message('Confirmation Reservation'))
            ->setFrom('send@example.com')
            ->setTo($e)
            ->setBody(

                '
                  Cher Mr/Mme '.$lastname.' '.$firstname.' '.
                  'Votre Reservation d ID '.$id.' a éte accepté par l admin.  
                  Pour plus d information, Contactez Nous Via notre site web via ces 2 liens: 
                  http://localhost:8000/ OU http://127.0.0.1:8000/
                  Ou via notre numero telephone:(+216)54164001
                  
                  Cordialement.
                  AYADI Malek, Chef departement BonPlans '
            );
        $mailer->send($message);

        return $this->redirectToRoute('afficheOrder');




    }



    /**
     * @param $email
     * @Route("/admin/mail/{email}/", name="confirmer2Order" )
     */
    public function affiche3(\Swift_Mailer $mailer, OrderRepository $orderRepository, $email){

        $order= $orderRepository->find($email);

        // $order= $orderRepository->findAll();

        $message = (new \Swift_Message('Hello Email'))
            ->setFrom('send@example.com')
            ->setTo($email)
            ->setBody(

                'Votre Reservation a ete Faite avec Success'
            );
        $mailer->send($message);

        return $this->redirectToRoute('afficheOrder');




    }




    /**
     * @param $id
     * @param OrderRepository $orderRepository
     * @Route ("admin/order/delete/{id}", name="deleteorder")
     */

    public function remove($id,OrderRepository $orderRepository,OrderDetailsRepository $orderDetailsRepository){


        $order= $orderRepository->find($id);

        $em=$this->getDoctrine()->getManager();
        $em->remove($order);
        $em->flush();


        return $this->redirectToRoute("afficheOrder");
    }


}
