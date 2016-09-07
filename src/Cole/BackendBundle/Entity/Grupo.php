<?php

namespace Cole\BackendBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * Grupo
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="Cole\BackendBundle\Entity\GrupoRepository")
 */
class Grupo
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
     * @ORM\Column(name="letra", type="string", length=5)
     */
    private $letra;

    /**
     * @var string
     *
     * @ORM\Column(name="aula", type="string", length=10, nullable=true)
     */
    private $aula;

    /**
     * @ORM\OneToMany(targetEntity="Imparte", mappedBy="grupo")
     */
    private $imparte;

    /**
     * @ORM\ManyToOne(targetEntity="Curso", inversedBy="grupos")
     * @ORM\JoinColumn(name="curso_id", referencedColumnName="id", nullable=false)
     */
    private $curso;

    /**
     * @ORM\OneToOne(targetEntity="Profesor", inversedBy="grupo")
     * @ORM\JoinColumn(name="tutor", referencedColumnName="id")
     */
    private $profesor;

    /**
    * @ORM\OneToMany(targetEntity="Alumno", mappedBy="grupo")
    */
    private $alumnos;

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
     * Set letra
     *
     * @param string $letra
     * @return Grupo
     */
    public function setLetra($letra)
    {
        $this->letra = $letra;

        return $this;
    }

    /**
     * Get letra
     *
     * @return string 
     */
    public function getLetra()
    {
        return $this->letra;
    }

    /**
     * Set aula
     *
     * @param string $aula
     * @return Grupo
     */
    public function setAula($aula)
    {
        $this->aula = $aula;

        return $this;
    }

    /**
     * Get aula
     *
     * @return string 
     */
    public function getAula()
    {
        return $this->aula;
    }

    /**
     * Add imparte
     *
     * @param \Cole\BackendBundle\Entity\Imparte $imparte
     * @return Grupo
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
     * Constructor
     */
    public function __construct()
    {
        $this->imparte = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Set curso
     *
     * @param \Cole\BackendBundle\Entity\Curso $curso
     * @return Grupo
     */
    public function setCurso(\Cole\BackendBundle\Entity\Curso $curso)
    {
        $this->curso = $curso;

        return $this;
    }

    /**
     * Get curso
     *
     * @return \Cole\BackendBundle\Entity\Curso 
     */
    public function getCurso()
    {
        return $this->curso;
    }

        public function __toString()
    {
        return $this->getLetra();
    }

    /**
     * Set profesor
     *
     * @param \Cole\BackendBundle\Entity\Profesor $profesor
     * @return Grupo
     */
    public function setProfesor(\Cole\BackendBundle\Entity\Profesor $profesor = null)
    {
        $this->profesor = $profesor;

        return $this;
    }

    /**
     * Get profesor
     *
     * @return \Cole\BackendBundle\Entity\Profesor 
     */
    public function getProfesor()
    {
        return $this->profesor;
    }

    /**
     * Add alumnos
     *
     * @param \Cole\BackendBundle\Entity\Alumno $alumnos
     * @return Grupo
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
}
