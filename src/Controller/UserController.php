<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class UserController extends AbstractController
{
   /**
    * @Route("/usuario", name="user")
    */
   public function index()
   {
       return $this->render('usuario/index.html.twig', [
           'controller_name' => 'UserController',
       ]);
   }
}
