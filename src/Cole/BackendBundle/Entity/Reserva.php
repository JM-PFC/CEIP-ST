<?php

namespace Cole\BackendBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Reserva
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="Cole\BackendBundle\Entity\ReservaRepository")
 */
class Reserva
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
    * @ORM\ManyToOne(targetEntity="Profesor", inversedBy="reserva")
    * @ORM\JoinColumn(name="profesor_id", referencedColumnName="id", nullable=false)
    */
    private $profesor;

    /**
    * @ORM\ManyToOne(targetEntity="Equipamiento", inversedBy="reserva")
    * @ORM\JoinColumn(name="equipamiento_id", referencedColumnName="id", nullable=false)
    */
    private $equipamiento;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="fecha", type="datetime")
     */
    private $fecha;

    /**
     * @var string
     *
     * @ORM\Column(name="propósito", type="string", length=300)
     */
    private $propósito;


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
     * Set fecha
     *
     * @param \DateTime $fecha
     * @return Reserva
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
     * Set propósito
     *
     * @param string $propósito
     * @return Reserva
     */
    public function setPropósito($propósito)
    {
        $this->propósito = $propósito;

        return $this;
    }

    /**
     * Get propósito
     *
     * @return string 
     */
    public function getPropósito()
    {
        return $this->propósito;
    }

    /**
     * Set profesor
     *
     * @param \Cole\BackendBundle\Entity\Profesor $profesor
     * @return Reserva
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
     * Set equipamiento
     *
     * @param \Cole\BackendBundle\Entity\Equipamiento $equipamiento
     * @return Reserva
     */
    public function setEquipamiento(\Cole\BackendBundle\Entity\Equipamiento $equipamiento)
    {
        $this->equipamiento = $equipamiento;

        return $this;
    }

    /**
     * Get equipamiento
     *
     * @return \Cole\BackendBundle\Entity\Equipamiento 
     */
    public function getEquipamiento()
    {
        return $this->equipamiento;
    }
}
