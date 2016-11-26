<?php

namespace Cole\BackendBundle\Entity;

use Doctrine\ORM\EntityRepository;

/**
 * CursoRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class CursoRepository extends EntityRepository
{

	public function findCursoByNivel($curso,$nivel)
	{
		return $this->getEntityManager()->createQuery(
			'SELECT c FROM BackendBundle:Curso c WHERE c.curso=:curso AND c.nivel=:nivel')
		->setParameters(array(
			'curso' => $curso,
			'nivel' => $nivel))
		->setMaxResults(1)
		->getOneOrNullResult();
	}

	public function findAll()
	{
		return $this->getEntityManager()->createQuery(
		'SELECT c FROM BackendBundle:Curso c ORDER BY c.numOrden')
		->getResult();
	}
	
	public function findUltimoCurso()
	{
		return $this->getEntityManager()->createQuery(
		'SELECT c FROM BackendBundle:Curso c WHERE c.numOrden=(SELECT max(co.numOrden) FROM BackendBundle:Curso co)')
		->setMaxResults(1)
		->getOneOrNullResult();
	}


}
