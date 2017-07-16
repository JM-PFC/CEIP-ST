<?php

namespace Cole\BackendBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * Administrativo
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="Cole\BackendBundle\Entity\AdministrativoRepository")
 * @UniqueEntity(fields={"dni"}, message="Este DNI ya existe en el sistema.")
 */
class Administrativo implements UserInterface, \Serializable
{

    /* Métodos para el logueo de administrativos */
    function equals(\Symfony\Component\Security\Core\User\UserInterface $administrativo)
    {
        return md5($this->getUsername()) == md5($administrativo->getUsername());
    }   

    function eraseCredentials()
    {

    }

    function getRoles()
    {
        return array("$this->role");
    }
    
    public function serialize()
    {
       return serialize($this->getId());
    }
 
    public function unserialize($data)
    {
        $this->id = unserialize($data);
    } 

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
     * @ORM\Column(name="dni", type="string", length=10, unique=true)
     */
    private $dni;

    /**
     * @var string
     *
     * @ORM\Column(name="nombre", type="string", length=255)
     * @Assert\NotBlank(message="El campo no puede estar vacío.")
    * @Assert\Regex(pattern="/^([A-z áéíóúÁÉÍÓÚÑñ]+\s?)*$/",message="Este valor no es válido.")
     */
    private $nombre;

    /**
     * @var string
     *
     * @ORM\Column(name="apellido1", type="string", length=255)
     * @Assert\NotBlank(message="El campo no puede estar vacío.")
     */
    private $apellido1;

    /**
     * @var string
     *
     * @ORM\Column(name="apellido2", type="string", length=255)
     * @Assert\NotBlank(message="El campo no puede estar vacío.")
     */
    private $apellido2;

    /**
     * @var string
     *
     * @ORM\Column(name="direccion", type="string", length=255)
     * @Assert\NotBlank(message="El campo no puede estar vacío.")
     */
    private $direccion;

    /**
     * @var string
     *
     * @ORM\Column(name="localidad", type="string", length=255)
     * @Assert\NotBlank(message="El campo no puede estar vacío.")
     */
    private $localidad;

    /**
     * @var string
     *
     * @ORM\Column(name="provincia", type="string", length=255)
     * @Assert\NotBlank(message="El campo no puede estar vacío.")
     */
    private $provincia;

    /**
     * @var string
     *
     * @ORM\Column(name="cp", type="string", length=5)
     * @Assert\NotBlank(message="El campo no puede estar vacío.")
     */
    private $cp;

    /**
     * @var string
     *
     * @ORM\Column(name="telefono", type="string", length=12, nullable=true)
     * @Assert\Regex(pattern="/^\d{3}([- .]?\d{2}){3}$/",message="No es un número de teléfono válido.")
     * @Assert\Length(max=12)
     */
    private $telefono;

    /**
     * @var string
     *
     * @ORM\Column(name="movil", type="string", length=12, nullable=true)
     * @Assert\Regex(pattern="/^\d{3}([- .]?\d{2}){3}$/",message="No es un número de teléfono válido.")
     * @Assert\Length( max=12)
     */
    private $movil;

