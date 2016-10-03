<?php

namespace Cole\BackendBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Equipamiento
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="Cole\BackendBundle\Entity\EquipamientoRepository")
 */
class Equipamiento
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
     * @ORM\Column(name="nombre", type="string", length=255)
     */
    private $nombre;

    /**
     * @var integer
     *
     * @ORM\Column(name="unidades", type="integer")
     */
    private $unidades;

    /**
     * @var string
     *
     * @ORM\Column(name="tipo", type="string", length=150)
     */
    private $tipo;

    /**
     * @ORM\OneToMany(targetEntity="Reserva", mappedBy="equipamiento")
     */
    private $reserva;


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
     * Set nombre
     *
     * @param string $nombre
     * @return Equipamiento
     */
    public function setNombre($nombre)
    {
        $this->nombre = $nombre;

        return $this;
    }

    /**
     * Get nombre
     *
     * @return string 
     */
    public function getNombre()
    {
        return $this->nombre;
    }

    /**
     * Set unidades
     *
     * @param string $unidades
     * @return Equipamiento
     */
    public function setUnidades($unidades)
    {
        $this->unidades = $unidades;

        return $this;
    }

    /**
     * Get unidades
     *
     * @return string 
     */
    public function getUnidades()
    {
        return $this->unidades;
    }

    /**
     * Set tipo
     *
     * @param string $tipo
     * @return Equipamiento
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
     * @return Equipamiento
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
}
