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
        return $this->render("base.html.twig");
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

 $noticiaSec= $repository->findOneBy(['seccion' => $seccion]);
 // Si el deporte que buscamos no se encuentra lanzamos la
 // excepcion 404 deporte no encontrado
 if(!$noticiaSec) {
     throw $this->createNotFoundException('Error 404 este deporte no está en nuestra Base de Datos');
 }

 // Almacenamos todas las noticias de una sección en una lista
 $noticias = $repository->findBy([
     "seccion"=>$seccion
 ]);

 return $this->render('noticias/listar.html.twig', [
     // La función str_replace elimina los símbolos - de los títulos
     'titulo' => ucwords(str_replace('-', ' ', $seccion)),
     'noticias'=>$noticias
 ]);
}

/**
* @Route("/deportes/{seccion}/{titular} ",
* defaults={"seccion":"tenis"}, name="verNoticia")
*/
public function noticia($titular, $seccion)
{
 $em=$this->getDoctrine()->getManager();
 $repository = $this->getDoctrine()->getRepository(Noticia::class);
 $noticia= $repository->findOneBy(['textoTitular' => $titular]);
 // Si la noticia que buscamos no se encuentra lanzamos error 404
 if(!$noticia){
         // Ahora que controlamos el manejo de plantilla twig, vamos a
         // redirigir al usuario a la página de inicio
         // y mostraremos el error 404, para así no mostrar la página de
         // errores generica de symfony
         throw $this->createNotFoundException('Error 404 este deporte no está en nuestra Base de Datos');
   return $this->render("base.html.twig",[
             'texto'=>"Error 404 Página no encontrada"
   ]);
 }
   return $this->render('noticias/noticia.html.twig', [
         // Parseamos el titular para quitar los símbolos -
         'titulo' => ucwords(str_replace('-', ' ', $titular)),
         'noticias'=>$noticia

     ]);
}

}




