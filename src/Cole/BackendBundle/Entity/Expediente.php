<?php

namespace Cole\BackendBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Expediente
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="Cole\BackendBundle\Entity\ExpedienteRepository")
 */
class Expediente
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
     * @ORM\ManyToOne(targetEntity="Alumno", inversedBy="expediente")
     * @ORM\JoinColumn(name="alumno_id", referencedColumnName="id", nullable=false)
     */
    private $alumno;
    /**
     * @var string
     *
     * @ORM\Column(name="nivel", type="string", length=50)
     */
    private $nivel;

    /**
     * @var string
     *
     * @ORM\Column(name="curso", type="string", length=50)
     */
    private $curso;

    /**
     * @var string
     *
     * @ORM\Column(name="grupo", type="string", length=1)
     */
    private $grupo;

    /**
     * @var string
     *
     * @ORM\Column(name="añoAcademico", type="string", length=30)
     */
    private $anyo_Academico;

    /**
     * @var string
     *
     * @ORM\Column(name="faltas", type="string", length=5, nullable=true)
     */
    private $faltas;

    /**
     * @var boolean
     *
     * @ORM\Column(name="promociona", type="boolean")
     */
    private $promociona;


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
     * Set alumno
     *
     * @param string $alumno
     * @return Expediente
     */
    public function setAlumno($alumno)
    {
        $this->alumno = $alumno;

        return $this;
    }

    /**
     * Get alumno
     *
     * @return string 
     */
    public function getAlumno()
    {
        return $this->alumno;
    }

    /**
     * Set grupo
     *
     * @param string $grupo
     * @return Expediente
     */
    public function setGrupo($grupo)
    {
        $this->grupo = $grupo;

        return $this;
    }

    /**
     * Get grupo
     *
     * @return string 
     */
    public function getGrupo()
    {
        return $this->grupo;
    }

    /**
     * Set faltas
     *
     * @param string $faltas
     * @return Expediente
     */
    public function setFaltas($faltas)
    {
        $this->faltas = $faltas;

        return $this;
    }

    /**
     * Get faltas
     *
     * @return string 
     */
    public function getFaltas()
    {
        return $this->faltas;
    }

    /**
     * Set promociona
     *
     * @param boolean $promociona
     * @return Expediente
     */
    public function setPromociona($promociona)
    {
        $this->promociona = $promociona;

        return $this;
    }

    /**
     * Get promociona
     *
     * @return boolean 
     */
    public function getPromociona()
    {
        return $this->promociona;
    }

    /**
     * Set curso
     *
     * @param string $curso
     * @return Expediente
     */
    public function setCurso($curso)
    {
        $this->curso = $curso;

        return $this;
    }

    /**
     * Get curso
     *
     * @return string 
     */
    public function getCurso()
    {
        return $this->curso;
    }

    /**
     * Set nivel
     *
     * @param string $nivel
     * @return Expediente
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
     * Set anyo_Academico
     *
     * @param string $anyoAcademico
     * @return Expediente
     */
    public function setAnyoAcademico($anyoAcademico)
    {
        $this->anyo_Academico = $anyoAcademico;

        return $this;
    }

    /**
     * Get anyo_Academico
     *
     * @return string 
     */
    public function getAnyoAcademico()
    {
        return $this->anyo_Academico;
    }
}
