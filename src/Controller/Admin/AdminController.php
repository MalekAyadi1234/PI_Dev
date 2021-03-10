<?php

namespace App\Controller\Admin;

use App\Repository\CarrierRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;




/**
 * @Route("/admin" )
 * @package App\Controller\Admin
 */
class AdminController extends AbstractController
{
    /**
     * @Route("/home", name="admin")
     */
    public function index(): Response
    {
        return $this->render('admin/index.html.twig');
    }

    /**
     * @return Response
     * @Route ("/carrier/affiche", name="afficheT")
     */
    public function AfficheT(): Response
    {
        $carrier =$this->getDoctrine()->getManager()->getRepository(CarrierRepository::class)->findAll();
        return $this->render('admin/carrier/index.html.twig',
            ['carrier' => $carrier]);
    }
}
