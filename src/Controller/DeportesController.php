<?php
/**
 * Created by PhpStorm.
 * User: imagina
 * Date: 13/02/18
 * Time: 11:48
 */

namespace App\Controller;

use App\Repository\NoticiaRepository;
use App\Entity\Noticia;
use App\Entity\Usuario;
use App\Form\Login;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Translation\Translator;
use Symfony\Component\Translation\Loader\ArrayLoader;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class DeportesController extends Controller

{

    /**
     * @Route("/deportes", name="inicio" )
     */
    public function inicio($texto="Mi página de deportes!!",$usuario="")
    {
        return $this->render("base.html.twig",[
            'texto'=>$texto,
            'usuario'=>$usuario
        ]);
    }
    /**
     * @Route("/deportes/cargarbd", name="noticia")
     */
    public function cargarBd()
    {
        $em=$this->getDoctrine()->getManager();
        $noticia=new Noticia();
        $noticia->setSeccion("Tenis");
        $noticia->setEquipo("roger-federer");
        $noticia->setFecha("16022018");
        $noticia->setTextoTitular("Roger-Federer-a-una-victoria-del-número-uno-de-Nadal");
        $noticia->setTextoNoticia("El suizo Roger Federer, el tenista más laureado de la historia, está a son un paso de regresar a la cima del tenis mundial a sus 36 años. Clasificado sin admitir ni réplica para cuartos de final del torneo de Rotterdam, si vence este viernes a Robin Haase se convertirá en el número uno del mundo ...");
        $noticia->setImagen('federer.jpg');
        $em->persist($noticia);
        $em->flush();
        return new Response("Noticia guardada con éxito con id:".$noticia->getId());

    }

    /**
     * @Route("/deportes/actualizar", name="actualizarNoticia")
     */
    public function actualizarBd(Request $request)
    {
        $em=$this->getDoctrine()->getManager();
        $id=$request->query->get('id');
        $noticia = $em->getRepository(Noticia::class)->find($id);

        $noticia->setTextoTitular("Rafa-Nadal-numero-uno-del-mundo");
        $em->flush();

        return new Response("Noticia actualizada!");

    }


    /**
     * @Route("/deportes/eliminar", name="eliminarNoticia")
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
     * @Route("/deportes/usuario", name="usuario" )
     */
    public function sesionUsuario(Request $request)
    {
           $usuario_get=$request->query->get('nombre');
            $session = $request->getSession();
            $session->set('nombre', $usuario_get);

            return $this->redirectToRoute('usuario_session',array('nombre'=>$usuario_get));

    }


    /**
     * @Route("/deportes/usuario/{nombre}", name="usuario_session" )
     */
    public function paginaUsuario()
    {
        $session=new Session();
        $usuario=$session->get('nombre');
        return new Response(sprintf('Sesion iniciada con el atributo nombre: %s'
            , $usuario
        ));
    }



    /**
     * @Route("/deportes/login", name="login_seguro" )
     */
    public function loginUsuario(Request $request)
    {
        $authenticationUtils = $this->get('security.authentication_utils');

        // capturar error de autenticacion
        $error = $authenticationUtils->getLastAuthenticationError();

        // ultimo nobre de usuario autenticado
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('usuario/login.html.twig', array(
            'last_username' => $lastUsername,
            'error'         => $error,
        ));
    }


    /**
     * @Route("/deportes/nuevousuario", name="usuariobd")
     */
    public function nuevoUsuarioBd()
    {
        $em=$this->getDoctrine()->getManager();

        $usuario=new Usuario();
        $usuario->setEmail("jose@imaginaformacion.com");
        $usuario->setUsername("jose");
        $password = $this->get('security.password_encoder')
            ->encodePassword($usuario, "imaginapass");
        $usuario->setPassword($password);

        $em->persist($usuario);

        $em->flush();

        return new Response("Usuario guradado!");

    }


    /**
     * @Route("/deportes/login_check", name="login_check")
     */
    public function loginCheck()
    {
        return $this->render("base.html.twig",[
        'texto'=>'a'
    ]);
    }



    /**
     * @Route("/deportes/{seccion}/{pagina}", name="lista_paginas",
     *      requirements={"pagina"="\d+"},
     *      defaults={"seccion":"tenis"})
     */
    public function lista($pagina = 1, $seccion)
    {

        $em=$this->getDoctrine()->getManager();
        $repository = $this->getDoctrine()->getRepository(Noticia::class);

        $noticiaSec= $repository->findOneBy(['seccion' => $seccion]);
        //Si el deporte que buscamos no se encuentra lanzamos la
        //excepcion 404 deporte no encontrado
        if(!$noticiaSec){
            //Ahora que controlamos el manejo de plantilla twig, vamos a redirigir al usuario a la pagina de inicio
            //y mostraremos el error 404, para así no mostrar la página de errores generica de symfony
            //throw $this->createNotFoundException('Error 404 este deporte no esta en nuestra Base de Datos');
            return $this->render("base.html.twig",[
                'texto'=>"Error 404 Página no encontrada"
            ]);
        }

        //almacenamos todas las noticias de una seccion en una lista
        $noticias = $repository->findBy([
            "seccion"=>$seccion
        ]);

        return $this->render('noticias/listar.html.twig', [
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
        //Si la noticia que buscamos no se encuentra lanzamos error 404
        if(!$noticia){
            //Ahora que controlamos el manejo de plantilla twig, vamos a redirigir al usuario a la pagina de inicio
            //y mostraremos el error 404, para así no mostrar la página de errores generica de symfony
            //throw $this->createNotFoundException('Error 404 este deporte no esta en nuestra Base de Datos');
            return $this->render("base.html.twig",[
                'texto'=>"Error 404 Página no encontrada"
            ]);

        }

        return $this->render('noticias/noticia.html.twig', [
            //parseamos el titular para quitar los simbolos -
            'titulo' => ucwords(str_replace('-', ' ', $titular)),
            'noticias'=>$noticia

        ]);
    }



    /**
     * @Route(
     *     "/deportes/{_idioma}/{fecha}/{seccion}/{equipo}/{pagina}",
     *     defaults={"_formato":"html","pagina":"1","_idioma"="es"},
     *     requirements={
     *         "_idioma": "es|en",
     *         "_formato": "html|json|xml",
     *         "fecha": "[\d+]{8}",
     *         "pagina"="\d+"
     *     }
     * )
     */
    public function rutaAvanzadaListado($_idioma,$fecha, $seccion, $equipo, $pagina)
    {

        //Realizamos una consulta un poco más avanzada. La función para
        //realizar esta consulta está en /serc/Repository/NoticiasRepository.php
        //Esta página se genera automaticamente para dar soporte a estas consultas
        $em=$this->getDoctrine()->getManager();
        $repository = $this->getDoctrine()->getRepository(Noticia::class);

        $noticias= $repository->listadoNoticias($seccion,$fecha,$equipo);
        return $this->render('noticias/listar.html.twig', [
            'titulo' => ucwords(str_replace('-', ' ', $seccion)),
            'noticias'=>$noticias
        ]);
    }



    /**
     * @Route(
     *     "/deportes/{_idioma}/{fecha}/{seccion}/{equipo}/{titular}.{_formato}",
     *     defaults={"titular": "1","_formato":"html"},
     *     requirements={
     *         "_idioma": "es|en",
     *         "_formato": "html|json|xml",
     *          "fecha": "[\d+]{8}"
     *     }
     * )
     */
    public function rutaAvanzada($_idioma,$fecha, $seccion, $equipo, $titular)
    {
        //Realizamos una consulta un poco más avanzada. La función para
        //realizar esta consulta está en /serc/Repository/NoticiasRepository.php
        //Esta página se genera automaticamente para dar soporte a estas consultas
        $em=$this->getDoctrine()->getManager();
        $repository = $this->getDoctrine()->getRepository(Noticia::class);

        $noticia= $repository->verNoticia($seccion,$fecha,$equipo,$titular);
        return $this->render('noticias/noticia.html.twig', [
            //parseamos el titular para quitar los simbolos -
            'titulo' => ucwords(str_replace('-', ' ', $titular)),
            'noticias'=>$noticia[0]
        ]);
    }



}
