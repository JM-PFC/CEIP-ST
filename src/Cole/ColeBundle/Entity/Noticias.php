<?php

namespace Cole\ColeBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Noticias
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="Cole\ColeBundle\Entity\NoticiasRepository")
 */
class Noticias
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
     * @ORM\Column(name="titulo", type="string", length=255)
     * @Assert\NotBlank()
     * @Assert\Type("string")
     */
    private $titulo;

    /**
     * @var string
     *
     * @ORM\Column(name="descripcion", type="text")
     * @Assert\NotBlank()
     * @Assert\Type("string")
     */
    private $descripcion;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="fecha", type="datetime")
     */
    private $fecha;

    /**
     * @var string
     *
     * @ORM\Column(name="foto", type="string", length=255, nullable=true)
     * 
     */
    private $foto;

    /**
     * @var boolean
     *
     * @ORM\Column(name="mostrarFoto", type="boolean")
     * 
     */
    private $mostrarFoto;

    /**
     * @var string
     *
     * @ORM\Column(name="galeria", type="string", length=255, nullable=true)
     * 
     */
    private $galeria;

    /**
     * @var string
     *
     * @ORM\Column(name="categoria", type="string", length=255)
     */
    private $categoria;

    /**
     * @var integer
     *
     * @ORM\Column(name="contador", type="integer", nullable=true)
     */
    private $contador;

 

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
     * Constructor
     *
     */
    public function __construct()
    {
        $this->fecha = new \DateTime();

    }
    /**
     * Set titulo
     *
     * @param string $titulo
     * @return Noticias
     */
    public function setTitulo($titulo)
    {
        $this->titulo = $titulo;

        return $this;
    }

    /**
     * Get titulo
     *
     * @return string 
     */
    public function getTitulo()
    {
        return $this->titulo;
    }

    /**
     * Set descripcion
     *
     * @param string $descripcion
     * @return Noticias
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
     * @return Noticias
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
     * Set foto
     *
     * @param string $foto
     * @return Noticias
     */
    public function setFoto($foto)
    {
        $this->foto = $foto;

        return $this;
    }

    /**
     * Get foto
     *
     * @return string 
     */
    public function getFoto()
    {
        return $this->foto;
    }

    /**
     * Set categoria
     *
     * @param string $categoria
     * @return Noticias
     */
    public function setCategoria($categoria)
    {
        $this->categoria = $categoria;

        return $this;
    }

    /**
     * Get categoria
     *
     * @return string 
     */
    public function getCategoria()
    {
        return $this->categoria;
    }

    /**
     * Set contador
     *
     * @param integer $contador
     * @return Noticias
     */
    public function setContador($contador)
    {
        $this->contador = $contador;

        return $this;
    }

    /**
     * Get contador
     *
     * @return integer 
     */
    public function getContador()
    {
        return $this->contador;
    }

    /**
     * Set galeria
     *
     * @param string $galeria
     * @return Noticias
     */
    public function setGaleria($galeria)
    {
        $this->galeria = $galeria;

        return $this;
    }

    /**
     * Get galeria
     *
     * @return string 
     */
    public function getGaleria()
    {
        return $this->galeria;
    }

    /**
     * Set mostrarFoto
     *
     * @param boolean $mostrarFoto
     * @return Noticias
     */
    public function setMostrarFoto($mostrarFoto)
    {
        $this->mostrarFoto = $mostrarFoto;

        return $this;
    }

    /**
     * Get mostrarFoto
     *
     * @return boolean 
     */
    public function getMostrarFoto()
    {
        return $this->mostrarFoto;
    }
}
