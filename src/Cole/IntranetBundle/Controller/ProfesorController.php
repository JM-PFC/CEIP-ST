<?php

namespace Cole\IntranetBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;

use Cole\BackendBundle\Entity\Profesor;
use Cole\ColeBundle\Entity\Noticias;
use Cole\IntranetBundle\Entity\Avisos;


use Cole\BackendBundle\Form\AlumnoIntranetType;
use Symfony\Component\HttpFoundation\Response;


class ProfesorController extends Controller
{

    public function indexAction()
    {
    	$em = $this->getDoctrine()->getManager();

        $entity = $this->get('security.context')->getToken()->getUser();

		return $this->render('IntranetBundle:Profesor:index.html.twig', array(
            'entity' => $entity,
            'id'=>$entity->getId()));
    }


    public function datosAlumnosGrupoAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $profesor = $this->get('security.context')->getToken()->getUser();
        $grupo=$em->getRepository('BackendBundle:Grupo')->findOneById($id);

        $tutor=$grupo->getProfesor();
        $entities=$em->getRepository('BackendBundle:Alumno')->findByGrupoOrdenado($grupo);

        $no_opcionales=$em->getRepository('BackendBundle:Imparte')->findNoOpcionalesProfesorGrupo($profesor, $grupo);   
        $opcional=$em->getRepository('BackendBundle:Imparte')->findOpcionalProfesorGrupo($profesor, $grupo);
        if($no_opcionales && $opcional){
            $tipos="ambos";
        }
        else if($no_opcionales){
            $tipos="no_opcionales";
        }
        else{
            $tipos="opcional";
        } 

        if($opcional){
            $alumnos_optativa=$em->getRepository('BackendBundle:Alumno')->findAlumnosOptativaGrupo($opcional->getAsignatura(), $grupo);
        }
        else{
            $alumnos_optativa=null;
        }

        $asignaciones_profesores=$em->getRepository('BackendBundle:Imparte')->findAsignacionesProfesores($grupo);
        $profesores_grupo=$em->getRepository('BackendBundle:Imparte')->findProfesoresGrupoSinRepetir($grupo);
        //Se comprueba si el tutor imparte alguna asignatura en el grupo o sólo tutorias(para lista de profesores/asignaturas).
        if($tutor){
            $asignaciones_tutor=$em->getRepository('BackendBundle:Imparte')->findTutorGrupoSinRepetir($tutor,$grupo);
            if($asignaciones_tutor){
                $asignaturas_tutor=$asignaciones_tutor;
            }
            else{
                $asignaturas_tutor=null;
            }
        }
        else{
            $asignaturas_tutor=null;
        }

