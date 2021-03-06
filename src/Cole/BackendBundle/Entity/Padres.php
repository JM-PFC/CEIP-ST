<?php

namespace Cole\BackendBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;


/**
 * Padres
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="Cole\BackendBundle\Entity\PadresRepository")
 */
 // Lo hemos quitado para que se pueda repetir dni en el formulario. 
 // @UniqueEntity(fields={"dni"}, message="Este valor ya se ha utilizado.")
 // unique=true en dni 

class Padres implements UserInterface, \Serializable
{

    /* Métodos para el logueo de profesores */
    function equals(\Symfony\Component\Security\Core\User\UserInterface $padres)
    {
        return md5($this->getUsername()) == md5($padres->getUsername());
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
     * @ORM\Column(name="dni", type="string", length=10, nullable=true)
     * 
     * @Assert\Regex(pattern="/(^([X-Zx-z]{1})([0-9]{7})([-]?)([A-Za-z]{1})$)|((^[0-9]{8})[-]?([A-Za-z]{1}$))/",message="No es un DNI ó NIE válido.")
     */
    private $dni;

    /**
     * @var string
     *
     * @ORM\Column(name="nombre", type="string", length=255, nullable=true)
     * 
     * @Assert\Regex(pattern="/^[A-Za-záéíóúÁÉÍÓÚüÜñÑ]{2,}([\s][A-Za-záéíóúÁÉÍÓÚüÜñÑ]+)*$/",message="Contiene caracteres inválidos.")
     */
    private $nombre;


    /**
     * @var \DateTime
     *
     * @ORM\Column(name="fecha_nacimiento", type="date", nullable=true)
     * 
     */
    private $fechaNacimiento;

    /**
     * @var string
     *
     * @ORM\Column(name="profesion", type="string", length=255, nullable=true)
     */
    private $profesion;

    /**
     * @var string
     *
     * @ORM\Column(name="estado_civil", type="string", length=255, nullable=true)
     * 
     */
    private $estadoCivil;

    /**
     * @var string
     *
     * @ORM\Column(name="movil", type="string", length=12, nullable=true)
     * @Assert\Regex(pattern="/^\d{3}([- .]?\d{2}){3}$/",message="No es un número de teléfono válido.")
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
     * @var string
     *
     * @ORM\Column(name="username", type="string", length=255, nullable=true)
     * 
     */
    private $username;

    /**
     * @var string
     *
     * @ORM\Column(name="password", type="string", length=255, nullable=true)
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
     * @var boolean
     *
     * @ORM\Column(name="activo", type="boolean", nullable=true)
     * 
     */
    private $activo;

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
     * @ORM\OneToMany(targetEntity="Alumno", mappedBy="$responsable1")
     * @ORM\OneToMany(targetEntity="Alumno", mappedBy="$responsable2")
     */
    private $alumnos;

    /**
    * @ORM\OneToMany(targetEntity="Cole\IntranetBundle\Entity\Seguimiento", mappedBy="responsable", cascade={"remove"})
    */
    private $seguimiento;

    /**
    * @ORM\OneToMany(targetEntity="Cole\IntranetBundle\Entity\Tutorias", mappedBy="responsable", cascade={"remove"})
    */
    private $tutorias;

    /**
    * @ORM\OneToMany(targetEntity="Cole\IntranetBundle\Entity\Ausencia", mappedBy="responsable", cascade={"remove"})
    */
    private $faltas;

    /**
     * @ORM\OneToMany(targetEntity="Cole\IntranetBundle\Entity\Comunicacion", mappedBy="responsableEmisor")
     * @ORM\OneToMany(targetEntity="Cole\IntranetBundle\Entity\Comunicacion", mappedBy="responsableReceptor")
     */
    private $mensajes;

    public function __construct()
    {
        $this->activo = true;
        $this->salt = base_convert(sha1(uniqid(mt_rand(),true)), 16, 36);
        $this->fechaAlta= new \DateTime();
        $this->alumnos = new ArrayCollection();
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
     * @return Padres
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
     * @return Padres
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
     * Set fechaNacimiento
     *
     * @param \DateTime $fechaNacimiento
     * @return Padres
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
     * Set profesion
     *
     * @param string $profesion
     * @return Padres
     */
    public function setProfesion($profesion)
    {
        $this->profesion = $profesion;

        return $this;
    }

    /**
     * Get profesion
     *
     * @return string 
     */
    public function getProfesion()
    {
        return $this->profesion;
    }

    /**
     * Set estadoCivil
     *
     * @param string $estadoCivil
     * @return Padres
     */
    public function setEstadoCivil($estadoCivil)
    {
        $this->estadoCivil = $estadoCivil;

        return $this;
    }

    /**
     * Get estadoCivil
     *
     * @return string 
     */
    public function getEstadoCivil()
    {
        return $this->estadoCivil;
    }

    /**
     * Set movil
     *
     * @param string $movil
     * @return Padres
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
     * Set username
     *
     * @param string $username
     * @return Padres
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
     * @return Padres
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
     * @return Padres
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
     * @return Padres
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

    /**
     * Set role
     *
     * @param \Cole\BackendBundle\Entity\Role $role
     * @return Padres
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
     * Set email
     *
     * @param string $email
     * @return Padres
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

    public function __toString()
    {
    if(is_null($this->getNombre())) {
        return 'NULL';
    }
        return $this->getNombre();
    }

    /**
     * Add alumnos
     *
     * @param \Cole\BackendBundle\Entity\Alumno $alumnos
     * @return Padres
     */
    public function addAlumno(\Cole\BackendBundle\Entity\Alumno $alumnos)
    {
        if (!$this->alumnos->contains($alumnos)) {
            $this->alumnos[] = $alumnos;
        }  
        return $this;
    }
 

    /**
     * Remove alumnos
     *
     * @param \Cole\BackendBundle\Entity\Alumno $alumnos
     */
    public function removeAlumno(\Cole\BackendBundle\Entity\Alumno $alumnos)
    {
        $this->alumnos->removeElement($alumnos);
    }

    /**
     * Get alumnos
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getAlumnos()
    {
        return $this->alumnos;
    }



    /**
     * Set lastAccess
     *
     * @param \DateTime $lastAccess
     * @return Padres
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
     * @return Padres
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
     * Add seguimiento
     *
     * @param \Cole\BackendBundle\Entity\Seguimiento $seguimiento
     * @return Padres
     */
    public function addSeguimiento(\Cole\IntranetBundle\Entity\Seguimiento $seguimiento)
    {
        $this->seguimiento[] = $seguimiento;

        return $this;
    }

    /**
     * Remove seguimiento
     *
     * @param \Cole\BackendBundle\Entity\Seguimiento $seguimiento
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
     * Add tutorias
     *
     * @param \Cole\IntranetBundle\Entity\Tutorias $tutorias
     * @return Padres
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
     * Set pregunta
     *
     * @param string $pregunta
     * @return Padres
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
     * @return Padres
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
     * Add faltas
     *
     * @param \Cole\IntranetBundle\Entity\Ausencia $faltas
     * @return Padres
     */
    public function addFalta(\Cole\IntranetBundle\Entity\Ausencia $faltas)
    {
        $this->faltas[] = $faltas;

        return $this;
    }

    /**
     * Remove faltas
     *
     * @param \Cole\IntranetBundle\Entity\Ausencia $faltas
     */
    public function removeFalta(\Cole\IntranetBundle\Entity\Ausencia $faltas)
    {
        $this->faltas->removeElement($faltas);
    }

    /**
     * Get faltas
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getFaltas()
    {
        return $this->faltas;
    }

    /**
     * Add mensajes
     *
     * @param \Cole\IntranetBundle\Entity\Comunicacion $mensajes
     * @return Padres
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
