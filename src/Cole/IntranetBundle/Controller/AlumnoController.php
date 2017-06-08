<?php

namespace Cole\IntranetBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;

use Cole\BackendBundle\Entity\Alumno;
use Cole\ColeBundle\Entity\Noticias;
use Cole\IntranetBundle\Entity\Avisos;


use Cole\BackendBundle\Form\AlumnoIntranetType;
use Symfony\Component\HttpFoundation\Response;


class AlumnoController extends Controller
{

    protected function comprobarHijo($id) 
    {
    	$em = $this->getDoctrine()->getManager();
    	$entity= $em->getRepository('BackendBundle:Alumno')->findOneById($id);
    	if(!$entity){
			throw $this->createNotFoundException('Unable to find Role entity.');
    	}
		$user=$this->get('security.context')->getToken()->getUser();
    	if($entity->getActivo()==1 && ($entity->getResponsable1()==$user || $entity->getResponsable2()==$user)){
    	}
    	else{
			throw $this->createNotFoundException('Unable to find Role entity.');
    	}
    }

    public function indexAction($id)
    {
    	$this->comprobarHijo($id);

    	$em = $this->getDoctrine()->getManager();

		$entity= $em->getRepository('BackendBundle:Alumno')->findOneById($id);


		return $this->render('IntranetBundle:Alumno:index.html.twig', array(
            'entity' => $entity,
            'id'=>$entity->getId()));
    }

    private function createEditAlumnoForm(Alumno $entity)
    {
        $form = $this->createForm(new AlumnoIntranetType(), $entity, array(
            'action' => $this->generateUrl('datos_personales_alumno', array('id' => $entity->getId())),
            'method' => 'PUT',
        ));

        $form->add('submit', 'submit', array('label' => 'Guardar cambios'));

        return $form;
    }