        return $this->render('IntranetBundle:Profesor:datos_alumnos_grupo.html.twig', array(
            'profesor' => $profesor,
            'entities'=>$entities,
            'grupo'=> $grupo,
            'tipos'=> $tipos,
            'opcional'=>$opcional,
            'alumnos_optativa' => $alumnos_optativa,
            'tutor'=>$tutor,
            'profesores_grupo'=>$profesores_grupo,
            'asignaturas_tutor' => $asignaturas_tutor,
            'asignaciones_profesores'=>$asignaciones_profesores));
    }

    public function InfoAlumnoAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $profesor = $this->get('security.context')->getToken()->getUser();
        $entity=$em->getRepository('BackendBundle:Alumno')->findOneById($id);
        //Se obtiene la edad del alumno mediante la fecha de nacimiento.
        $edad = date_diff($entity->getFechaNacimiento(), date_create('now'))->y;

        return $this->render('IntranetBundle:Profesor:info_alumno.html.twig', array(
            'profesor' => $profesor,
            'entity'=>$entity,
            'edad'=>$edad));
    }

    public function InfoProfesorAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity=$em->getRepository('BackendBundle:Profesor')->findOneById($id);
        $tutor_grupo= $em->getRepository('BackendBundle:Grupo')->findOneByProfesor($entity);

        $dia = date('N', strtotime(date("Y-m-d H:i:s")));
        $hora=date('H:i');

        $centro =$em->getRepository('BackendBundle:Centro')->findCentro();

        $hora_inicio=$centro->getInicioHorario();

        $hora_fin=$centro->getFinHorario();
        if($hora_inicio && $hora_fin){
            if($hora_inicio<$hora && $hora<$hora_fin){
                $horario = $em->getRepository('BackendBundle:Horario')->findByHora($hora);
                $imparte = $em->getRepository('BackendBundle:Imparte')->findExistence($entity, $dia, $horario);

                if($imparte){
                    $grupo=$imparte->getGrupo()->getCurso()->getCurso()." ".$imparte->getGrupo()->getLetra();
                    if($imparte->getAula()){
                        $aula=$imparte->getAula()->getNombre();
                        $clase=$grupo." (".$aula.")";
                    }
                    else{
                        $clase=$grupo;
                    }
                }
                else{
                  $clase=null;  
                }
            }
            else{
              $clase=null;  
            }
        }
        else{
            $clase="fuera de horario";
        }

        $tutor_grupo= $em->getRepository('BackendBundle:Grupo')->findOneByProfesor($entity);
        $dia_semana=date("N", strtotime(date("Y-m-d")));
        return $this->render('IntranetBundle:Profesor:info_profesor.html.twig', array(
            'grupo' => $tutor_grupo,
            'clase' => $clase,
            'entity'=>$entity,
            'dia'=>$dia_semana));
    }


    public function cursosAction()
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $this->get('security.context')->getToken()->getUser();

        $tutor_grupo= $em->getRepository('BackendBundle:Grupo')->findOneByProfesor($entity);
        $inicio =$em->getRepository('BackendBundle:Centro')->findInicioCurso();
        $fin =$em->getRepository('BackendBundle:Centro')->findFinCurso();

        if($entity->getNivel()=="Primaria"){
            $cursos = $em->getRepository('BackendBundle:Imparte')->findAsignacionesProfesor($entity);
        }
        else{
            //Para los profesores de infantil se asigna sólo el grupo que es tutor.
            $cursos=$tutor_grupo;
        }
  
        return $this->render('IntranetBundle:Profesor:cursos.html.twig', array(
            'entity' => $entity, 
            'tutor_grupo' => $tutor_grupo,
            'inicio' => $inicio,
            'fin' =>$fin,
            'cursos'=>$cursos));
    }

    public function HorariosProfesorAction($num)
    {

        $em = $this->getDoctrine()->getManager();

        $profesor = $this->get('security.context')->getToken()->getUser();

        $entity = $em->getRepository('BackendBundle:Horario')->findAll();
        
        $imparte = $em->getRepository('BackendBundle:Imparte')->findByProfesor($profesor);

        $array=[];
        foreach($imparte as $registro){
            if($registro->getAula()){
                $aula=$registro->getAula()->getNombre();
            }
            else{
                $aula="";
            }

            if($registro->getDiaSemanal()){
                $dia=$registro->getDiaSemanal();
            }
            else{
                $dia=""; 
            }

            if($registro->getHorario()){
                $horario=$registro->getHorario()->getId();
            }
            else{
                $horario="";
            }
           
            $array[$horario."-".$dia."-".$registro->getAsignatura()->getAsignatura()->getNombre()."-".$registro->getAsignatura()->getAsignatura()->getAbreviatura()."-".$aula."-".$registro->getGrupo()->getCurso()->getCurso()." ".$registro->getGrupo()->getLetra()]=$registro->getAsignatura()->getId();
        }

        return $this->render('IntranetBundle:Profesor:datos_horario.html.twig', array(
            'entities' => $imparte,
            'imparte' => $array,
            'entity' => $entity,
            'num' => $num
        ));
    }

    //Función de prueba para ver los resultados del horario en html.
    public function HorarioAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $alumno= $em->getRepository('BackendBundle:Alumno')->findOneById($id);

        $grupo= $alumno->getGrupo();
        if (!$grupo) {
            throw $this->createNotFoundException('Unable to find Grupo.');
        }
        $inicio =$em->getRepository('BackendBundle:Centro')->findInicioCurso();
        $fin =$em->getRepository('BackendBundle:Centro')->findFinCurso();

        $horarios = $em->getRepository('BackendBundle:Horario')->findAll();

        $entities = $em->getRepository('BackendBundle:Imparte')->findByGrupoConHorario($grupo);

        $imparte= $em->getRepository('BackendBundle:Imparte')->findAsignacionesNoOpcionales($grupo);

        $array=[];
        foreach($imparte as $registro){
            if($registro->getAula()){
                $aula=$registro->getAula()->getNombre();
            }
            else{
                $aula="";
            }

            if($registro->getDiaSemanal()){
                $dia=$registro->getDiaSemanal();
            }
            else{
                $dia=""; 
            }

            if($registro->getHorario()){
                $horario=$registro->getHorario()->getId();
            }
            else{
                $horario="";
            }
           
            $array[$horario."-".$dia."-".$registro->getAsignatura()->getAsignatura()->getNombre()."-".$registro->getAsignatura()->getAsignatura()->getAbreviatura()."-".$aula."-".$registro->getGrupo()->getId()]=$registro->getProfesor()->getNombre()." ".$registro->getProfesor()->getApellido1()." ".$registro->getProfesor()->getApellido2();
        }
        //Se obtiene las asignaciones de la asignatura optativa del alumno.
        if($alumno->getOptativa()){
            $imparte= $em->getRepository('BackendBundle:Imparte')->findAsignacionesOptativaAlumno($grupo,$alumno->getOptativa());

            $array_op=[];
            foreach($imparte as $registro){
                if($registro->getAula()){
                    $aula=$registro->getAula()->getNombre();
                }
                else{
                    $aula="";
                }

                if($registro->getDiaSemanal()){
                    $dia=$registro->getDiaSemanal();
                }
                else{
                    $dia=""; 
                }

                if($registro->getHorario()){
                    $horario=$registro->getHorario()->getId();
                }
                else{
                    $horario="";
                }
                $asig_op=$em->getRepository('BackendBundle:AsignaturasCursos')->findAsignaturasEspecificasOpcionalesCurso($grupo->getCurso());
                $num_asig_op=count($asig_op);
                $array_op[$horario."-".$dia."-".$registro->getAsignatura()->getAsignatura()->getNombre()."-".$registro->getAsignatura()->getAsignatura()->getAbreviatura()."-".$aula."-".$num_asig_op]=$registro->getProfesor()->getNombre()." ".$registro->getProfesor()->getApellido1()." ".$registro->getProfesor()->getApellido2();
            }
        }
        else{
            $array_op=null;
        }
        
        if($grupo->getAula()==null){
            $aula=null;
        }
        else{
            $aula=$grupo->getAula();
        }

        return $this->render('IntranetBundle:Alumno:horario_de_clase.html.twig', array(
            'alumno' => $alumno,
            'entities' => $entities,
            'inicio' => $inicio,
            'fin' => $fin,
            'horarios' => $horarios,
            'aula' => $aula,
            'grupo' => $grupo->getCurso()->getCurso()." ".$grupo->getCurso()->getNivel()." - Grupo ".$grupo->getLetra(),
            'imparte' => $array,
            'imparte_op' => $array_op,
            'profesor' => $grupo->getProfesor(),
        ));
        
    }

    public function seguimientosAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $this->get('security.context')->getToken()->getUser();
        $seguimientosNuevos=$em->getRepository('IntranetBundle:Seguimiento')->findNuevosSeguimientos($entity);
        if(count($seguimientosNuevos)<5){
            $seguimientos= $em->getRepository('IntranetBundle:Seguimiento')->findAntiguosSeguimientosContador($entity, 5-count($seguimientosNuevos));
        }
        else{
            $seguimientos=null;
        }

        return $this->render('IntranetBundle:Profesor:seguimientos.html.twig', array(
            'entity' => $entity, 
            'seguimientosNuevos' => $seguimientosNuevos,
            'seguimientos'=> $seguimientos,
            ));
    }

    public function seguimientoAction(Request $request, $num)
    {
        $em = $this->getDoctrine()->getManager();
        $entity = $this->get('security.context')->getToken()->getUser();
        $seguimiento=$em->getRepository('IntranetBundle:Seguimiento')->findOneById($num);
        $respuestas=$em->getRepository('IntranetBundle:Seguimiento')->findRespuestas($num);

        return $this->render('IntranetBundle:Profesor:seguimiento.html.twig', array(
            'entity' => $entity, 
            'seguimiento'=> $seguimiento,
            'respuestas' => $respuestas,
            ));
    }

    public function CargarNuevosSeguimientosAction(Request $request, $fecha)
    {
        $em = $this->getDoctrine()->getManager();
        $entity = $this->get('security.context')->getToken()->getUser();

        $seguimientosNuevos=$em->getRepository('IntranetBundle:Seguimiento')->findCargaSeguimientosNuevosProfesor($fecha, $entity);

        if(count($seguimientosNuevos)<5){
            $seguimientos= $em->getRepository('IntranetBundle:Seguimiento')->findCargaSeguimientosInicialProfesor($entity, 5-count($seguimientosNuevos));
        }
        else{
            $seguimientos=null;
        }
        return new JsonResponse(array(
            'seguimientos' => $seguimientos,
            'seguimientosNuevos' => $seguimientosNuevos,
            'html' => $this->renderView('IntranetBundle:Profesor:lista_seguimiento.html.twig', array(
            'seguimientos' => $seguimientos, 'seguimientosNuevos' => $seguimientosNuevos, 'entity'=>$entity)),
            'success' => true
            ), 200);
    }

    public function CargarSeguimientosAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();
        $entity = $this->get('security.context')->getToken()->getUser();
        
        $seguimientos= $em->getRepository('IntranetBundle:Seguimiento')->findCargaSeguimientosProfesor($entity, $id);

        return new JsonResponse(array(
            'seguimientos' => $seguimientos,
            'seguimientosNuevos' => null,
            'html' => $this->renderView('IntranetBundle:Profesor:lista_seguimiento.html.twig', array(
            'seguimientos' => $seguimientos, 'seguimientosNuevos' => null, 'entity'=>$entity)),
            'success' => true
            ), 200);
    }
   
    public function comprobarSeguimientosNuevosAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity= $em->getRepository('BackendBundle:Profesor')->findOneById($id);
        $num=0;

            if($entity->getAccesoSeguimientos()){
                $seguimientos =$em->getRepository('IntranetBundle:Seguimiento')->findNuevosSeguimientosProfesor($entity->getAccesoSeguimientos(),$entity);
                
            }
            else{
                $seguimientos =$em->getRepository('IntranetBundle:Seguimiento')->findNuevosSeguimientosInicioProfesor($entity);
            }
        
            //Si hay seguimientos nuevos se añade los id a la tabla Avisos.
            if($seguimientos){
                foreach($seguimientos as $seguimiento){
                    if($seguimiento->getSeguimiento() == null){
                        $existencia=$em->getRepository('IntranetBundle:Avisos')->findExistenciaAviso($entity->getId(),null, "Profesor",$seguimiento->getId(), "Seguimiento" );
                        if(!$existencia){
                            $aviso = new Avisos();
                            $aviso->setIdUsuario($entity->getId());
                            $aviso->setIdResponsable(null);
                            $aviso->setTipoUsuario("Profesor");
                            $aviso->setIdAviso($seguimiento->getId());
                            $aviso->setTipoAviso("Seguimiento");
                            $em->persist($aviso);
                        }
                    }
                    else{
                        $existencia=$em->getRepository('IntranetBundle:Avisos')->findExistenciaAviso($entity->getId(),null, "Profesor",$seguimiento->getSeguimiento(), "Seguimiento" );
                        if(!$existencia){
                            $aviso = new Avisos();
                            $aviso->setIdUsuario($entity->getId());
                            $aviso->setIdResponsable(null);
                            $aviso->setTipoUsuario("Profesor");
                            $aviso->setIdAviso($seguimiento->getSeguimiento());
                            $aviso->setTipoAviso("Seguimiento");
                            $em->persist($aviso);
                        }
                    } 
                }

                //Se actualiza la fecha del último acceso a noticias.
                $entity->setAccesoSeguimientos(new \DateTime("now"));
                $em->persist($entity);
                $em->flush();
            }
            //Se obtiene el número de seguimientos nuevos.
            $avisos =$em->getRepository('IntranetBundle:Avisos')->findAvisos($entity,NULL, "Profesor", "Seguimiento");
            $num=count($avisos);

        return new JsonResponse(array(
            'num'=> $num,
            'id' => $id,
            'success' => true), 200);
    }

    public function  AsignaturasGrupoProfesorAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $this->get('security.context')->getToken()->getUser();
        $grupo= $em->getRepository('BackendBundle:Grupo')->findOneById($id);
        
        #Se devuelve sólo el id, abreviatura y nombre para mostrarlo por ajax.
        $asignaturas= $em->getRepository('BackendBundle:Imparte')->findAsignaturasProfesorGrupo($entity, $grupo);

        return new JsonResponse(array(
            'asignaturas' => $asignaturas
            ), 200);
    }


    public function AlumnosGrupoAsignaturaAction($id, $asig)
    {
        $em = $this->getDoctrine()->getManager();

        $profesor = $this->get('security.context')->getToken()->getUser();
        $grupo=$em->getRepository('BackendBundle:Grupo')->findOneById($id);
        $asignatura=$em->getRepository('BackendBundle:AsignaturasCursos')->findOneById($asig);
        //Se comprueba si la asignatura es opcional para obtener sólo los alumnos que imparten la asignatura.
        if($asignatura->getAsignatura()->getOpcional()==0){
            $entities=$em->getRepository('BackendBundle:Alumno')->findBy( array("grupo"=>$grupo),array("numAlum"=>"ASC"));
        }
        else{
            $entities=$em->getRepository('BackendBundle:Alumno')->findBy(array("optativa"=>$asignatura),array("numAlum"=>"ASC"));
        }

        return $this->render('IntranetBundle:Profesor:alumnos_grupo_asignatura.html.twig', array(
            'entities'=>$entities));
    }

    public function AlumnosGrupoAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $profesor = $this->get('security.context')->getToken()->getUser();
        $grupo=$em->getRepository('BackendBundle:Grupo')->findOneById($id);
    
        $entities=$em->getRepository('BackendBundle:Alumno')->findByGrupo($grupo);

        return $this->render('IntranetBundle:Profesor:alumnos_grupo_asignatura.html.twig', array(
            'entities'=>$entities));
    }



    public function noticiasAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $this->get('security.context')->getToken()->getUser();

        $noticias= $em->getRepository('ColeBundle:Noticias')->findBy(array('categoria'=>'profesor'), array('fecha'=>'DESC'));
        $noticiasNuevas=$em->getRepository('ColeBundle:Noticias')->findNoticiasNuevasProfesor($entity,"profesor" );

        $paginator  = $this->get('knp_paginator');
        $pagination = $paginator->paginate(
            $noticias, /* query NOT result */
            $request->query->getInt('page', 1)/*page number*/,
            10/*limit per page*/
        );

        return $this->render('IntranetBundle:Profesor:noticias.html.twig', array(
            'entity' => $entity, 
            'noticias'=> $noticias,
            'noticias_nuevas' => $noticiasNuevas,
            'pagination'=> $pagination));
    }

    public function noticiaAction($num)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $this->get('security.context')->getToken()->getUser();
        $noticia= $em->getRepository('ColeBundle:Noticias')->findOneById($num);
     
        $imagenes = array();
        if($noticia->getGaleria()!=null) {
            $photoDir = $this->container->getParameter('kernel.root_dir').'/../web/uploads/noticias/galeria/'.$noticia->getGaleria().'/';
            
            foreach (glob($photoDir."*.*") as $nombre_dir) {
                $nombre_dir = explode("/", $nombre_dir);
                $nombre_dir =end($nombre_dir);
                $imagenes[]=$nombre_dir;
            }
        }

        //Se elimina el id de la noticia mostrada en la tabla de avisos.
        $aviso= $em->getRepository('IntranetBundle:Avisos')->findnoticiaMostrada($entity->getId(), null, $num, "Profesor");
        if($aviso){
            $em->remove($aviso);
        }
        $em->flush();

        return $this->render('IntranetBundle:Profesor:noticia.html.twig', array(
            'entity' => $entity, 
            'noticia'=> $noticia,
            'imagenes' => $imagenes));
    }



    public function comprobarNoticiasNuevasAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity= $em->getRepository('BackendBundle:Profesor')->findOneById($id);
        $num=0;

        if($entity->getAccesoNoticias()){
            $noticias =$em->getRepository('ColeBundle:Noticias')->findNuevasNoticias($entity->getAccesoNoticias(),"profesor");
        }
        else{
            $noticias= $em->getRepository('ColeBundle:Noticias')->findBy(array('categoria'=>'profesor'), array('fecha'=>'DESC'));
        }

        //Si hay noticias nuevas se añade los id a la tabla Avisos.
        if($noticias){
            foreach($noticias as $noticia){
                $existencia=$em->getRepository('IntranetBundle:Avisos')->findExistenciaAviso($entity->getId(),null, "Profesor",$noticia->getId(), "Noticia" );
                if(!$existencia){
                    $aviso = new Avisos();
                    $aviso->setIdUsuario($entity->getId());
                    $aviso->setIdResponsable(null);
                    $aviso->setTipoUsuario("Profesor");
                    $aviso->setIdAviso($noticia->getId());
                    $aviso->setTipoAviso("Noticia");
                    $em->persist($aviso);
                }
            }


            //Se actualiza la fecha del último acceso a noticias.
            $entity->setAccesoNoticias(new \DateTime("now"));
            $em->persist($entity);
            $em->flush();
        }
        //Se obtiene el número de noticias nuevas.
        $avisos =$em->getRepository('IntranetBundle:Avisos')->findAvisos($entity,null, "Profesor", "Noticia");
        $num=count($avisos);

        return new JsonResponse(array(
            'num'=> $num,
            'id' => $id,
            'success' => true), 200);
    }



}