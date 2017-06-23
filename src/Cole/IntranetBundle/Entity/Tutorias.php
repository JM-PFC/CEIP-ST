<?php

namespace Cole\IntranetBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Tutorias
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="Cole\IntranetBundle\Entity\TutoriasRepository")
 */
class Tutorias
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
     * @var \Date
     *
     * @ORM\Column(name="fecha", type="date")
     */
    private $fecha;

    /**
     * @var \Time
     *
     * @ORM\Column(name="hora", type="time")
     */
    private $hora;

    /**
     * @var boolean
     *
     * @ORM\Column(name="activo", type="boolean")
     */
    private $activo;

    /**
    * @ORM\ManyToOne(targetEntity="Cole\BackendBundle\Entity\Profesor", inversedBy="tutorias")
    * @ORM\JoinColumn(name="profesor_id", referencedColumnName="id", nullable=false)
    */
    private $profesor;

    /**
     * @ORM\ManyToOne(targetEntity="Cole\BackendBundle\Entity\Alumno", inversedBy="tutorias")
     * @ORM\JoinColumn(name="alumno_id", referencedColumnName="id")
     */
    private $alumno;

    /**
     * @ORM\ManyToOne(targetEntity="Cole\BackendBundle\Entity\Padres", inversedBy="tutorias")
     * @ORM\JoinColumn(name="responsable_id", referencedColumnName="id")
     */
    private $responsable;

    /**
     * @ORM\OneToOne(targetEntity="Seguimiento", inversedBy="tutoria")
     * @ORM\JoinColumn(name="seguimiento", referencedColumnName="id")
     */
    private $seguimiento;

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
     * Set activo
     *
     * @param boolean $activo
     * @return Tutorias
     */
    public function setActivo($activo)
    {
        $this->activo = $activo;

        return $this;
    }

    /**
     * Get activo
     *
     * @return boolean 
     */
    public function getActivo()
    {
        return $this->activo;
    }

    /**
     * Set profesor
     *
     * @param \Cole\BackendBundle\Entity\Profesor $profesor
     * @return Tutorias
     */
    public function setProfesor(\Cole\BackendBundle\Entity\Profesor $profesor)
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
     * Set alumno
     *
     * @param \Cole\BackendBundle\Entity\Alumno $alumno
     * @return Tutorias
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
     * Set responsable
     *
     * @param \Cole\BackendBundle\Entity\Padres $responsable
     * @return Tutorias
     */
    public function setResponsable(\Cole\BackendBundle\Entity\Padres $responsable = null)
    {
        $this->responsable = $responsable;

        return $this;
    }

    /**
     * Get responsable
     *
     * @return \Cole\BackendBundle\Entity\Padres 
     */
    public function getResponsable()
    {
        return $this->responsable;
    }

    /**
     * Set seguimiento
     *
     * @param \Cole\IntranetBundle\Entity\Seguimiento $seguimiento
     * @return Tutorias
     */
    public function setSeguimiento(\Cole\IntranetBundle\Entity\Seguimiento $seguimiento = null)
    {
        $this->seguimiento = $seguimiento;

        return $this;
    }

    /**
     * Get seguimiento
     *
     * @return \Cole\IntranetBundle\Entity\Seguimiento 
     */
    public function getSeguimiento()
    {
        return $this->seguimiento;
    }

    /**
     * Set fecha
     *
     * @param \DateTime $fecha
     * @return Tutorias
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
     * Set hora
     *
     * @param \DateTime $hora
     * @return Tutorias
     */
    public function setHora($hora)
    {
        $this->hora = $hora;

        return $this;
    }

    /**
     * Get hora
     *
     * @return \DateTime 
     */
    public function getHora()
    {
        return $this->hora;
    }
}
