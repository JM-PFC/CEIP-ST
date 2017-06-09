<?php

namespace Cole\IntranetBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Avisos
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="Cole\IntranetBundle\Entity\AvisosRepository")
 */
class Avisos
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
     * @ORM\Column(name="idUsuario", type="string", length=20)
     */
    private $idUsuario;

    /**
     * @var string
     *
     * @ORM\Column(name="idResponsable", type="string", length=20 , nullable=true)
     */
    private $idResponsable;

    /**
     * @var string
     *
     * @ORM\Column(name="tipoUsuario", type="string", length=50)
     */
    private $tipoUsuario;

    /**
     * @var string
     *
     * @ORM\Column(name="idAviso", type="string", length=20)
     */
    private $idAviso;

    /**
     * @var string
     *
     * @ORM\Column(name="tipoAviso", type="string", length=50)
     */
    private $tipoAviso;


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
     * Set idUsuario
     *
     * @param string $idUsuario
     * @return Avisos
     */
    public function setIdUsuario($idUsuario)
    {
        $this->idUsuario = $idUsuario;

        return $this;
    }

    /**
     * Get idUsuario
     *
     * @return string 
     */
    public function getIdUsuario()
    {
        return $this->idUsuario;
    }

    /**
     * Set tipoUsuario
     *
     * @param string $tipoUsuario
     * @return Avisos
     */
    public function setTipoUsuario($tipoUsuario)
    {
        $this->tipoUsuario = $tipoUsuario;

        return $this;
    }

    /**
     * Get tipoUsuario
     *
     * @return string 
     */
    public function getTipoUsuario()
    {
        return $this->tipoUsuario;
    }

    /**
     * Set idAviso
     *
     * @param string $idAviso
     * @return Avisos
     */
    public function setIdAviso($idAviso)
    {
        $this->idAviso = $idAviso;

        return $this;
    }

    /**
     * Get idAviso
     *
     * @return string 
     */
    public function getIdAviso()
    {
        return $this->idAviso;
    }

    /**
     * Set tipoAviso
     *
     * @param string $tipoAviso
     * @return Avisos
     */
    public function setTipoAviso($tipoAviso)
    {
        $this->tipoAviso = $tipoAviso;

        return $this;
    }

    /**
     * Get tipoAviso
     *
     * @return string 
     */
    public function getTipoAviso()
    {
        return $this->tipoAviso;
    }

    /**
     * Set idResponsable
     *
     * @param string $idResponsable
     * @return Avisos
     */
    public function setIdResponsable($idResponsable)
    {
        $this->idResponsable = $idResponsable;

        return $this;
    }

    /**
     * Get idResponsable
     *
     * @return string 
     */
    public function getIdResponsable()
    {
        return $this->idResponsable;
    }
}
