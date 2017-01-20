<?php

namespace Cole\BackendBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Horario
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="Cole\BackendBundle\Entity\HorarioRepository")
 */
class Horario
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
     * @ORM\Column(name="horaClase", type="string", length=50)
     */
    private $horaClase;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="inicio", type="time")
     */
    private $inicio;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="fin", type="time")
     */
    private $fin;

    /**
     * @ORM\OneToMany(targetEntity="Reserva", mappedBy="horario", cascade={"remove"})
     */
    private $reserva;

    /**
    * @ORM\OneToMany(targetEntity="Imparte", mappedBy="horario",cascade={"remove"})
    */
    private $imparte;

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
     * Set horaClase
     *
     * @param string $horaClase
     * @return Horario
     */
    public function setHoraClase($horaClase)
    {
        $this->horaClase = $horaClase;

        return $this;
    }

    /**
     * Get horaClase
     *
     * @return string 
     */
    public function getHoraClase()
    {
        return $this->horaClase;
    }

    /**
     * Set inicio
     *
     * @param \DateTime $inicio
     * @return Horario
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
     * @return Horario
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
     * Constructor
     */
    public function __construct()
    {
        $this->reserva = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Add reserva
     *
     * @param \Cole\BackendBundle\Entity\Reserva $reserva
     * @return Horario
     */
    public function addReserva(\Cole\BackendBundle\Entity\Reserva $reserva)
    {
        $this->reserva[] = $reserva;

        return $this;
    }

    /**
     * Remove reserva
     *
     * @param \Cole\BackendBundle\Entity\Reserva $reserva
     */
    public function removeReserva(\Cole\BackendBundle\Entity\Reserva $reserva)
    {
        $this->reserva->removeElement($reserva);
    }

    /**
     * Get reserva
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getReserva()
    {
        return $this->reserva;
    }

    /**
     * Add imparte
     *
     * @param \Cole\BackendBundle\Entity\Imparte $imparte
     * @return Horario
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
}
