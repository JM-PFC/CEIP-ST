<?php

namespace Cole\BackendBundle\Entity;

use Doctrine\ORM\EntityRepository;

class AlumnoRepository extends EntityRepository
{

	public function findAlumno($nombre, $apellido1, $apellido2 )
	{
		return $this->getEntityManager()->createQuery(
			'SELECT a FROM BackendBundle:Alumno a WHERE a.nombre=:nombre AND a.apellido1=:apellido1 AND a.apellido2=:apellido2 AND a.activo=:activo')
		->setParameters(array(
			'nombre' => $nombre,
			'apellido1' => $apellido1,
			'apellido2' => $apellido2,
			'activo'=>1))
		->setMaxResults(1)
		->getOneOrNullResult();
	}

	public function findAlumnoById($id)
	{
		return $this->getEntityManager()->createQuery(
			'SELECT a FROM BackendBundle:Alumno a WHERE a.id=:id')
		->setParameters(array(
			'id' => $id))
		->setMaxResults(1)
		->getOneOrNullResult();
	}

	public function findAlumnosPorCurso($id)
	{
	return $this->getEntityManager()->createQuery(
		'SELECT a FROM BackendBundle:Alumno a WHERE a.activo=:activo AND a.curso=:id ORDER BY a.apellido1')
		->setParameters(array(
			'id' => $id,
			'activo'=>1))
		->getResult();
	}

	public function findAntiguosAlumnosPorCurso($id)
	{
	return $this->getEntityManager()->createQuery(
		'SELECT a FROM BackendBundle:Alumno a WHERE a.activo=:activo AND a.curso=:id ORDER BY a.apellido1')
		->setParameters(array(
			'id' => $id,
			'activo'=>0))
		->getResult();
	}
	public function findAlumnosPorGrupo($grupo)
	{
	return $this->getEntityManager()->createQuery(
		'SELECT a FROM BackendBundle:Alumno a WHERE a.activo=:activo AND a.grupo=:grupo ORDER BY a.apellido1')
		->setParameters(array(
			'grupo' => $grupo,
			'activo'=>1))
		->getResult();
	}

	public function findAlumnosPorCurso_Grupo($curso,$grupo)
	{
	return $this->getEntityManager()->createQuery(
		'SELECT a FROM BackendBundle:Alumno a WHERE a.activo=:activo AND a.curso=:curso AND a.grupo=:grupo ORDER BY a.apellido1')
		->setParameters(array(
			'curso' => $curso,
			'grupo' => $grupo,
			'activo'=>1))
		->getResult();
	}

	public function findAlumnosAsignadosCurso($curso,$nivel)
	{
		return $this->getEntityManager()->createQuery(
		'SELECT a FROM BackendBundle:Alumno a WHERE a.curso=:curso AND a.nivel=:nivel')
		->getResult();
	}

	public function findByResponsables($responsable)
	{
		return $this->getEntityManager()->createQuery(
		'SELECT a FROM BackendBundle:Alumno a WHERE a.responsable1=:responsable OR a.responsable2=:responsable')
		->setParameters(array(
			'responsable' => $responsable))
		->getResult();
	}

	public function findByResponsableActivo($responsable)
	{
		return $this->getEntityManager()->createQuery(
		'SELECT a FROM BackendBundle:Alumno a WHERE (a.responsable1=:responsable OR a.responsable2=:responsable) and a.activo=:activo and a.curso IS not NULL ')
		->setParameters(array(
			'responsable' => $responsable,
			'activo'=>1))
		->getResult();
	}

	public function findOptativaNoAsignada()
	{
		return $this->getEntityManager()->createQuery(
		'SELECT a FROM BackendBundle:Alumno a INNER JOIN a.curso c WHERE a.activo=:activo and a.curso IS not NULL and c.nivel=:nivel and a.optativa IS NULL')
		->setParameters(array(
			'activo'=>1,
			'nivel'=>'Primaria'))
		->getResult();
	}

	public function findOptativaAsignada()
	{
		return $this->getEntityManager()->createQuery(
		'SELECT a FROM BackendBundle:Alumno a INNER JOIN a.curso c WHERE a.activo=:activo and a.curso IS not NULL and c.nivel=:nivel and a.optativa IS not NULL')
		->setParameters(array(
			'activo'=>1,
			'nivel'=>'Primaria'))
		->getResult();
	}

	public function findAlumnosConAsigComoOptativa($asignatura)
	{
		return $this->getEntityManager()->createQuery(
		'SELECT a FROM BackendBundle:Alumno a INNER JOIN a.curso c INNER JOIN a.optativa o INNER JOIN o.asignatura asig WHERE c.nivel=:nivel and asig=:asignatura')
		->setParameters(array(
			'asignatura'=>$asignatura,
			'nivel'=>'Primaria'))
		->getResult();
	}


}
