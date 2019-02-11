<?php
namespace App\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;

class DeportesController extends Controller {
  /**
   * @Route("/deportes")

   */
  public function inicio() {
      return new Response('Mi página de deportes!');
  }




    /**
     * @Route(
     *     "/deportes/{_idioma}/{fecha}/{seccion}/{equipo}/{slug}.{_formato}",
     *     defaults={"slug": "1","_formato":"html"},
     *     requirements={
     *         "_idioma": "es|en",
     *         "_formato": "html|json|xml",
     *          "fecha": "[\d+]{8}"
     *     }
     * )
     */
    public function rutaAvanzada($_idioma,$fecha, $seccion, $equipo, $slug)
    {
        //Simulamos una base de datos de equipos o personas
        $deportes=["valencia", "barcelona","federer", "rafa-nadal"];

        //Si el equipo o persona que buscamos no se encuentra redirigimos
        //al usuario a la pagina de inicio
        if(!in_array($equipo,$deportes)){
           return $this->redirectToRoute('inicio');
        }
        return new Response(sprintf(
            'Mi noticia en idioma=%s,
             fehca=%s,deporte=%s,equipo=%s, noticia=%s ',
            $_idioma, $fecha, $seccion, $equipo, $slug));
    }



// ...
/**
* @Route("/deportes/usuario", name="usuario" )
*/
public function sesionUsuario(Request $request) {
 $usuario_get=$request->query->get('nombre');
  $session = $request->getSession();
  $session->set('nombre', $usuario_get);
  return $this->redirectToRoute('usuario_session',array('nombre'=>$usuario_get));
}
// …

/**
* @Route("/deportes/usuario/{nombre}", name="usuario_session" )
*/
public function paginaUsuario() {
$session=new Session();
$usuario=$session->get('nombre');
return new Response(sprintf('Sesión iniciada con el atributo nombre: %s', $usuario
));
}

/**
* @Route("/deportes/{section}/{page}", name="lista_paginas",
*      requirements={"page"="\d+"},
*      defaults={"section":"tenis"})
*/
    public function lista($page = 1, $section){
 // Simulamos una base de datos de deportes
 $sports=["futbol", "tenis","rugby"];
 // Si el deporte que buscamos no se encuentra lanzamos la
 // excepcion 404 deporte no encontrado
 if(!in_array($section,$sports)) {
     throw $this->createNotFoundException('Error 404 este deporte no está en nuestra Base de Datos');
 }
 return new Response(sprintf( 'Deportes seccion: seccion %s, listado de noticias página %s', $section, $page));
}




}