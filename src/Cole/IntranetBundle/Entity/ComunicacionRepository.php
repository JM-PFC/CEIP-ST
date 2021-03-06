<?php

namespace Cole\IntranetBundle\Entity;

use Doctrine\ORM\EntityRepository;

/**
 * ComunicacionRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class ComunicacionRepository extends EntityRepository
{
	public function findEntradaProfesor($profesor)
	{
		return $this->getEntityManager()->createQuery(
			'SELECT c FROM IntranetBundle:Comunicacion c WHERE c.profesorReceptor=:profesor AND c.tipoReceptor=1 AND c.eliminadoReceptor!=1 ORDER BY c.fecha DESC')
			->setParameters(array(
			'profesor' => $profesor))
			->getResult();
	}

	public function findRecibidosProfesor($profesor)
	{
		return $this->getEntityManager()->createQuery(
			'SELECT c FROM IntranetBundle:Comunicacion c WHERE c.profesorReceptor=:profesor AND c.tipoReceptor=1 AND c.leido=0 ORDER BY c.fecha DESC')
			->setParameters(array(
			'profesor' => $profesor))
			->getResult();
	}

	public function findSalidaProfesor($profesor)
	{
		return $this->getEntityManager()->createQuery(
			'SELECT c FROM IntranetBundle:Comunicacion c WHERE c.profesorEmisor=:profesor AND c.tipoEmisor=1 ORDER BY c.fecha DESC')
			->setParameters(array(
			'profesor' => $profesor))
			->getResult();
	}

	public function findPapeleraProfesor($profesor)
	{
		return $this->getEntityManager()->createQuery(
			'SELECT c FROM IntranetBundle:Comunicacion c WHERE ((c.profesorEmisor=:profesor AND c.eliminadoEmisor=1) OR (c.profesorReceptor=:profesor AND c.eliminadoReceptor=1)) AND c.tipoEmisor=1 ORDER BY c.fecha DESC')
			->setParameters(array(
			'profesor' => $profesor))
			->getResult();
	}
}