    /**
     * @var string
     *
     * @ORM\Column(name="email", type="string", length=255, nullable=true)
     * @Assert\Email()
     */
    private $email;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="fechaAlta", type="datetime")
     * @Assert\NotBlank(message="El campo no puede estar vacío.")
     */
    private $fechaAlta;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="fechaBaja", type="datetime" , nullable=true)
     */
    private $fechaBaja;

    /**
     * @var string
     *
     * @ORM\Column(name="username", type="string", length=255)
     */
    private $username;

    /**
     * @var string
     *
     * @ORM\Column(name="password", type="string", length=255)
     * @Assert\Length(min= 6, minMessage = "La contraseña debe tener al menos 6 caracteres")
     */
    private $password;

    /**
     * @var string
     *
     * @ORM\Column(name="salt", type="string", length=255)
     */
    private $salt;

    /**
     * @var string
     *
     * @ORM\Column(name="tipo", type="string", length=255)
     */
    private $tipo;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="lastAccess", type="datetime"  , nullable=true)
     */
    private $lastAccess;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="lastAccessAnt", type="datetime" , nullable=true)
     */
    private $lastAccessAnt;

    /**
     * @ORM\ManyToOne(targetEntity="Role", cascade={"persist"})
     */
    private $role;

    public function __construct()
    {
        $this->activo = true;
        $this->salt = base_convert(sha1(uniqid(mt_rand(),true)), 16, 36);
        $this->fechaAlta= new \DateTime();
    }


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
     * Set dni
     *
     * @param string $dni
     * @return Administrativo
     */
    public function setDni($dni)
    {
        $this->dni = $dni;

        return $this;
    }

    /**
     * Get dni
     *
     * @return string 
     */
    public function getDni()
    {
        return $this->dni;
    }

    /**
     * Set nombre
     *
     * @param string $nombre
     * @return Administrativo
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
     * Set apellido1
     *
     * @param string $apellido1
     * @return Administrativo
     */
    public function setApellido1($apellido1)
    {
        $this->apellido1 = $apellido1;

        return $this;
    }

    /**
     * Get apellido1
     *
     * @return string 
     */
    public function getApellido1()
    {
        return $this->apellido1;
    }

    /**
     * Set apellido2
     *
     * @param string $apellido2
     * @return Administrativo
     */
    public function setApellido2($apellido2)
    {
        $this->apellido2 = $apellido2;

        return $this;
    }

    /**
     * Get apellido2
     *
     * @return string 
     */
    public function getApellido2()
    {
        return $this->apellido2;
    }

    /**
     * Set direccion
     *
     * @param string $direccion
     * @return Administrativo
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
     * @return Administrativo
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
     * @return Administrativo
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
     * @return Administrativo
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
     * @return Administrativo
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
     * Set movil
     *
     * @param string $movil
     * @return Administrativo
     */
    public function setMovil($movil)
    {
        $this->movil = $movil;

        return $this;
    }

    /**
     * Get movil
     *
     * @return string 
     */
    public function getMovil()
    {
        return $this->movil;
    }

    /**
     * Set email
     *
     * @param string $email
     * @return Administrativo
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
     * Set fechaAlta
     *
     * @param \DateTime $fechaAlta
     * @return Administrativo
     */
    public function setFechaAlta($fechaAlta)
    {
        $this->fechaAlta = $fechaAlta;

        return $this;
    }

    /**
     * Get fechaAlta
     *
     * @return \DateTime 
     */
    public function getFechaAlta()
    {
        return $this->fechaAlta;
    }

    /**
     * Set fechaBaja
     *
     * @param \DateTime $fechaBaja
     * @return Administrativo
     */
    public function setFechaBaja($fechaBaja)
    {
        $this->fechaBaja = $fechaBaja;

        return $this;
    }

    /**
     * Get fechaBaja
     *
     * @return \DateTime 
     */
    public function getFechaBaja()
    {
        return $this->fechaBaja;
    }

    /**
     * Set username
     *
     * @param string $username
     * @return Administrativo
     */
    public function setUsername($username)
    {
        $this->username = $username;

        return $this;
    }

    /**
     * Get username
     *
     * @return string 
     */
    public function getUsername()
    {
        return $this->username;
    }

    /**
     * Set password
     *
     * @param string $password
     * @return Administrativo
     */
    public function setPassword($password)
    {
        $this->password = $password;

        return $this;
    }

    /**
     * Get password
     *
     * @return string 
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * Set salt
     *
     * @param string $salt
     * @return Administrativo
     */
    public function setSalt($salt)
    {
        $this->salt = $salt;

        return $this;
    }

    /**
     * Get salt
     *
     * @return string 
     */
    public function getSalt()
    {
        return $this->salt;
    }

    /**
     * Set tipo
     *
     * @param string $tipo
     * @return Administrativo
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
     * Set lastAccess
     *
     * @param \DateTime $lastAccess
     * @return Administrativo
     */
    public function setLastAccess($lastAccess)
    {
        $this->lastAccess = $lastAccess;

        return $this;
    }

    /**
     * Get lastAccess
     *
     * @return \DateTime 
     */
    public function getLastAccess()
    {
        return $this->lastAccess;
    }

    /**
     * Set lastAccessAnt
     *
     * @param \DateTime $lastAccessAnt
     * @return Administrativo
     */
    public function setLastAccessAnt($lastAccessAnt)
    {
        $this->lastAccessAnt = $lastAccessAnt;

        return $this;
    }

    /**
     * Get lastAccessAnt
     *
     * @return \DateTime 
     */
    public function getLastAccessAnt()
    {
        return $this->lastAccessAnt;
    }

    /**
     * Set role
     *
     * @param \Cole\BackendBundle\Entity\Role $role
     * @return Administrativo
     */
    public function setRole(\Cole\BackendBundle\Entity\Role $role = null)
    {
        $this->role = $role;

        return $this;
    }

    /**
     * Get role
     *
     * @return \Cole\BackendBundle\Entity\Role 
     */
    public function getRole()
    {
        return $this->role;
    }

    public function __toString()
    {
        return $this->getNombre().' '.$this->getApellido1().' '.$this->getApellido2();
    }
}
