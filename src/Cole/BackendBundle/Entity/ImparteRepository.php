<?php

namespace Cole\BackendBundle\Entity;

use Doctrine\ORM\EntityRepository;

/**
 * ImparteRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class ImparteRepository extends EntityRepository
{
/*
	public function findByCurso($curso)
	{
		return $this->getEntityManager()->createQuery(
		'SELECT i FROM BackendBundle:Imparte i WHERE i.curso=:curso GROUP BY i.asignatura')
		->setParameters(array(
			'curso' => $curso))
		->getResult();
	}
*/
	public function findByGrupo($grupo)
	{
		return $this->getEntityManager()->createQuery(
		'SELECT i FROM BackendBundle:Imparte i WHERE i.grupo=:grupo GROUP BY i.asignatura')
		->setParameters(array(
			'grupo' => $grupo))
		->getResult();
	}

	public function findByDatos($dia_semanal,$ini,$fin,$profesor)
	{
		return $this->getEntityManager()->createQuery(
			'SELECT i FROM BackendBundle:Imparte i INNER JOIN i.horario h WHERE i.profesor=:profesor and i.dia_semanal=:dia_semanal and h.inicio=:ini and h.fin=:fin')
		->setParameters(array(
			'profesor' => $profesor,
			'dia_semanal' => $dia_semanal, 
			'ini' => $ini,
			'fin' => $fin))
		->setMaxResults(1)
		->getOneOrNullResult();
	}

	//Se obtiene los profesores que tienen asignado un curso.
    //$entities_active = $em->getRepository('BackendBundle:Imparte')->findAsignaciones();
	
	public function findAsignaciones()
	{
		return $this->getEntityManager()->createQuery(
		'SELECT i FROM BackendBundle:Imparte i INNER JOIN i.grupo g INNER JOIN g.curso c GROUP BY i.profesor, c.id')
		->getResult();
	}

	public function findByOneGrupoAndAsignatura($grupo,$asignatura)
	{
		return $this->getEntityManager()->createQuery(
		'SELECT i FROM BackendBundle:Imparte i INNER JOIN i.grupo g INNER JOIN i.asignatura a WHERE g=:grupo AND a=:asignatura GROUP BY i.asignatura')
		->setParameters(array(
			'grupo' => $grupo,
			'asignatura'=>$asignatura))
		->setMaxResults(1)
		->getOneOrNullResult();
	}
	public function findByGrupoAndAsignatura($grupo,$asignatura)
	{
		return $this->getEntityManager()->createQuery(
		'SELECT i FROM BackendBundle:Imparte i INNER JOIN i.grupo g INNER JOIN i.asignatura a WHERE g=:grupo AND a=:asignatura')
		->setParameters(array(
			'grupo' => $grupo,
			'asignatura'=>$asignatura))
		->getResult();
	}

	public function findByAsignaturaLibre($grupo,$asignatura)
	{
		return $this->getEntityManager()->createQuery(
		'SELECT i FROM BackendBundle:Imparte i INNER JOIN i.grupo g INNER JOIN i.asignatura a WHERE g=:grupo AND a=:asignatura and i.horario IS NULL and i.dia_semanal IS NULL')
		->setParameters(array(
			'grupo' => $grupo,
			'asignatura'=>$asignatura))
		->setMaxResults(1)
		->getOneOrNullResult();
	}

	public function findByAsignaturaOcupada($grupo, $dia, $horario)
	{
		return $this->getEntityManager()->createQuery(
		'SELECT i FROM BackendBundle:Imparte i INNER JOIN i.grupo g  WHERE g=:grupo and i.horario=:horario and i.dia_semanal=:dia')
		->setParameters(array(
			'grupo' => $grupo,
			'dia' => $dia,
			'horario'=>$horario))
		->setMaxResults(1)
		->getOneOrNullResult();
	}

	public function findByAsignaturasOpLibre($grupo)
	{
		return $this->getEntityManager()->createQuery(
		'SELECT i FROM BackendBundle:Imparte i INNER JOIN i.grupo g INNER JOIN i.asignatura a INNER JOIN a.asignatura asig WHERE g=:grupo and asig.opcional=:opcional and i.horario IS NULL and i.dia_semanal IS NULL GROUP BY i.asignatura')
		->setParameters(array(
			'grupo' => $grupo,
			'opcional'=>1))
		->getResult();
	}

	public function findByAsignaturasOpOcupada($grupo, $dia, $horario)
	{
		return $this->getEntityManager()->createQuery(
		'SELECT i FROM BackendBundle:Imparte i INNER JOIN i.grupo g INNER JOIN i.asignatura a INNER JOIN a.asignatura asig WHERE g=:grupo and asig.opcional=:opcional and i.horario=:horario and i.dia_semanal=:dia')
		->setParameters(array(
			'grupo' => $grupo,
			'dia' => $dia,
			'horario'=>$horario,
			'opcional'=>1))
		->getResult();
	}


	
	public function findNumAsignacionesProfesor($profesor)
	{
		return $this->getEntityManager()->createQuery(
		'SELECT COUNT(i) FROM BackendBundle:Imparte i WHERE i.profesor=:profesor GROUP BY i.asignatura, i.grupo')
		->setParameters(array(
			'profesor' => $profesor))
		->setMaxResults(1)
		->getOneOrNullResult();
	}

	public function findConHorario()
	{
		return $this->getEntityManager()->createQuery(
		'SELECT i FROM BackendBundle:Imparte i WHERE i.horario IS not NULL ')
		->getResult();
	}

	public function findByGrupoConHorario($grupo)
	{
		return $this->getEntityManager()->createQuery(
		'SELECT i FROM BackendBundle:Imparte i WHERE i.horario IS not NULL and i.grupo=:grupo GROUP BY i.asignatura')
		->setParameters(array(
			'grupo' => $grupo))
		->getResult();
	}

	public function findAulaAsignada($aula,$grupo)
	{
		return $this->getEntityManager()->createQuery(
		'SELECT i FROM BackendBundle:Imparte i INNER JOIN i.asignatura a INNER JOIN a.asignatura asig WHERE i.grupo=:grupo and i.aula=:aula and i.aula IS not NULL and asig.opcional=:opcional GROUP BY i.asignatura')
		->setParameters(array(
			'grupo' => $grupo,
			'aula' => $aula,
			'opcional' => 1))
		->getResult();
	}

	public function findOpcionalesGrupo($grupo)
	{
		return $this->getEntityManager()->createQuery(
		'SELECT i FROM BackendBundle:Imparte i INNER JOIN i.asignatura a INNER JOIN a.asignatura asig WHERE i.grupo=:grupo and asig.opcional=:opcional GROUP BY i.asignatura')
		->setParameters(array(
			'grupo' => $grupo,
			'opcional' => 1))
		->getResult();
	}


	public function findOcupadoPorProfesor($profesor, $grupo)
	{
		return $this->getEntityManager()->createQuery(
		'SELECT i FROM BackendBundle:Imparte i WHERE i.profesor=:profesor and i.dia_semanal IS not NULL and i.horario IS not NULL and i.grupo NOT IN (:grupo) GROUP BY i.asignatura')
		->setParameters(array(
			'profesor' => $profesor,
			'grupo' => $grupo))
		->getResult();
	}

		public function findOcupadoPorAula($aula, $grupo)
	{
		return $this->getEntityManager()->createQuery(
		'SELECT i FROM BackendBundle:Imparte i WHERE i.aula=:aula and i.dia_semanal IS not NULL and i.horario IS not NULL and i.grupo NOT IN (:grupo) GROUP BY i.asignatura')
		->setParameters(array(
			'aula' => $aula,
			'grupo' => $grupo))
		->getResult();
	}

		public function findAsignaturasSinRepetir($asignatura)
	{
		return $this->getEntityManager()->createQuery(
		'SELECT i FROM BackendBundle:Imparte i INNER JOIN i.asignatura a where a.id=:asignatura GROUP BY i.asignatura')
		->setParameters(array(
			'asignatura'=>$asignatura))
		->getResult();
	}


	public function findAsignacionesNoOpcionales($grupo)
	{
	return $this->getEntityManager()->createQuery(
		'SELECT i FROM BackendBundle:Imparte i INNER JOIN i.asignatura a INNER JOIN a.asignatura asig WHERE i.grupo=:grupo and asig.opcional=:opcional')
		->setParameters(array(
			'grupo' => $grupo,
			'opcional' => 0))
		->getResult();
	}

	public function findAsignacionesOpcionales($grupo)
	{
	return $this->getEntityManager()->createQuery(
		'SELECT i FROM BackendBundle:Imparte i INNER JOIN i.asignatura a INNER JOIN a.asignatura asig WHERE i.grupo=:grupo and asig.opcional=:opcional')
		->setParameters(array(
			'grupo' => $grupo,
			'opcional' => 1))
		->getResult();
	}

	
	public function findAsignacionesOtrosGrupos($profesor, $grupo)
	{
	return $this->getEntityManager()->createQuery(
		'SELECT i FROM BackendBundle:Imparte i INNER JOIN i.profesor p WHERE i.grupo NOT IN (:grupo) and p=:profesor and i.dia_semanal IS not NULL and i.horario IS not NULL')
		->setParameters(array(
			'grupo' => $grupo,
			'profesor' => $profesor))
		->getResult();
	}

}
