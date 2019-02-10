<?php
namespace App\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
class DeportesController {
  /**
   * @Route("/deportes")

   */
  public function inicio() {
      return new Response('Mi página de deportes!');
  }
  /**
* @Route(
*    "/deportes/{_locale}/{fecha}/{seccion}/{equipo}/{slug}.{_format}",
*     defaults={"slug": "1","_format":"html"},
*     requirements={
*         "_locale": "es|en",
*         "_format": "html|json|xml",
*          "fecha": "[\d+]{8}"
*     }
* )
*/
public function rutaAvanzada($_locale,$fecha, $seccion, $equipo, $slug) {
 return new Response(sprintf(
     'Mi noticia en idioma=%s,
      fecha=%s,deporte=%s,equipo=%s, noticia=%s ',
     $_locale, $fecha, $seccion, $equipo, $slug));
}


/**
* @Route(
*     "/deportes/{_locale}/{fecha}/{seccion}/{equipo}/{pagina}",
*     defaults={"slug": "1","_format":"html","pagina":"1"},
*     requirements={
*         "_locale": "es|en",
*         "_format": "html|json|xml",
*         "fecha": "[\d+]{8}",
*         "pagina"="\d+"
*     }
* )
*/
public function rutaAvanzadaListado($_locale, $fecha, $seccion, $equipo, $pagina) {
 return new Response(sprintf(
     'Listado de noticias  en idioma=%s,
      fecha=%s,deporte=%s,equipo=%s, página=%s ',
     $_locale, $fecha, $seccion, $equipo, $pagina));
}


}