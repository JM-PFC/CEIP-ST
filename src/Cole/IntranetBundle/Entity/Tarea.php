<?php

namespace Cole\IntranetBundle\Entity;
use Symfony\Component\Validator\Constraints as Assert;

use Doctrine\ORM\Mapping as ORM;

/**
 * Tarea
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="Cole\IntranetBundle\Entity\TareaRepository")
 */
class Tarea
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
     * @ORM\Column(name="descripcion", type="string", length=255)
     */
    private $descripcion;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="fecha", type="datetime")
     */
    private $fecha;

    /**
    * @ORM\OneToMany(targetEntity="Cursa", mappedBy="tarea", cascade={"remove"})
    */
    private $cursa;

    /**
    * @ORM\ManyToOne(targetEntity="Cole\BackendBundle\Entity\Grupo", inversedBy="tarea")
    * @ORM\JoinColumn(name="grupo_id", referencedColumnName="id", nullable=false)
    */
    private $grupo;

    /**
    * @ORM\ManyToOne(targetEntity="Cole\BackendBundle\Entity\Profesor", inversedBy="tarea")
    * @ORM\JoinColumn(name="profesor_id", referencedColumnName="id", nullable=false)
    */
    private $profesor;

    /**
    * @ORM\ManyToOne(targetEntity="Cole\BackendBundle\Entity\AsignaturasCursos", inversedBy="tarea")
    * @ORM\JoinColumn(name="asignatura_id", referencedColumnName="id", nullable=true)
    */
    private $asignatura;
    
    /**
     * @var string
     *
     * @ORM\Column(name="trimestre", type="string", length=1, nullable=true)
     */
    private $trimestre;

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
     * Set descripcion
     *
     * @param string $descripcion
     * @return Tarea
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
     * Set fecha
     *
     * @param \DateTime $fecha
     * @return Tarea
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
     * Constructor
     */
    public function __construct()
    {
        $this->cursa = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Add cursa
     *
     * @param \Cole\IntranetBundle\Entity\Cursa $cursa
     * @return Tarea
     */
    public function addCursa(\Cole\IntranetBundle\Entity\Cursa $cursa)
    {
        $this->cursa[] = $cursa;

        return $this;
    }

    /**
     * Remove cursa
     *
     * @param \Cole\IntranetBundle\Entity\Cursa $cursa
     */
    public function removeCursa(\Cole\IntranetBundle\Entity\Cursa $cursa)
    {
        $this->cursa->removeElement($cursa);
    }

    /**
     * Get cursa
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getCursa()
    {
        return $this->cursa;
    }

    /**
     * Set grupo
     *
     * @param \Cole\BackendBundle\Entity\Grupo $grupo
     * @return Tarea
     */
    public function setGrupo(\Cole\BackendBundle\Entity\Grupo $grupo)
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

    /**
     * Set profesor
     *
     * @param \Cole\BackendBundle\Entity\Profesor $profesor
     * @return Tarea
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
     * Set asignatura
     *
     * @param \Cole\BackendBundle\Entity\AsignaturasCursos $asignatura
     * @return Tarea
     */
    public function setAsignatura(\Cole\BackendBundle\Entity\AsignaturasCursos $asignatura = null)
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

    public function __toString()
    {
        return $this->getDescripcion();
    }


    /**
     * Set trimestre
     *
     * @param string $trimestre
     * @return Tarea
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
}
