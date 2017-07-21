<?php

namespace Cole\BackendBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * AsignaturasCursos
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="Cole\BackendBundle\Entity\AsignaturasCursosRepository")
 */
class AsignaturasCursos
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
     * @ORM\Column(name="libro", type="string", length=255, nullable=true)
     */
    private $libro;

    /**
     * @var integer
     *
     * @ORM\Column(name="NumModulos", type="integer", nullable=false)
     */
    private $numModulos;

    /**
    * @ORM\ManyToOne(targetEntity="Asignatura", inversedBy="asignaturas_cursos")
    * @ORM\JoinColumn(name="id_asignatura", referencedColumnName="id", nullable=false)
    */
    private $asignatura;

    /**
    * @ORM\ManyToOne(targetEntity="Curso", inversedBy="asignaturas_cursos")
    * @ORM\JoinColumn(name="id_curso", referencedColumnName="id", nullable=false)
    */
    private $curso;
    
    /**
    * @ORM\OneToMany(targetEntity="Imparte", mappedBy="asignatura")
    */
    private $imparte;

    /**
    * @ORM\OneToMany(targetEntity="Alumno", mappedBy="optativa")
    */
    private $alumnos;

    /**
    * @ORM\OneToMany(targetEntity="Cole\IntranetBundle\Entity\Seguimiento", mappedBy="asignatura")
    */
    private $seguimiento;

    /**
     * @ORM\OneToMany(targetEntity="Cole\IntranetBundle\Entity\Cursa", mappedBy="asignaturasCursos")
     */
    private $cursa;

    /**
    * @ORM\OneToMany(targetEntity="Cole\IntranetBundle\Entity\Tarea", mappedBy="asignatura")
    */
    private $tarea;

    /**
     * @ORM\OneToMany(targetEntity="Cole\IntranetBundle\Entity\Ausencia", mappedBy="asignatura")
     */
    private $ausencia;

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
     * Set libro
     *
     * @param string $libro
     * @return AsignaturasCursos
     */
    public function setLibro($libro)
    {
        $this->libro = $libro;

        return $this;
    }

    /**
     * Get libro
     *
     * @return string 
     */
    public function getLibro()
    {
        return $this->libro;
    }

    /**
     * Set numModulos
     *
     * @param integer $numModulos
     * @return AsignaturasCursos
     */
    public function setNumModulos($numModulos)
    {
        $this->numModulos = $numModulos;

        return $this;
    }

    /**
     * Get numModulos
     *
     * @return integer 
     */
    public function getNumModulos()
    {
        return $this->numModulos;
    }

    /**
     * Set asignatura
     *
     * @param \Cole\BackendBundle\Entity\Asignatura $asignatura
     * @return AsignaturasCursos
     */
    public function setAsignatura(\Cole\BackendBundle\Entity\Asignatura $asignatura = null)
    {
        $this->asignatura = $asignatura;

        return $this;
    }

    /**
     * Get asignatura
     *
     * @return \Cole\BackendBundle\Entity\Asignatura 
     */
    public function getAsignatura()
    {
        return $this->asignatura;
    }

    /**
     * Set curso
     *
     * @param \Cole\BackendBundle\Entity\Curso $curso
     * @return AsignaturasCursos
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
        $this->imparte = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Add imparte
     *
     * @param \Cole\BackendBundle\Entity\Imparte $imparte
     * @return AsignaturasCursos
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
     * Add alumnos
     *
     * @param \Cole\BackendBundle\Entity\Alumno $alumnos
     * @return AsignaturasCursos
     */
    public function addAlumno(\Cole\BackendBundle\Entity\Alumno $alumnos)
    {
        $this->alumnos[] = $alumnos;

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
     * Add seguimiento
     *
     * @param \Cole\IntranetBundle\Entity\Seguimiento $seguimiento
     * @return AsignaturasCursos
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

    public function __toString()
    {
        return $this->getCurso()->getCurso()." - ".  $this->getAsignatura()->getNombre();
    }

    /**
     * Add cursa
     *
     * @param \Cole\IntranetBundle\Entity\Cursa $cursa
     * @return AsignaturasCursos
     */
    public function addCursa(\Cole\IntranetBundle\Entity\Cursa $cursa)
    {
        $this->cursa[] = $cursa;

        return $this;
    }

    /**
     * Remove cursa
     *
     * @param \Cole\IntranetBundle\Entity\Cursa $cursa
     */
    public function removeCursa(\Cole\IntranetBundle\Entity\Cursa $cursa)
    {
        $this->cursa->removeElement($cursa);
    }

    /**
     * Get cursa
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getCursa()
    {
        return $this->cursa;
    }

    /**
     * Add tarea
     *
     * @param \Cole\IntranetBundle\Entity\Tarea $tarea
     * @return AsignaturasCursos
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
     * Add ausencia
     *
     * @param \Cole\IntranetBundle\Entity\Ausencia $ausencia
     * @return AsignaturasCursos
     */
    public function addAusencium(\Cole\IntranetBundle\Entity\Ausencia $ausencia)
    {
        $this->ausencia[] = $ausencia;

        return $this;
    }

    /**
     * Remove ausencia
     *
     * @param \Cole\IntranetBundle\Entity\Ausencia $ausencia
     */
    public function removeAusencium(\Cole\IntranetBundle\Entity\Ausencia $ausencia)
    {
        $this->ausencia->removeElement($ausencia);
    }

    /**
     * Get ausencia
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getAusencia()
    {
        return $this->ausencia;
    }
}
