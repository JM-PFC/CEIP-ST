<?php

namespace Cole\BackendBundle\Entity;

use Doctrine\ORM\EntityRepository;

/**
 * ReservaRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class ReservaRepository extends EntityRepository
{
	public function findReservasUsuario($usuario, $equipamiento, $fecha)
	{
		if($usuario==null){
		  return $this->getEntityManager()->createQuery(
			'SELECT DISTINCT h.horaClase FROM BackendBundle:Reserva r LEFT JOIN r.horario h WHERE r.profesor IS NULL 
			and r.equipamiento=:equipamiento  
			and r.fecha=:fecha ORDER BY h.horaClase')
		  ->setParameters(array(
			'equipamiento' => $equipamiento,
			'fecha' => $fecha))
		  ->getResult();
		}
		else{
		  return $this->getEntityManager()->createQuery(
			'SELECT DISTINCT h.horaClase FROM BackendBundle:Reserva r INNER JOIN r.horario h WHERE r.profesor=:usuario 
			and r.equipamiento=:equipamiento  
			and r.fecha=:fecha ORDER BY h.horaClase')
		  ->setParameters(array(
			'usuario' => $usuario,
			'equipamiento' => $equipamiento,
			'fecha' => $fecha))
		  ->getResult();
		}
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

	public function findComprobarReserva($usuario, $horario, $equipamiento, $fecha) 
	{
		if($usuario==null){
			return $this->getEntityManager()->createQuery(
				'SELECT r FROM BackendBundle:Reserva r WHERE r.profesor IS NULL and r.equipamiento=:equipamiento  
				and r.fecha=:fecha and r.horario=:horaClase')
			->setParameters(array(
				'equipamiento' => $equipamiento,
				'horaClase' => $horario,
				'fecha' => $fecha))
			->setMaxResults(1)
			->getOneOrNullResult();
		}
		else{
			return $this->getEntityManager()->createQuery(
				'SELECT r FROM BackendBundle:Reserva r WHERE r.profesor=:usuario and r.equipamiento=:equipamiento  
				and r.fecha=:fecha and r.horario=:horaClase')
			->setParameters(array(
				'usuario' => $usuario,
				'equipamiento' => $equipamiento,
				'horaClase' => $horario,
				'fecha' => $fecha))
			->setMaxResults(1)
			->getOneOrNullResult();
		}
	}

	public function findReservasUnidades($horario, $equipamiento, $fecha) 
	{
		return $this->getEntityManager()->createQuery(
			'SELECT h.horaClase FROM BackendBundle:Reserva r INNER JOIN r.horario h WHERE r.equipamiento=:equipamiento  
			and r.fecha=:fecha and r.horario=:horaClase ORDER BY h.horaClase')
		->setParameters(array(
			'equipamiento' => $equipamiento,
			'horaClase' => $horario, 
			'fecha' => $fecha))
		->setMaxResults(1)
		->getOneOrNullResult();
	}

	public function findByEquipamiento($equipamiento) 
	{
		return $this->getEntityManager()->createQuery(
			'SELECT r FROM BackendBundle:Reserva r  WHERE r.equipamiento=:equipamiento and r.fecha>=CURRENT_DATE()')
		->setParameters(array(
			'equipamiento' => $equipamiento))
		->getResult();
	}

	public function findallReservasInstalaciones() 
	{
		return $this->getEntityManager()->createQuery(
			'SELECT r FROM BackendBundle:Reserva r INNER JOIN r.equipamiento e INNER JOIN r.horario h LEFT JOIN r.profesor p WHERE e.tipo=:equipamiento AND r.fecha>= CURRENT_DATE()  ORDER BY r.fecha ASC, h.inicio ASC, p.nombre ASC')
		->setParameters(array(
			'equipamiento' => "Instalación"))
		->getResult();
	}

		public function findallReservasEquipamientos() 
	{
		return $this->getEntityManager()->createQuery(
			'SELECT r FROM BackendBundle:Reserva r INNER JOIN r.equipamiento e INNER JOIN r.horario h WHERE e.tipo=:equipamiento AND r.fecha>= CURRENT_DATE()  ORDER BY r.fecha ASC, h.inicio ASC')
		->setParameters(array(
			'equipamiento' => "Equipamiento"))
		->getResult();
	}

}
