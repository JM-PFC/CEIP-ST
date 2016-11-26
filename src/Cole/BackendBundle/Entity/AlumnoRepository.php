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




}
