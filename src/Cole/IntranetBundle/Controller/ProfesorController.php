<?php

namespace Cole\IntranetBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;

use Cole\BackendBundle\Entity\Reserva;
use Cole\BackendBundle\Entity\Profesor;
use Cole\ColeBundle\Entity\Noticias;
use Cole\IntranetBundle\Entity\Avisos;
use Cole\BackendBundle\Form\ReservaType;
use Cole\IntranetBundle\Entity\Seguimiento;
use Cole\IntranetBundle\Form\SeguimientoType;
use Cole\IntranetBundle\Entity\Tarea;
use Cole\IntranetBundle\Form\TareaType;
use Cole\IntranetBundle\Entity\Ausencia;
use Cole\IntranetBundle\Form\AusenciaType;
use Cole\IntranetBundle\Entity\Comunicacion;
use Cole\IntranetBundle\Form\ComunicacionType;

use Cole\BackendBundle\Form\AlumnoIntranetType;
use Symfony\Component\HttpFoundation\Response;


class ProfesorController extends Controller
{


    protected function comprobarAlumno($id) 
    {
        $em = $this->getDoctrine()->getManager();
        $entity= $em->getRepository('BackendBundle:Alumno')->findOneById($id);
        if(!$entity){
            throw $this->createNotFoundException('Unable to find Role entity.');
        }
        $user=$this->get('security.context')->getToken()->getUser();

        if($entity->getGrupo()){
            $profesores_grupo=$em->getRepository('BackendBundle:Imparte')->findProfesoresGrupoSinRepetir($entity->getGrupo());
            $cont=0;
            foreach($profesores_grupo as $profesor){
                if($profesor->getProfesor()==$user){
                    $cont=1;
                }
            }
            if($cont==0){
                throw $this->createNotFoundException('No profesor.');
            }
        }
        else{
            throw $this->createNotFoundException('Unable to find Role entity.');
        }
    }

    protected function comprobarSeguimiento($id) 
    {
        $em = $this->getDoctrine()->getManager();
        $entity= $em->getRepository('IntranetBundle:Seguimiento')->findOneById($id);
        if(!$entity){
            throw $this->createNotFoundException('Unable to find Role entity.');
        }

        $user=$this->get('security.context')->getToken()->getUser();

        if($entity->getProfesor()!=$user){
            throw $this->createNotFoundException('Unable to find Role entity.');
        }
    }


    public function indexAction()
    {
    	$em = $this->getDoctrine()->getManager();

        $entity = $this->get('security.context')->getToken()->getUser();
        $tutor_grupo= $em->getRepository('BackendBundle:Grupo')->findOneByProfesor($entity);

		return $this->render('IntranetBundle:Profesor:index.html.twig', array(
            'entity' => $entity,
            'tutor_grupo' =>$tutor_grupo,
            'id'=>$entity->getId()));
    }

    ///////////////////////////////////////////
    //            Cursos Impartidos          //
    ///////////////////////////////////////////

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

    public function InfoTutoriaAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $this->get('security.context')->getToken()->getUser();

        $tutoria=$em->getRepository('IntranetBundle:Tutorias')->findOneById($id);

