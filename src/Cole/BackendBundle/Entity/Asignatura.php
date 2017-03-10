<?php

namespace Cole\BackendBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * Asignatura
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="Cole\BackendBundle\Entity\AsignaturaRepository")
 */
class Asignatura
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="nombre", type="string", length=255)
     */
    private $nombre;

    /**
     * @var string
     *
     * @ORM\Column(name="abreviatura", type="string", length=255)
     */
    private $abreviatura;

    /**
     * @var string
     *
     * @ORM\Column(name="tipo", type="string", length=255)
     */
    private $tipo;
    
    /**
     * 
     *
     * @ORM\Column(name="opcional", type="boolean")
     */
    private $opcional;

    /**
    * @ORM\OneToMany(targetEntity="AsignaturasCursos", mappedBy="asignatura", cascade={"remove"})
    */
    private $asignaturas_cursos;

    public function __construct()
    {

        $this->imparte = new ArrayCollection();
    }

    /**
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set nombre
     *
     * @param string $nombre
     * @return Asignatura
     */
    public function setNombre($nombre)
    {
        $this->nombre = $nombre;

        return $this;
    }

    /**
     * Get nombre
     *
     * @return string 
     */
    public function getNombre()
    {
        return $this->nombre;
    }

        /**
     * Set abreviatura
     *
     * @param string $abreviatura
     * @return Asignatura
     */
    public function setAbreviatura($abreviatura)
    {
        $this->abreviatura = $abreviatura;

        return $this;
    }

    /**
     * Get abreviatura
     *
     * @return string 
     */
    public function getAbreviatura()
    {
        return $this->abreviatura;
    }

    /**
     * Set tipo
     *
     * @param string $tipo
     * @return Asignatura
     */
    public function setTipo($tipo)
    {
        $this->tipo = $tipo;

        return $this;
    }

    /**
     * Get tipo
     *
     * @return string 
     */
    public function getTipo()
    {
        return $this->tipo;
    }

    public function __toString()
    {
        return $this->getNombre();
    }

    /**
     * Add asignaturas_cursos
     *
     * @param \Cole\BackendBundle\Entity\AsignaturasCursos $asignaturasCursos
     * @return Asignatura
     */
    public function addAsignaturasCurso(\Cole\BackendBundle\Entity\AsignaturasCursos $asignaturasCursos)
    {
        $this->asignaturas_cursos[] = $asignaturasCursos;

        return $this;
    }

    /**
     * Remove asignaturas_cursos
     *
     * @param \Cole\BackendBundle\Entity\AsignaturasCursos $asignaturasCursos
     */
    public function removeAsignaturasCurso(\Cole\BackendBundle\Entity\AsignaturasCursos $asignaturasCursos)
    {
        $this->asignaturas_cursos->removeElement($asignaturasCursos);
    }

    /**
     * Get asignaturas_cursos
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getAsignaturasCursos()
    {
        return $this->asignaturas_cursos;
    }





    /**
     * Set opcional
     *
     * @param boolean $opcional
     * @return Asignatura
     */
    public function setOpcional($opcional)
    {
        $this->opcional = $opcional;

        return $this;
    }

    /**
     * Get opcional
     *
     * @return boolean 
     */
    public function getOpcional()
    {
        return $this->opcional;
    }
}
