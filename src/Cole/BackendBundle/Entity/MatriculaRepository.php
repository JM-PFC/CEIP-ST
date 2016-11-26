<?php

namespace Cole\BackendBundle\Entity;

use Doctrine\ORM\EntityRepository;

/**
 * MatriculaRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class MatriculaRepository extends EntityRepository
{
	public function findPorAño($id,$year)
	{
	return $this->getEntityManager()->createQuery(
		'SELECT m FROM BackendBundle:Matricula m WHERE m.alumno=:alumno AND m.anyoAcademico=:year')
		->setParameters(array(
			'alumno' => $id,
			'year'=>$year))
		->setMaxResults(1)
		->getOneOrNullResult();
	}

	public function findOneByAnyoAcademico($year)
	{
	return $this->getEntityManager()->createQuery(
		'SELECT m FROM BackendBundle:Matricula m WHERE m.anyoAcademico=:year')
		->setParameters(array(
			'year'=>$year))
		->getResult();
	}


	public function findNumPorCurso($curso,$year)
	{
	return $this->getEntityManager()->createQuery(
		'SELECT COUNT(m) FROM BackendBundle:Matricula m WHERE m.curso=:curso AND m.anyoAcademico=:year')
		->setParameters(array(
			'curso' => $curso,
			'year'=>$year))
		->setMaxResults(1)
		->getOneOrNullResult();
	}

	public function findNumMatriculas()
	{
	return $this->getEntityManager()->createQuery(
		'SELECT COUNT(m) FROM BackendBundle:Matricula m')//Se deberia buscar matriculas de éste año.
		->setMaxResults(1)
		->getOneOrNullResult();
	}

	public function findMatriculadosConGrupo($curso,$year,$letra)
	{
	return $this->getEntityManager()->createQuery(
		'SELECT m FROM BackendBundle:Matricula m LEFT JOIN m.alumno a LEFT JOIN m.grupo g WHERE m.anyoAcademico=:year AND m.curso=:curso AND g.letra=:letra ORDER BY a.apellido1')
		->setParameters(array(
			'letra' => $letra,
			'curso' => $curso,
			'year'=>$year))
		->getResult();
	}

	public function findMatriculadosSinGrupo($curso,$year)
	{
	return $this->getEntityManager()->createQuery(
		'SELECT m FROM BackendBundle:Matricula m  LEFT JOIN m.alumno a WHERE m.anyoAcademico=:year AND m.curso=:curso AND  m.grupo IS NULL ORDER BY a.apellido1')
		->setParameters(array(
			'curso' => $curso,
			'year'=>$year))
		->getResult();
	}
	
}
