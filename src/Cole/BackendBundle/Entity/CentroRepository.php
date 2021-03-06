<?php

namespace Cole\BackendBundle\Entity;

use Doctrine\ORM\EntityRepository;

/**
 * CentroRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class CentroRepository extends EntityRepository
{

	public function findInicioCurso()
	{
		return $this->getEntityManager()->createQuery(
			'SELECT c.inicioCurso FROM BackendBundle:Centro c')
		->setMaxResults(1)
		->getOneOrNullResult();
	}

	public function findFinCurso()
	{
		return $this->getEntityManager()->createQuery(
			'SELECT c.finCurso FROM BackendBundle:Centro c')
		->setMaxResults(1)
		->getOneOrNullResult();
	}

	public function findCentro()
	{
		return $this->getEntityManager()->createQuery(
			'SELECT c FROM BackendBundle:Centro c')
		->setMaxResults(1)
		->getOneOrNullResult();
	}





}
