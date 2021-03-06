<?php

namespace Cole\BackendBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;


/**
 * Profesor
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="Cole\BackendBundle\Entity\ProfesorRepository")
 * @UniqueEntity(fields={"dni"}, message="Este DNI ya existe en el sistema.")
 */
class Profesor implements UserInterface, \Serializable
{

    /* Métodos para el logueo de profesores */
    function equals(\Symfony\Component\Security\Core\User\UserInterface $profesor)
    {
        return md5($this->getUsername()) == md5($profesor->getUsername());
    }   

    function eraseCredentials()
    {

    }

    function getRoles()
    {
        return array("$this->role");
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
     * @ORM\Column(name="dni", type="string", length=10, unique=true )
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
     * @ORM\Column(name="sexo", type="string", length=9)
     * @Assert\NotBlank(message="El campo no puede estar vacío.")
     */
    private $sexo;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="fechaNacimiento", type="date")
     * @Assert\NotBlank(message="El campo no puede estar vacío.")
     */
    private $fechaNacimiento;

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
    //@Assert\Email(groups={"actualizar"})
    private $email;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="fecha_alta", type="datetime")
     * @Assert\NotBlank(message="El campo no puede estar vacío.")
     */
    private $fechaAlta;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="fecha_baja", type="datetime", nullable=true)
     *
     */
    private $fechaBaja;
    
    /**
     * @var string
     *
     * @ORM\Column(name="foto", type="string", length=255, nullable=true)
     * @Assert\File(
     * maxSize = "1024k",
     * mimeTypes = {"image/jpeg", "image/png"},
     * mimeTypesMessage = "Por favor, sube una imagen válida.")
     */
    private $foto;

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
     * @ORM\Column(name="perfilAcademico", type="string", length=1200, nullable=true)
     * 
     */
    private $perfilAcademico;

    /**
     * @var string
     *
     * @ORM\Column(name="perfilProfesional", type="string", length=1200, nullable=true)
     * 
     */
    private $perfilProfesional;

    /**
     * @var string
     *
     * @ORM\Column(name="nivel", type="string", length=10)
     * @Assert\NotBlank(message="El campo no puede estar vacío.")
     */
    private $nivel;

    /**
     *
     * @ORM\Column(name="horas", type="decimal", precision=3, scale=1)
     * @Assert\Range(min=15, max=40)
     * @Assert\NotBlank(message="El campo no puede estar vacío.")
     */
    private $horas;

    /**
     *
     * @ORM\Column(name="lectivas", type="decimal", precision=3, scale=1)
     * @Assert\Range(min=5, max=25)
     * @Assert\NotBlank(message="El campo no puede estar vacío.")
     */
    private $horasLectivas;

    /**
     * @var string
     *
     * @ORM\Column(name="observaciones", type="string", length=500, nullable=true)
     * 
     */
    private $observaciones;

    /**
     * @var boolean
     *
     * @ORM\Column(name="activo", type="boolean")
     */
    private $activo;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="accesoNoticias", type="datetime", nullable=true)
     * 
     */
    private $accesoNoticias;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="accesoSeguimientos", type="datetime", nullable=true)
     * 
     */
    private $accesoSeguimientos;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="accesoTutorias", type="datetime", nullable=true)
     * 
     */
    private $accesoTutorias;
    
    /**
     * @var \DateTime
     *
     * @ORM\Column(name="lastAccess", type="datetime" , nullable=true)
     */
    private $lastAccess;
    /**
     * @var \DateTime
     *
     * @ORM\Column(name="lastAccessAnt", type="datetime" , nullable=true)
     */
    private $lastAccessAnt;

    /**
     * @var string
     *
     * @ORM\Column(name="pregunta", type="string", length=100, nullable=true)
     * 
     */
    private $pregunta;

        /**
     * @var string
     *
     * @ORM\Column(name="respuesta", type="string", length=100, nullable=true)
     * 
     */
    private $respuesta;

    /**
     * @ORM\ManyToOne(targetEntity="Role", cascade={"persist"})
     */
    private $role;

    /**
     * @ORM\OneToMany(targetEntity="Imparte", mappedBy="profesor")
     */
    private $imparte;

    /**
     * @ORM\OneToOne(targetEntity="Grupo", mappedBy="profesor")
     */
    private $grupo;

    /**
     * @ORM\OneToMany(targetEntity="Reserva", mappedBy="profesor")
     */
    private $reserva;

    /**
    * @ORM\OneToMany(targetEntity="Cole\IntranetBundle\Entity\Seguimiento", mappedBy="profesor", cascade={"remove"})
    */
    private $seguimiento;

    /**
    * @ORM\OneToMany(targetEntity="Cole\IntranetBundle\Entity\Tutorias", mappedBy="profesor", cascade={"remove"})
    */
    private $tutorias;

    /**
    * @ORM\OneToMany(targetEntity="Cole\IntranetBundle\Entity\Tarea", mappedBy="profesor", cascade={"remove"})
    */
    private $tarea;

    /**
     * @ORM\OneToMany(targetEntity="Cole\IntranetBundle\Entity\Comunicacion", mappedBy="profesorEmisor")
     * @ORM\OneToMany(targetEntity="Cole\IntranetBundle\Entity\Comunicacion", mappedBy="profesorReceptor")
     */
    private $mensajes;
    
    public function __construct()
    {
        $this->activo = true;
        $this->salt = base_convert(sha1(uniqid(mt_rand(),true)), 16, 36);
        $this->fechaAlta= new \DateTime();
        $this->imparte = new ArrayCollection();
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
     * @return Profesor
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
     * @return Profesor
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
     * @return Profesor
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
     * @return Profesor
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
     * Set sexo
     *
     * @param string $sexo
     * @return Profesor
     */
    public function setSexo($sexo)
    {
        $this->sexo = $sexo;

        return $this;
    }

    /**
     * Get sexo
     *
     * @return string 
     */
    public function getSexo()
    {
        return $this->sexo;
    }

    /**
     * Set fechaNacimiento
     *
     * @param \DateTime $fechaNacimiento
     * @return Profesor
     */
    public function setFechaNacimiento($fechaNacimiento)
    {
        $this->fechaNacimiento = $fechaNacimiento;

        return $this;
    }

    /**
     * Get fechaNacimiento
     *
     * @return \DateTime 
     */
    public function getFechaNacimiento()
    {
        return $this->fechaNacimiento;
    }

    /**
     * Set direccion
     *
     * @param string $direccion
     * @return Profesor
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
     * @return Profesor
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
     * @return Profesor
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
     * @return Profesor
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
     * @return Profesor
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
     * @return Profesor
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
     * @return Profesor
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
     * @return Profesor
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
     * Set foto
     *
     * @param string $foto
     * @return Profesor
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
     * Set username
     *
     * @param string $username
     * @return Profesor
     */
    public function setUsername($username)
    {
        $this->username = $username;

        return $this;
    }


    /**
     * Set password
     *
     * @param string $password
     * @return Profesor
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
     * @return Profesor
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
     * Set activo
     *
     * @param boolean $activo
     * @return Profesor
     */
    public function setActivo($activo)
    {
        $this->activo = $activo;

        return $this;
    }

    /**
     * Get activo
     *
     * @return boolean 
     */
    public function getActivo()
    {
        return $this->activo;
    }
    
    public function __toString()
    {
        return $this->getNombre().' '.$this->getApellido1().' '.$this->getApellido2();
    }

    /**
     * Set role
     *
     * @param \Cole\BackendBundle\Entity\Role $role
     * @return Profesor
     */
    public function setRole(Role $role)
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



    /**
     * Set perfilAcademico
     *
     * @param string $perfilAcademico
     * @return Profesor
     */
    public function setPerfilAcademico($perfilAcademico)
    {
        $this->perfilAcademico = $perfilAcademico;

        return $this;
    }

    /**
     * Get perfilAcademico
     *
     * @return string 
     */
    public function getPerfilAcademico()
    {
        return $this->perfilAcademico;
    }

    /**
     * Set perfilProfesional
     *
     * @param string $perfilProfesional
     * @return Profesor
     */
    public function setPerfilProfesional($perfilProfesional)
    {
        $this->perfilProfesional = $perfilProfesional;

        return $this;
    }

    /**
     * Get perfilProfesional
     *
     * @return string 
     */
    public function getPerfilProfesional()
    {
        return $this->perfilProfesional;
    }

    /**
     * Add imparte
     *
     * @param \Cole\BackendBundle\Entity\Imparte $imparte
     * @return Profesor
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
     * Set grupo
     *
     * @param \Cole\BackendBundle\Entity\Grupo $grupo
     * @return Profesor
     */
    public function setGrupo(\Cole\BackendBundle\Entity\Grupo $grupo = null)
    {
        $this->grupo = $grupo;

        return $this;
    }

    /**
     * Get grupo
     *
     * @return \Cole\BackendBundle\Entity\Grupo 
     */
    public function getGrupo()
    {
        return $this->grupo;
    }

    /**
     * Add reserva
     *
     * @param \Cole\BackendBundle\Entity\Reserva $reserva
     * @return Profesor
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
     * Set nivel
     *
     * @param string $nivel
     * @return Profesor
     */
    public function setNivel($nivel)
    {
        $this->nivel = $nivel;

        return $this;
    }

    /**
     * Get nivel
     *
     * @return string 
     */
    public function getNivel()
    {
        return $this->nivel;
    }

    /**
     * Set fechaBaja
     *
     * @param \DateTime $fechaBaja
     * @return Profesor
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
     * Set observaciones
     *
     * @param string $observaciones
     * @return Profesor
     */
    public function setObservaciones($observaciones)
    {
        $this->observaciones = $observaciones;

        return $this;
    }

    /**
     * Get observaciones
     *
     * @return string 
     */
    public function getObservaciones()
    {
        return $this->observaciones;
    }

    /**
     * Set horas
     *
     * @param string $horas
     * @return Profesor
     */
    public function setHoras($horas)
    {
        $this->horas = $horas;

        return $this;
    }

    /**
     * Get horas
     *
     * @return string 
     */
    public function getHoras()
    {
        return (float)$this->horas;
    }

    /**
     * Set horasLectivas
     *
     * @param string $horasLectivas
     * @return Profesor
     */
    public function setHorasLectivas($horasLectivas)
    {
        $this->horasLectivas = $horasLectivas;

        return $this;
    }

    /**
     * Get horasLectivas
     *
     * @return string 
     */
    public function getHorasLectivas()
    {
        return (float)$this->horasLectivas;
    }



    /**
     * Set lastAccess
     *
     * @param \DateTime $lastAccess
     * @return Profesor
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
     * @return Profesor
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
     * Set accesoNoticias
     *
     * @param \DateTime $accesoNoticias
     * @return Profesor
     */
    public function setAccesoNoticias($accesoNoticias)
    {
        $this->accesoNoticias = $accesoNoticias;

        return $this;
    }

    /**
     * Get accesoNoticias
     *
     * @return \DateTime 
     */
    public function getAccesoNoticias()
    {
        return $this->accesoNoticias;
    }

    /**
     * Add seguimiento
     *
     * @param \Cole\IntranetBundle\Entity\Seguimiento $seguimiento
     * @return Profesor
     */
    public function addSeguimiento(\Cole\IntranetBundle\Entity\Seguimiento $seguimiento)
    {
        $this->seguimiento[] = $seguimiento;

        return $this;
    }

    /**
     * Remove seguimiento
     *
     * @param \Cole\IntranetBundle\Entity\Seguimiento $seguimiento
     */
    public function removeSeguimiento(\Cole\IntranetBundle\Entity\Seguimiento $seguimiento)
    {
        $this->seguimiento->removeElement($seguimiento);
    }

    /**
     * Get seguimiento
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getSeguimiento()
    {
        return $this->seguimiento;
    }

    /**
     * Set accesoSeguimientos
     *
     * @param \DateTime $accesoSeguimientos
     * @return Profesor
     */
    public function setAccesoSeguimientos($accesoSeguimientos)
    {
        $this->accesoSeguimientos = $accesoSeguimientos;

        return $this;
    }

    /**
     * Get accesoSeguimientos
     *
     * @return \DateTime 
     */
    public function getAccesoSeguimientos()
    {
        return $this->accesoSeguimientos;
    }

    /**
     * Add tutorias
     *
     * @param \Cole\IntranetBundle\Entity\Tutorias $tutorias
     * @return Profesor
     */
    public function addTutoria(\Cole\IntranetBundle\Entity\Tutorias $tutorias)
    {
        $this->tutorias[] = $tutorias;

        return $this;
    }

    /**
     * Remove tutorias
     *
     * @param \Cole\IntranetBundle\Entity\Tutorias $tutorias
     */
    public function removeTutoria(\Cole\IntranetBundle\Entity\Tutorias $tutorias)
    {
        $this->tutorias->removeElement($tutorias);
    }

    /**
     * Get tutorias
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getTutorias()
    {
        return $this->tutorias;
    }

    /**
     * Set accesoTutorias
     *
     * @param \DateTime $accesoTutorias
     * @return Profesor
     */
    public function setAccesoTutorias($accesoTutorias)
    {
        $this->accesoTutorias = $accesoTutorias;

        return $this;
    }

    /**
     * Get accesoTutorias
     *
     * @return \DateTime 
     */
    public function getAccesoTutorias()
    {
        return $this->accesoTutorias;
    }

    /**
     * Set pregunta
     *
     * @param string $pregunta
     * @return Profesor
     */
    public function setPregunta($pregunta)
    {
        $this->pregunta = $pregunta;

        return $this;
    }

    /**
     * Get pregunta
     *
     * @return string 
     */
    public function getPregunta()
    {
        return $this->pregunta;
    }

    /**
     * Set respuesta
     *
     * @param string $respuesta
     * @return Profesor
     */
    public function setRespuesta($respuesta)
    {
        $this->respuesta = $respuesta;

        return $this;
    }

    /**
     * Get respuesta
     *
     * @return string 
     */
    public function getRespuesta()
    {
        return $this->respuesta;
    }

    /**
     * Add tarea
     *
     * @param \Cole\IntranetBundle\Entity\Tarea $tarea
     * @return Profesor
     */
    public function addTarea(\Cole\IntranetBundle\Entity\Tarea $tarea)
    {
        $this->tarea[] = $tarea;

        return $this;
    }

    /**
     * Remove tarea
     *
     * @param \Cole\IntranetBundle\Entity\Tarea $tarea
     */
    public function removeTarea(\Cole\IntranetBundle\Entity\Tarea $tarea)
    {
        $this->tarea->removeElement($tarea);
    }

    /**
     * Get tarea
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getTarea()
    {
        return $this->tarea;
    }

    /**
     * Add mensajes
     *
     * @param \Cole\IntranetBundle\Entity\Comunicacion $mensajes
     * @return Profesor
     */
    public function addMensaje(\Cole\IntranetBundle\Entity\Comunicacion $mensajes)
    {
        $this->mensajes[] = $mensajes;

        return $this;
    }

    /**
     * Remove mensajes
     *
     * @param \Cole\IntranetBundle\Entity\Comunicacion $mensajes
     */
    public function removeMensaje(\Cole\IntranetBundle\Entity\Comunicacion $mensajes)
    {
        $this->mensajes->removeElement($mensajes);
    }

    /**
     * Get mensajes
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getMensajes()
    {
        return $this->mensajes;
    }
}
