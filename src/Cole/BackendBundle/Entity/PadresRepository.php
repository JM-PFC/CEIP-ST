<?php

namespace Cole\BackendBundle\Entity;

use Doctrine\ORM\EntityRepository;

class PadresRepository extends EntityRepository
{


	public function findResponsable($dni)
	{
		return $this->getEntityManager()->createQuery(
			'SELECT p FROM BackendBundle:Padres p WHERE p.dni=:dni')
		->setParameter('dni',$dni)
		->setMaxResults(1)
		->getOneOrNullResult();
	}

}