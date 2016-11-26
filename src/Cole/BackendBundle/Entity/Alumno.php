<?php

namespace Cole\BackendBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
//use Symfony\Component\HttpFoundation\File\UploadedFile;
/**
 * Alumno
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="Cole\BackendBundle\Entity\AlumnoRepository")
 */
class Alumno
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     *
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="nombre", type="string", length=255)    
     * @Assert\NotBlank()
     * @Assert\Regex(pattern="/^[A-Za-záéíóúÁÉÍÓÚüÜñÑ]{2,}([\s][A-Za-záéíóúÁÉÍÓÚüÜñÑ]+)*$/",message="Contiene caracteres inválidos.")
     */
    private $nombre;

    /**
     * @var string
     *
     * @ORM\Column(name="apellido1", type="string", length=255)
     * @Assert\NotBlank()
     * @Assert\Regex(pattern="/^[A-Za-záéíóúÁÉÍÓÚüÜñÑ]{2,}([\s][A-Za-záéíóúÁÉÍÓÚüÜñÑ]+)*$/",message="Contiene caracteres inválidos.")
     */
    private $apellido1;

    /**
     * @var string
     *
     * @ORM\Column(name="apellido2", type="string", length=255)
     * @Assert\NotBlank()
     * @Assert\Regex(pattern="/^[A-Za-záéíóúÁÉÍÓÚüÜñÑ]{2,}([\s][A-Za-záéíóúÁÉÍÓÚüÜñÑ]+)*$/",message="Contiene caracteres inválidos.")
     */
    private $apellido2;

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
     * @ORM\Column(name="sexo", type="string", length=9)
     * @Assert\NotBlank()
     */
    private $sexo;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="fechaNacimiento", type="date")
     * @Assert\NotBlank()
     */
    private $fechaNacimiento;


    /**
     * 
     * @ORM\ManyToOne(targetEntity="Padres", inversedBy="alumnos", cascade={"persist"})
     * @Assert\Valid
     * 
     */
    private $responsable1;
    //@ORM\JoinColumn(name="responsable2_id", referencedColumnName="id", nullable=true)
    /**
     * 
     * @ORM\ManyToOne(targetEntity="Padres", inversedBy="alumnos", cascade={"persist"})
     * 
     * @Assert\Valid
     */
    private $responsable2;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="fechaAlta", type="datetime")
     * 
     */
    private $fechaAlta;

    /**
     * @var string
     *
     * @ORM\Column(name="cursoIngreso", type="string", length=255, nullable=true)
     * @Assert\NotBlank()
     */
    private $cursoIngreso;

    /**
     * @ORM\ManyToOne(targetEntity="Curso", inversedBy="alumnos")
     * @ORM\JoinColumn(name="curso", referencedColumnName="id" ,nullable=true)
     */
    private $curso;

    /**
     * @ORM\ManyToOne(targetEntity="Grupo", inversedBy="alumnos")
     * @ORM\JoinColumn(name="grupo", referencedColumnName="id" ,nullable=true)
     */
    private $grupo;

    /**
     * @var string
     *
     * @ORM\Column(name="añoAcademico", type="string", length=30, nullable=true)
     * 
     */
    private $anyoAcademico;

    /**
     * @ORM\OneToMany(targetEntity="Matricula", mappedBy="alumno")
     */
    private $matricula;
     
     // @Assert\File(mimeTypes={ "image/png","image/jpg" }) 

    /**
     * @var string
     *
     * @ORM\Column(name="foto", type="string", length=255, nullable=true)
     * 
     */
    private $foto;

    /**
     * @var string
     *
     * @ORM\Column(name="num_alum", type="string", length=2, nullable=true)
     * 
     */
    private $numAlum;

    /**
     * @var string
     *
     * @ORM\Column(name="grupoSangre", type="string", length=5, nullable=true)
     * 
     */
    private $grupoSangre;

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
     * 
     */
    private $activo;

    /**
    * @ORM\OneToMany(targetEntity="Expediente", mappedBy="alumno", cascade={"remove"})
    */
    private $expediente;


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
     * @return Alumno
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
     * @return Alumno
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
     * @return Alumno
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
     * @return Alumno
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
     * @return Alumno
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
     * @return Alumno
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
     * @return Alumno
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
     * @return Alumno
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
     * @return Alumno
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
     * @return Alumno
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
     * Set responsable1
     *
     * @param string $responsable1
     * @return Alumno
     */
    public function setResponsable1($responsable1)
    {
        $this->responsable1 = $responsable1;

        return $this;
    }

    /**
     * Get responsable1
     *
     * @return string 
     */
    public function getResponsable1()
    {
        return $this->responsable1;
    }

    /**
     * Set responsable2
     *
     * @param string $responsable2
     * @return Alumno
     */
    public function setResponsable2($responsable2)
    {
        $this->responsable2 = $responsable2;

        return $this;
    }

    /**
     * Get responsable2
     *
     * @return string 
     */
    public function getResponsable2()
    {
        return $this->responsable2;
    }

    /**
     * Set fechaAlta
     *
     * @param \DateTime $fechaAlta
     * @return Alumno
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
     * Set cursoIngreso
     *
     * @param string $cursoIngreso
     * @return Alumno
     */
    public function setCursoIngreso($cursoIngreso)
    {
        $this->cursoIngreso = $cursoIngreso;

        return $this;
    }

    /**
     * Get cursoIngreso
     *
     * @return string 
     */
    public function getCursoIngreso()
    {
        return $this->cursoIngreso;
    }



    /**
     * Set foto
     *
     * @param string $foto
     * @return Alumno
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
     * Set numAlum
     *
     * @param string $numAlum
     * @return Alumno
     */
    public function setNumAlum($numAlum)
    {
        $this->numAlum = $numAlum;

        return $this;
    }

    /**
     * Get numAlum
     *
     * @return string 
     */
    public function getNumAlum()
    {
        return $this->numAlum;
    }

    /**
     * Set grupoSangre
     *
     * @param string $grupoSangre
     * @return Alumno
     */
    public function setGrupoSangre($grupoSangre)
    {
        $this->grupoSangre = $grupoSangre;

        return $this;
    }

    /**
     * Get grupoSangre
     *
     * @return string 
     */
    public function getGrupoSangre()
    {
        return $this->grupoSangre;
    }

    /**
     * Set observaciones
     *
     * @param string $observaciones
     * @return Alumno
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
     * Set activo
     *
     * @param boolean $activo
     * @return Alumno
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
     * Set grupo
     *
     * @param \Cole\BackendBundle\Entity\Grupo $grupo
     * @return Alumno
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
     * Set curso
     *
     * @param \Cole\BackendBundle\Entity\Curso $curso
     * @return Alumno
     */
    public function setCurso(\Cole\BackendBundle\Entity\Curso $curso = null)
    {
        $this->curso = $curso;

        return $this;
    }

    /**
     * Get curso
     *
     * @return \Cole\BackendBundle\Entity\Curso 
     */
    public function getCurso()
    {
        return $this->curso;
    }
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->matricula = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Add matricula
     *
     * @param \Cole\BackendBundle\Entity\Matricula $matricula
     * @return Alumno
     */
    public function addMatricula(\Cole\BackendBundle\Entity\Matricula $matricula)
    {
        $this->matricula[] = $matricula;

        return $this;
    }

    /**
     * Remove matricula
     *
     * @param \Cole\BackendBundle\Entity\Matricula $matricula
     */
    public function removeMatricula(\Cole\BackendBundle\Entity\Matricula $matricula)
    {
        $this->matricula->removeElement($matricula);
    }

    /**
     * Get matricula
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getMatricula()
    {
        return $this->matricula;
    }

    /**
     * Set añoAcademico
     *
     * @param string $añoAcademico
     * @return Alumno
     */
    public function setAñoAcademico($añoAcademico)
    {
        $this->añoAcademico = $añoAcademico;

        return $this;
    }

    /**
     * Get añoAcademico
     *
     * @return string 
     */
    public function getAñoAcademico()
    {
        return $this->añoAcademico;
    }

    /**
     * Set anyoAcademico
     *
     * @param string $anyoAcademico
     * @return Alumno
     */
    public function setAnyoAcademico($anyoAcademico)
    {
        $this->anyoAcademico = $anyoAcademico;

        return $this;
    }

    /**
     * Get anyoAcademico
     *
     * @return string 
     */
    public function getAnyoAcademico()
    {
        return $this->anyoAcademico;
    }

    /**
     * Add expediente
     *
     * @param \Cole\BackendBundle\Entity\Expediente $expediente
     * @return Alumno
     */
    public function addExpediente(\Cole\BackendBundle\Entity\Expediente $expediente)
    {
        $this->expediente[] = $expediente;

        return $this;
    }

    /**
     * Remove expediente
     *
     * @param \Cole\BackendBundle\Entity\Expediente $expediente
     */
    public function removeExpediente(\Cole\BackendBundle\Entity\Expediente $expediente)
    {
        $this->expediente->removeElement($expediente);
    }

    /**
     * Get expediente
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getExpediente()
    {
        return $this->expediente;
    }
}
