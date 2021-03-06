<?php

namespace Cole\IntranetBundle\Entity;

use Doctrine\ORM\EntityRepository;

/**
 * TareaRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class TareaRepository extends EntityRepository
{

		public function findTareaByProfesorGrupo($tarea, $profesor, $grupo, $asignatura)
	{
		return $this->getEntityManager()->createQuery(
			'SELECT t FROM IntranetBundle:Tarea t WHERE t.descripcion=:tarea AND t.profesor=:profesor AND t.grupo=:grupo AND t.asignatura=:asignatura ')
			->setParameters(array(
			'tarea' => $tarea,
			'profesor' => $profesor,
			'asignatura' =>$asignatura,
			'grupo' => $grupo))
			->setMaxResults(1)
			->getOneOrNullResult();
	}

	public function findTareasComunes($profesor)
	{
		return $this->getEntityManager()->createQuery(
			'SELECT t FROM IntranetBundle:Tarea t INNER JOIN t.asignatura a INNER JOIN a.asignatura asig 
			WHERE t.profesor=:profesor AND t.descripcion not like :descripcion AND t.trimestre 	is not null GROUP BY asig.nombre, t.descripcion ORDER BY t.fecha DESC')
			->setParameters(array(
			'profesor' => $profesor,
			'descripcion'=>"Evaluación_Trimestral"))
			->getResult();
	}

	public function findByDescripcionOrdenado($tarea, $asignatura)
	{
		return $this->getEntityManager()->createQuery(
			'SELECT t FROM IntranetBundle:Tarea t INNER JOIN t.grupo g INNER JOIN g.curso c INNER JOIN t.asignatura a INNER JOIN a.asignatura asig WHERE t.descripcion=:tarea AND asig.nombre=:asignatura  ORDER BY c.curso, g.letra ASC')
			->setParameters(array(
			'asignatura' => $asignatura,
			'tarea' => $tarea))
			->getResult();
	}

	public function findUltimaTarea($profesor)
	{
		return $this->getEntityManager()->createQuery(
			'SELECT t FROM IntranetBundle:Tarea t WHERE t.profesor=:profesor ORDER BY t.fecha DESC')
		->setParameters(array(
			'profesor' => $profesor))
		->setMaxResults(1)
		->getOneOrNullResult();
	}

	public function findTareasGrupoAsignatura($grupo, $asignatura)
	{
		return $this->getEntityManager()->createQuery(
			'SELECT t FROM IntranetBundle:Tarea t WHERE t.grupo=:grupo AND t.asignatura=:asignatura
			AND t.descripcion not like :descripcion AND t.trimestre is not null ORDER BY t.fecha DESC')
			->setParameters(array(
			'grupo' => $grupo,
			'asignatura' => $asignatura,
			'descripcion'=>"Evaluación_Trimestral"))
			->getResult();
	}

	public function findFinalTrimestre($grupo, $asignatura, $trimestre)
	{
		return $this->getEntityManager()->createQuery(
			'SELECT t FROM IntranetBundle:Tarea t WHERE t.grupo=:grupo AND t.asignatura=:asignatura AND t.trimestre=:trimestre 
			AND t.descripcion=:descripcion ORDER BY t.fecha DESC')
			->setParameters(array(
			'grupo' => $grupo,
			'asignatura' => $asignatura,
			'trimestre' => $trimestre,
			'descripcion'=>"Evaluación_Trimestral"))
		->setMaxResults(1)
		->getOneOrNullResult();
	}
	



}