        return $this->render('IntranetBundle:Profesor:info_tutoria.html.twig', array(
            'tutoria' => $tutoria,
            'entity'=>$entity));
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
    ///////////////////////////////////////////
    //              Seguimiento              //
    ///////////////////////////////////////////

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
        $this->comprobarSeguimiento($seguimiento);

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
            'seguimientos' => $seguimientos, 'seguimientosNuevos' => $seguimientosNuevos, 'entity'=>$entity, 'tipo'=>'seguimientos')),
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
            'seguimientos' => $seguimientos, 'seguimientosNuevos' => null, 'entity'=>$entity, 'tipo'=>'seguimientos')),
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
            //Se actualiza la fecha del último acceso a seguimientos.
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
            'entities'=>$entities,
            'asignatura' => $asignatura));
    }

    public function AlumnosGrupoAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $profesor = $this->get('security.context')->getToken()->getUser();
        $grupo=$em->getRepository('BackendBundle:Grupo')->findOneById($id);
    
        $entities=$em->getRepository('BackendBundle:Alumno')->findByGrupoOrdenado($grupo);

        return $this->render('IntranetBundle:Profesor:alumnos_grupo_asignatura.html.twig', array(
            'entities'=>$entities));
    }


    ///////////////////////////////////////////
    //               comunicación             //
    ///////////////////////////////////////////


    private function createCreateComunicacionForm(Comunicacion $entity)
    {
        $form = $this->createForm(new ComunicacionType(), $entity, array(
            'action' => $this->generateUrl('comunicacion_create'),
            'method' => 'POST',
        ));

        $titulo=$this->get("translator")->trans("Enviar");
        $form->add('submit', 'submit', array('label' => $titulo, 'attr' => array('class' => 'btn btn-success')));


        return $form;
    }



    public function comunicacionAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $this->get('security.context')->getToken()->getUser();
        $comunicacion = new Comunicacion();
        $form = $this->createCreateComunicacionForm($comunicacion);
        
        $recibidos = $em->getRepository('IntranetBundle:Comunicacion')->findRecibidosProfesor($entity);
        $entrada = $em->getRepository('IntranetBundle:Comunicacion')->findEntradaProfesor($entity);
        $salida = $em->getRepository('IntranetBundle:Comunicacion')->findSalidaProfesor($entity);
        $papelera = $em->getRepository('IntranetBundle:Comunicacion')->findPapeleraProfesor($entity);
        $numRecibidos=count($recibidos);
        $numEliminados=count($papelera);

        $array=null;
        foreach($recibidos as $recibido){
            $array[]=$recibido->getId();
        }

        return $this->render('IntranetBundle:Profesor:mensajes.html.twig', array(
            'entity' => $entity, 
            'form'   => $form->createView(),
            'entrada'=>$entrada,
            'salida'=>$salida,
            'papelera'=>$papelera,
            'recibidos'=>$array,
            'numRecibidos'=>$numRecibidos,
            'numEliminados'=>$numEliminados,

        ));
    }



    public function comprobarMensajesNuevosAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity= $em->getRepository('BackendBundle:Profesor')->findOneById($id);
        $mensajes =$em->getRepository('IntranetBundle:Comunicacion')->findRecibidosProfesor($entity);
    
        $num=count($mensajes);

        return new JsonResponse(array(
            'num'=> $num,
            'id' => $id,
            'success' => true), 200);
    }



    ///////////////////////////////////////////
    //               asistencia              //
    ///////////////////////////////////////////
    
    private function createCreateAusenciaForm(Ausencia $entity)
    {
        $form = $this->createForm(new AusenciaType(), $entity, array(
            'action' => $this->generateUrl('ausencia_create'),
            'method' => 'POST',
        ));
        $titulo=$this->get("translator")->trans("Guardar");
        $form->add('submit', 'submit', array('label' => $titulo, 'attr' => array('class' => 'btn btn-success')));

        return $form;
    }


    public function ausenciaAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $this->get('security.context')->getToken()->getUser();

        if($entity->getNivel()=="Primaria"){
            $cursos = $em->getRepository('BackendBundle:Imparte')->findAsignacionesProfesor($entity);
        }
        else{
            //Para los profesores de infantil se asigna sólo el grupo que es tutor.
            $cursos=$tutor_grupo;
        }

        // Se obtiene la fecha inicial y final del curso para usar luego el año correspondiente. 
        $ini_curso=$em->getRepository('BackendBundle:Centro')->findInicioCurso();
        $array_ini=explode("-",$ini_curso["inicioCurso"]->format('Y-m-d')); //Conversión de array a String
        $fin_curso=$em->getRepository('BackendBundle:Centro')->findFinCurso();
        $array_fin=explode("-",$fin_curso["finCurso"]->format('Y-m-d'));

        // Se obtiene las fechas de inicio de las vacaciones.
        $ini_nav=$em->getRepository('BackendBundle:Festivos')->findFestivosPorDescripcion("Inicio Vacaciones de Navidad");
        $ini_ss=$em->getRepository('BackendBundle:Festivos')->findFestivosPorDescripcion("Inicio Vacaciones de Semana Santa");
        
        $Fecha_ini_nav = date('Y-m-d', strtotime($array_ini[0]."-".$ini_nav->getNumMes()."-".$ini_nav->getDia()));
        $Fecha_ini_ss = date('Y-m-d', strtotime($array_fin[0]."-".$ini_ss->getNumMes()."-".$ini_ss->getDia()));

        $hoy=date("Y-m-d");

        if ($hoy <= $Fecha_ini_nav){
            $trimestre=1;
        }
        else if($hoy >= $Fecha_ini_ss){
            $trimestre=3;
        }
        else{
            $trimestre=2;
        }
        $horarios=$em->getRepository('BackendBundle:Horario')->findClases();
        $dia_semana = date('N', strtotime(date("Y-m-d")));
        $hora=date('H:i');

        $clase=0;
        foreach ($horarios as $horario) {

            $inicio=$horario->getInicio()->format('H:i');
            $fin=$horario->getFin()->format('H:i');

            if($clase==0 && $inicio<=$hora  && $hora<=$fin){
                $clase=$horario->getId();
            }
        }
        
        $centro =$em->getRepository('BackendBundle:Centro')->findCentro();

        $hora_inicio=$centro->getInicioHorario();
        $hora_fin=$centro->getFinHorario();
        $alumnos=null;
        $asignatura=null;
        $faltas=null;
        if($hora_inicio<$hora && $hora<$hora_fin){
            $horario = $em->getRepository('BackendBundle:Horario')->findOneById($clase);
            $imparte = $em->getRepository('BackendBundle:Imparte')->findExistence($entity, $dia_semana, $horario);

            if($imparte){
                //Se obtiene el grupo y asignatura actual.
                $grupo=$imparte->getGrupo();
                $asignatura=$imparte->getAsignatura();

                //Se obtiene la lista de alumnos del grupo.
                $alumnos = $em->getRepository('BackendBundle:Alumno')->findByGrupo($grupo);
                
                //Se obtiene las faltas asignadas a la clase actual.
                $faltas = $em->getRepository('IntranetBundle:Ausencia')->findFaltasClase($grupo, $asignatura, $hoy, $horario);
            }
        }

        $array=null;
        if($faltas){
            foreach ($faltas as $falta) {
                $array[$falta->getAlumno()->getId()."-".$falta->getId()]=$falta->getTipo();     
            }
        }

        $ausencia = new Ausencia();
        $form   = $this->createCreateAusenciaForm($ausencia);

        if($horario){
            $id_horario=$horario->getId();
        }
        else{
            $id_horario=null;
        }

        if($asignatura){
            $id_asignatura=$asignatura->getId();
        }
        else{
            $id_asignatura=null;
        }


        $tutor_grupo= $em->getRepository('BackendBundle:Grupo')->findOneByProfesor($entity);
        if($tutor_grupo){
            $id_grupo=$tutor_grupo->getId();

            //Se obtiene las faltas justificadas pendientes de confirmación.
            $pendientes=$em->getRepository('IntranetBundle:Ausencia')->findFaltasPendientes($tutor_grupo); 
        }
        else{
            $id_grupo=null;

            $pendientes=null;
        }

        return $this->render('IntranetBundle:Profesor:asistencia.html.twig', array(
            'entity' => $entity,
            'tutor_grupo' => $id_grupo,
            'cursos'=>$cursos,
            'alumnos'=>$alumnos,
            'form'=> $form->createView(),
            'asignatura'=>$id_asignatura,
            'horario'=>$id_horario,
            'fecha'=>date('d-m-Y'),
            'faltas'=>$array,
            'pendientes' => $pendientes
        ));
    }


    protected function ComprobarDiaNoLectivo($dia,$mes,$anyo) 
    {
        // Se comprueba que no es festivo.
        $em = $this->getDoctrine()->getManager();
        $entities = $em->getRepository('BackendBundle:Festivos')->findFestivo($dia, $mes);
        if(!empty($entities)){
            return true;
        }

        // Se comprueba que siendo lunes y el domingo no sea festivo.
        $fecha=$anyo."-".$mes."-".$dia;
        $dw = date('w', strtotime($fecha));

        if($dw==1){
            $dia_anterior = date( 'Y-m-d', strtotime( $fecha.' -1 day' ) );
            $fecha_ant=explode("-",$dia_anterior);
            $DomingoFestivo= $em->getRepository('BackendBundle:Festivos')->findFestivo($fecha_ant[2], $fecha_ant[1]);
            if(!empty($DomingoFestivo)){
                return true;
            }
        }

        // Se comprueba que no sea un día de vacaciones.
        $dw = date('Y-m-d', strtotime($fecha));
        // Se obtiene la fecha inicial y final del curso para usar luego el año correspondiente. 
        $ini_curso=$em->getRepository('BackendBundle:Centro')->findInicioCurso();
        $array_ini=explode("-",$ini_curso["inicioCurso"]->format('Y-m-d')); //Conversión de array a String
        $fin_curso=$em->getRepository('BackendBundle:Centro')->findFinCurso();
        $array_fin=explode("-",$fin_curso["finCurso"]->format('Y-m-d'));

        // Se obtiene las fechas de vacaciones.
        $ini_nav=$em->getRepository('BackendBundle:Festivos')->findFestivosPorDescripcion("Inicio Vacaciones de Navidad");
        $fin_nav=$em->getRepository('BackendBundle:Festivos')->findFestivosPorDescripcion("Fin Vacaciones de Navidad");
        $ini_ss=$em->getRepository('BackendBundle:Festivos')->findFestivosPorDescripcion("Inicio Vacaciones de Semana Santa");
        $fin_ss=$em->getRepository('BackendBundle:Festivos')->findFestivosPorDescripcion("Fin Vacaciones de Semana Santa");
        
        // Se comrpueba si existe vacaciones de Navidad y si un día de esas vacaciones.
        if(!empty($ini_nav) && !empty($fin_nav)) {
            $Fecha_ini_nav = date('Y-m-d', strtotime($array_ini[0]."-".$ini_nav->getNumMes()."-".$ini_nav->getDia()));
            $Fecha_fin_nav = date('Y-m-d', strtotime($array_fin[0]."-".$fin_nav->getNumMes()."-".$fin_nav->getDia()));

            if (($dw >= $Fecha_ini_nav) && ($dw <= $Fecha_fin_nav)){
                return true;
            }
        }
         // Se comrpueba si existe vacaciones de Semana Santa y si un día de esas vacaciones.
        if(!empty($ini_ss) && !empty($fin_ss)){

            $Fecha_ini_ss = date('Y-m-d', strtotime($array_fin[0]."-".$ini_ss->getNumMes()."-".$ini_ss->getDia()));
            $Fecha_fin_ss = date('Y-m-d', strtotime($array_fin[0]."-".$fin_ss->getNumMes()."-".$fin_ss->getDia()));

            if (($dw >= $Fecha_ini_ss) && ($dw <= $Fecha_fin_ss)){
                return true;
            }
        }
        // En caso de que no exista ningún festivo se devolverá false para que se pueda guardar la reserva.
        return false;
    }


    //se muestra las fechas que ha impartido el profesor en los N días anteriores.
    public function  FechasImpartidasProfesorAction(Request $request, $num, $id, $grupo)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $this->get('security.context')->getToken()->getUser();
        $grupo= $em->getRepository('BackendBundle:Grupo')->findOneById($grupo);
        $asignatura= $em->getRepository('BackendBundle:AsignaturasCursos')->findOneById($id);

        // Se obtiene la fecha inicial y final del curso para. 
        $ini_curso=$em->getRepository('BackendBundle:Centro')->findInicioCurso();
        $array_ini=explode("-",$ini_curso["inicioCurso"]->format('Y-m-d')); //Conversión de array a String
        $fin_curso=$em->getRepository('BackendBundle:Centro')->findFinCurso();
        $array_fin=explode("-",$fin_curso["finCurso"]->format('Y-m-d'));

        $Fecha_Fin=$fin_curso["finCurso"]->format('Y-m-d');
        $Fecha_Ini=$ini_curso["inicioCurso"]->format('Y-m-d');

        // Se obtiene las fechas de vacaciones.
        $ini_nav=$em->getRepository('BackendBundle:Festivos')->findFestivosPorDescripcion("Inicio Vacaciones de Navidad");
        $fin_nav=$em->getRepository('BackendBundle:Festivos')->findFestivosPorDescripcion("Fin Vacaciones de Navidad");
        $ini_ss=$em->getRepository('BackendBundle:Festivos')->findFestivosPorDescripcion("Inicio Vacaciones de Semana Santa");
        $fin_ss=$em->getRepository('BackendBundle:Festivos')->findFestivosPorDescripcion("Fin Vacaciones de Semana Santa");
        
        $Fecha_ini_nav = date('Y-m-d', strtotime($array_ini[0]."-".$ini_nav->getNumMes()."-".$ini_nav->getDia()));
        $Fecha_fin_nav = date('Y-m-d', strtotime($array_fin[0]."-".$fin_nav->getNumMes()."-".$fin_nav->getDia()));
        $Fecha_ini_ss = date('Y-m-d', strtotime($array_fin[0]."-".$ini_ss->getNumMes()."-".$ini_ss->getDia()));
        $Fecha_fin_ss = date('Y-m-d', strtotime($array_fin[0]."-".$fin_ss->getNumMes()."-".$fin_ss->getDia()));

        $hoy=date("Y-m-d");

        if ($hoy <= $Fecha_ini_nav){
            $trimestre=1;
        }
        else if($hoy >= $Fecha_ini_ss){
            $trimestre=3;
        }
        else{
            $trimestre=2;
        }

        //Se obtiene los días de la semana que da clase el profesor para luego comprobar si la fecha corresponde a esos días.
        $imparte=$em->getRepository('BackendBundle:Imparte')->findDiasSemanaProfesor($entity,$grupo,$asignatura);
        $array=null;
        
        //Se obtiene las fechas impartidas por el profesor.
        if($trimestre==1){
            //Primer día del primer trimestre.
            $T1_fin= date("Y-m-d", strtotime($Fecha_ini_nav."- 1 days" ));  

            //Se comprueba si el dia actual ha pasado del final de trimestre para omitir las fechas siguientes.
            if($hoy < $T1_fin){
                //Se establece el último día a buscar.
                if(date("Y-m-d", strtotime($hoy."- ".$num." days" ))<$Fecha_Ini){
                    $ultima=$Fecha_Ini;
                }
                else{
                    $ultima=date("Y-m-d", strtotime($hoy."- ".$num." days" ));
                }

                for($i=$hoy;$i>=$ultima;$i = date("Y-m-d", strtotime($i ."- 1 days"))){
                    //Se comprueba si es fectivo.
                    $fecha_det= explode("-",$i);
                    $festivo=$this->ComprobarDiaNoLectivo($fecha_det[2],$fecha_det[1],$fecha_det[0]) ;
                    if(!$festivo){
                        //Se comprueba si es un día de la semana donde imparte el profesor.

                        //Día de la semana de cada fecha.
                        $dia_semanal=date("N", strtotime($i));

                        //Variable para detectar si la fecha coincide en el dia semanal qe imparte clase el profesor en ese grupo.
                        $dia_clase=0;
                        foreach ($imparte as $clase) {
                            if($dia_clase==0 && $clase->getDiaSemanal()== $dia_semanal ){
                                $dia_clase=1;
                            }
                        }
                        if($dia_clase==1){
                            $array[]=date("d-m-Y", strtotime($i));
                        }
                    }
                }
            }
            else{
                //Se establece el último día a buscar.
                if(date("Y-m-d", strtotime($T1_fin."- ".$num." days" ))<$Fecha_Ini){
                    $ultima=$Fecha_Ini;
                }
                else{

                    $ultima=date("Y-m-d", strtotime($T1_fin."- ".$num." days" ));
                }

                for($i=$T1_fin;$i>=$ultima;$i = date("Y-m-d", strtotime($i ."- 1 days"))){
                    
                    //Se comprueba si es fectivo.
                    $fecha_det= explode("-",$i);
                    $festivo=$this->ComprobarDiaNoLectivo($fecha_det[2],$fecha_det[1],$fecha_det[0]) ;
                    if(!$festivo){
                        //Se comprueba si es un día de la semana donde imparte el profesor.

                        //Día de la semana de cada fecha.
                        $dia_semanal=date("N", strtotime($i));

                        //Variable para detectar si la fecha coincide en el dia semanal qe imparte clase el profesor en ese grupo.
                        $dia_clase=0;
                        foreach ($imparte as $clase) {
                            if($dia_clase==0 && $clase->getDiaSemanal()== $dia_semanal ){
                                $dia_clase=1;
                            }
                        }
                        if($dia_clase==1){
                            $array[]=date("d-m-Y", strtotime($i));
                        }
                    }
                }
            }
        }
        else if($trimestre==2){
            //Primer día del segundo trimestre.
            $T2_ini= date("Y-m-d", strtotime($Fecha_fin_nav."+ 1 days" ));  

            //Se comprueba si el dia actual ha pasado del final de trimestre para omitir las fechas siguientes.
            if($hoy < $Fecha_ini_ss){
                //Se establece el último día a buscar.
                if(date("Y-m-d", strtotime($hoy."- ".$num." days" ))<$T2_ini){
                    $ultima=$T2_ini;
                }
                else{
                    $ultima=date("Y-m-d", strtotime($hoy."- ".$num." days" ));
                }

                for($i=$hoy;$i>=$ultima;$i = date("Y-m-d", strtotime($i ."- 1 days"))){
                    //Se comprueba si es fectivo.
                    $fecha_det= explode("-",$i);
                    $festivo=$this->ComprobarDiaNoLectivo($fecha_det[2],$fecha_det[1],$fecha_det[0]) ;
                    if(!$festivo){
                        //Se comprueba si es un día de la semana donde imparte el profesor.

                        //Día de la semana de cada fecha.
                        $dia_semanal=date("N", strtotime($i));

                        //Variable para detectar si la fecha coincide en el dia semanal qe imparte clase el profesor en ese grupo.
                        $dia_clase=0;
                        foreach ($imparte as $clase) {
                            if($dia_clase==0 && $clase->getDiaSemanal()== $dia_semanal ){
                                $dia_clase=1;
                            }
                        }
                        if($dia_clase==1){
                            $array[]=date("d-m-Y", strtotime($i));
                        }
                    }
                }
            }
            else{
                //Se establece el último día a buscar.
                if(date("Y-m-d", strtotime($Fecha_ini_ss."- ".$num." days" ))<$Fecha_Ini){
                    $ultima=$Fecha_Ini;
                }
                else{

                    $ultima=date("Y-m-d", strtotime($Fecha_ini_ss."- ".$num." days" ));
                }

                for($i=$Fecha_ini_ss;$i>=$ultima;$i = date("Y-m-d", strtotime($i ."- 1 days"))){
                    
                    //Se comprueba si es fectivo.
                    $fecha_det= explode("-",$i);
                    $festivo=$this->ComprobarDiaNoLectivo($fecha_det[2],$fecha_det[1],$fecha_det[0]) ;
                    if(!$festivo){
                        //Se comprueba si es un día de la semana donde imparte el profesor.

                        //Día de la semana de cada fecha.
                        $dia_semanal=date("N", strtotime($i));

                        //Variable para detectar si la fecha coincide en el dia semanal qe imparte clase el profesor en ese grupo.
                        $dia_clase=0;
                        foreach ($imparte as $clase) {
                            if($dia_clase==0 && $clase->getDiaSemanal()== $dia_semanal ){
                                $dia_clase=1;
                            }
                        }
                        if($dia_clase==1){
                            $array[]=date("d-m-Y", strtotime($i));
                        }
                    }
                }
            }
        }
        else{
            //Primer día del tercer trimestre.
            $T3_ini= date("Y-m-d", strtotime($Fecha_fin_ss."+ 1 days" ));  

            //Se comprueba si el dia actual ha pasado del final de curso para omitir las fechas siguientes.
            if($hoy < $Fecha_Fin){
                //Se establece el último día a buscar.
                if(date("Y-m-d", strtotime($hoy."- ".$num." days" ))<$T3_ini){
                    $ultima=$T3_ini;
                }
                else{
                    $ultima=date("Y-m-d", strtotime($hoy."- ".$num." days" ));
                }

                for($i=$hoy;$i>=$ultima;$i = date("Y-m-d", strtotime($i ."- 1 days"))){
                //echo $Fecha_fin_ss . "<br />";
                    //Se comprueba si es fectivo.
                    $fecha_det= explode("-",$i);
                    $festivo=$this->ComprobarDiaNoLectivo($fecha_det[2],$fecha_det[1],$fecha_det[0]) ;
                    if(!$festivo){
                        //Se comprueba si es un día de la semana donde imparte el profesor.

                        //Día de la semana de cada fecha.
                        $dia_semanal=date("N", strtotime($i));

                        //Variable para detectar si la fecha coincide en el dia semanal qe imparte clase el profesor en ese grupo.
                        $dia_clase=0;
                        foreach ($imparte as $clase) {
                            if($dia_clase==0 && $clase->getDiaSemanal()== $dia_semanal ){
                                $dia_clase=1;
                            }
                        }
                        if($dia_clase==1){
                            $array[]=date("d-m-Y", strtotime($i));
                        }
                    }
                }
            }
            else{
                //Se establece el último día a buscar.
                if(date("Y-m-d", strtotime($Fecha_Fin."- ".$num." days" ))<$T3_ini){
                    $ultima=$T3_ini;
                }
                else{
                    $ultima=date("Y-m-d", strtotime($Fecha_Fin."- ".$num." days" ));
                }

                for($i=$Fecha_Fin;$i>=$ultima;$i = date("Y-m-d", strtotime($i ."- 1 days"))){
                    
                    //Se comprueba si es fectivo.
                    $fecha_det= explode("-",$i);
                    $festivo=$this->ComprobarDiaNoLectivo($fecha_det[2],$fecha_det[1],$fecha_det[0]) ;
                    if(!$festivo){
                        //Se comprueba si es un día de la semana donde imparte el profesor.

                        //Día de la semana de cada fecha.
                        $dia_semanal=date("N", strtotime($i));

                        //Variable para detectar si la fecha coincide en el dia semanal qe imparte clase el profesor en ese grupo.
                        $dia_clase=0;
                        foreach ($imparte as $clase) {
                            if($dia_clase==0 && $clase->getDiaSemanal()== $dia_semanal ){
                                $dia_clase=1;
                            }
                        }
                        if($dia_clase==1){
                            $array[]=date("d-m-Y", strtotime($i));
                        }
                    }
                }
            }
        }

        return new JsonResponse(array('fechas' => $array), 200);
    }



    public function  HorasImpartidaClaseProfesorAction(Request $request, $fecha, $id, $grupo)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $this->get('security.context')->getToken()->getUser();
        $grupo= $em->getRepository('BackendBundle:Grupo')->findOneById($grupo);
        $asignatura= $em->getRepository('BackendBundle:AsignaturasCursos')->findOneById($id);

        $array=explode("-",$fecha);

        $Fecha = date('Y-m-d', strtotime($array[2]."-".$array[1]."-".$array[0]));
        $dia_semanal=date("N", strtotime($Fecha));

        $array=null;
        //Se obtiene las horas de la las clases del mismo día.
        $imparte=$em->getRepository('BackendBundle:Imparte')->findHorasImpartidaClaseProfesor($entity,$grupo,$asignatura,$dia_semanal);
        foreach ($imparte as $clase) {
            $inicio=$clase->getHorario()->getInicio();
            $fin=$clase->getHorario()->getFin();

            $array[$clase->getHorario()->getId()]=$inicio->format('H:i')." - ".$fin->format('H:i');     
        }
        
        $num=count($imparte);

        return new JsonResponse(array('fechas' => $array,'num'=> $num), 200);
    }


    public function  claseSeleccionadaProfesorAction(Request $request, $fecha, $horario, $asig, $grupo)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $this->get('security.context')->getToken()->getUser();
        $grupo= $em->getRepository('BackendBundle:Grupo')->findOneById($grupo);
        $asignatura= $em->getRepository('BackendBundle:AsignaturasCursos')->findOneById($asig);
        $_horario= $em->getRepository('BackendBundle:Horario')->findOneById($horario);
        
        $array=explode("-",$fecha);

        $Fecha = date('Y-m-d', strtotime($array[2]."-".$array[1]."-".$array[0]));
        $dia_semanal=date("N", strtotime($Fecha));

        //Se obtiene los alumnos según el tipo de asignatura.
        $alumnos=null;
        if($asignatura->getAsignatura()->getOpcional() == 0){
            $alumnos=$em->getRepository('BackendBundle:Alumno')->findByGrupoOrdenado($grupo);
        }
        else{
            $alumnos=$em->getRepository('BackendBundle:Alumno')->findAlumnosOptativaGrupo($asignatura, $grupo);
        }

        //Se obtiene las faltas de la clase seleccionada.
        $array=null;

        $faltas=$em->getRepository('IntranetBundle:Ausencia')->findFaltasClase($grupo,$asignatura,$Fecha,$_horario);
        foreach ($faltas as $falta) {
            $array[$falta->getAlumno()->getId()."-".$falta->getId()]=$falta->getTipo();     
        }
        //Horario para mostrar.
        $inicio=$_horario->getInicio();
        $fin=$_horario->getFin();
        $modulo=$inicio->format('H:i')."-".$fin->format('H:i'); 

        $ausencia = new Ausencia();
        $form   = $this->createCreateAusenciaForm($ausencia);
        
        return $this->render('IntranetBundle:Profesor:claseAsignada.html.twig', array(
            'alumnos' => $alumnos,
            'faltas'=>$array,
            'grupo'=>$grupo,
            'asignatura'=>$asignatura,
            'asig'=>$asig,
            'fecha'=>$fecha,
            'horario'=>$modulo,
            'id_horario'=>$horario,
            'form'   => $form->createView(),

        ));

    }

    public function comprobarJustificacionesNuevasAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity= $em->getRepository('BackendBundle:Profesor')->findOneById($id);
        $tutor_grupo= $em->getRepository('BackendBundle:Grupo')->findOneByProfesor($entity);
        
        $num=0;
        //Se obtiene las faltas justificadas pendientes de de confirmación.
        $pendientes=$em->getRepository('IntranetBundle:Ausencia')->findFaltasPendientes($tutor_grupo); 

         if($pendientes){
            $num=count($pendientes);
         }

        return new JsonResponse(array(
            'num'=> $num,
            'id' => $id,
            'success' => true), 200);
    }


    ///////////////////////////////////////////
    //             Calificaciones            //
    ///////////////////////////////////////////

    private function createCreateTareaForm(Tarea $entity)
    {
        $form = $this->createForm(new TareaType(), $entity, array(
            'action' => $this->generateUrl('tarea_create'),
            'method' => 'POST',
        ));
        $titulo=$this->get("translator")->trans("Guardar");
        $form->add('submit', 'submit', array('label' => $titulo, 'attr' => array('class' => 'btn btn-success')));

        return $form;
    }


    public function calificacionesAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $this->get('security.context')->getToken()->getUser();
        $tutor_grupo= $em->getRepository('BackendBundle:Grupo')->findOneByProfesor($entity);
        $tareas= $em->getRepository('IntranetBundle:Tarea')->findTareasComunes($entity);

        //Se obtiene los grupos agrupados por cada tarea de una asignatura del profesor.
        $array=null;
        foreach ($tareas as $tarea) {
            $tareasGrupos= $em->getRepository('IntranetBundle:Tarea')->findByDescripcionOrdenado($tarea->getDescripcion(), $tarea->getAsignatura()->getAsignatura()->getNombre());
            $cursos="";
            foreach ($tareasGrupos as $TG) {
                $cursos=$cursos.$TG->getGrupo()->getCurso()->getCurso().$TG->getGrupo()->getLetra()." - ";
            }
            $final = trim($cursos, ' - ');
            $array[$tarea->getId()]=$final;
        }

        if($entity->getNivel()=="Primaria"){
            $cursos = $em->getRepository('BackendBundle:Imparte')->findAsignacionesProfesorAsignaturaGrupo($entity);
            $imparte=$em->getRepository('BackendBundle:Imparte')->findAsignaturasProfesor($entity);
        }
        else{
            //Para los profesores de infantil se asigna sólo el grupo que es tutor.
            $cursos=$tutor_grupo;
            $imparte=null;
        }

        // Se obtiene la fecha inicial y final del curso para usar luego el año correspondiente. 
        $ini_curso=$em->getRepository('BackendBundle:Centro')->findInicioCurso();
        $array_ini=explode("-",$ini_curso["inicioCurso"]->format('Y-m-d')); //Conversión de array a String
        $fin_curso=$em->getRepository('BackendBundle:Centro')->findFinCurso();
        $array_fin=explode("-",$fin_curso["finCurso"]->format('Y-m-d'));

        // Se obtiene las fechas de inicio de las vacaciones.
        $ini_nav=$em->getRepository('BackendBundle:Festivos')->findFestivosPorDescripcion("Inicio Vacaciones de Navidad");
        $ini_ss=$em->getRepository('BackendBundle:Festivos')->findFestivosPorDescripcion("Inicio Vacaciones de Semana Santa");
        
        $Fecha_ini_nav = date('Y-m-d', strtotime($array_ini[0]."-".$ini_nav->getNumMes()."-".$ini_nav->getDia()));
        $Fecha_ini_ss = date('Y-m-d', strtotime($array_fin[0]."-".$ini_ss->getNumMes()."-".$ini_ss->getDia()));

        $hoy=date("Y-m-d");

        if ($hoy <= $Fecha_ini_nav){
            $trimestre=1;
        }
        else if($hoy >= $Fecha_ini_ss){
            $trimestre=3;
        }
        else{
            $trimestre=2;
        }

        $tarea = new Tarea();
        $form   = $this->createCreateTareaForm($tarea);

        return $this->render('IntranetBundle:Profesor:calificaciones.html.twig', array(
            'entity' => $entity,
            'tutor_grupo' => $tutor_grupo,
            'cursos'=>$cursos,
            'tareas'=>$tareas,
            'imparte'=>$imparte,
            'grupos' => $array,
            'trimestre' =>$trimestre,
            'form'   => $form->createView(),
        ));
    }

    public function  GruposAsignaturasGrupoProfesorAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $this->get('security.context')->getToken()->getUser();
        $asignatura= $em->getRepository('BackendBundle:AsignaturasCursos')->findOneById($id);

        #Se devuelve sólo el id, curso y letra para mostrarlo por ajax.
        $grupos= $em->getRepository('BackendBundle:Imparte')->findGruposProfesorAsignatura($entity, $asignatura->getAsignatura());

        return new JsonResponse(array(
            'grupos' => $grupos
            ), 200);
    }


    public function ListaTareasAlumnoAction($id, $trimestre, $asignatura)
    {
        $em = $this->getDoctrine()->getManager();

        $profesor = $this->get('security.context')->getToken()->getUser();
        $asignatura=$em->getRepository('BackendBundle:AsignaturasCursos')->findOneById($asignatura);
        $alumno=$em->getRepository('BackendBundle:Alumno')->findOneById($id);

        $tareas=$em->getRepository('IntranetBundle:Cursa')->findByTareasAlumnoAsignaturaTrimestre($alumno, $trimestre, $asignatura);
      
        return $this->render('IntranetBundle:Profesor:lista_tareas_alumno.html.twig', array(
            'profesor' => $profesor,
            'tareas'=>$tareas,
            'asignatura' => $asignatura,
            'alumno' => $alumno,
            'trimestre' => $trimestre));
    }

    public function evaluacionGrupoAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $profesor = $this->get('security.context')->getToken()->getUser();
        $grupo=$em->getRepository('BackendBundle:Grupo')->findOneById($id);
        //$asignatura=$em->getRepository('BackendBundle:AsignaturasCursos')->findOneById($asignatura);
        $alumnos=$em->getRepository('BackendBundle:Alumno')->findByGrupoOrdenado($grupo);
        $asignaturas=$em->getRepository('BackendBundle:AsignaturasCursos')->findByCurso($grupo->getCurso()->getId());
   
        return $this->render('IntranetBundle:Profesor:evaluacion_grupo.html.twig', array(
            'entity' => $profesor,
            'alumnos' => $alumnos,
            'grupo' => $grupo,
            'asignaturas' => $asignaturas,
            ));
    }

    public function evaluacionAlumnoAction($id)
    {

        $em = $this->getDoctrine()->getManager();

        $profesor = $this->get('security.context')->getToken()->getUser();
        $alumno=$em->getRepository('BackendBundle:Alumno')->findOneById($id);

        $asignaturas=$em->getRepository('BackendBundle:AsignaturasCursos')->findAsignaturasAlumno($alumno->getCurso(), $alumno->getOptativa());

        // Se obtiene la fecha inicial y final del curso para usar luego el año correspondiente. 
        $ini_curso=$em->getRepository('BackendBundle:Centro')->findInicioCurso();
        $array_ini=explode("-",$ini_curso["inicioCurso"]->format('Y-m-d')); //Conversión de array a String
        $fin_curso=$em->getRepository('BackendBundle:Centro')->findFinCurso();
        $array_fin=explode("-",$fin_curso["finCurso"]->format('Y-m-d'));

        // Se obtiene las fechas de inicio de las vacaciones.
        $ini_nav=$em->getRepository('BackendBundle:Festivos')->findFestivosPorDescripcion("Inicio Vacaciones de Navidad");
        $ini_ss=$em->getRepository('BackendBundle:Festivos')->findFestivosPorDescripcion("Inicio Vacaciones de Semana Santa");
        
        $Fecha_ini_nav = date('Y-m-d', strtotime($array_ini[0]."-".$ini_nav->getNumMes()."-".$ini_nav->getDia()));
        $Fecha_ini_ss = date('Y-m-d', strtotime($array_fin[0]."-".$ini_ss->getNumMes()."-".$ini_ss->getDia()));

        $hoy=date("Y-m-d");

        if ($hoy <= $Fecha_ini_nav){
            $trimestre=1;
        }
        else if($hoy >= $Fecha_ini_ss){
            $trimestre=3;
        }
        else{
            $trimestre=2;
        }   
        $trimestre1= $em->getRepository('IntranetBundle:Cursa')->findByNotasAlumnoTrimestre($alumno,1);
        $trimestre2= $em->getRepository('IntranetBundle:Cursa')->findByNotasAlumnoTrimestre($alumno,2);
        $trimestre3= $em->getRepository('IntranetBundle:Cursa')->findByNotasAlumnoTrimestre($alumno,3);

        $array1=[];
        foreach($trimestre1 as $registro){

            if($registro->getNota()){
                $nota=$registro->getNota();
            }
            else{
                $nota="";
            }
            $array1[$registro->getAsignaturasCursos()->getId()."-".$registro->getTarea()->getTrimestre()]=$nota;
        }

        $array2=[];
        foreach($trimestre2 as $registro){

            if($registro->getNota()){
                $nota=$registro->getNota();
            }
            else{
                $nota="";
            }
            $array2[$registro->getAsignaturasCursos()->getId()."-".$registro->getTarea()->getTrimestre()]=$nota;

        }

        $array3=[];
        foreach($trimestre3 as $registro){

            if($registro->getNota()){
                $nota=$registro->getNota();
            }
            else{
                $nota="";
            }
            $array3[$registro->getAsignaturasCursos()->getId()."-".$registro->getTarea()->getTrimestre()."-".$registro->getOrd()]=$nota;
        }

        return $this->render('IntranetBundle:Profesor:evaluacion_alumno.html.twig', array(
            'entity' => $profesor,
            'asignaturas' => $asignaturas,
            'alumno' => $alumno,
            'trimestre' => $trimestre,
            'trimestre1' => $array1,
            'trimestre2' => $array2, 
            'trimestre3' => $array3  
        ));
    }



    //Función de prueba para ver los resultados del boletin en html.
    public function BoletinEvaluacionAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $profesor = $this->get('security.context')->getToken()->getUser();
        $alumno=$em->getRepository('BackendBundle:Alumno')->findOneById($id); 

        $this->comprobarAlumno($alumno);

        $asignaturas=$em->getRepository('BackendBundle:AsignaturasCursos')->findAsignaturasAlumno($alumno->getCurso(), $alumno->getOptativa());

        $centro =$em->getRepository('BackendBundle:Centro')->findCentro();
        $horario=$centro->getHTutorias();

        // Se obtiene la fecha inicial y final del curso para usar luego el año correspondiente. 
        $ini_curso=$em->getRepository('BackendBundle:Centro')->findInicioCurso();
        $array_ini=explode("-",$ini_curso["inicioCurso"]->format('Y-m-d')); //Conversión de array a String
        $fin_curso=$em->getRepository('BackendBundle:Centro')->findFinCurso();
        $array_fin=explode("-",$fin_curso["finCurso"]->format('Y-m-d'));

        // Se obtiene las fechas de inicio de las vacaciones.
        $ini_nav=$em->getRepository('BackendBundle:Festivos')->findFestivosPorDescripcion("Inicio Vacaciones de Navidad");
        $ini_ss=$em->getRepository('BackendBundle:Festivos')->findFestivosPorDescripcion("Inicio Vacaciones de Semana Santa");
        
        $Fecha_ini_nav = date('Y-m-d', strtotime($array_ini[0]."-".$ini_nav->getNumMes()."-".$ini_nav->getDia()));
        $Fecha_ini_ss = date('Y-m-d', strtotime($array_fin[0]."-".$ini_ss->getNumMes()."-".$ini_ss->getDia()));

        $hoy=date("Y-m-d");

        if ($hoy <= $Fecha_ini_nav){
            $trimestre=1;
        }
        else if($hoy >= $Fecha_ini_ss){
            $trimestre=3;
        }
        else{
            $trimestre=2;
        }   
        $trimestre1= $em->getRepository('IntranetBundle:Cursa')->findByNotasAlumnoTrimestre($alumno,1);
        $trimestre2= $em->getRepository('IntranetBundle:Cursa')->findByNotasAlumnoTrimestre($alumno,2);
        $trimestre3= $em->getRepository('IntranetBundle:Cursa')->findByNotasAlumnoTrimestre($alumno,3);

        $array1=[];
        foreach($trimestre1 as $registro){

            if($registro->getNota()){
                $nota=$registro->getNota();
            }
            else{
                $nota="";
            }
            $array1[$registro->getAsignaturasCursos()->getId()."-".$registro->getTarea()->getTrimestre()]=$nota;
        }

        $array2=[];
        foreach($trimestre2 as $registro){

            if($registro->getNota()){
                $nota=$registro->getNota();
            }
            else{
                $nota="";
            }
            $array2[$registro->getAsignaturasCursos()->getId()."-".$registro->getTarea()->getTrimestre()]=$nota;

        }

        $array3=[];
        foreach($trimestre3 as $registro){

            if($registro->getNota()){
                $nota=$registro->getNota();
            }
            else{
                $nota="";
            }
            $array3[$registro->getAsignaturasCursos()->getId()."-".$registro->getTarea()->getTrimestre()."-".$registro->getOrd()]=$nota;
        }

        //Se optienes las faltas para mostrarlas en la tabla.
        $faltas=$em->getRepository('IntranetBundle:Ausencia')->findFaltasAlumnoPorDia($alumno);
        $retrasos=$em->getRepository('IntranetBundle:Ausencia')->findRetrasosAlumnoPorDia($alumno);
        $horarios=$em->getRepository('BackendBundle:Horario')->findClases();
        
        $justificadasDia=null;
        $injustificadasDia=null;
        $justificadasTramo=null;
        $injustificadasTramo=null;
        foreach($faltas as $falta){
            $dia=$falta->getFecha()->format('d/m/Y');
            
            $faltas_dia=$em->getRepository('IntranetBundle:Ausencia')->findFaltasAlumnoDia($alumno, $dia);


            //Se comprueba si tiene falta en todas las clases del día o solo en algún tramo.
            if(count($faltas_dia)==count($horarios)){
                if($falta->getConfirmada()==1){
                    $justificadasDia[]=$falta;
                }
                else{
                    $injustificadasDia[]=$falta;
                }
            }
            else{
                if($falta->getConfirmada()==1){
                    $justificadasTramo[]=$falta;
                }
                else{
                    $injustificadasTramo[]=$falta;
                }
            }
        }
        $numjustificadasDia=count($justificadasDia);
        $numinjustificadasDia=count($injustificadasDia);
        $numjustificadasTramo=count($justificadasTramo);
        $numinjustificadasTramo=count($injustificadasTramo);
        $numretrasos=count($retrasos);

        return $this->render('IntranetBundle:Profesor:boletin_evaluacion.html.twig', array(
            'entity' => $profesor,
            'asignaturas' => $asignaturas,
            'alumno' => $alumno,
            'trimestre' => $trimestre,
            'trimestre1' => $array1,
            'trimestre2' => $array2, 
            'trimestre3' => $array3,
            'h_tutorias' => $horario,  
            'justificadasDia'=>$numjustificadasDia,
            'injustificadasDia'=>$numinjustificadasDia,
            'justificadasTramo'=>$numjustificadasTramo,
            'injustificadasTramo'=>$numinjustificadasTramo,
            'retrasos'=>$numretrasos
        ));
    }



    public function BoletinEvaluacionPdfAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $profesor = $this->get('security.context')->getToken()->getUser();
        $alumno=$em->getRepository('BackendBundle:Alumno')->findOneById($id);
        $this->comprobarAlumno($alumno);

        $asignaturas=$em->getRepository('BackendBundle:AsignaturasCursos')->findAsignaturasAlumno($alumno->getCurso(), $alumno->getOptativa());
        
        $centro =$em->getRepository('BackendBundle:Centro')->findCentro();
        $horario=$centro->getHTutorias();

        // Se obtiene la fecha inicial y final del curso para usar luego el año correspondiente. 
        $ini_curso=$em->getRepository('BackendBundle:Centro')->findInicioCurso();
        $array_ini=explode("-",$ini_curso["inicioCurso"]->format('Y-m-d')); //Conversión de array a String
        $fin_curso=$em->getRepository('BackendBundle:Centro')->findFinCurso();
        $array_fin=explode("-",$fin_curso["finCurso"]->format('Y-m-d'));

        // Se obtiene las fechas de inicio de las vacaciones.
        $ini_nav=$em->getRepository('BackendBundle:Festivos')->findFestivosPorDescripcion("Inicio Vacaciones de Navidad");
        $ini_ss=$em->getRepository('BackendBundle:Festivos')->findFestivosPorDescripcion("Inicio Vacaciones de Semana Santa");
        
        $Fecha_ini_nav = date('Y-m-d', strtotime($array_ini[0]."-".$ini_nav->getNumMes()."-".$ini_nav->getDia()));
        $Fecha_ini_ss = date('Y-m-d', strtotime($array_fin[0]."-".$ini_ss->getNumMes()."-".$ini_ss->getDia()));

        $hoy=date("Y-m-d");

        if ($hoy <= $Fecha_ini_nav){
            $trimestre=1;
        }
        else if($hoy >= $Fecha_ini_ss){
            $trimestre=3;
        }
        else{
            $trimestre=2;
        }   
        $trimestre1= $em->getRepository('IntranetBundle:Cursa')->findByNotasAlumnoTrimestre($alumno,1);
        $trimestre2= $em->getRepository('IntranetBundle:Cursa')->findByNotasAlumnoTrimestre($alumno,2);
        $trimestre3= $em->getRepository('IntranetBundle:Cursa')->findByNotasAlumnoTrimestre($alumno,3);

        $array1=[];
        foreach($trimestre1 as $registro){

            if($registro->getNota()){
                $nota=$registro->getNota();
            }
            else{
                $nota="";
            }
            $array1[$registro->getAsignaturasCursos()->getId()."-".$registro->getTarea()->getTrimestre()]=$nota;
        }

        $array2=[];
        foreach($trimestre2 as $registro){

            if($registro->getNota()){
                $nota=$registro->getNota();
            }
            else{
                $nota="";
            }
            $array2[$registro->getAsignaturasCursos()->getId()."-".$registro->getTarea()->getTrimestre()]=$nota;
        }

        $array3=[];
        foreach($trimestre3 as $registro){

            if($registro->getNota()){
                $nota=$registro->getNota();
            }
            else{
                $nota="";
            }
            $array3[$registro->getAsignaturasCursos()->getId()."-".$registro->getTarea()->getTrimestre()."-".$registro->getOrd()]=$nota;
        }

        if($trimestre==1){
            $titulo="1ª Evaluación";
        }
        else if($trimestre==2){
            $titulo="2ª Evaluación";
        }
        else{
            $titulo="3ª Evaluación";
        }
        $subtitulo=null;

        //Se optienes las faltas para mostrarlas en la tabla.
        $faltas=$em->getRepository('IntranetBundle:Ausencia')->findFaltasAlumnoPorDia($alumno);
        $retrasos=$em->getRepository('IntranetBundle:Ausencia')->findRetrasosAlumnoPorDia($alumno);
        $horarios=$em->getRepository('BackendBundle:Horario')->findClases();
        
        $justificadasDia=null;
        $injustificadasDia=null;
        $justificadasTramo=null;
        $injustificadasTramo=null;
        foreach($faltas as $falta){
            $dia=$falta->getFecha()->format('d/m/Y');
            
            $faltas_dia=$em->getRepository('IntranetBundle:Ausencia')->findFaltasAlumnoDia($alumno, $dia);


            //Se comprueba si tiene falta en todas las clases del día o solo en algún tramo.
            if(count($faltas_dia)==count($horarios)){
                if($falta->getConfirmada()==1){
                    $justificadasDia[]=$falta;
                }
                else{
                    $injustificadasDia[]=$falta;
                }
            }
            else{
                if($falta->getConfirmada()==1){
                    $justificadasTramo[]=$falta;
                }
                else{
                    $injustificadasTramo[]=$falta;
                }
            }
        }

        $numjustificadasDia=count($justificadasDia);
        $numinjustificadasDia=count($injustificadasDia);
        $numjustificadasTramo=count($justificadasTramo);
        $numinjustificadasTramo=count($injustificadasTramo);
        $numretrasos=count($retrasos);

        $html = $this->renderView('IntranetBundle:Profesor:boletin_evaluacion.html.twig', array(
            'entity' => $profesor,
            'asignaturas' => $asignaturas,
            'alumno' => $alumno,
            'trimestre' => $trimestre,
            'trimestre1' => $array1,
            'trimestre2' => $array2, 
            'trimestre3' => $array3,
            'h_tutorias' => $horario,
            'justificadasDia'=>$numjustificadasDia,
            'injustificadasDia'=>$numinjustificadasDia,
            'justificadasTramo'=>$numjustificadasTramo,
            'injustificadasTramo'=>$numinjustificadasTramo,
            'retrasos'=>$numretrasos
        ));
        $header = $this->renderView('IntranetBundle:Default:header_boletin.html.twig', array(
            'inicio' => $ini_curso,
            'fin' => $fin_curso,
            'grupo' => $alumno->getGrupo(),
            'titulo' => $titulo,
            'subtitulo' =>$subtitulo
        ));
        $options = [
            'margin-top'    => 40,
            'margin-right'  => 7,
            'margin-bottom' => 20,
            'margin-left'   => 7,
          //Opciones para orientación horizontal.
            //'orientation'=>'Landscape', 
            //'default-header'=>false,
            //'header-html' =>'http://www.pikemere.co.uk/testerpdf.html',
            
    //'footer-right'=>utf8_decode('Seite [page] von [topage] - '.date('\ d.m.Y\ H:i')),
    //'footer-left'=>utf8_decode('Klaus Müller'),
             //'header-left' => 'nothing',
        'header-html' => $header,

            //'footer-center' => '[page] / [topage]',
            'footer-font-size' => 8,
            //'footer-left' => 'Confidential',
            //'page-size' => 'A4',

            'header-spacing' => 5, 
            'footer-spacing' => 10
        ];
        $iniciales=substr($alumno->getNombre(), 0, 1).substr($alumno->getApellido1(), 0, 1).substr($alumno->getApellido2(), 0, 1);
        return new Response(
            $this->get('knp_snappy.pdf')->getOutputFromHtml($html,$options),
            200,
            array(
                'Content-Type'        => 'application/pdf',
                'Content-Disposition' => 'attachment; filename="Boletin_'. $iniciales.'.pdf"'
            )
        );  
    }


    public function BoletinEvaluacionesPdfAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $profesor = $this->get('security.context')->getToken()->getUser();

        $centro =$em->getRepository('BackendBundle:Centro')->findCentro();
        $horario=$centro->getHTutorias();
        $grupo =$em->getRepository('BackendBundle:Grupo')->findOneById($id);

        // Se obtiene la fecha inicial y final del curso para usar luego el año correspondiente. 
        $ini_curso=$em->getRepository('BackendBundle:Centro')->findInicioCurso();
        $array_ini=explode("-",$ini_curso["inicioCurso"]->format('Y-m-d')); //Conversión de array a String
        $fin_curso=$em->getRepository('BackendBundle:Centro')->findFinCurso();
        $array_fin=explode("-",$fin_curso["finCurso"]->format('Y-m-d'));

        // Se obtiene las fechas de inicio de las vacaciones.
        $ini_nav=$em->getRepository('BackendBundle:Festivos')->findFestivosPorDescripcion("Inicio Vacaciones de Navidad");
        $ini_ss=$em->getRepository('BackendBundle:Festivos')->findFestivosPorDescripcion("Inicio Vacaciones de Semana Santa");
        
        $Fecha_ini_nav = date('Y-m-d', strtotime($array_ini[0]."-".$ini_nav->getNumMes()."-".$ini_nav->getDia()));
        $Fecha_ini_ss = date('Y-m-d', strtotime($array_fin[0]."-".$ini_ss->getNumMes()."-".$ini_ss->getDia()));

        $hoy=date("Y-m-d");

        if ($hoy <= $Fecha_ini_nav){
            $trimestre=1;
        }
        else if($hoy >= $Fecha_ini_ss){
            $trimestre=3;
        }
        else{
            $trimestre=2;
        }

        $alumnos=$em->getRepository('BackendBundle:Alumno')->findByGrupoOrdenado($grupo);


        foreach($alumnos as $alumno){
            $asignaturas=$em->getRepository('BackendBundle:AsignaturasCursos')->findAsignaturasAlumno($alumno->getCurso(), $alumno->getOptativa());

            $trimestre1= $em->getRepository('IntranetBundle:Cursa')->findByNotasAlumnoTrimestre($alumno,1);
            $trimestre2= $em->getRepository('IntranetBundle:Cursa')->findByNotasAlumnoTrimestre($alumno,2);
            $trimestre3= $em->getRepository('IntranetBundle:Cursa')->findByNotasAlumnoTrimestre($alumno,3);

            $array1=[];
            foreach($trimestre1 as $registro){

                if($registro->getNota()){
                    $nota=$registro->getNota();
                }
                else{
                    $nota="";
                }
                $array1[$registro->getAsignaturasCursos()->getId()."-".$registro->getTarea()->getTrimestre()]=$nota;
            }

            $array2=[];
            foreach($trimestre2 as $registro){

                if($registro->getNota()){
                    $nota=$registro->getNota();
                }
                else{
                    $nota="";
                }
                $array2[$registro->getAsignaturasCursos()->getId()."-".$registro->getTarea()->getTrimestre()]=$nota;
            }

            $array3=[];
            foreach($trimestre3 as $registro){

                if($registro->getNota()){
                    $nota=$registro->getNota();
                }
                else{
                    $nota="";
                }
                $array3[$registro->getAsignaturasCursos()->getId()."-".$registro->getTarea()->getTrimestre()."-".$registro->getOrd()]=$nota;
            }

            if($trimestre==1){
                $titulo="1ª Evaluación";
            }
            else if($trimestre==2){
                $titulo="2ª Evaluación";
            }
            else{
                $titulo="3ª Evaluación";
            }
            $subtitulo=null;
             $html[] = $this->renderView('IntranetBundle:Profesor:boletin_evaluacion.html.twig', array(
                'entity' => $profesor,
                'asignaturas' => $asignaturas,
                'alumno' => $alumno,
                'trimestre' => $trimestre,
                'trimestre1' => $array1,
                'trimestre2' => $array2, 
                'trimestre3' => $array3,
                'h_tutorias' => $horario,   
            ));
        } 

        $header = $this->renderView('IntranetBundle:Default:header_boletin.html.twig', array(
            'inicio' => $ini_curso,
            'fin' => $fin_curso,
            'grupo' => $alumno->getGrupo(),
            'titulo' => $titulo,
            'subtitulo' =>$subtitulo
        ));
        $options = [
            'margin-top'    => 40,
            'margin-right'  => 7,
            'margin-bottom' => 20,
            'margin-left'   => 7,
            'header-html' => $header,
            'footer-font-size' => 8,
            'header-spacing' => 5, 
            'footer-spacing' => 10
        ];
        $curso=$grupo;
        return new Response(
            $this->get('knp_snappy.pdf')->getOutputFromHtml($html,$options),200,
            array(
                'Content-Type'        => 'application/pdf',
                'Content-Disposition' => 'attachment; filename="Boletines_'. $curso.'.pdf"'
            )
        ); 
    }


    ///////////////////////////////////////////
    //               Reservas                //
    ///////////////////////////////////////////

    public function reservasAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $this->get('security.context')->getToken()->getUser();
        $entities = $em->getRepository('BackendBundle:Equipamiento')->findInstalaciones();

        $instalaciones = $em->getRepository('BackendBundle:Reserva')->findReservasInstalacionesProfesor($entity);
        $equipamientos = $em->getRepository('BackendBundle:Reserva')->findReservasEquipamientosProfesor($entity);

        return $this->render('IntranetBundle:Profesor:reservas.html.twig', array(
            'entity' => $entity,            
            'instalaciones' => $instalaciones,
            'equipamientos' => $equipamientos,
        ));
    }

    public function createReservaAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $profesor = $this->get('security.context')->getToken()->getUser();

        $entity = new Reserva();
        $form = $this->createCreateReservaForm($entity);
        $form->handleRequest($request);

        if ($form->isValid()) {

            //Se añade al formulario una variable extra de tipo entity con checkboxes para seleccionar.
            $extra = $form->get('seleccion')->getData();
            //Se convierte el ArrayCollection en un array normal.
            $arr = $extra->toArray();

            $contador=0;
            //Para cada checkbox marcado se crea una nueva reserva y se guarda con el resto de valores seleccionado.
            foreach ($arr as $horario) {
                $entity = new Reserva();
                $form = $this->createCreateReservaForm($entity);
                $form->handleRequest($request);

                $modulo = $em->getRepository('BackendBundle:Horario')->findOneById($horario);
                $entity->setHorario($modulo);       
                $entity->setProfesor($profesor);             

                $em->persist($entity);
                $em->flush();

                $contador++;
            }
            if($contador>1){
                $ms = $this->get('translator')->trans('Se han realizado %$contador% reservas correctamente.',array('%$contador%' =>$contador ));
            }
            else{
                $ms = $this->get('translator')->trans('Se ha realizado la reserva correctamente.');
            }
            $this->get('session')->getFlashBag()->add('notice',$ms);

            return $this->redirect($this->generateUrl('intranet_profesor_reservas'));
        }
        return $this->render('IntranetBundle:Profesor:reservar.html.twig', array(
            'entity' => $entity,
            'form'   => $form->createView(),
        ));
    }

    private function createCreateReservaForm(Reserva $entity)
    {
        $form = $this->createForm(new ReservaType(), $entity, array(
            'action' => $this->generateUrl('intranet_profesor_reservar_create'),
            'method' => 'POST',
        ));
        $titulo=$this->get("translator")->trans("Reservar");
        $form->add('submit', 'submit', array('label' => $titulo));

        return $form;
    }

    //Se comprueba sólo las reservas del profesor.
    public function ComprobarReservasAction()
    {
        $equipamiento=$this->get('request')->request->get('equipamiento');
        $fecha=$this->get('request')->request->get('fecha');
        $entity = $this->get('security.context')->getToken()->getUser();

        $em = $this->getDoctrine()->getEntityManager();

        $equipamiento=$em->getRepository('BackendBundle:Equipamiento')->findEquipamientoByName($equipamiento);   
        $reserva= $em->getRepository('BackendBundle:Reserva')->findReservasUsuario($entity, $equipamiento, $fecha);
        $horarios= $em->getRepository('BackendBundle:Horario')->findClases();
        $longitud = count($horarios);
        $NoDisponible=array();
        for($i=0; $i<$longitud; $i++)
        {
            $unidades= $em->getRepository('BackendBundle:Reserva')->findComprobarUnidades($horarios[$i],$equipamiento, $fecha);
            if(!((int)$unidades[1] < $equipamiento->getUnidades() && (int)$unidades[1] >= 0)){
                $NoDisponible[]= $em->getRepository('BackendBundle:Reserva')->findReservasUnidades($horarios[$i],$equipamiento, $fecha);
            }       
        }

        if($reserva){
            if($NoDisponible){
                return new JsonResponse(array('data' =>$reserva , 'data2' =>$NoDisponible), 200);
            }
            else{
                return new JsonResponse(array('data' =>$reserva, 'data2' =>null), 200);
            }
        }
        else{
            if($NoDisponible){
                return new JsonResponse(array('data' =>null , 'data2' =>$NoDisponible), 200);
            }
            else{
                return new JsonResponse(array('data' =>null, 'data2' =>null), 200);
            }
        }
    }

    public function reservarInstalacionAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $this->get('security.context')->getToken()->getUser();
        $entities = $em->getRepository('BackendBundle:Equipamiento')->findInstalaciones();
        $clases = $em->getRepository('BackendBundle:Horario')->findClases();
        $tipo="Instalación";

        $inicio =$em->getRepository('BackendBundle:Centro')->findInicioCurso();
        $fin =$em->getRepository('BackendBundle:Centro')->findFinCurso();

        $reserva = new Reserva();
        $form   = $this->createCreateReservaForm($reserva);

        return $this->render('IntranetBundle:Profesor:reservar.html.twig', array(
            'entity' => $entity,
            'tipo' => $tipo,
            'clases'=>$clases,
            'inicio' => $inicio,
            'fin' => $fin,
            'entities' => $entities,                
            'form' => $form->createView(),
        ));
    }

    public function reservarEquipamientoAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $this->get('security.context')->getToken()->getUser();
        $entities = $em->getRepository('BackendBundle:Equipamiento')->findEquipamientos();
        $clases = $em->getRepository('BackendBundle:Horario')->findClases();
        $tipo="Equipamiento";

        $inicio =$em->getRepository('BackendBundle:Centro')->findInicioCurso();
        $fin =$em->getRepository('BackendBundle:Centro')->findFinCurso();

        $reserva = new Reserva();
        $form   = $this->createCreateReservaForm($reserva);

        return $this->render('IntranetBundle:Profesor:reservar.html.twig', array(
            'entity' => $entity,
            'tipo' => $tipo,
            'clases'=>$clases,
            'inicio' => $inicio,
            'fin' => $fin,
            'form' => $form->createView(),
            'entities' => $entities,              

        ));
    }


    public function deleteReservaAction(Request $request, $id)
    {
        $form = $this->createDeleteReservaForm($id);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('BackendBundle:Reserva')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find Reserva entity.');
            }

            $em->remove($entity);
            $em->flush();
        }
        $ms = $this->get('translator')->trans('La reserva ha sido eliminada correctamente.');
        $this->get('session')->getFlashBag()->add('notice',$ms);
        return $this->redirect($this->generateUrl('intranet_profesor_reservas'));
    }

    private function createDeleteReservaForm($id)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('intranet_profesor_reserva_delete', array('id' => $id)))
            ->setMethod('DELETE')
            ->add('submit', 'submit', array('label' => "Eliminar", 'attr' => array('class' => 'btn btn-danger')))
            ->getForm()
        ;
    }

    public function eliminarReservaAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('BackendBundle:Reserva')->find($id);

        $deleteForm = $this->createDeleteReservaForm($id);

        return $this->render('IntranetBundle:Profesor:eliminarReserva.html.twig', array(
            'entity'      => $entity,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    ///////////////////////////////////////////
    //               Tutorias                //
    ///////////////////////////////////////////

    public function tutoriasAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $this->get('security.context')->getToken()->getUser();
        $tutor_grupo= $em->getRepository('BackendBundle:Grupo')->findOneByProfesor($entity);

        $centro =$em->getRepository('BackendBundle:Centro')->findCentro();

        $horario=$centro->getHTutorias();
        $tutorias=$em->getRepository('IntranetBundle:Tutorias')->findTutoriasPendientesProfesor($entity);

       
        $tutoriasNuevas=$em->getRepository('IntranetBundle:Seguimiento')->findNuevasTutorias($entity);
        //Se obtiene las consultas finalizadas.      
        $seguimientos_tutorias_finalizadas= $em->getRepository('IntranetBundle:Seguimiento')->findAntiguasTutoriasContador($entity, 5-count($tutoriasNuevas),"finalizadas");
        //Si hay menos de 5 consultas nuevas ,se añade algunas antiguas hasta llegar a mostrar 5 consultas
        if(count($tutoriasNuevas)<5){
            $seguimientos_tutorias_activas= $em->getRepository('IntranetBundle:Seguimiento')->findAntiguasTutoriasContador($entity, 5-count($tutoriasNuevas),"activas");
        }
        else{
            $seguimientos_tutorias_activas=null;
        }
                   
        return $this->render('IntranetBundle:Profesor:tutorias.html.twig', array(
            'entity' => $entity, 
            'tutor_grupo' => $tutor_grupo,
            'h_tutorias' => $horario,
            'tutorias' => $tutorias,
            'tutoriasNuevas' => $tutoriasNuevas,
            'seguimientos_tutorias_activas'=>$seguimientos_tutorias_activas,
            'seguimientos_tutorias_finalizadas'=>$seguimientos_tutorias_finalizadas
            ));
    }

    public function seguimientoTutoriaAction(Request $request, $num)
    {
        $em = $this->getDoctrine()->getManager();
        $entity = $this->get('security.context')->getToken()->getUser();
        $tutor_grupo= $em->getRepository('BackendBundle:Grupo')->findOneByProfesor($entity);
        $seguimiento=$em->getRepository('IntranetBundle:Seguimiento')->findOneById($num);

        $this->comprobarSeguimiento($seguimiento);

        $respuestas=$em->getRepository('IntranetBundle:Seguimiento')->findRespuestasTutorias($num);

        //Se comprueba si la consulta tiene tutoria asignada.
        $tutoria=$em->getRepository('IntranetBundle:Tutorias')->findTutoriaConsulta($num);


        return $this->render('IntranetBundle:Profesor:seguimiento_tutoria.html.twig', array(
            'entity' => $entity, 
            'seguimiento'=> $seguimiento,
            'respuestas' => $respuestas,
            'tutoria' =>$tutoria,
            'grupo' => $tutor_grupo
            ));
    }


    public function comprobarTutoriasNuevasAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity= $em->getRepository('BackendBundle:Profesor')->findOneById($id);
        $num=0;

        if($entity->getAccesoTutorias()){
            $seguimientos =$em->getRepository('IntranetBundle:Seguimiento')->findNuevasTutoriasProfesor($entity->getAccesoTutorias(),$entity);
        }
        else{
            $seguimientos =$em->getRepository('IntranetBundle:Seguimiento')->findNuevasTutoriasInicioProfesor($entity);
        }
        //Si hay consultas de tutorias nuevas se añade los id a la tabla Avisos.
        if($seguimientos){
            foreach($seguimientos as $seguimiento){
                if($seguimiento->getSeguimiento() == null){
                    $existencia=$em->getRepository('IntranetBundle:Avisos')->findExistenciaAviso($entity->getId(),null, "Profesor",$seguimiento->getId(), "Tutoria" );
                    
                    if(!$existencia){
                        $aviso = new Avisos();
                        $aviso->setIdUsuario($entity->getId());
                        $aviso->setIdResponsable(null);
                        $aviso->setTipoUsuario("Profesor");
                        $aviso->setIdAviso($seguimiento->getId());
                        $aviso->setTipoAviso("Tutoria");
                        $em->persist($aviso);
                    }
                }
                else{
                    $existencia=$em->getRepository('IntranetBundle:Avisos')->findExistenciaAviso($entity->getId(),null, "Profesor",$seguimiento->getSeguimiento(), "Tutoria" );
                    if(!$existencia){
                        $aviso = new Avisos();
                        $aviso->setIdUsuario($entity->getId());
                        $aviso->setIdResponsable(null);
                        $aviso->setTipoUsuario("Profesor");
                        $aviso->setIdAviso($seguimiento->getSeguimiento());
                        $aviso->setTipoAviso("Tutoria");
                        $em->persist($aviso);
                    }
                } 
            }
            //Se actualiza la fecha del último acceso a tutorias.
            $entity->setAccesoTutorias(new \DateTime("now"));
            $em->persist($entity);
            $em->flush();
        }
        //Se obtiene el número de consultas de tutorias nuevas.
        $avisos =$em->getRepository('IntranetBundle:Avisos')->findAvisos($entity,NULL, "Profesor", "Tutoria");
        $num=count($avisos);

        return new JsonResponse(array(
            'num'=> $num,
            'id' => $id,
            'success' => true), 200);
    }

    public function CargarSeguimientosTutoriaAction(Request $request, $id, $tipo)
    {
        $em = $this->getDoctrine()->getManager();
        $entity = $this->get('security.context')->getToken()->getUser();
        
        $seguimientos= $em->getRepository('IntranetBundle:Seguimiento')->findCargaSeguimientosTutoriasProfesor($entity, $id, $tipo);

        return new JsonResponse(array(
            'seguimientos' => $seguimientos,
            'seguimientosNuevos' => null,
            'html' => $this->renderView('IntranetBundle:Profesor:lista_seguimiento.html.twig', array(
            'seguimientos' => $seguimientos, 'seguimientosNuevos' => null, 'entity'=>$entity, 'tipo'=>'tutorias')),
            'success' => true
            ), 200);
    }

    public function CargarNuevosSeguimientosTutoriaAction(Request $request, $fecha)
    {
        $em = $this->getDoctrine()->getManager();
        $entity = $this->get('security.context')->getToken()->getUser();

        $seguimientosNuevos=$em->getRepository('IntranetBundle:Seguimiento')->findCargaSeguimientosNuevosTutoriasProfesor($fecha, $entity);

        if(count($seguimientosNuevos)<5){
            $seguimientos= $em->getRepository('IntranetBundle:Seguimiento')->findCargaSeguimientosInicialTutoriasProfesor($entity, 5-count($seguimientosNuevos));
        }
        else{
            $seguimientos=null;
        }
        return new JsonResponse(array(
            'seguimientos' => $seguimientos,
            'seguimientosNuevos' => $seguimientosNuevos,
            'html' => $this->renderView('IntranetBundle:Profesor:lista_seguimiento.html.twig', array(
            'seguimientos' => $seguimientos, 'seguimientosNuevos' => $seguimientosNuevos, 'entity'=>$entity, 'tipo'=>'tutorias')),
            'success' => true
            ), 200);
    }

    public function CancelarTutoriaAction($num)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $this->get('security.context')->getToken()->getUser();
        $tutoria=$em->getRepository('IntranetBundle:Tutorias')->findOneById($num);
        $alumno=$tutoria->getAlumno();
        $profesor=$tutoria->getProfesor();

        //Se añade un comentario para mostrar como aviso del sistema (FechaActualizada == null)
        $seguimiento = new Seguimiento();
        $seguimiento->setProfesor($alumno->getGrupo()->getProfesor());
        $seguimiento->setAlumno($alumno);
        $seguimiento->setResponsable($tutoria->getResponsable());
        $seguimiento->setAsignatura(null);
        $seguimiento->setGrupo($alumno->getGrupo());
        $seguimiento->setTipo(0);
        $seguimiento->setTipoUser(1);
        //$descripcion = $this->get('translator')->trans("El responsable ha cancelado la petición de tutoría.");
        //$seguimiento->setDescripcion($descripcion);
        if($profesor->getSexo() == "Masculino"){
            $seguimiento->setDescripcion("El tutor ha cancelado la petición de tutoría.");
        }else{
            $seguimiento->setDescripcion("La tutora ha cancelado la petición de tutoría.");
        }   
        $seguimiento->setFecha(new \DateTime("now"));
        $seguimiento->setFechaActualizada(null);
        $seguimiento->setSeguimiento($tutoria->getSeguimiento());
        $seguimiento->setRespuesta(0);
        $seguimiento->setFechaTerminada(null);

        $em->persist($seguimiento);
        $em->flush();

        //Se modifica el valor de respuesta en la consulta principal
        $consulta_principal=$tutoria->getSeguimiento();
        $consulta_principal->setRespuesta(1);
        $em->persist($consulta_principal);
        $em->flush();

        //Se elimina la tutoría pendiente.
        $em->remove($tutoria);
        $em->flush();

        $ms = $this->get('translator')->trans('La petición de tutoría ha sido cancelada.');
        $this->get('session')->getFlashBag()->add('notice',$ms);

        return $this->redirect($this->generateUrl('intranet_profesor_seguimiento_tutoria', array('num'=>$tutoria->getSeguimiento())));

    }

    ///////////////////////////////////////////
    //               Noticias                //
    ///////////////////////////////////////////

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