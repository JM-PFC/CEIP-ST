<?php

namespace Cole\BackendBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Festivos
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="Cole\BackendBundle\Entity\FestivosRepository")
 */
class Festivos
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
     * @var integer
     *
     * @ORM\Column(name="dia", type="integer")     
     */
    private $dia;

    /**
     * @var string
     *
     * @ORM\Column(name="mes", type="string", length=15)     
     */
    private $mes;

    /**
     * @var string
     *
     * @ORM\Column(name="numMes", type="string", length=2)
     */
    private $numMes;

    /**
     * @var string
     *
     * @ORM\Column(name="descripcion", type="string", length=255)
     */
    private $descripcion;

    /**
     * @var string
     *
     * @ORM\Column(name="tipo", type="string", length=15)
     */
    private $tipo;


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
     * @return Festivos
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
     * Set tipo
     *
     * @param string $tipo
     * @return Festivos
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
     * Set mes
     *
     * @param string $mes
     * @return Festivos
     */
    public function setMes($mes)
    {
        $this->mes = $mes;

        return $this;
    }

    /**
     * Get mes
     *
     * @return string 
     */
    public function getMes()
    {
        return $this->mes;
    }


    /**
     * Set dia
     *
     * @param integer $dia
     * @return Festivos
     */
    public function setDia($dia)
    {
        $this->dia = $dia;

        return $this;
    }

    /**
     * Get dia
     *
     * @return integer 
     */
    public function getDia()
    {
        return $this->dia;
    }

    /**
     * Set numMes
     *
     * @param string $numMes
     * @return Festivos
     */
    public function setNumMes($numMes)
    {
        $this->numMes = $numMes;

        return $this;
    }

    /**
     * Get numMes
     *
     * @return string 
     */
    public function getNumMes()
    {
        return $this->numMes;
    }

    public function __toString()
    {
        return $this->getDia().' '.$this->getMes().' '.$this->getDescripcion();
    }
}
