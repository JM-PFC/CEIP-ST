<?php

namespace Cole\IntranetBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
/**
 * Comunicacion
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="Cole\IntranetBundle\Entity\ComunicacionRepository")
 */
class Comunicacion
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
     * @ORM\Column(name="emisor", type="string", length=20, nullable=false)
     */
    private $emisor;

    /**
     * @var string
     *
     * @ORM\Column(name="receptor", type="string", length=20, nullable=false)
     */
    private $receptor;

    /**
     * 
     * @ORM\ManyToOne(targetEntity="Cole\BackendBundle\Entity\Padres", inversedBy="mensajes")
     * 
     */
    private $responsableEmisor;
    /**
     * 
     * @ORM\ManyToOne(targetEntity="Cole\BackendBundle\Entity\Padres", inversedBy="mensajes")
     * 
     */
    private $responsableReceptor;

        /**
     * 
     * @ORM\ManyToOne(targetEntity="Cole\BackendBundle\Entity\Profesor", inversedBy="mensajes")
     * 
     */
    private $profesorEmisor;
    /**
     * 
     * @ORM\ManyToOne(targetEntity="Cole\BackendBundle\Entity\Profesor", inversedBy="mensajes")
     * 
     */
    private $profesorReceptor;

    /**
     * @var string
     *
     * @ORM\Column(name="asunto", type="string", length=255, nullable=true)
     */
    private $asunto;

    /**
     * @var string
     *
     * @ORM\Column(name="mensaje", type="text", nullable=false)
     */
    private $mensaje;

    /**
     * @var boolean
     *
     * @ORM\Column(name="leido", type="boolean")
     */
    private $leido;

    /**
     * @var string
     *
     * @ORM\Column(name="fichero", type="string", length=255, nullable=true)
     */
    private $fichero;
    
    /**
     * @var date
     *
     * @ORM\Column(name="fecha", type="date")
     */
    private $fecha;

    /**
     * @var string
     *
     * @ORM\Column(name="tipoEmisor", type="string", length=100, nullable=false)
     */
    private $tipoEmisor;

    /**
     * @var string
     *
     * @ORM\Column(name="tipoReceptor", type="string", length=100, nullable=false)
     */
    private $tipoReceptor;

    /**
     * @var boolean
     *
     * @ORM\Column(name="eliminadoEmisor", type="boolean", nullable=true)
     */
    private $eliminadoEmisor;

        /**
     * @var boolean
     *
     * @ORM\Column(name="eliminadoReceptor", type="boolean", nullable=true)
     */
    private $eliminadoReceptor;
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
     * Set asunto
     *
     * @param string $asunto
     * @return Comunicacion
     */
    public function setAsunto($asunto)
    {
        $this->asunto = $asunto;

        return $this;
    }

    /**
     * Get asunto
     *
     * @return string 
     */
    public function getAsunto()
    {
        return $this->asunto;
    }

    /**
     * Set mensaje
     *
     * @param string $mensaje
     * @return Comunicacion
     */
    public function setMensaje($mensaje)
    {
        $this->mensaje = $mensaje;

        return $this;
    }

    /**
     * Get mensaje
     *
     * @return string 
     */
    public function getMensaje()
    {
        return $this->mensaje;
    }

    /**
     * Set leido
     *
     * @param boolean $leido
     * @return Comunicacion
     */
    public function setLeido($leido)
    {
        $this->leido = $leido;

        return $this;
    }

    /**
     * Get leido
     *
     * @return boolean 
     */
    public function getLeido()
    {
        return $this->leido;
    }

    /**
     * Set fichero
     *
     * @param string $fichero
     * @return Comunicacion
     */
    public function setFichero($fichero)
    {
        $this->fichero = $fichero;

        return $this;
    }

    /**
     * Get fichero
     *
     * @return string 
     */
    public function getFichero()
    {
        return $this->fichero;
    }

    /**
     * Set tipoEmisor
     *
     * @param string $tipoEmisor
     * @return Comunicacion
     */
    public function setTipoEmisor($tipoEmisor)
    {
        $this->tipoEmisor = $tipoEmisor;

        return $this;
    }

    /**
     * Get tipoEmisor
     *
     * @return string 
     */
    public function getTipoEmisor()
    {
        return $this->tipoEmisor;
    }

    /**
     * Set tipoReceptor
     *
     * @param string $tipoReceptor
     * @return Comunicacion
     */
    public function setTipoReceptor($tipoReceptor)
    {
        $this->tipoReceptor = $tipoReceptor;

        return $this;
    }

    /**
     * Get tipoReceptor
     *
     * @return string 
     */
    public function getTipoReceptor()
    {
        return $this->tipoReceptor;
    }

    /**
     * Set eliminadoEmisor
     *
     * @param boolean $eliminadoEmisor
     * @return Comunicacion
     */
    public function setEliminadoEmisor($eliminadoEmisor)
    {
        $this->eliminadoEmisor = $eliminadoEmisor;

        return $this;
    }

    /**
     * Get eliminadoEmisor
     *
     * @return boolean 
     */
    public function getEliminadoEmisor()
    {
        return $this->eliminadoEmisor;
    }

    /**
     * Set eliminadoReceptor
     *
     * @param boolean $eliminadoReceptor
     * @return Comunicacion
     */
    public function setEliminadoReceptor($eliminadoReceptor)
    {
        $this->eliminadoReceptor = $eliminadoReceptor;

        return $this;
    }

    /**
     * Get eliminadoReceptor
     *
     * @return boolean 
     */
    public function getEliminadoReceptor()
    {
        return $this->eliminadoReceptor;
    }

    /**
     * Set fecha
     *
     * @param \DateTime $fecha
     * @return Comunicacion
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
     * Set responsableEmisor
     *
     * @param \Cole\IntranetBundle\Entity\Padres $responsableEmisor
     * @return Comunicacion
     */
    public function setResponsableEmisor(\Cole\BackendBundle\Entity\Padres $responsableEmisor = null)
    {
        $this->responsableEmisor = $responsableEmisor;

        return $this;
    }

    /**
     * Get responsableEmisor
     *
     * @return \Cole\IntranetBundle\Entity\Padres 
     */
    public function getResponsableEmisor()
    {
        return $this->responsableEmisor;
    }

    /**
     * Set responsableReceptor
     *
     * @param \Cole\IntranetBundle\Entity\Padres $responsableReceptor
     * @return Comunicacion
     */
    public function setResponsableReceptor(\Cole\BackendBundle\Entity\Padres $responsableReceptor = null)
    {
        $this->responsableReceptor = $responsableReceptor;

        return $this;
    }

    /**
     * Get responsableReceptor
     *
     * @return \Cole\IntranetBundle\Entity\Padres 
     */
    public function getResponsableReceptor()
    {
        return $this->responsableReceptor;
    }

    /**
     * Set profesorEmisor
     *
     * @param \Cole\IntranetBundle\Entity\Profesor $profesorEmisor
     * @return Comunicacion
     */
    public function setProfesorEmisor(\Cole\BackendBundle\Entity\Profesor $profesorEmisor = null)
    {
        $this->profesorEmisor = $profesorEmisor;

        return $this;
    }

    /**
     * Get profesorEmisor
     *
     * @return \Cole\IntranetBundle\Entity\Profesor 
     */
    public function getProfesorEmisor()
    {
        return $this->profesorEmisor;
    }

    /**
     * Set profesorReceptor
     *
     * @param \Cole\IntranetBundle\Entity\Profesor $profesorReceptor
     * @return Comunicacion
     */
    public function setProfesorReceptor(\Cole\BackendBundle\Entity\Profesor $profesorReceptor = null)
    {
        $this->profesorReceptor = $profesorReceptor;

        return $this;
    }

    /**
     * Get profesorReceptor
     *
     * @return \Cole\IntranetBundle\Entity\Profesor 
     */
    public function getProfesorReceptor()
    {
        return $this->profesorReceptor;
    }

    /**
     * Set emisor
     *
     * @param string $emisor
     * @return Comunicacion
     */
    public function setEmisor($emisor)
    {
        $this->emisor = $emisor;

        return $this;
    }

    /**
     * Get emisor
     *
     * @return string 
     */
    public function getEmisor()
    {
        return $this->emisor;
    }

    /**
     * Set receptor
     *
     * @param string $receptor
     * @return Comunicacion
     */
    public function setReceptor($receptor)
    {
        $this->receptor = $receptor;

        return $this;
    }

    /**
     * Get receptor
     *
     * @return string 
     */
    public function getReceptor()
    {
        return $this->receptor;
    }
}
