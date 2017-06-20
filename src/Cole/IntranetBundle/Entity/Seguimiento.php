<?php

namespace Cole\IntranetBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Seguimiento
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="Cole\IntranetBundle\Entity\SeguimientoRepository")
 */
class Seguimiento
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
     * @ORM\Column(name="descripcion", type="text")
     * @Assert\NotBlank()
     * @Assert\Type("string")
     */
    private $descripcion;

    /**
     * @var boolean
     *
     * @ORM\Column(name="tipo", type="boolean")
     */
    private $tipo;

    /**
     * @var boolean
     *
     * @ORM\Column(name="tipo_user", type="boolean")
     */
    private $tipoUser;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="fecha", type="datetime")
     */
    private $fecha;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="fecha_actualizada", type="datetime" , nullable=true)
     */
    private $fechaActualizada;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="fecha_terminada", type="datetime" , nullable=true)
     */
    private $fechaTerminada;

    /**
    * @ORM\ManyToOne(targetEntity="Cole\BackendBundle\Entity\AsignaturasCursos", inversedBy="seguimiento")
    * @ORM\JoinColumn(name="asignatura_id", referencedColumnName="id", nullable=true)
    */
    private $asignatura;

    /**
     * @var boolean
     *
     * @ORM\Column(name="respuesta", type="boolean")
     */
    private $respuesta;

    /**
    * @ORM\ManyToOne(targetEntity="Cole\BackendBundle\Entity\Grupo", inversedBy="seguimiento")
    * @ORM\JoinColumn(name="grupo_id", referencedColumnName="id", nullable=false)
    */
    private $grupo;

    /**
    * @ORM\ManyToOne(targetEntity="Cole\BackendBundle\Entity\Profesor", inversedBy="seguimiento")
    * @ORM\JoinColumn(name="profesor_id", referencedColumnName="id", nullable=false)
    */
    private $profesor;

    /* One-To-Many, Self-referencing */
    /** 
     * @ORM\OneToMany(targetEntity="Seguimiento", mappedBy="seguimiento", cascade={"remove"})
     */
    private $children;

    /**
     * @ORM\ManyToOne(targetEntity="Seguimiento", inversedBy="children" )
     * @ORM\JoinColumn(name="seguimiento_id", referencedColumnName="id")
     */
    private $seguimiento;

    /**
     * @ORM\ManyToOne(targetEntity="Cole\BackendBundle\Entity\Alumno", inversedBy="seguimiento")
     * @ORM\JoinColumn(name="alumno_id", referencedColumnName="id", nullable=true)
     */
    private $alumno;

    /**
     * @ORM\ManyToOne(targetEntity="Cole\BackendBundle\Entity\Padres", inversedBy="seguimiento")
     * @ORM\JoinColumn(name="responsable_id", referencedColumnName="id", nullable=true)
     */
    private $responsable;

    /**
     * @ORM\OneToOne(targetEntity="Tutorias", mappedBy="seguimiento")
     */
    private $tutoria;

    /**
     * Get id
     *
     * @return integer 
     */

    public function __construct() {
        $this->children = new \Doctrine\Common\Collections\ArrayCollection();
    }
    
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set tipo
     *
     * @param boolean $tipo
     * @return Seguimiento
     */
    public function setTipo($tipo)
    {
        $this->tipo = $tipo;

        return $this;
    }

    /**
     * Get tipo
     *
     * @return boolean 
     */
    public function getTipo()
    {
        return $this->tipo;
    }

    /**
     * Set tipoUser
     *
     * @param boolean $tipoUser
     * @return Seguimiento
     */
    public function setTipoUser($tipoUser)
    {
        $this->tipoUser = $tipoUser;

        return $this;
    }

    /**
     * Get tipoUser
     *
     * @return boolean 
     */
    public function getTipoUser()
    {
        return $this->tipoUser;
    }

    /**
     * Set fecha
     *
     * @param \DateTime $fecha
     * @return Seguimiento
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
     * Set descripcion
     *
     * @param string $descripcion
     * @return Seguimiento
     */
    public function setDescripcion($descripcion)
    {
        $this->descripcion = $descripcion;

        return $this;
    }

    /**
     * Get descripcion
     *
     * @return string 
     */
    public function getDescripcion()
    {
        return $this->descripcion;
    }

    /**
     * Set asignatura
     *
     * @param \Cole\BackendBundle\Entity\AsignaturasCursos $asignatura
     * @return Seguimiento
     */
    public function setAsignatura(\Cole\BackendBundle\Entity\AsignaturasCursos $asignatura = null)
    {
        $this->asignatura = $asignatura;

        return $this;
    }

    /**
     * Get asignatura
     *
     * @return \Cole\IntranetBundle\Entity\AsignaturasCursos 
     */
    public function getAsignatura()
    {
        return $this->asignatura;
    }

    /**
     * Set grupo
     *
     * @param \Cole\BackendBundleBundle\Entity\Grupo $grupo
     * @return Seguimiento
     */
    public function setGrupo(\Cole\BackendBundle\Entity\Grupo $grupo)
    {
        $this->grupo = $grupo;

        return $this;
    }

    /**
     * Get grupo
     *
     * @return \Cole\IntranetBundle\Entity\Grupo 
     */
    public function getGrupo()
    {
        return $this->grupo;
    }

    /**
     * Set alumno
     *
     * @param \Cole\BackendBundleBundle\Entity\Alumno $alumno
     * @return Seguimiento
     */
    public function setAlumno(\Cole\BackendBundle\Entity\Alumno $alumno = null)
    {
        $this->alumno = $alumno;

        return $this;
    }

    /**
     * Get alumno
     *
     * @return \Cole\IntranetBundle\Entity\Alumno 
     */
    public function getAlumno()
    {
        return $this->alumno;
    }

    /**
     * Set responsable
     *
     * @param \Cole\BackendBundleBundle\Entity\Padres $responsable
     * @return Seguimiento
     */
    public function setResponsable(\Cole\BackendBundle\Entity\Padres $responsable = null)
    {
        $this->responsable = $responsable;

        return $this;
    }

    /**
     * Get responsable
     *
     * @return \Cole\IntranetBundle\Entity\Padres 
     */
    public function getResponsable()
    {
        return $this->responsable;
    }

    /**
     * Set profesor
     *
     * @param \Cole\BackendBundle\Entity\Profesor $profesor
     * @return Seguimiento
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
     * Set fechaActualizada
     *
     * @param \DateTime $fechaActualizada
     * @return Seguimiento
     */
    public function setFechaActualizada($fechaActualizada)
    {
        $this->fechaActualizada = $fechaActualizada;

        return $this;
    }

    /**
     * Get fechaActualizada
     *
     * @return \DateTime 
     */
    public function getFechaActualizada()
    {
        return $this->fechaActualizada;
    }

    /**
     * Add children
     *
     * @param \Cole\IntranetBundle\Entity\Seguimiento $children
     * @return Seguimiento
     */
    public function addChild(\Cole\IntranetBundle\Entity\Seguimiento $children)
    {
        $this->children[] = $children;

        return $this;
    }

    /**
     * Remove children
     *
     * @param \Cole\IntranetBundle\Entity\Seguimiento $children
     */
    public function removeChild(\Cole\IntranetBundle\Entity\Seguimiento $children)
    {
        $this->children->removeElement($children);
    }

    /**
     * Get children
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getChildren()
    {
        return $this->children;
    }

    /**
     * Set seguimiento
     *
     * @param \Cole\IntranetBundle\Entity\Seguimiento $seguimiento
     * @return Seguimiento
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
     * Set respuesta
     *
     * @param boolean $respuesta
     * @return Seguimiento
     */
    public function setRespuesta($respuesta)
    {
        $this->respuesta = $respuesta;

        return $this;
    }

    /**
     * Get respuesta
     *
     * @return boolean 
     */
    public function getRespuesta()
    {
        return $this->respuesta;
    }

    public function __toString()
    {
        return (string)$this->getId();
    }




    /**
     * Set tutoria
     *
     * @param \Cole\IntranetBundle\Entity\Tutorias $tutoria
     * @return Seguimiento
     */
    public function setTutoria(\Cole\IntranetBundle\Entity\Tutorias $tutoria = null)
    {
        $this->tutoria = $tutoria;

        return $this;
    }

    /**
     * Get tutoria
     *
     * @return \Cole\IntranetBundle\Entity\Tutorias 
     */
    public function getTutoria()
    {
        return $this->tutoria;
    }


    /**
     * Set fechaTerminada
     *
     * @param \DateTime $fechaTerminada
     * @return Seguimiento
     */
    public function setFechaTerminada($fechaTerminada)
    {
        $this->fechaTerminada = $fechaTerminada;

        return $this;
    }

    /**
     * Get fechaTerminada
     *
     * @return \DateTime 
     */
    public function getFechaTerminada()
    {
        return $this->fechaTerminada;
    }
}
