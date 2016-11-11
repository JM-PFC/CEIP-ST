<?php

namespace Cole\BackendBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Matricula
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="Cole\BackendBundle\Entity\MatriculaRepository")
 */
class Matricula
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
    * @ORM\ManyToOne(targetEntity="Alumno", inversedBy="matricula")
    * @ORM\JoinColumn(name="alumno_id", referencedColumnName="id")
    */
    private $alumno;

    /**
    * @ORM\ManyToOne(targetEntity="Curso", inversedBy="matricula")
    * @ORM\JoinColumn(name="curso_id", referencedColumnName="id")
    */
    private $curso;

    /**
    * @ORM\ManyToOne(targetEntity="Grupo", inversedBy="matricula")
    * @ORM\JoinColumn(name="grupo_id", referencedColumnName="id")
    */
    private $grupo;

    /**
     * @var string
     *
     * @ORM\Column(name="añoAcademico", type="string", length=255)
     */
    private $añoAcademico;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="fecha", type="datetime")
     */
    private $fecha;


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
     * Set añoAcademico
     *
     * @param string $añoAcademico
     * @return Matricula
     */
    public function setAñoAcademico($añoAcademico)
    {
        $this->añoAcademico = $añoAcademico;

        return $this;
    }

    /**
     * Get añoAcademico
     *
     * @return string 
     */
    public function getAñoAcademico()
    {
        return $this->añoAcademico;
    }

    /**
     * Set fecha
     *
     * @param \DateTime $fecha
     * @return Matricula
     */
    public function setFecha($fecha)
    {
        $this->fecha = $fecha;

        return $this;
    }

    /**
     * Get fecha
     *
     * @return \DateTime 
     */
    public function getFecha()
    {
        return $this->fecha;
    }

    /**
     * Set alumno
     *
     * @param \Cole\BackendBundle\Entity\Alumno $alumno
     * @return Matricula
     */
    public function setAlumno(\Cole\BackendBundle\Entity\Alumno $alumno = null)
    {
        $this->alumno = $alumno;

        return $this;
    }

    /**
     * Get alumno
     *
     * @return \Cole\BackendBundle\Entity\Alumno 
     */
    public function getAlumno()
    {
        return $this->alumno;
    }

    /**
     * Set curso
     *
     * @param \Cole\BackendBundle\Entity\Curso $curso
     * @return Matricula
     */
    public function setCurso(\Cole\BackendBundle\Entity\Curso $curso = null)
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

    /**
     * Set grupo
     *
     * @param \Cole\BackendBundle\Entity\Grupo $grupo
     * @return Matricula
     */
    public function setGrupo(\Cole\BackendBundle\Entity\Grupo $grupo = null)
    {
        $this->grupo = $grupo;

        return $this;
    }

    /**
     * Get grupo
     *
     * @return \Cole\BackendBundle\Entity\Grupo 
     */
    public function getGrupo()
    {
        return $this->grupo;
    }
}
