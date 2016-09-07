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
     * @ORM\Column(name="hora_clase", type="string", length=50)
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
    
}
