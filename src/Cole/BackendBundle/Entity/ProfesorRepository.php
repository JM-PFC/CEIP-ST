<?php

namespace Cole\BackendBundle\Entity;

use Doctrine\ORM\EntityRepository;

class ProfesorRepository extends EntityRepository
{
	public function findProfesor($nombre, $apellido1, $apellido2 )
	{
		return $this->getEntityManager()->createQuery(
			'SELECT p FROM BackendBundle:Profesor p WHERE p.nombre=:nombre AND p.apellido1=:apellido1 AND p.apellido2=:apellido2 AND p.activo=:activo')
		->setParameters(array(
			'nombre' => $nombre,
			'apellido1' => $apellido1,
			'apellido2' => $apellido2,
			'activo'=>1))
		->setMaxResults(1)
		->getOneOrNullResult();
	}

	public function findProfesoresPorCurso($id)
	{
	return $this->getEntityManager()->createQuery(
			'SELECT p FROM BackendBundle:Profesor p WHERE p.activo=1 ORDER BY p.apellido1')
		
		->getResult();
	}


	public function findComprobarUnidades($horario, $equipamiento, $fecha) 
	{
		return $this->getEntityManager()->createQuery(
			'SELECT COUNT(r) FROM BackendBundle:Reserva r WHERE r.equipamiento=:equipamiento  
			and r.fecha=:fecha and r.horario=:horaClase')
		->setParameters(array(
			'equipamiento' => $equipamiento,
			'horaClase' => $horario,
			'fecha' => $fecha))
		->setMaxResults(1)
		->getOneOrNullResult();
	}

	public function findProfesoresDePrimaria()
	{
	return $this->getEntityManager()->createQuery(
			'SELECT p FROM BackendBundle:Profesor p WHERE p.activo=1  and p.nivel=:nivel ORDER BY p.nombre')
			->setParameters(array(
			'nivel' => "Primaria"))
		->getResult();
	}
	

}
