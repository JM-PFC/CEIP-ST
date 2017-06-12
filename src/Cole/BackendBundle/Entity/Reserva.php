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
    * @ORM\JoinColumn(name="profesor_id", referencedColumnName="id")
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
     * @ORM\Column(name="fecha", type="date")
     */
    private $fecha;

    /**
    * @ORM\ManyToOne(targetEntity="Horario", inversedBy="reserva")
    * @ORM\JoinColumn(name="horario_id", referencedColumnName="id", nullable=false)
    */
    private $horario;


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

    /**
     * Set inicio
     *
     * @param \DateTime $inicio
     * @return Reserva
     */
    public function setInicio($inicio)
    {
        $this->inicio = $inicio;

        return $this;
    }

    /**
     * Get inicio
     *
     * @return \DateTime 
     */
    public function getInicio()
    {
        return $this->inicio;
    }

    /**
     * Set fin
     *
     * @param \DateTime $fin
     * @return Reserva
     */
    public function setFin($fin)
    {
        $this->fin = $fin;

        return $this;
    }

    /**
     * Get fin
     *
     * @return \DateTime 
     */
    public function getFin()
    {
        return $this->fin;
    }



    /**
     * Set horario
     *
     * @param \Cole\BackendBundle\Entity\Horario $horario
     * @return Reserva
     */
    public function setHorario(\Cole\BackendBundle\Entity\Horario $horario)
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
}
