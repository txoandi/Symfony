<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\NoticiaRepository")
 */
class Noticia
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;


    /**
     * @ORM\Column(type="string", length=50)
     */
    private $seccion;

    /**
     * @ORM\Column(type="string", length=50)
     */
    private $equipo;

    /**
     * @ORM\Column(type="string", length=8)
     */
    private $fecha;

    /**
     * @ORM\Column(type="text")
     */
    private $textoTitular;

    /**
     * @ORM\Column(type="text")
     */
    private $textoNoticia;



    /*Getters&Setters*/
    public function getId(){
        return $this->id;
    }

    public function getSeccion(){
        return $this->seccion;
    }

    public function getEquipo(){
        return $this->equipo;
    }

    public function getFecha(){
        return $this->fecha;
    }
    public function getTextoNoticia(){
        return $this->fecha;
    }
    public function getTextoTitular(){
        return $this->fecha;
    }

    public function setSeccion($seccion){
        $this->seccion=$seccion;
    }
    public function setEquipo($equipo){
        $this->equipo=$equipo;
    }
    public function setFecha($fecha){
        $this->fecha=$fecha;
    }

    public function setTextoTitular($textoTitular){
        $this->textoTitular=$textoTitular;
    }

    public function setTextoNoticia($textoNoticia){
        $this->textoNoticia=$textoNoticia;
    }



}
