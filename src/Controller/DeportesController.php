<?php

namespace App\Controller;

use App\Entity\Noticia;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Session\Session;


class DeportesController extends Controller

{
    /**
     * @Route("/deportes", name="inicio" )
     */
    public function inicio()
    {
        return new Response('Mi página de deportes!');
    }

  /**
  * @Route("/deportes/primer-articulo")
  */
  public function mostrar() {
     return new Response('Mi primera ruta Primer Artículo!');
  }
/**
* @Route("/deportes/cargarbd")
*/
public function cargarBd() {
   $em=$this->getDoctrine()->getManager();

   $noticia=new Noticia();
   $noticia->setSeccion("Tenis");
   $noticia->setEquipo("roger-federer");
   $noticia->setFecha("16022018");
   $noticia->setTextoTitular("Roger-Federer-a-una-victoria-del-número-uno-de-Nadal");
   $noticia->setTextoNoticia("El suizo Roger Federer, el tenista más laureado de la historia, está a son un paso de regresar a la cima del tenis mundial a sus 36 años. Clasificado sin admitir ni réplica para cuartos de final del torneo de Rotterdam, si vence este viernes a Robin Haase se convertirá en el número uno del mundo ...");
   //$noticia->setImagen('federer.jpg');

   $em->persist($noticia);
   $em->flush();
   return new Response("Noticia guardada con éxito con id:".$noticia->getId());
}

    /**
     * @Route("/deportes/actualizar")
     */
    public function actualizarBd(Request $request)
    {
        $em=$this->getDoctrine()->getManager();
        $id=$request->query->get('id');
        $noticia = $em->getRepository(Noticia::class)->find($id);

        $noticia->setTextoTitular("Titular de ejemplo actualizado para la noticia con id:".$noticia->getId());
        $noticia->setTextoNoticia("Texto de ejemplo actualizado para la noticia con id:".$noticia->getId());

        $em->flush();

        return new Response("Noticia actualizada!");

    }


    /**
     * @Route("/deportes/eliminar", name="actualizarNoticia")
     */
    public function eliminarBd(Request $request)
    {
        $em=$this->getDoctrine()->getManager();
        $id=$request->query->get('id');
        $noticia = $em->getRepository(Noticia::class)->find($id);

        $em->remove($noticia);
        $em->flush();

        return new Response("Noticia eliminada!");

    }

/**
* @Route("/deportes/{seccion}/{pagina}", name="lista_paginas",
*      requirements={"pagina"="\d+"},
*      defaults={"seccion":"tenis"})
*/
public function lista($pagina = 1, $seccion) {
   $em=$this->getDoctrine()->getManager();
   $repository = $this->getDoctrine()->getRepository(Noticia::class);
   //Buscamos las noticias de una sección
   $noticiaSec= $repository->findOneBy(['seccion' => $seccion]);
   // Si la sección no existe saltará una excepción
   if(!$noticiaSec) {
       throw $this->createNotFoundException('Error 404 este deporte no está en nuestra Base de Datos');
   }
   // Almacenamos todas las noticias de una sección en una lista
   $noticias = $repository->findBy([
       "seccion"=>$seccion
   ]);
   return new Response("Hay un total de ".count($noticias)." noticias de la sección de ".$seccion);
}

}




