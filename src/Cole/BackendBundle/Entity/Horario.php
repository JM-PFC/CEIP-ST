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
     * @var string
     *
     * @ORM\Column(name="tipo", type="string", length=50,nullable=true)
     */
    private $tipo;

    /**
     * @var string
     *
     * @ORM\Column(name="duracion", type="string", length=10,nullable=true)
     */
    private $duracion;
    
    /**
     * @ORM\OneToMany(targetEntity="Reserva", mappedBy="horario", cascade={"remove"})
     */
    private $reserva;

    /**
    * @ORM\OneToMany(targetEntity="Imparte", mappedBy="horario")
    */
    private $imparte;

   /**
    * @ORM\OneToMany(targetEntity="Cole\IntranetBundle\Entity\Ausencia", mappedBy="horario", cascade={"remove"})
    */
    private $ausencia;


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

    /**
     * Set tipo
     *
     * @param string $tipo
     * @return Horario
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
     * Set duracion
     *
     * @param string $duracion
     * @return Horario
     */
    public function setDuracion($duracion)
    {
        $this->duracion = $duracion;

        return $this;
    }

    /**
     * Get duracion
     *
     * @return string 
     */
    public function getDuracion()
    {
        return $this->duracion;
    }
    public function __toString()
    {
        return $this->getHoraClase();
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
     * Add ausencia
     *
     * @param \Cole\IntranetBundle\Entity\Ausencia $ausencia
     * @return Horario
     */
    public function addAusencium(\Cole\IntranetBundle\Entity\Ausencia $ausencia)
    {
        $this->ausencia[] = $ausencia;

        return $this;
    }

    /**
     * Remove ausencia
     *
     * @param \Cole\IntranetBundle\Entity\Ausencia $ausencia
     */
    public function removeAusencium(\Cole\IntranetBundle\Entity\Ausencia $ausencia)
    {
        $this->ausencia->removeElement($ausencia);
    }

    /**
     * Get ausencia
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getAusencia()
    {
        return $this->ausencia;
    }
}
