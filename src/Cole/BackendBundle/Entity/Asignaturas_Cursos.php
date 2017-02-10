<?php

namespace Cole\BackendBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Asignaturas_Cursos
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="Cole\BackendBundle\Entity\Asignaturas_CursosRepository")
 */
class Asignaturas_Cursos
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
     * @return Asignaturas_Cursos
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
     * @return Asignaturas_Cursos
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
     * @return Asignaturas_Cursos
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
     * @return Asignaturas_Cursos
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
}
