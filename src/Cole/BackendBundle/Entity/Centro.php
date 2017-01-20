<?php

namespace Cole\BackendBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;


/**
 * Centro
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="Cole\BackendBundle\Entity\CentroRepository")
 */
class Centro
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
     * @Assert\NotBlank()
     * @Assert\Regex(pattern="/^[A-Za-záéíóúÁÉÍÓÚüÜñÑ]/",message="El nombre del colegio debe empezar por una letra.")
     */
    private $nombre;

   /**
     * @var string
     *
     * @ORM\Column(name="direccion", type="string", length=255)
     * @Assert\NotBlank()
     * @Assert\Regex(pattern="/^[A-Za-záéíóúÁÉÍÓÚüÜñÑ]/",message="La dirección debe empezar por una letra.")
     */
    private $direccion;

    /**
     * @var string
     *
     * @ORM\Column(name="localidad", type="string", length=255)
     * @Assert\NotBlank()
     * @Assert\Regex(pattern="/^[A-Za-záéíóúÁÉÍÓÚüÜñÑ][^0-9]/",message="El valor introducido es incorrecto.")
     */
    private $localidad;

    /**
     * @var string
     *
     * @ORM\Column(name="provincia", type="string", length=255)
     * @Assert\NotBlank()
     * @Assert\Regex(pattern="/^[A-Za-záéíóúÁÉÍÓÚüÜñÑ][^0-9]/",message="El valor introducido es incorrecto.")
     */
    private $provincia;

    /**
     * @var string
     *
     * @ORM\Column(name="cp", type="string", length=5)
     * @Assert\NotBlank()
     * @Assert\Regex(pattern="/^([1-9]{2}|[0-9][1-9]|[1-9][0-9])[0-9]{3}$/",message="No es un código postal válido.")
     */
    private $cp;

    /**
     * @var string
     *
     * @ORM\Column(name="telefono", type="string", length=12, nullable=true)
     * @Assert\Regex(pattern="/^\d{3}([- .]?\d{2}){3}$/",message="No es un número de teléfono válido.")
     */
    private $telefono;

    /**
     * @var string
     *
     * @ORM\Column(name="email", type="string", length=255, nullable=true)
     * @Assert\Email()
     */
    private $email;

    /**
     * @var string
     *
     * @ORM\Column(name="web", type="string", length=255, nullable=true)    
     * @Assert\Regex(pattern="/^(www|http:\/\/)(.*\.(com$|es$|net$|org$))$/",message="No es una direccion web válida.")
     */
    private $web;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="inicioCurso", type="date" , nullable=true)
     */
    private $inicioCurso;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="finCurso", type="date" , nullable=true)
     */
    private $finCurso;

    /**
     * @var string
     *
     * @ORM\Column(name="inicioHorario", type="string", length=255, nullable=true)
     */
    private $inicioHorario;

    /**
     * @var string
     *
     * @ORM\Column(name="finHorario", type="string", length=255, nullable=true)
     */
    private $finHorario;


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
     * @return Centro
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
     * Set direccion
     *
     * @param string $direccion
     * @return Centro
     */
    public function setDireccion($direccion)
    {
        $this->direccion = $direccion;

        return $this;
    }

    /**
     * Get direccion
     *
     * @return string 
     */
    public function getDireccion()
    {
        return $this->direccion;
    }

    /**
     * Set localidad
     *
     * @param string $localidad
     * @return Centro
     */
    public function setLocalidad($localidad)
    {
        $this->localidad = $localidad;

        return $this;
    }

    /**
     * Get localidad
     *
     * @return string 
     */
    public function getLocalidad()
    {
        return $this->localidad;
    }

    /**
     * Set provincia
     *
     * @param string $provincia
     * @return Centro
     */
    public function setProvincia($provincia)
    {
        $this->provincia = $provincia;

        return $this;
    }

    /**
     * Get provincia
     *
     * @return string 
     */
    public function getProvincia()
    {
        return $this->provincia;
    }

    /**
     * Set cp
     *
     * @param string $cp
     * @return Centro
     */
    public function setCp($cp)
    {
        $this->cp = $cp;

        return $this;
    }

    /**
     * Get cp
     *
     * @return string 
     */
    public function getCp()
    {
        return $this->cp;
    }

    /**
     * Set telefono
     *
     * @param string $telefono
     * @return Centro
     */
    public function setTelefono($telefono)
    {
        $this->telefono = $telefono;

        return $this;
    }

    /**
     * Get telefono
     *
     * @return string 
     */
    public function getTelefono()
    {
        return $this->telefono;
    }

    /**
     * Set email
     *
     * @param string $email
     * @return Centro
     */
    public function setEmail($email)
    {
        $this->email = $email;

        return $this;
    }

    /**
     * Get email
     *
     * @return string 
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * Set web
     *
     * @param string $web
     * @return Centro
     */
    public function setWeb($web)
    {
        $this->web = $web;

        return $this;
    }

    /**
     * Get web
     *
     * @return string 
     */
    public function getWeb()
    {
        return $this->web;
    }

    /**
     * Set inicioCurso
     *
     * @param \DateTime $inicioCurso
     * @return Centro
     */
    public function setInicioCurso($inicioCurso)
    {
        $this->inicioCurso = $inicioCurso;

        return $this;
    }

    /**
     * Get inicioCurso
     *
     * @return \DateTime 
     */
    public function getInicioCurso()
    {
        return $this->inicioCurso;
    }

    /**
     * Set finCurso
     *
     * @param \DateTime $finCurso
     * @return Centro
     */
    public function setFinCurso($finCurso)
    {
        $this->finCurso = $finCurso;

        return $this;
    }

    /**
     * Get finCurso
     *
     * @return \DateTime 
     */
    public function getFinCurso()
    {
        return $this->finCurso;
    }



    /**
     * Set inicioHorario
     *
     * @param string $inicioHorario
     * @return Centro
     */
    public function setInicioHorario($inicioHorario)
    {
        $this->inicioHorario = $inicioHorario;

        return $this;
    }

    /**
     * Get inicioHorario
     *
     * @return string 
     */
    public function getInicioHorario()
    {
        return $this->inicioHorario;
    }

    /**
     * Set finHorario
     *
     * @param string $finHorario
     * @return Centro
     */
    public function setFinHorario($finHorario)
    {
        $this->finHorario = $finHorario;

        return $this;
    }

    /**
     * Get finHorario
     *
     * @return string 
     */
    public function getFinHorario()
    {
        return $this->finHorario;
    }
}
