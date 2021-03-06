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
	public function findByGrupo($grupo)
	{
		return $this->getEntityManager()->createQuery(
		'SELECT i FROM BackendBundle:Imparte i WHERE i.grupo=:grupo GROUP BY i.asignatura')
		->setParameters(array(
			'grupo' => $grupo))
		->getResult();
	}

	public function findByGrupoTodas($grupo)
	{
		return $this->getEntityManager()->createQuery(
		'SELECT i FROM BackendBundle:Imparte i WHERE i.grupo=:grupo')
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

	public function findAsignacionesProfesor($profesor)
	{
		return $this->getEntityManager()->createQuery(
		'SELECT i FROM BackendBundle:Imparte i INNER JOIN i.grupo g INNER JOIN g.curso c INNER JOIN i.asignatura a INNER JOIN a.asignatura asig  WHERE i.profesor=:profesor GROUP BY i.grupo, i.profesor ORDER BY c.numOrden, g.letra')
		->setParameters(array(
			'profesor' => $profesor))
		->getResult();
	}

	public function findAsignacionesProfesorAsignaturaGrupo($profesor)
	{
		return $this->getEntityManager()->createQuery(
		'SELECT i FROM BackendBundle:Imparte i INNER JOIN i.grupo g INNER JOIN g.curso c INNER JOIN i.asignatura a INNER JOIN a.asignatura asig  WHERE i.profesor=:profesor and asig.nombre not like :asignatura GROUP BY i.grupo, i.asignatura  ORDER BY c.numOrden, g.letra')
		->setParameters(array(
			'asignatura'=>"Tutoría",
			'profesor' => $profesor))
		->getResult();
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
		'SELECT i FROM BackendBundle:Imparte i WHERE i.horario IS not NULL and i.grupo=:grupo')
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

	public function findOpcionalesCurso($curso)
	{
		return $this->getEntityManager()->createQuery(
		'SELECT i FROM BackendBundle:Imparte i INNER JOIN i.asignatura a INNER JOIN a.asignatura asig INNER JOIN i.grupo g WHERE asig.opcional=:opcional and g.curso=:curso')
		->setParameters(array(
			'curso' => $curso,
			'opcional' => 1))
		->getResult();
	}


	public function findOcupadoPorProfesor($profesor, $grupo)
	{
		return $this->getEntityManager()->createQuery(
		'SELECT i FROM BackendBundle:Imparte i WHERE i.profesor=:profesor and i.dia_semanal IS not NULL and i.horario IS not NULL and i.grupo NOT IN (:grupo)')
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

	public function findAsignacionesOptativaAlumno($grupo, $asignatura)
	{
		return $this->getEntityManager()->createQuery(
		'SELECT i FROM BackendBundle:Imparte i WHERE i.grupo=:grupo and i.asignatura=:asignatura')
		->setParameters(array(
			'grupo' => $grupo,
			'asignatura' => $asignatura))
		->getResult();
	}

	public function findNoOpcionalesConHorario($grupo)
	{
		return $this->getEntityManager()->createQuery(
		'SELECT i FROM BackendBundle:Imparte i INNER JOIN i.asignatura a INNER JOIN a.asignatura asig WHERE i.horario IS not NULL and i.grupo=:grupo and asig.opcional=:opcional')
		->setParameters(array(
			'grupo' => $grupo,
			'opcional' => 0))
		->getResult();
	}

	public function findOpcionalesConHorario($grupo)
	{
		return $this->getEntityManager()->createQuery(
		'SELECT i FROM BackendBundle:Imparte i INNER JOIN i.asignatura a INNER JOIN a.asignatura asig WHERE i.horario IS not NULL and i.grupo=:grupo and asig.opcional=:opcional')
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

	public function findOpcionalesSinRepetir($grupo)
	{
		return $this->getEntityManager()->createQuery(
		'SELECT i FROM BackendBundle:Imparte i INNER JOIN i.asignatura a INNER JOIN a.asignatura asig WHERE i.grupo=:grupo and asig.opcional=:opcional GROUP BY i.asignatura')
		->setParameters(array(
			'grupo' => $grupo,
			'opcional' => 1))
		->getResult();
	}

	public function findAsignacionesProfesores($grupo)
	{
		return $this->getEntityManager()->createQuery(
		'SELECT i FROM BackendBundle:Imparte i INNER JOIN i.grupo g INNER JOIN i.asignatura a INNER JOIN a.asignatura asig INNER JOIN i.profesor p WHERE i.grupo=:grupo and asig.nombre not like :asignatura GROUP BY i.profesor, i.asignatura ORDER BY p.apellido1, asig.nombre')
		->setParameters(array(
			'grupo' => $grupo,
			'asignatura'=>"Tutoría"))
		->getResult();
	}

	public function findProfesoresGrupoSinRepetir($grupo)
	{
		return $this->getEntityManager()->createQuery(
		'SELECT i FROM BackendBundle:Imparte i INNER JOIN i.grupo g INNER JOIN i.profesor p WHERE i.grupo=:grupo GROUP BY i.profesor ORDER BY p.apellido1, p.apellido2')
		->setParameters(array(
			'grupo' => $grupo))
		->getResult();
	}

	public function findTutorGrupoSinRepetir($profesor, $grupo)
	{
		return $this->getEntityManager()->createQuery(
		'SELECT i FROM BackendBundle:Imparte i INNER JOIN i.grupo g INNER JOIN i.profesor p INNER JOIN i.asignatura a INNER JOIN a.asignatura asig WHERE i.grupo=:grupo and i.profesor=:profesor and asig.nombre not like :asignatura GROUP BY i.profesor, i.asignatura')
		->setParameters(array(
			'profesor'=>$profesor,
			'grupo' => $grupo,
			'asignatura'=>"Tutoría"))
		->getResult();
	}
	#Se devuelve sólo el id, abreviatura y nombre para mostrarlo por ajax.(No se devuelve "Tutoría")
	public function findAsignaturasProfesorGrupo($profesor, $grupo)
	{
		return $this->getEntityManager()->createQuery(
		'SELECT a.id, asig.abreviatura, asig.nombre FROM BackendBundle:Imparte i INNER JOIN i.grupo g INNER JOIN i.profesor p INNER JOIN i.asignatura a INNER JOIN a.asignatura asig  WHERE p=:profesor and  i.grupo=:grupo and asig.nombre not like :tutoria GROUP BY i.asignatura')
		->setParameters(array(
			'profesor'=>$profesor,
			'tutoria'=>"Tutoría",
			'grupo' => $grupo))
		->getResult();
	}
	public function findNoOpcionalesProfesorGrupo($profesor, $grupo)
	{
		return $this->getEntityManager()->createQuery(
		'SELECT i FROM BackendBundle:Imparte i INNER JOIN i.grupo g INNER JOIN i.profesor p INNER JOIN i.asignatura a INNER JOIN a.asignatura asig WHERE p=:profesor and  i.grupo=:grupo and asig.opcional=:opcional GROUP BY i.asignatura')
		->setParameters(array(
			'profesor'=>$profesor,
			'grupo' => $grupo,
			'opcional'=>0))
		->getResult();
	}

	public function findOpcionalProfesorGrupo($profesor, $grupo)
	{
		return $this->getEntityManager()->createQuery(
		'SELECT i FROM BackendBundle:Imparte i INNER JOIN i.grupo g INNER JOIN i.profesor p INNER JOIN i.asignatura a INNER JOIN a.asignatura asig WHERE p=:profesor and  i.grupo=:grupo and asig.opcional=:opcional GROUP BY i.asignatura')
		->setParameters(array(
			'profesor'=>$profesor,
			'grupo' => $grupo,
			'opcional'=>1))
		->setMaxResults(1)
		->getOneOrNullResult();
	}

	public function findExistence($profesor, $dia, $hora)
	{
		return $this->getEntityManager()->createQuery(
			'SELECT i FROM BackendBundle:Imparte i WHERE i.profesor=:profesor and i.dia_semanal=:dia and i.horario=:hora ')
		->setParameters(array(
			'profesor' => $profesor,
			'dia' => $dia, 
			'hora' => $hora))
		->setMaxResults(1)
		->getOneOrNullResult();
	}

	public function findAsignaturasProfesor($profesor)
	{
		return $this->getEntityManager()->createQuery(
		'SELECT i FROM BackendBundle:Imparte i INNER JOIN i.profesor p INNER JOIN i.asignatura a INNER JOIN a.asignatura asig  WHERE p=:profesor and asig.nombre not like :asignatura GROUP BY asig.id ORDER BY asig.nombre ASC')
		->setParameters(array(
			'profesor'=>$profesor,
			'asignatura'=>"Tutoría"))
		->getResult();
	}
	
	#Se devuelve sólo el id, curso y letra para mostrarlo por ajax.
	public function findGruposProfesorAsignatura($profesor, $asignatura)
	{
		return $this->getEntityManager()->createQuery(
		'SELECT g.id, c.curso, g.letra FROM BackendBundle:Imparte i INNER JOIN i.grupo g  INNER JOIN g.curso c INNER JOIN i.asignatura a INNER JOIN i.profesor p WHERE p=:profesor and  a.asignatura=:asignatura GROUP BY c.numOrden, g.letra')
		->setParameters(array(
			'profesor'=>$profesor,
			'asignatura' => $asignatura))
		->getResult();
	}

	public function findDiasSemanaProfesor($profesor, $grupo, $asignatura)
	{
		return $this->getEntityManager()->createQuery(
		'SELECT i FROM BackendBundle:Imparte i WHERE i.profesor=:profesor AND i.asignatura=:asignatura AND i.grupo=:grupo AND i.dia_semanal is not null GROUP BY i.dia_semanal ORDER BY i.dia_semanal ASC')
		->setParameters(array(
			'profesor'=>$profesor,
			'grupo'=>$grupo,
			'asignatura' => $asignatura))
		->getResult();
	}

	public function findHorasImpartidaClaseProfesor($profesor, $grupo, $asignatura, $dia)
	{
		return $this->getEntityManager()->createQuery(
		'SELECT i FROM BackendBundle:Imparte i WHERE i.profesor=:profesor AND i.asignatura=:asignatura AND i.grupo=:grupo AND i.dia_semanal=:dia ORDER BY i.dia_semanal ASC')
		->setParameters(array(
			'profesor'=>$profesor,
			'grupo'=>$grupo,
			'dia'=>$dia,
			'asignatura' => $asignatura))
		->getResult();
	}


}
