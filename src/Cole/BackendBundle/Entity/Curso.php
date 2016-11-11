<?php

namespace Cole\BackendBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * Curso
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="Cole\BackendBundle\Entity\CursoRepository")
 */
class Curso
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
     * @ORM\Column(name="Nivel", type="string", length=100)
     */
    private $nivel;

    /**
     * @var string
     *
     * @ORM\Column(name="Curso", type="string", length=100)
     */
    private $curso;

    /**
     * @var integer
     *
     * @ORM\Column(name="numGrupos", type="integer")
     */
    private $numGrupos;

    /**
     * @var integer
     *
     * @ORM\Column(name="numOrden", type="integer")
     */
    private $numOrden;

    /**
     * @var integer
     *
     * @ORM\Column(name="ratio", type="integer")
     */
    private $ratio;


    /**
    * @ORM\OneToMany(targetEntity="Grupo", mappedBy="curso", cascade={"remove"})
    */
    private $grupos;

    /**
    * @ORM\OneToMany(targetEntity="Alumno", mappedBy="curso")
    */
    private $alumnos;

    /**
     * @ORM\OneToMany(targetEntity="Matricula", mappedBy="curso")
     */
    private $matricula;
    
    public function __construct()
    {

        $this->grupos = new ArrayCollection();
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
     * Set nivel
     *
     * @param string $nivel
     * @return Curso
     */
    public function setNivel($nivel)
    {
        $this->nivel = $nivel;

        return $this;
    }

    /**
     * Get nivel
     *
     * @return string 
     */
    public function getNivel()
    {
        return $this->nivel;
    }

    /**
     * Set curso
     *
     * @param string $curso
     * @return Curso
     */
    public function setCurso($curso)
    {
        $this->curso = $curso;

        return $this;
    }

    /**
     * Get curso
     *
     * @return string 
     */
    public function getCurso()
    {
        return $this->curso;
    }

        public function __toString()
    {
         return $this->getCurso().'  '.$this->getNivel();
    }

    /**
     * Add grupos
     *
     * @param \Cole\BackendBundle\Entity\Grupo $grupos
     * @return Curso
     */
    public function addGrupo(\Cole\BackendBundle\Entity\Grupo $grupos)
    {
        $this->grupos[] = $grupos;

        return $this;
    }

    /**
     * Remove grupos
     *
     * @param \Cole\BackendBundle\Entity\Grupo $grupos
     */
    public function removeGrupo(\Cole\BackendBundle\Entity\Grupo $grupos)
    {
        $this->grupos->removeElement($grupos);
    }

    /**
     * Get grupos
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getGrupos()
    {
        return $this->grupos;
    }

    /**
     * Add imparte
     *
     * @param \Cole\BackendBundle\Entity\Imparte $imparte
     * @return Curso
     */
    public function addImparte(\Cole\BackendBundle\Entity\Imparte $imparte)
    {
        $this->imparte[] = $imparte;

        return $this;
    }

    /**
     * Remove imparte
     *
     * @param \Cole\BackendBundle\Entity\Imparte $imparte
     */
    public function removeImparte(\Cole\BackendBundle\Entity\Imparte $imparte)
    {
        $this->imparte->removeElement($imparte);
    }

    /**
     * Get imparte
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getImparte()
    {
        return $this->imparte;
    }

    /**
     * Set numGrupos
     *
     * @param integer $numGrupos
     * @return Curso
     */
    public function setNumGrupos($numGrupos)
    {
        $this->numGrupos = $numGrupos;

        return $this;
    }

    /**
     * Get numGrupos
     *
     * @return integer 
     */
    public function getNumGrupos()
    {
        return $this->numGrupos;
    }

    /**
     * Add alumnos
     *
     * @param \Cole\BackendBundle\Entity\Alumno $alumnos
     * @return Curso
     */
    public function addAlumno(\Cole\BackendBundle\Entity\Alumno $alumnos)
    {
        $this->alumnos[] = $alumnos;

        return $this;
    }

    /**
     * Remove alumnos
     *
     * @param \Cole\BackendBundle\Entity\Alumno $alumnos
     */
    public function removeAlumno(\Cole\BackendBundle\Entity\Alumno $alumnos)
    {
        $this->alumnos->removeElement($alumnos);
    }

    /**
     * Get alumnos
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getAlumnos()
    {
        return $this->alumnos;
    }

    /**
     * Add matricula
     *
     * @param \Cole\BackendBundle\Entity\Matricula $matricula
     * @return Curso
     */
    public function addMatricula(\Cole\BackendBundle\Entity\Matricula $matricula)
    {
        $this->matricula[] = $matricula;

        return $this;
    }

    /**
     * Remove matricula
     *
     * @param \Cole\BackendBundle\Entity\Matricula $matricula
     */
    public function removeMatricula(\Cole\BackendBundle\Entity\Matricula $matricula)
    {
        $this->matricula->removeElement($matricula);
    }

    /**
     * Get matricula
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getMatricula()
    {
        return $this->matricula;
    }

    /**
     * Set numOrden
     *
     * @param integer $numOrden
     * @return Curso
     */
    public function setNumOrden($numOrden)
    {
        $this->numOrden = $numOrden;

        return $this;
    }

    /**
     * Get numOrden
     *
     * @return integer 
     */
    public function getNumOrden()
    {
        return $this->numOrden;
    }

    /**
     * Set ratio
     *
     * @param integer $ratio
     * @return Curso
     */
    public function setRatio($ratio)
    {
        $this->ratio = $ratio;

        return $this;
    }

    /**
     * Get ratio
     *
     * @return integer 
     */
    public function getRatio()
    {
        return $this->ratio;
    }
}
