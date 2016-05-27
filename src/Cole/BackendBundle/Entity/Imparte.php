<?php

namespace Cole\BackendBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Imparte
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="Cole\BackendBundle\Entity\ImparteRepository")
 */
class Imparte
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
    * @ORM\ManyToOne(targetEntity="Asignatura", inversedBy="imparte")
    * @ORM\JoinColumn(name="asignatura_id", referencedColumnName="id", nullable=false)
    */
    private $asignatura;

        /**
    * @ORM\ManyToOne(targetEntity="Curso", inversedBy="imparte")
    * @ORM\JoinColumn(name="curso_id", referencedColumnName="id", nullable=false)
    */
    private $curso;

    /**
    * @ORM\ManyToOne(targetEntity="Grupo", inversedBy="imparte")
    * @ORM\JoinColumn(name="grupo_id", referencedColumnName="id", nullable=false)
    */
    private $grupo;

    /**
    * @ORM\ManyToOne(targetEntity="Profesor", inversedBy="imparte")
    * @ORM\JoinColumn(name="profesor_id", referencedColumnName="id", nullable=false)
    */
    private $profesor;

    /**
     * @var string
     *
     * @ORM\Column(name="horario", type="string", length=255)
     */
    private $horario;

    /**
     * @var string
     *
     * @ORM\Column(name="libro", type="string", length=255)
     */
    private $libro;

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
     * Set asignatura
     *
     * @param \Cole\BackendBundle\Entity\Asignatura $asignatura
     * @return Imparte
     */
    public function setAsignatura(\Cole\BackendBundle\Entity\Asignatura $asignatura)
    {
        $this->asignatura = $asignatura;

        return $this;
    }

    /**
     * Get asignatura
     *
     * @return \Cole\BackendBundle\Entity\Profesor 
     */
    public function getAsignatura()
    {
        return $this->asignatura;
    }

    /**
     * Set grupo
     *
     * @param \Cole\BackendBundle\Entity\Grupo $grupo
     * @return Imparte
     */
    public function setGrupo(\Cole\BackendBundle\Entity\Grupo $grupo)
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
     * Set profesor
     *
     * @param \Cole\BackendBundle\Entity\Profesor $profesor
     * @return Imparte
     */
    public function setProfesor(\Cole\BackendBundle\Entity\Profesor $profesor)
    {
        $this->profesor = $profesor;

        return $this;
    }

    /**
     * Get profesor
     *
     * @return \Cole\BackendBundle\Entity\Profesor 
     */
    public function getProfesor()
    {
        return $this->profesor;
    }

    /**
     * Set horario
     *
     * @param string $horario
     * @return Imparte
     */
    public function setHorario($horario)
    {
        $this->horario = $horario;

        return $this;
    }

    /**
     * Get horario
     *
     * @return string 
     */
    public function getHorario()
    {
        return $this->horario;
    }

    /**
     * Set libro
     *
     * @param string $libro
     * @return Imparte
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
     * Set curso
     *
     * @param \Cole\BackendBundle\Entity\Curso $curso
     * @return Imparte
     */
    public function setCurso(\Cole\BackendBundle\Entity\Curso $curso)
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
