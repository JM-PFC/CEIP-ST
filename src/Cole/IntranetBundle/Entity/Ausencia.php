<?php

namespace Cole\IntranetBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Ausencia
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="Cole\IntranetBundle\Entity\AusenciaRepository")
 */
class Ausencia
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
     * @ORM\Column(name="tipo", type="string", length=255)
     */
    private $tipo;

    /**
     * @var date
     *
     * @ORM\Column(name="fecha", type="date")
     */
    private $fecha;

    /**
     * @var string
     *
     * @ORM\Column(name="justificacion", type="text" , nullable=true)
     */
    private $justificacion;

    /**
     * @var string
     *
     * @ORM\Column(name="confirmada", type="string", length=1 , nullable=true)
     * 
     */
    private $confirmada;

    /**
    * @ORM\ManyToOne(targetEntity="Cole\BackendBundle\Entity\Alumno", inversedBy="Ausencia")
    * @ORM\JoinColumn(name="alumno_id", referencedColumnName="id", nullable=false)
    */
    private $alumno;

    /**
    * @ORM\ManyToOne(targetEntity="Cole\BackendBundle\Entity\AsignaturasCursos", inversedBy="Ausencia")
    * @ORM\JoinColumn(name="asignatura", referencedColumnName="id", nullable=false)
    */
    private $asignatura;

    /**
     * @ORM\ManyToOne(targetEntity="Cole\BackendBundle\Entity\Horario", inversedBy="ausencia")
     * @ORM\JoinColumn(name="horario", referencedColumnName="id")
     */
    private $horario;

    /**
     * @ORM\ManyToOne(targetEntity="Cole\BackendBundle\Entity\Padres", inversedBy="faltas")
     * @ORM\JoinColumn(name="responsable", referencedColumnName="id", nullable=true)
     */
    private $responsable;

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
     * Set tipo
     *
     * @param string $tipo
     * @return Ausencia
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

    /**
     * Set justificacion
     *
     * @param string $justificacion
     * @return Ausencia
     */
    public function setJustificacion($justificacion)
    {
        $this->justificacion = $justificacion;

        return $this;
    }

    /**
     * Get justificacion
     *
     * @return string 
     */
    public function getJustificacion()
    {
        return $this->justificacion;
    }

    /**
     * Set fecha
     *
     * @param \DateTime $fecha
     * @return Ausencia
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
     * @return Ausencia
     */
    public function setAlumno(\Cole\BackendBundle\Entity\Alumno $alumno)
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
     * Set asignatura
     *
     * @param \Cole\BackendBundle\Entity\AsignaturasCursos $asignatura
     * @return Ausencia
     */
    public function setAsignatura(\Cole\BackendBundle\Entity\AsignaturasCursos $asignatura)
    {
        $this->asignatura = $asignatura;

        return $this;
    }

    /**
     * Get asignatura
     *
     * @return \Cole\BackendBundle\Entity\AsignaturasCursos 
     */
    public function getAsignatura()
    {
        return $this->asignatura;
    }

    /**
     * Set horario
     *
     * @param \Cole\BackendBundle\Entity\Horario $horario
     * @return Ausencia
     */
    public function setHorario(\Cole\BackendBundle\Entity\Horario $horario = null)
    {
        $this->horario = $horario;

        return $this;
    }

    /**
     * Get horario
     *
     * @return \Cole\BackendBundle\Entity\Horario 
     */
    public function getHorario()
    {
        return $this->horario;
    }

    /**
     * Set responsable
     *
     * @param \Cole\BackendBundle\Entity\Padres $responsable
     * @return Ausencia
     */
    public function setResponsable(\Cole\BackendBundle\Entity\Padres $responsable)
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
     * Set confirmada
     *
     * @param string $confirmada
     * @return Ausencia
     */
    public function setConfirmada($confirmada)
    {
        $this->confirmada = $confirmada;

        return $this;
    }

    /**
     * Get confirmada
     *
     * @return string 
     */
    public function getConfirmada()
    {
        return $this->confirmada;
    }
}