    private function createDeleteForm($id)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('alumno_delete', array('id' => $id)))
            ->setMethod('DELETE')
            ->add('submit', 'submit', array('label' => 'Delete'))
            ->getForm()
        ;
    }

    public function perfilAction($id)
    {
        $this->comprobarHijo($id);

        $em = $this->getDoctrine()->getManager();
        
        $entity= $em->getRepository('BackendBundle:Alumno')->findOneById($id);

        $editForm = $this->createEditAlumnoForm($entity);

        $deleteForm = $this->createDeleteForm($entity->getId());

        return $this->render('IntranetBundle:Alumno:perfil.html.twig', array(
            'entity' => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    public function DatosPersonalesAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('BackendBundle:Alumno')->find($id);
        $editForm = $this->createEditAlumnoForm($entity);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Alumno entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        $editForm->handleRequest($request);

        if ($editForm->isValid()) {

            $em->persist($entity);

            $em->flush();
            $ms = $this->get('translator')->trans('Se han guardado los cambios.');

            $this->get('session')->getFlashBag()->add('notice',$ms);

            return $this->redirect($this->generateUrl('intranet_alumno_perfil', array('id'=>$entity->getId())));
        }


        return $this->render('IntranetBundle:Alumno:perfil.html.twig', array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    public function cursoAction($id)
    {
        $this->comprobarHijo($id);

        $em = $this->getDoctrine()->getManager();

        $entity= $em->getRepository('BackendBundle:Alumno')->findOneById($id);
        if($entity->getGrupo()){
            $grupo= $em->getRepository('BackendBundle:Grupo')->findOneById($entity->getGrupo());
            $tutor= $em->getRepository('BackendBundle:Profesor')->findOneById($grupo->getProfesor());
        }
        else{
            $tutor=null;
        }
        //Se comprueba si está asignado todo el horario del grupo para mostrarlo o no.
        $clases=$em->getRepository('BackendBundle:Horario')->findClases();

        $no_opcionales = $em->getRepository('BackendBundle:Imparte')->findNoOpcionalesConHorario($entity->getGrupo());
        $opcionales=$em->getRepository('BackendBundle:Imparte')->findOpcionalesConHorario($entity->getGrupo());
        $num_opcionales=$em->getRepository('BackendBundle:Imparte')->findOpcionalesSinRepetir($entity->getGrupo());
        if($opcionales){
            //Las asignaciones de asignaturas optativas se dividen entre el número de optativas, para obtener el número de módulos asignados.
            if(count($no_opcionales)+(count($opcionales)/count($num_opcionales))==count($clases)*5){
                $numAsigHorarios=1;
            }
            else{
                $numAsigHorarios=0;
            }
        }else{
            $numAsigHorarios=0;
        }

        $asignaciones_profesores=$em->getRepository('BackendBundle:Imparte')->findAsignacionesProfesores($entity->getGrupo());
        $profesores_grupo=$em->getRepository('BackendBundle:Imparte')->findProfesoresGrupoSinRepetir($entity->getGrupo());
        //Se comprueba si el tutor imparte alguna asignatura en el grupo o sólo tutorias(para lista de profesores/asignaturas).
        if($tutor){
            $asignaciones_tutor=$em->getRepository('BackendBundle:Imparte')->findTutorGrupoSinRepetir($tutor,$entity->getGrupo());
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

        return $this->render('IntranetBundle:Alumno:curso.html.twig', array(
            'entity' => $entity, 
            'tutor' => $tutor,
            'numAsigHorarios'=>$numAsigHorarios,
            'profesores_grupo'=>$profesores_grupo,
            'asignaturas_tutor' => $asignaturas_tutor,
            'asignaciones_profesores'=>$asignaciones_profesores));
    }

    public function HorariosGruposAction($id, $id_alumno,$num)
    {
        $em = $this->getDoctrine()->getManager();
        $grupo = $em->getRepository('BackendBundle:Grupo')->findOneById($id);
        $entity = $em->getRepository('BackendBundle:Horario')->findAll();
        $alumno=$em->getRepository('BackendBundle:Alumno')->findOneById($id_alumno);
        if (!$alumno) {
            throw $this->createNotFoundException('Unable to find Alumno.');
        }
        $imparte = $em->getRepository('BackendBundle:Imparte')->findByGrupo($id);

        $asignaciones = $em->getRepository('BackendBundle:Imparte')->findByGrupoConHorario($grupo);
        $clases=$em->getRepository('BackendBundle:Horario')->findClases();
        if(count($asignaciones)==count($clases)){
            $numAsigHorarios=1;
        }
        else{
            $numAsigHorarios=0;
        }

        $asignaciones = $em->getRepository('BackendBundle:AsignaturasCursos')->findByCurso($grupo->getCurso());
        if($asignaciones){
            $numAsigCurso=1;
        }
        else{
            $numAsigCurso=0;
        }
        $asignaturas=$em->getRepository('BackendBundle:AsignaturasCursos')->findByCurso($grupo->getCurso());
        if(count($asignaturas)==count($imparte)){
            $completo=1;
        }
        else{
            $completo=0;
        }

        $imparte= $em->getRepository('BackendBundle:Imparte')->findAsignacionesNoOpcionales($grupo);

        $array=[];
        foreach($imparte as $registro){

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
           
            $array[$horario."-".$dia."-".$registro->getAsignatura()->getAsignatura()->getNombre()."-".$registro->getAsignatura()->getAsignatura()->getAbreviatura()."-".$registro->getAsignatura()->getAsignatura()->getColor()."-".$registro->getGrupo()->getId()]=$registro->getAsignatura()->getId();
        }

        $imparte= $em->getRepository('BackendBundle:Imparte')->findAsignacionesOpcionales($grupo);

        $array_op=[];
        foreach($imparte as $registro){

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
           
            $array_op[$horario."-".$dia."-".$registro->getAsignatura()->getAsignatura()->getNombre()."-".$registro->getAsignatura()->getAsignatura()->getAbreviatura()."-".$registro->getAsignatura()->getAsignatura()->getColor()."-".$registro->getGrupo()->getId()]=$registro->getAsignatura()->getId();
        }
        //Asignatura optativa del alumno
        if($alumno->getOptativa()){
            $optativa=$alumno->getOptativa()->getId();
        }
        else{
            $optativa=null;
        }

        return $this->render('IntranetBundle:Alumno:datos_horarios.html.twig', array(
            'imparte' => $array,
            'imparte_op' => $array_op,
            'numAsigHorarios' => $numAsigHorarios,
            'numAsigCurso' => $numAsigCurso,
            'completo' => $completo,
            'entity' => $entity,
            'num' => $num,
            'alumno'=> $optativa ));
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

    public function HorarioPdfAction($id)
    {
       $em = $this->getDoctrine()->getManager();

        $alumno= $em->getRepository('BackendBundle:Alumno')->findOneById($id);

        $grupo= $alumno->getGrupo();

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

        $html = $this->renderView('IntranetBundle:Alumno:horario_de_clase.html.twig', array(
            'alumno' => $alumno,
            'entities' => $entities,
            'inicio' => $inicio,
            'fin' => $fin,
            'horarios' => $horarios,
            'aula' => $grupo->getAula(),
            'grupo' => $grupo->getCurso()->getCurso()." ".$grupo->getCurso()->getNivel()." - Grupo ".$grupo->getLetra(),
            'imparte' => $array,
            'imparte_op' => $array_op,
            'profesor' => $grupo->getProfesor(),
        ));
        $options = [
            'margin-top'    => 3,
            'margin-right'  => 8,
            'margin-bottom' => 3,
            'margin-left'   => 8,
            //Opciones para orientación horizontal.
            'orientation'=>'Landscape', 
        ];
        $iniciales=substr($alumno->getNombre(), 0, 1).substr($alumno->getApellido1(), 0, 1).substr($alumno->getApellido2(), 0, 1);

        return new Response(
            $this->get('knp_snappy.pdf')->getOutputFromHtml($html,$options),
            200,
            array(
                'Content-Type'        => 'application/pdf',
                'Content-Disposition' => 'attachment; filename="Horario_Alumno_'.$iniciales.'.pdf"'
            )
        );
    }


    public function seguimientosAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();
        $entity= $em->getRepository('BackendBundle:Alumno')->findOneById($id);
        $seguimientosNuevos=$em->getRepository('IntranetBundle:Seguimiento')->findSeguimientosActualizadosAlumno($entity, $entity->getGrupo());
        if(count($seguimientosNuevos)<5){
            $seguimientos= $em->getRepository('IntranetBundle:Seguimiento')->findAntiguosSeguimientosContadorAlumno($entity, $entity->getGrupo(), 5-count($seguimientosNuevos));
        }
        else{
            $seguimientos=null;
        }

        return $this->render('IntranetBundle:Alumno:seguimientos.html.twig', array(
            'entity' => $entity, 
            'seguimientosNuevos' => $seguimientosNuevos,
            'seguimientos'=> $seguimientos,
        ));
    }

    public function seguimientoAction(Request $request, $id, $num)
    {
        $em = $this->getDoctrine()->getManager();
        $entity= $em->getRepository('BackendBundle:Alumno')->findOneById($id);
        $seguimiento=$em->getRepository('IntranetBundle:Seguimiento')->findOneById($num);
        $respuestas=$em->getRepository('IntranetBundle:Seguimiento')->findRespuestas($num);

        return $this->render('IntranetBundle:Alumno:seguimiento.html.twig', array(
            'entity' => $entity, 
            'seguimiento'=> $seguimiento,
            'respuestas' => $respuestas,
            ));
    }

    public function CargarNuevosSeguimientosAction(Request $request, $id, $fecha)
    {
        $em = $this->getDoctrine()->getManager();
        $entity= $em->getRepository('BackendBundle:Alumno')->findOneById($id);

        $seguimientosNuevos=$em->getRepository('IntranetBundle:Seguimiento')->findCargaSeguimientosNuevosAlumno($fecha, $entity, $entity->getGrupo());

        if(count($seguimientosNuevos)<5){
            $seguimientos= $em->getRepository('IntranetBundle:Seguimiento')->findCargaSeguimientosInicialAlumno($entity, $entity->getGrupo(), 5-count($seguimientosNuevos));
        }
        else{
            $seguimientos=null;
        }
        return new JsonResponse(array(
            'seguimientos' => $seguimientos,
            'seguimientosNuevos' => $seguimientosNuevos,
            'html' => $this->renderView('IntranetBundle:Alumno:lista_seguimiento.html.twig', array(
            'seguimientos' => $seguimientos, 'seguimientosNuevos' => $seguimientosNuevos, 'entity'=>$entity)),
            'success' => true
            ), 200);
    }

    public function CargarSeguimientosAction(Request $request, $id, $iden)
    {
        $em = $this->getDoctrine()->getManager();
        $entity= $em->getRepository('BackendBundle:Alumno')->findOneById($id);
        
        $seguimientos= $em->getRepository('IntranetBundle:Seguimiento')->findCargaSeguimientosAlumno($entity, $iden, $entity->getGrupo());

        return new JsonResponse(array(
            'seguimientos' => $seguimientos,
            'seguimientosNuevos' => null,
            'html' => $this->renderView('IntranetBundle:Alumno:lista_seguimiento.html.twig', array(
            'seguimientos' => $seguimientos, 'seguimientosNuevos' => null, 'entity'=>$entity)),
            'success' => true
            ), 200);
    }

    public function comprobarSeguimientosNuevosAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity= $em->getRepository('BackendBundle:Alumno')->findOneById($id);
        $num=0;

        if($entity->getCurso() && $entity->getGrupo()){
            if($entity->getAccesoSeguimientos()){
                $seguimientos =$em->getRepository('IntranetBundle:Seguimiento')->findNuevosSeguimientosAlumno($entity->getAccesoSeguimientos(),$entity, $entity->getGrupo());
            }
            else{
                $seguimientos =$em->getRepository('IntranetBundle:Seguimiento')->findNuevosSeguimientosInicioAlumno($entity, $entity->getGrupo());
            }

            //Si hay seguimientos nuevos se añade los id a la tabla Avisos.
            if($seguimientos){
                foreach($seguimientos as $seguimiento){

                    if($seguimiento->getSeguimiento() == null){
                        $existencia=$em->getRepository('IntranetBundle:Avisos')->findExistenciaAviso($entity->getId(), "Alumno",$seguimiento->getId(), "Seguimiento" );
                        if(!$existencia){
                            $aviso = new Avisos();
                            $aviso->setIdUsuario($entity->getId());
                            $aviso->setTipoUsuario("Alumno");
                            $aviso->setIdAviso($seguimiento->getId());
                            $aviso->setTipoAviso("Seguimiento");
                            $em->persist($aviso);
                        }
                    }
                    else{
                        $existencia=$em->getRepository('IntranetBundle:Avisos')->findExistenciaAviso($entity->getId(), "Alumno",$seguimiento->getSeguimiento(), "Seguimiento" );
                        if(!$existencia){
                            $aviso = new Avisos();
                            $aviso->setIdUsuario($entity->getId());
                            $aviso->setTipoUsuario("Alumno");
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
            $avisos =$em->getRepository('IntranetBundle:Avisos')->findAvisosAlumno($entity, "Alumno", "Seguimiento");
            $num=count($avisos);
        }

        return new JsonResponse(array(
            'num'=> $num,
            'id' => $id,
            'success' => true), 200);
    }

    public function noticiasAction(Request $request, $id)
    {
        $this->comprobarHijo($id);

        $em = $this->getDoctrine()->getManager();

        $entity= $em->getRepository('BackendBundle:Alumno')->findOneById($id);

        if($entity->getCurso()->getNivel()=="Primaria"){
            $noticias= $em->getRepository('ColeBundle:Noticias')->findBy(array('categoria'=>'primaria'), array('fecha'=>'DESC'));
        }
        else{
            $noticias= $em->getRepository('ColeBundle:Noticias')->findBy(array('categoria'=>'infantil'), array('fecha'=>'DESC'));
        }
        $paginator  = $this->get('knp_paginator');
        $pagination = $paginator->paginate(
            $noticias, /* query NOT result */
            $request->query->getInt('page', 1)/*page number*/,
            6/*limit per page*/
        );
/*
        if($entity->getAccesoNoticias()){
            if($entity->getCurso()->getNivel()=="Primaria"){
                $noticias_nuevas =$em->getRepository('ColeBundle:Noticias')->findNuevasNoticias($entity->getAccesoNoticias(),"primaria");
            }
            else{
                $noticias_nuevas =$em->getRepository('ColeBundle:Noticias')->findNuevasNoticias($entity->getAccesoNoticias(),"infantil");
            }
        }
        else{
            if($entity->getCurso()->getNivel()=="Primaria"){
                $noticias_nuevas= $em->getRepository('ColeBundle:Noticias')->findBy(array('categoria'=>'primaria'), array('fecha'=>'DESC'));
            }
            else{
                $noticias_nuevas= $em->getRepository('ColeBundle:Noticias')->findBy(array('categoria'=>'infantil'), array('fecha'=>'DESC'));
            }
        }

        $array=[];
        if($noticias_nuevas){
            foreach($noticias_nuevas as $noticia){
                $array[$noticia->getId()]=$noticia->getTitulo();
            }
        }
*/
        //Se obtiene los id de las noticias nuevas mediante el string del campo NoticiasNuevas en alumno.
        $noticias_nuevas=$entity->getNoticiasNuevas();
        $id_noticias = explode("|", $noticias_nuevas);
        $array=[];
        foreach($id_noticias as $id){
            if($id!=""){
                $array[$id]=$id;
            }
        }

        //Se actualiza la fecha del último acceso a noticias.
        $entity->setAccesoNoticias(new \DateTime("now"));
        $em->persist($entity);
        $em->flush();

        return $this->render('IntranetBundle:Alumno:noticias.html.twig', array(
            'entity' => $entity, 
            'noticias'=> $noticias,
            'noticias_nuevas' => $array,
            'pagination'=> $pagination));
    }

    public function noticiaAction($id,$num)
    {
        $this->comprobarHijo($id);

        $em = $this->getDoctrine()->getManager();

        $entity= $em->getRepository('BackendBundle:Alumno')->findOneById($id);

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
        //Se elimina el id de la noticia mostrada del string en el campo NoticiasNuevas del alumno.
        $string=$entity->getNoticiasNuevas();
        $new_string= str_replace("|".$num."|", "|", $string);
        $entity->setNoticiasNuevas($new_string);
        $em->persist($entity);
        $em->flush();

        return $this->render('IntranetBundle:Alumno:noticia.html.twig', array(
            'entity' => $entity, 
            'noticia'=> $noticia,
            'imagenes' => $imagenes));
    }



    public function comprobarNoticiasNuevasAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity= $em->getRepository('BackendBundle:Alumno')->findOneById($id);
        $num=0;

        if($entity->getCurso()){
            if($entity->getAccesoNoticias()){
                if($entity->getCurso()->getNivel()=="Primaria"){
                    $noticias =$em->getRepository('ColeBundle:Noticias')->findNuevasNoticias($entity->getAccesoNoticias(),"primaria");
                }
                else{
                    $noticias =$em->getRepository('ColeBundle:Noticias')->findNuevasNoticias($entity->getAccesoNoticias(),"infantil");
                }
            }
            else{
                if($entity->getCurso()->getNivel()=="Primaria"){
                    $noticias= $em->getRepository('ColeBundle:Noticias')->findBy(array('categoria'=>'primaria'), array('fecha'=>'DESC'));
                }
                else{
                    $noticias= $em->getRepository('ColeBundle:Noticias')->findBy(array('categoria'=>'infantil'), array('fecha'=>'DESC'));
                }
            }

            //Si hay noticias nuevas se añade los id en el campo NoticiasNuevas del alumno.
            if($noticias){
                if($entity->getNoticiasNuevas()){
                    $string=$entity->getNoticiasNuevas();
                }
                else{
                    $string="|";
                }
                
                foreach($noticias as $noticia){
                    $string=$string.$noticia->getId()."|";
                }
                //Se actualiza la fecha del último acceso a noticias.
                $entity->setAccesoNoticias(new \DateTime("now"));
                $entity->setNoticiasNuevas($string);
                $em->persist($entity);
                $em->flush();
            }
            //Se obtiene el número de noticias nuevas mediante los id del campo NoticiasNuevas del alumno.
            $noticias_nuevas=$entity->getNoticiasNuevas();
            $id_noticias = explode("|", $noticias_nuevas);

            foreach($id_noticias as $noticia){
                if($noticia!=""){
                    $num++;
                }
            }   
        }

        return new JsonResponse(array(
            'num'=> $num,
            'id' => $id,
            'success' => true), 200);
    }








}