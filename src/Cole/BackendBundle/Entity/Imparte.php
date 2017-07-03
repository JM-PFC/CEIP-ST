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
    * @ORM\ManyToOne(targetEntity="AsignaturasCursos", inversedBy="imparte")
    * @ORM\JoinColumn(name="asignatura_id", referencedColumnName="id", nullable=false)
    */
    private $asignatura;


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
     * @ORM\Column(name="dia_semanal", type="string", length=1, nullable=true)
     */
    private $dia_semanal;

    /**
     * @var string
     *
     * 
     * @ORM\ManyToOne(targetEntity="Horario", inversedBy="imparte")
     * @ORM\JoinColumn(name="horario", referencedColumnName="id" )
     */
    private $horario;

    /**
     * @var string
     *
     * 
     * @ORM\ManyToOne(targetEntity="Equipamiento", inversedBy="imparte")
     * @ORM\JoinColumn(name="aula", referencedColumnName="id" )
     */
    private $aula;

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
     * Set dia_semanal
     *
     * @param string $diaSemanal
     * @return Imparte
     */
    public function setDiaSemanal($diaSemanal)
    {
        $this->dia_semanal = $diaSemanal;

        return $this;
    }

    /**
     * Get dia_semanal
     *
     * @return string 
     */
    public function getDiaSemanal()
    {
        return $this->dia_semanal;
    }

    /**
     * Set asignatura
     *
     * @param \Cole\BackendBundle\Entity\AsignaturasCursos $asignatura
     * @return Imparte
     */
    public function setAsignatura(\Cole\BackendBundle\Entity\AsignaturasCursos $asignatura)
    {
        $this->asignatura = $asignatura;

        return $this;
    }

    /**
     * Get asignatura
     *
     * @return \Cole\BackendBundle\Entity\AsignaturasCursos 
     */
    public function getAsignatura()
    {
        return $this->asignatura;
    }

    /**
     * Set aula
     *
     * @param \Cole\BackendBundle\Entity\Equipamiento $aula
     * @return Imparte
     */
    public function setAula(\Cole\BackendBundle\Entity\Equipamiento $aula = null)
    {
        $this->aula = $aula;

        return $this;
    }

    /**
     * Get aula
     *
     * @return \Cole\BackendBundle\Entity\Equipamiento 
     */
    public function getAula()
    {
        return $this->aula;
    }
}
