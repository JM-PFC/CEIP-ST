<?php

namespace Cole\IntranetBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Cursa
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="Cole\IntranetBundle\Entity\CursaRepository")
 */
class Cursa
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
     * @ORM\Column(name="trimestre", type="string", length=1, nullable=true)
     */
    private $trimestre;

    /**
     * @var integer
     *
     * @ORM\Column(name="nota", type="integer", nullable=true)
     */
    private $nota;

    /**
    * @ORM\ManyToOne(targetEntity="Cole\BackendBundle\Entity\Alumno", inversedBy="Cursa")
    * @ORM\JoinColumn(name="alumno_id", referencedColumnName="id")
    */
    private $alumno;

    /**
    * @ORM\ManyToOne(targetEntity="Cole\BackendBundle\Entity\AsignaturasCursos", inversedBy="Cursa")
    * @ORM\JoinColumn(name="asignaturasCursos_id", referencedColumnName="id", nullable=false)
    */
    private $asignaturasCursos;

    /**
     * @ORM\ManyToOne(targetEntity="Tarea", inversedBy="cursa")
     * @ORM\JoinColumn(name="tarea_id", referencedColumnName="id", nullable=true)
     */
    private $tarea;

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
     * Set trimestre
     *
     * @param string $trimestre
     * @return Cursa
     */
    public function setTrimestre($trimestre)
    {
        $this->trimestre = $trimestre;

        return $this;
    }

    /**
     * Get trimestre
     *
     * @return string 
     */
    public function getTrimestre()
    {
        return $this->trimestre;
    }

    /**
     * Set nota
     *
     * @param integer $nota
     * @return Cursa
     */
    public function setNota($nota)
    {
        $this->nota = $nota;

        return $this;
    }

    /**
     * Get nota
     *
     * @return integer 
     */
    public function getNota()
    {
        return $this->nota;
    }
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->tareas = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Set alumno
     *
     * @param \Cole\BackendBundle\Entity\Alumno $alumno
     * @return Cursa
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
     * Set asignaturasCursos
     *
     * @param \Cole\BackendBundle\Entity\AsignaturasCursos $asignaturasCursos
     * @return Cursa
     */
    public function setAsignaturasCursos(\Cole\BackendBundle\Entity\AsignaturasCursos $asignaturasCursos)
    {
        $this->asignaturasCursos = $asignaturasCursos;

        return $this;
    }

    /**
     * Get asignaturasCursos
     *
     * @return \Cole\BackendBundle\Entity\AsignaturasCursos 
     */
    public function getAsignaturasCursos()
    {
        return $this->asignaturasCursos;
    }


    /**
     * Set tarea
     *
     * @param \Cole\IntranetBundle\Entity\Tarea $tarea
     * @return Cursa
     */
    public function setTarea(\Cole\IntranetBundle\Entity\Tarea $tarea)
    {
        $this->tarea = $tarea;

        return $this;
    }

    /**
     * Get tarea
     *
     * @return \Cole\IntranetBundle\Entity\Tarea 
     */
    public function getTarea()
    {
        return $this->tarea;
    }
}
