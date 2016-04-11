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
			'SELECT a FROM BackendBundle:Alumno a WHERE a.id=:id AND a.activo=:activo')
		->setParameters(array(
			'id' => $id,
			'activo'=>1))
		->setMaxResults(1)
		->getOneOrNullResult();
	}

	public function findAlumnosPorCurso($id)
	{
	return $this->getEntityManager()->createQuery(
		'SELECT a FROM BackendBundle:Alumno a WHERE a.activo=1 ORDER BY a.apellido1')
		->getResult();
	}
}
