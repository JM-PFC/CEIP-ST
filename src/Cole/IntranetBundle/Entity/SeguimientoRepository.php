<?php

namespace Cole\IntranetBundle\Entity;

use Doctrine\ORM\EntityRepository;

/**
 * SeguimientoRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class SeguimientoRepository extends EntityRepository
{
	public function findNuevosSeguimientos($profesor)
	{
		return $this->getEntityManager()->createQuery(
			'SELECT s FROM IntranetBundle:Seguimiento s WHERE s.profesor=:profesor AND s.tipo=:tipo AND s.tipoUser=:tipoUser AND s.asignatura IS NOT NULL
			AND s.id IN(select a.idAviso FROM IntranetBundle:Avisos a where a.idUsuario=:profesor AND a.tipoUsuario=:tipoUsuario AND a.tipoAviso=:tipoAviso) ORDER BY s.fecha DESC')
		->setParameters(array(
			'profesor' => $profesor,
			'tipoUsuario' => "Profesor",
			'tipoAviso' => "Seguimiento",
			'tipo'=>1,
			'tipoUser'=>1))
		->setMaxResults(5)
		->getResult();
	}

	public function findAntiguosSeguimientos($profesor)
	{
		return $this->getEntityManager()->createQuery(
			'SELECT s FROM IntranetBundle:Seguimiento s WHERE s.profesor=:profesor AND s.tipo=:tipo AND s.tipoUser=:tipoUser AND s.asignatura IS NOT NULL
			AND s.id NOT IN(select a.idAviso FROM IntranetBundle:Avisos a where a.idUsuario=:profesor AND a.tipoUsuario=:tipoUsuario AND a.tipoAviso=:tipoAviso) ORDER BY s.fecha DESC')
		->setParameters(array(
			'profesor' => $profesor,
			'tipoUsuario' => "Profesor",
			'tipoAviso' => "Seguimiento",
			'tipo'=>1,
			'tipoUser'=>1))
		->setMaxResults(5)
		->getResult();
	}

	public function findAntiguosSeguimientosContador($profesor, $contador)
	{
		return $this->getEntityManager()->createQuery(
			'SELECT s FROM IntranetBundle:Seguimiento s WHERE s.profesor=:profesor AND s.tipo=:tipo AND s.tipoUser=:tipoUser AND s.asignatura IS NOT NULL
			AND s.id NOT IN(select a.idAviso FROM IntranetBundle:Avisos a where a.idUsuario=:profesor AND a.tipoUsuario=:tipoUsuario AND a.tipoAviso=:tipoAviso) ORDER BY s.fecha DESC')
		->setParameters(array(
			'profesor' => $profesor,
			'tipoUsuario' => "Profesor",
			'tipoAviso' => "Seguimiento",
			'tipo'=>1,
			'tipoUser'=>1))
		->setMaxResults($contador)
		->getResult();
	}

	public function findUltimosSeguimientos($profesor)
	{
		return $this->getEntityManager()->createQuery(
			'SELECT s FROM IntranetBundle:Seguimiento s WHERE s.profesor=:profesor AND s.tipo=:tipo AND s.tipoUser=:tipoUser AND s.asignatura IS NOT NULL ORDER BY s.fecha DESC')
		->setParameters(array(
			'profesor' => $profesor,
			'tipo'=>1,
			'tipoUser'=>1))
		->setMaxResults(5)
		->getResult();
	}

	public function findSeguimientosActualizadosAlumno($alumno, $idResponsable, $grupo)
	{
		return $this->getEntityManager()->createQuery(
			'SELECT s FROM IntranetBundle:Seguimiento s WHERE s.grupo=:grupo AND (s.alumno=:alumno or s.alumno IS NULL) AND s.tipo=:tipo AND s.tipoUser=:tipoUser AND s.asignatura IS NOT NULL AND  
			s.id IN(select a.idAviso FROM IntranetBundle:Avisos a where a.idUsuario=:alumno AND a.idResponsable=:idResponsable AND (a.tipoUsuario=:tipoUsuario OR a.tipoUsuario=:tipoGrupo) AND a.tipoAviso=:tipoAviso) ORDER BY s.fecha DESC')
		->setParameters(array(
			'alumno' => $alumno,
			'grupo' =>$grupo,
			'idResponsable' => $idResponsable,
			'tipoUsuario' => "Alumno",
			'tipoGrupo' => "Grupo",
			'tipoAviso' => "Seguimiento",
			'tipo'=>1,
			'tipoUser'=>1))
		->setMaxResults(5)
		->getResult();
	}

	public function findAntiguosSeguimientosAlumno($alumno, $idResponsable, $grupo)
	{
		return $this->getEntityManager()->createQuery(
			'SELECT s FROM IntranetBundle:Seguimiento s WHERE s.grupo=:grupo AND s.tipo=:tipo AND s.tipoUser=:tipoUser AND (s.alumno=:alumno or s.alumno IS NULL) AND s.asignatura IS NOT NULL
			AND s.id NOT IN(select a.idAviso FROM IntranetBundle:Avisos a where a.idUsuario=:alumno AND a.idResponsable=:idResponsable AND (a.tipoUsuario=:tipoUsuario OR a.tipoUsuario=:tipoGrupo) AND a.tipoAviso=:tipoAviso) ORDER BY s.fecha DESC')
		->setParameters(array(
			'alumno' => $alumno,
			'grupo' =>$grupo,
			'idResponsable' => $idResponsable,
			'tipoUsuario' => "Alumno",
			'tipoGrupo' => "Grupo",
			'tipoAviso' => "Seguimiento",
			'tipo'=>1,
			'tipoUser'=>1))
		->setMaxResults(5)
		->getResult();
	}

	public function findAntiguosSeguimientosContadorAlumno($alumno, $idResponsable, $grupo, $contador)
	{
		return $this->getEntityManager()->createQuery(
			'SELECT s FROM IntranetBundle:Seguimiento s WHERE s.grupo=:grupo AND s.tipo=:tipo AND s.tipoUser=:tipoUser AND (s.alumno=:alumno or s.alumno IS NULL) AND s.asignatura IS NOT NULL
			AND s.id NOT IN(select a.idAviso FROM IntranetBundle:Avisos a where a.idUsuario=:alumno AND a.idResponsable=:idResponsable AND (a.tipoUsuario=:tipoUsuario OR a.tipoUsuario=:tipoGrupo) AND a.tipoAviso=:tipoAviso) ORDER BY s.fecha DESC')
		->setParameters(array(
			'alumno' => $alumno,
			'grupo' =>$grupo,
			'idResponsable' => $idResponsable,
			'tipoUsuario' => "Alumno",
			'tipoGrupo' => "Grupo",
			'tipoAviso' => "Seguimiento",
			'tipo'=>1,
			'tipoUser'=>1))
		->setMaxResults($contador)
		->getResult();
	}


	public function findUltimosSeguimientosAlumnoGrupo($alumno, $grupo)
	{
		return $this->getEntityManager()->createQuery(
			'SELECT s FROM IntranetBundle:Seguimiento s WHERE s.grupo=:grupo AND s.tipo=:tipo AND s.tipoUser=:tipoUser AND s.asignatura IS NOT NULL and  (s.alumno=:alumno or s.alumno IS NULL) ORDER BY s.fecha DESC')
		->setParameters(array(
			'alumno' => $alumno,
			'grupo' => $grupo,
			'tipo'=>1,
			'tipoUser'=>1))
		->setMaxResults(5)
		->getResult();
	}
					
	public function findCargaSeguimientosProfesor($profesor, $id)
	{
		return $this->getEntityManager()->createQuery(
			'SELECT s FROM IntranetBundle:Seguimiento s WHERE s.profesor=:profesor AND s.tipo=:tipo AND s.tipoUser=:tipoUser and s.id<:id  AND s.asignatura IS NOT NULL
			 AND s.id NOT IN(select a.idAviso FROM IntranetBundle:Avisos a where a.idUsuario=:profesor AND a.tipoUsuario=:tipoUsuario AND a.tipoAviso=:tipoAviso) ORDER BY s.fecha DESC')
		->setParameters(array(
			'profesor' => $profesor,
			'id' => $id,
			'tipoUsuario' => "Profesor",
			'tipoAviso' => "Seguimiento",
			'tipo'=>1,
			'tipoUser'=>1))
		->setMaxResults(3)
		->getResult();
	}

	public function findCargaSeguimientosNuevosProfesor($fecha, $profesor)
	{
		return $this->getEntityManager()->createQuery(
			'SELECT s FROM IntranetBundle:Seguimiento s WHERE  s.profesor=:profesor AND s.tipo=:tipo AND s.tipoUser=:tipoUser AND s.fechaActualizada<:fecha  AND s.asignatura IS NOT NULL AND
			s.id IN(select a.idAviso FROM IntranetBundle:Avisos a where a.idUsuario=:profesor AND a.tipoUsuario=:tipoUsuario AND a.tipoAviso=:tipoAviso)  ORDER BY s.fecha DESC')
		->setParameters(array(
			'profesor' => $profesor,
			'tipoUsuario' => "Profesor",
			'tipoAviso' => "Seguimiento",
			'fecha' => $fecha,
			'tipo'=>1,
			'tipoUser'=>1))
		->setMaxResults(5)
		->getResult();
	}

	public function findCargaSeguimientosInicialProfesor($profesor, $contador)
	{
		return $this->getEntityManager()->createQuery(
			'SELECT s FROM IntranetBundle:Seguimiento s WHERE s.tipo=:tipo AND s.tipoUser=:tipoUser AND s.alumno=:profesor  AND s.asignatura IS NOT NULL AND 
			s.id NOT IN(select a.idAviso FROM IntranetBundle:Avisos a where a.idUsuario=:profesor AND a.tipoUsuario=:tipoUsuario AND a.tipoAviso=:tipoAviso) ORDER BY s.fecha DESC')
		->setParameters(array(
			'profesor' => $profesor,
			'tipoUsuario' => "Profesor",
			'tipoAviso' => "Seguimiento",
			'tipo'=>1,
			'tipoUser'=>1))
		->setMaxResults($contador)
		->getResult();
	}

	public function findCargaSeguimientosNuevosAlumno($fecha, $alumno, $idResponsable, $grupo)
	{
		return $this->getEntityManager()->createQuery(
			'SELECT s FROM IntranetBundle:Seguimiento s WHERE s.grupo=:grupo AND (s.alumno=:alumno or s.alumno IS NULL) AND s.tipo=:tipo AND s.tipoUser=:tipoUser  AND s.asignatura IS NOT NULL AND 
			s.id IN(select a.idAviso FROM IntranetBundle:Avisos a where a.idUsuario=:alumno AND a.idResponsable=:idResponsable AND (a.tipoUsuario=:tipoUsuario OR a.tipoUsuario=:tipoGrupo) AND a.tipoAviso=:tipoAviso) AND s.fechaActualizada<:fecha ORDER BY s.fecha DESC')
		->setParameters(array(
			'alumno' => $alumno,
			'grupo' =>$grupo,
			'idResponsable' => "idResponsable",
			'tipoUsuario' => "Alumno",
			'tipoGrupo' => "Grupo",
			'tipoAviso' => "Seguimiento",
			'fecha' => $fecha,
			'tipo'=>1,
			'tipoUser'=>1))
		->setMaxResults(5)
		->getResult();
	}

	public function findCargaSeguimientosInicialAlumno($alumno, $idResponsable, $grupo, $contador)
	{
		return $this->getEntityManager()->createQuery(
			'SELECT s FROM IntranetBundle:Seguimiento s WHERE s.grupo=:grupo AND s.tipo=:tipo AND s.tipoUser=:tipoUser AND (s.alumno=:alumno or s.alumno IS NULL) AND s.asignatura IS NOT NULL
			AND s.id NOT IN(select a.idAviso FROM IntranetBundle:Avisos a where a.idUsuario=:alumno AND a.idResponsable=:idResponsable AND (a.tipoUsuario=:tipoUsuario OR a.tipoUsuario=:tipoGrupo) AND a.tipoAviso=:tipoAviso) ORDER BY s.fecha DESC')
		->setParameters(array(
			'alumno' => $alumno,
			'grupo' =>$grupo,
			'tipoUsuario' => "Alumno",
			'tipoGrupo' => "Grupo",
			'idResponsable' => "idResponsable",
			'tipoAviso' => "Seguimiento",
			'tipo'=>1,
			'tipoUser'=>1))
		->setMaxResults($contador)
		->getResult();
	}

	public function findCargaSeguimientosAlumno($alumno,$idResponsable, $id , $grupo)
	{
		return $this->getEntityManager()->createQuery(
			'SELECT s FROM IntranetBundle:Seguimiento s WHERE (s.alumno=:alumno or s.alumno IS NULL) AND s.grupo=:grupo AND s.tipo=:tipo AND s.tipoUser=:tipoUser AND s.id<:id  AND s.asignatura IS NOT NULL
			AND s.id NOT IN(select a.idAviso FROM IntranetBundle:Avisos a where a.idUsuario=:alumno AND a.idResponsable=:idResponsable AND (a.tipoUsuario=:tipoUsuario OR a.tipoUsuario=:tipoGrupo) AND a.tipoAviso=:tipoAviso) ORDER BY s.fecha DESC')
		->setParameters(array(
			'alumno' => $alumno,
			'grupo' => $grupo,
			'id' => $id,
			'idResponsable' => $idResponsable,
			'tipoUsuario' => "Alumno",
			'tipoGrupo' => "Grupo",
			'tipoAviso' => "Seguimiento",
			'tipo'=>1,
			'tipoUser'=>1))
		->setMaxResults(3)
		->getResult();
	}


	public function findNuevosSeguimientosAlumno($ultimo_acceso, $alumno, $grupo)
	{
		return $this->getEntityManager()->createQuery(
		'SELECT s FROM IntranetBundle:Seguimiento s WHERE (s.alumno=:alumno OR (s.alumno IS NULL AND s.grupo=:grupo))  AND s.asignatura IS NOT NULL
		AND (s.tipo<>s.tipoUser OR (s.tipo=s.tipoUser AND s.tipo=:tipo AND s.tipoUser=:tipoUser)) AND s.fechaActualizada>:ultimo_acceso GROUP BY s.profesor, s.alumno, s.asignatura, s.grupo ORDER BY s.fechaActualizada DESC')
		->setParameters(array(
		'alumno' => $alumno,
		'grupo' => $grupo,
		'ultimo_acceso' => $ultimo_acceso,
		'tipo'=>1,
		'tipoUser'=>1))
		->getResult();
	}

	#Seguimientos nuevos para el alumno que nunca ha visto la lista de seguimientos pendientes.
	public function findNuevosSeguimientosInicioAlumno($alumno, $grupo)
	{
		return $this->getEntityManager()->createQuery(
		'SELECT s FROM IntranetBundle:Seguimiento s WHERE (s.alumno=:alumno OR (s.alumno IS NULL AND s.grupo=:grupo))  AND s.asignatura IS NOT NULL
		AND s.tipo=s.tipoUser AND s.tipo=:tipo AND s.tipoUser=:tipoUser ORDER BY s.fechaActualizada DESC')
		->setParameters(array(
		'alumno' => $alumno,
		'grupo' => $grupo,
		'tipo'=>1,
		'tipoUser'=>1))
		->getResult();
	}
	

	public function findNuevosSeguimientosProfesor($ultimo_acceso, $profesor)
	{
		return $this->getEntityManager()->createQuery(
		'SELECT s FROM IntranetBundle:Seguimiento s WHERE s.profesor=:profesor  AND s.tipo=s.tipoUser AND s.tipo=:tipo AND s.tipoUser=:tipoUser AND s.asignatura IS NOT NULL
		AND s.fechaActualizada>=:ultimo_acceso GROUP BY s.profesor, s.alumno, s.asignatura, s.grupo ORDER BY s.fechaActualizada DESC')
		->setParameters(array(
		'profesor' => $profesor,
		'ultimo_acceso' => $ultimo_acceso,
		'tipo'=>0,
		'tipoUser'=>0))
		->getResult();
	}

	#Seguimientos nuevos para el profesor que nunca ha visto la lista de seguimientos pendientes.
	public function findNuevosSeguimientosInicioProfesor($profesor)
	{
		return $this->getEntityManager()->createQuery(
		'SELECT s FROM IntranetBundle:Seguimiento s WHERE s.profesor=:profesor AND s.tipo=s.tipoUser AND s.tipo=:tipo AND s.tipoUser=:tipoUser AND s.asignatura IS NOT NULL ORDER BY s.fechaActualizada DESC')
		->setParameters(array(
		'profesor' => $profesor,
		'tipo'=>0,
		'tipoUser'=>0))
		->getResult();
	}

	public function findRespuestas($id)
	{
		return $this->getEntityManager()->createQuery(
		'SELECT s FROM IntranetBundle:Seguimiento s WHERE s.seguimiento=:id  AND s.asignatura IS NOT NULL ORDER BY s.fecha ASC')
		->setParameters(array(
		'id' => $id))
		->getResult();
	}


  	///////////////////////////////////////////////////
  	//  Seguimientos de Tutoría  (Asignatura==NULL)  //
  	////////////////////////////////// ////////////////

	public function findNuevasTutorias($profesor)
	{
		return $this->getEntityManager()->createQuery(
			'SELECT s FROM IntranetBundle:Seguimiento s WHERE s.profesor=:profesor AND s.tipoUser=:tipoUser AND s.asignatura IS NULL
			AND s.id IN(select a.idAviso FROM IntranetBundle:Avisos a where a.idUsuario=:profesor AND a.tipoUsuario=:tipoUsuario AND a.tipoAviso=:tipoAviso) ORDER BY s.fechaActualizada DESC')
		->setParameters(array(
			'profesor' => $profesor,
			'tipoUsuario' => "Profesor",
			'tipoAviso' => "Tutoria",
			'tipoUser'=>0))
		->setMaxResults(5)
		->getResult();
	}

	public function findAntiguasTutoriasContador($profesor, $contador, $tipo)
	{

		if($tipo == "activas"){
			return $this->getEntityManager()->createQuery(
				'SELECT s FROM IntranetBundle:Seguimiento s WHERE s.profesor=:profesor AND s.tipo=:tipo AND s.asignatura IS NULL AND s.fechaTerminada Is NULL
				AND s.id NOT IN(select a.idAviso FROM IntranetBundle:Avisos a where a.idUsuario=:profesor AND a.tipoUsuario=:tipoUsuario AND a.tipoAviso=:tipoAviso) ORDER BY s.id DESC')
			->setParameters(array(
				'profesor' => $profesor,
				'tipoUsuario' => "Profesor",
				'tipoAviso' => "Tutoria",
				'tipo'=>1))
			->setMaxResults($contador)
			->getResult();
		}else{
			return $this->getEntityManager()->createQuery(
				'SELECT s FROM IntranetBundle:Seguimiento s WHERE s.profesor=:profesor AND s.tipo=:tipo AND s.asignatura IS NULL AND s.fechaTerminada Is NOT NULL
				AND s.id NOT IN(select a.idAviso FROM IntranetBundle:Avisos a where a.idUsuario=:profesor AND a.tipoUsuario=:tipoUsuario AND a.tipoAviso=:tipoAviso) ORDER BY s.fecha DESC')
			->setParameters(array(
				'profesor' => $profesor,
				'tipoUsuario' => "Profesor",
				'tipoAviso' => "Tutoria",
				'tipo'=>1))
			->setMaxResults(5)
			->getResult();
		}
	}


	public function findNuevasTutoriasAlumno($alumno, $idResponsable)
	{
		return $this->getEntityManager()->createQuery(
			'SELECT s FROM IntranetBundle:Seguimiento s WHERE  s.alumno=:alumno AND s.tipoUser=:tipoUser AND s.asignatura IS NULL AND  
			s.id IN(select a.idAviso FROM IntranetBundle:Avisos a where a.idUsuario=:alumno AND a.idResponsable=:idResponsable AND a.tipoUsuario=:tipoUsuario AND a.tipoAviso=:tipoAviso) ORDER BY s.fechaActualizada DESC')
		->setParameters(array(
			'alumno' => $alumno,
			'idResponsable' => $idResponsable,
			'tipoUsuario' => "Alumno",
			'tipoAviso' => "Tutoria",
			'tipoUser'=>1))
		->setMaxResults(5)
		->getResult();
	}



	public function findAntiguasTutoriasContadorAlumno($alumno, $idResponsable, $contador, $tipo)
	{

		if($tipo == "activas"){
			return $this->getEntityManager()->createQuery(
				'SELECT s FROM IntranetBundle:Seguimiento s WHERE  s.alumno=:alumno AND s.tipo=:tipo AND s.asignatura IS NULL AND s.fechaTerminada Is NULL AND
				s.id NOT IN(select a.idAviso FROM IntranetBundle:Avisos a where a.idUsuario=:alumno AND a.idResponsable=:idResponsable AND a.tipoUsuario=:tipoUsuario AND a.tipoAviso=:tipoAviso) ORDER BY s.fecha DESC')
			->setParameters(array(
				'alumno' => $alumno,
				'idResponsable' => $idResponsable,
				'tipoUsuario' => "Alumno",
				'tipoAviso' => "Tutoria",
				'tipo'=>1))
			->setMaxResults($contador)
			->getResult();
		}else{
			return $this->getEntityManager()->createQuery(
				'SELECT s FROM IntranetBundle:Seguimiento s WHERE  s.alumno=:alumno AND s.tipo=:tipo AND s.asignatura IS NULL AND s.fechaTerminada Is NOT NULL AND 
				s.id NOT IN(select a.idAviso FROM IntranetBundle:Avisos a where a.idUsuario=:alumno AND a.idResponsable=:idResponsable AND a.tipoUsuario=:tipoUsuario AND a.tipoAviso=:tipoAviso) ORDER BY s.fecha DESC')
				->setParameters(array(
				'alumno' => $alumno,
				'idResponsable' => $idResponsable,
				'tipoUsuario' => "Alumno",
				'tipoAviso' => "Tutoria",
				'tipo'=>1))
			->setMaxResults(5)
			->getResult();
		}
	}

	public function findCargaSeguimientosTutoriasProfesor($profesor, $id, $tipo)
	{
		if($tipo == "activas"){
			return $this->getEntityManager()->createQuery(
				'SELECT s FROM IntranetBundle:Seguimiento s WHERE s.profesor=:profesor AND s.tipo=:tipo AND s.id<:id AND s.fechaTerminada Is NULL AND s.asignatura IS NULL
			 	AND s.id NOT IN(select a.idAviso FROM IntranetBundle:Avisos a where a.idUsuario=:profesor AND a.tipoUsuario=:tipoUsuario AND a.tipoAviso=:tipoAviso) ORDER BY s.fecha DESC')
			->setParameters(array(
				'profesor' => $profesor,
				'id' => $id,
				'tipoUsuario' => "Profesor",
				'tipoAviso' => "Tutoria",
				'tipo'=>1))
			->setMaxResults(3)
			->getResult();
		}
		else{
			return $this->getEntityManager()->createQuery(
				'SELECT s FROM IntranetBundle:Seguimiento s WHERE s.profesor=:profesor AND s.tipo=:tipo AND s.id<:id AND s.fechaTerminada Is NOT NULL AND s.asignatura IS NULL
			 	AND s.id NOT IN(select a.idAviso FROM IntranetBundle:Avisos a where a.idUsuario=:profesor AND a.tipoUsuario=:tipoUsuario AND a.tipoAviso=:tipoAviso) ORDER BY s.fecha DESC')
			->setParameters(array(
				'profesor' => $profesor,
				'id' => $id,
				'tipoUsuario' => "Profesor",
				'tipoAviso' => "Tutoria",
				'tipo'=>1))
			->setMaxResults(3)
			->getResult();
		}

	}

	public function findCargaSeguimientosNuevosTutoriasProfesor($fecha, $profesor)
	{
		return $this->getEntityManager()->createQuery(
			'SELECT s FROM IntranetBundle:Seguimiento s WHERE  s.profesor=:profesor AND s.tipoUser=:tipoUser AND s.fechaTerminada Is NULL AND s.fechaActualizada<:fecha  AND s.asignatura IS NULL AND
			s.id IN(select a.idAviso FROM IntranetBundle:Avisos a where a.idUsuario=:profesor AND a.tipoUsuario=:tipoUsuario AND a.tipoAviso=:tipoAviso)  ORDER BY s.fechaActualizada DESC')
		->setParameters(array(
			'profesor' => $profesor,
			'tipoUsuario' => "Profesor",
			'tipoAviso' => "Tutoria",
			'fecha' => $fecha,
			'tipoUser'=>0))
		->setMaxResults(5)
		->getResult();
	}

	public function findCargaSeguimientosInicialTutoriasProfesor($profesor, $contador)
	{
		return $this->getEntityManager()->createQuery(
			'SELECT s FROM IntranetBundle:Seguimiento s WHERE s.tipo=:tipo AND s.profesor=:profesor  AND s.fechaTerminada Is NULL AND s.asignatura IS NULL AND 
			s.id NOT IN(select a.idAviso FROM IntranetBundle:Avisos a where a.idUsuario=:profesor AND a.tipoUsuario=:tipoUsuario AND a.tipoAviso=:tipoAviso) ORDER BY s.fecha DESC')
		->setParameters(array(
			'profesor' => $profesor,
			'tipoUsuario' => "Profesor",
			'tipoAviso' => "Tutoria",
			'tipo'=>1))
		->setMaxResults($contador)
		->getResult();
	}


	public function findNuevasTutoriasProfesor($ultimo_acceso, $profesor)
	{
		return $this->getEntityManager()->createQuery(
		'SELECT s FROM IntranetBundle:Seguimiento s WHERE s.profesor=:profesor AND s.asignatura IS NULL
		AND s.fechaActualizada>=:ultimo_acceso AND s.tipoUser=:tipoUser  ORDER BY s.fechaActualizada DESC')
		->setParameters(array(
		'profesor' => $profesor,
		'ultimo_acceso' => $ultimo_acceso,
		'tipoUser'=>0))
		->getResult();
	}

	#Seguimientos nuevos para el alumno que nunca ha visto la lista de consultas de tutorias pendientes.
	public function findNuevasTutoriasInicioProfesor($profesor)
	{
		return $this->getEntityManager()->createQuery(
		'SELECT s FROM IntranetBundle:Seguimiento s WHERE s.profesor=:profesor AND s.asignatura IS NULL 
		 AND s.tipoUser=:tipoUser ORDER BY s.fechaActualizada DESC')
		->setParameters(array(
		'profesor' => $profesor,
		'tipoUser'=>0))
		->getResult();
	}

	public function findCargaSeguimientosTutoriasAlumno($alumno,$idResponsable, $id, $tipo )
	{

		if($tipo == "activas"){
			return $this->getEntityManager()->createQuery(
				'SELECT s FROM IntranetBundle:Seguimiento s WHERE s.alumno=:alumno AND s.tipo=:tipo AND s.id<:id  AND s.fechaTerminada Is NULL AND s.asignatura IS NULL
				AND s.id NOT IN(select a.idAviso FROM IntranetBundle:Avisos a where a.idUsuario=:alumno AND a.idResponsable=:idResponsable AND a.tipoUsuario=:tipoUsuario AND a.tipoAviso=:tipoAviso) ORDER BY s.fecha DESC')
			->setParameters(array(
				'alumno' => $alumno,
				'id' => $id,
				'idResponsable' => $idResponsable,
				'tipoUsuario' => "Alumno",
				'tipoAviso' => "Tutoria",
				'tipo'=>1))
			->setMaxResults(3)
			->getResult();
		}
		else{
			return $this->getEntityManager()->createQuery(
				'SELECT s FROM IntranetBundle:Seguimiento s WHERE s.alumno=:alumno AND s.tipo=:tipo AND s.id<:id  AND s.fechaTerminada Is NOT NULL AND s.asignatura IS NULL
				AND s.id NOT IN(select a.idAviso FROM IntranetBundle:Avisos a where a.idUsuario=:alumno AND a.idResponsable=:idResponsable AND a.tipoUsuario=:tipoUsuario AND a.tipoAviso=:tipoAviso) ORDER BY s.fecha DESC')
			->setParameters(array(
				'alumno' => $alumno,
				'id' => $id,
				'idResponsable' => $idResponsable,
				'tipoUsuario' => "Alumno",
				'tipoAviso' => "Tutoria",
				'tipo'=>1))
			->setMaxResults(3)
			->getResult();
		}
	}

	public function findCargaSeguimientosNuevosTutoriasAlumno($fecha, $alumno, $idResponsable)
	{
		return $this->getEntityManager()->createQuery(
			'SELECT s FROM IntranetBundle:Seguimiento s WHERE s.alumno=:alumno  AND s.tipo=:tipo AND s.fechaTerminada Is NULL AND s.asignatura IS NULL AND 
			s.id IN(select a.idAviso FROM IntranetBundle:Avisos a where a.idUsuario=:alumno AND a.idResponsable=:idResponsable AND a.tipoUsuario=:tipoUsuario AND a.tipoAviso=:tipoAviso) AND s.fechaActualizada<:fecha  ORDER BY s.fechaActualizada DESC')
		->setParameters(array(
			'alumno' => $alumno,
			'idResponsable' => $idResponsable,
			'tipoUsuario' => "Alumno",
			'tipoAviso' => "Tutoria",
			'fecha' => $fecha,
			'tipo'=>1))
		->setMaxResults(5)
		->getResult();
	}

	public function findCargaSeguimientosInicialTutoriasAlumno($alumno, $idResponsable, $contador)
	{
		return $this->getEntityManager()->createQuery(
			'SELECT s FROM IntranetBundle:Seguimiento s WHERE s.tipo=:tipo AND s.alumno=:alumno AND s.fechaTerminada Is NULL AND s.asignatura IS NULL
			AND s.id NOT IN(select a.idAviso FROM IntranetBundle:Avisos a where a.idUsuario=:alumno AND a.idResponsable=:idResponsable AND a.tipoUsuario=:tipoUsuario AND a.tipoAviso=:tipoAviso) ORDER BY s.fecha DESC')
		->setParameters(array(
			'alumno' => $alumno,
			'tipoUsuario' => "Alumno",
			'idResponsable' => $idResponsable,
			'tipoAviso' => "Tutoria",
			'tipo'=>1))
		->setMaxResults($contador)
		->getResult();
	}

	public function findNuevosSeguimientosTutoriasAlumno($ultimo_acceso, $alumno)
	{
		return $this->getEntityManager()->createQuery(
		'SELECT s FROM IntranetBundle:Seguimiento s WHERE s.alumno=:alumno  AND s.asignatura IS NULL AND s.tipoUser=:tipoUser 
		AND s.fechaActualizada>:ultimo_acceso ORDER BY s.fechaActualizada DESC')
		->setParameters(array(
		'alumno' => $alumno,
		'ultimo_acceso' => $ultimo_acceso,
		'tipoUser'=>1))
		->getResult();
	}


	#Seguimientos nuevos para el alumno que nunca ha visto la lista de seguimientos pendientes.
	public function findNuevosSeguimientosInicioTutoriasAlumno($alumno)
	{
		return $this->getEntityManager()->createQuery(
		'SELECT s FROM IntranetBundle:Seguimiento s WHERE s.alumno=:alumno AND s.tipoUser=:tipoUser  AND s.asignatura IS NULL ORDER BY s.fechaActualizada DESC')
		->setParameters(array(
		'alumno' => $alumno,
		'tipoUser'=>1))
		->getResult();
	}

	public function findRespuestasTutorias($id)
	{
		return $this->getEntityManager()->createQuery(
		'SELECT s FROM IntranetBundle:Seguimiento s WHERE s.seguimiento=:id  AND s.asignatura IS NULL ORDER BY s.fecha ASC')
		->setParameters(array(
		'id' => $id))
		->getResult();
	}


	public function findUltimoAvisoTutoria($profesor, $tipo)
	{
		return $this->getEntityManager()->createQuery(
			'SELECT s FROM IntranetBundle:Seguimiento s WHERE s.profesor=:profesor AND s.tipo=:tipo AND s.tipoUser=:tipoUser AND s.asignatura IS NULL  ORDER BY s.fecha DESC')
		->setParameters(array(
			'profesor' => $profesor,
			'tipoUser'=>1,
			'tipo'=>$tipo))
		->setMaxResults(1)
		->getSingleResult();
	}




}
