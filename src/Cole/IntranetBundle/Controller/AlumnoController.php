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
        
        $titulo=$this->get("translator")->trans("Guardar cambios");
        $form->add('submit', 'submit', array('label' => $titulo));
        return $form;
    }
    private function createCreateForm(Reserva $entity)
    {
        $form = $this->createForm(new ReservaType(), $entity, array(
            'action' => $this->generateUrl('reserva_create'),
            'method' => 'POST',
        ));

        $form->add('submit', 'submit', array('label' => 'Create'));

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
        $this->comprobarHijo($id);
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

    ///////////////////////////////////////////
    //             Cursos actual             //
    ///////////////////////////////////////////

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
        $this->comprobarHijo($id);
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
        $this->comprobarHijo($id);
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

    ///////////////////////////////////////////
    //              Seguimiento              //
    ///////////////////////////////////////////

    public function seguimientosAction(Request $request, $id)
    {
        $this->comprobarHijo($id);
        $em = $this->getDoctrine()->getManager();

        $entity= $em->getRepository('BackendBundle:Alumno')->findOneById($id);
        $responsable = $this->get('security.context')->getToken()->getUser();

        $seguimientosNuevos=$em->getRepository('IntranetBundle:Seguimiento')->findSeguimientosActualizadosAlumno($entity, $responsable->getId(), $entity->getGrupo());
        if(count($seguimientosNuevos)<5){
            $seguimientos= $em->getRepository('IntranetBundle:Seguimiento')->findAntiguosSeguimientosContadorAlumno($entity, $responsable->getId(), $entity->getGrupo(), 5-count($seguimientosNuevos));
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
        $this->comprobarHijo($id);
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
        $responsable = $this->get('security.context')->getToken()->getUser();

        $seguimientosNuevos=$em->getRepository('IntranetBundle:Seguimiento')->findCargaSeguimientosNuevosAlumno($fecha, $entity, $responsable->getId(), $entity->getGrupo());

        if(count($seguimientosNuevos)<5){
            $seguimientos= $em->getRepository('IntranetBundle:Seguimiento')->findCargaSeguimientosInicialAlumno($entity, $responsable->getId(), $entity->getGrupo(), 5-count($seguimientosNuevos));
        }
        else{
            $seguimientos=null;
        }
        return new JsonResponse(array(
            'seguimientos' => $seguimientos,
            'seguimientosNuevos' => $seguimientosNuevos,
            'html' => $this->renderView('IntranetBundle:Alumno:lista_seguimiento.html.twig', array(
            'seguimientos' => $seguimientos, 'seguimientosNuevos' => $seguimientosNuevos, 'entity'=>$entity, 'tipo' =>'seguimientos')),
            'success' => true
            ), 200);
    }

    public function CargarSeguimientosAction(Request $request, $id, $iden)
    {
        $em = $this->getDoctrine()->getManager();
        $entity= $em->getRepository('BackendBundle:Alumno')->findOneById($id);
        $responsable = $this->get('security.context')->getToken()->getUser();

        $seguimientos= $em->getRepository('IntranetBundle:Seguimiento')->findCargaSeguimientosAlumno($entity, $responsable->getId(), $iden, $entity->getGrupo());

        return new JsonResponse(array(
            'seguimientos' => $seguimientos,
            'seguimientosNuevos' => null,
            'html' => $this->renderView('IntranetBundle:Alumno:lista_seguimiento.html.twig', array(
            'seguimientos' => $seguimientos, 'seguimientosNuevos' => null, 'entity'=>$entity, 'tipo' =>'seguimientos')),
            'success' => true
            ), 200);
    }

    public function comprobarSeguimientosNuevosAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity= $em->getRepository('BackendBundle:Alumno')->findOneById($id);
        $responsable = $this->get('security.context')->getToken()->getUser();
        if($entity->getResponsable1()==$responsable){
            $acceso=$entity->getAccesoSeguimientosResponsable1();
        }
        else{
            $acceso=$entity->getAccesoSeguimientosResponsable2();
        }

        $num=0;

        if($entity->getCurso() && $entity->getGrupo()){
            if($acceso){
                $seguimientos =$em->getRepository('IntranetBundle:Seguimiento')->findNuevosSeguimientosAlumno($acceso,$entity, $entity->getGrupo());
            }
            else{
                $seguimientos =$em->getRepository('IntranetBundle:Seguimiento')->findNuevosSeguimientosInicioAlumno($entity, $entity->getGrupo());
            }

            //Si hay seguimientos nuevos se añade los id a la tabla Avisos.
            if($seguimientos){
                foreach($seguimientos as $seguimiento){
                    //Se añade el id del responsable en la tabla de avisos, para que ambos responsable tenga sus propios avisos.
                    if($seguimiento->getSeguimiento() == null){
                        $existencia=$em->getRepository('IntranetBundle:Avisos')->findExistenciaAviso($entity->getId(),$responsable->getId(), "Alumno",$seguimiento->getId(), "Seguimiento" );
                        if(!$existencia){
                            $aviso = new Avisos();
                            $aviso->setIdUsuario($entity->getId());
                            $aviso->setIdResponsable($responsable->getId());
                            $aviso->setTipoUsuario("Alumno");
                            $aviso->setIdAviso($seguimiento->getId());
                            $aviso->setTipoAviso("Seguimiento");
                            $em->persist($aviso);
                        }
                    }
                    else{
                        $existencia=$em->getRepository('IntranetBundle:Avisos')->findExistenciaAviso($entity->getId(),$responsable->getId(), "Alumno",$seguimiento->getSeguimiento(), "Seguimiento" );
                        if(!$existencia){
                            $aviso = new Avisos();
                            $aviso->setIdUsuario($entity->getId());
                            $aviso->setIdResponsable($responsable->getId());
                            $aviso->setTipoUsuario("Alumno");
                            $aviso->setIdAviso($seguimiento->getSeguimiento());
                            $aviso->setTipoAviso("Seguimiento");
                            $em->persist($aviso);
                        }
                    } 
                }

                //Se actualiza la fecha del último acceso del responsable correspondiente.
                if($entity->getResponsable1()==$responsable){
                    $entity->setAccesoSeguimientosResponsable1(new \DateTime("now"));
                }
                else{
                    $entity->setAccesoSeguimientosResponsable2(new \DateTime("now"));
                }
                $em->persist($entity);
                $em->flush();
            }
            //Se obtiene el número de seguimientos nuevos.
            $avisos =$em->getRepository('IntranetBundle:Avisos')->findAvisos($entity, $responsable, "Alumno", "Seguimiento");
            $num=count($avisos);
        }

        return new JsonResponse(array(
            'num'=> $num,
            'id' => $id,
            'success' => true), 200);
    }

    ///////////////////////////////////////////
    //               Tutorias                //
    ///////////////////////////////////////////

    public function tutoriasAction(Request $request, $id)
    {
        $this->comprobarHijo($id);
        $em = $this->getDoctrine()->getManager();

        $entity= $em->getRepository('BackendBundle:Alumno')->findOneById($id);
        $responsable = $this->get('security.context')->getToken()->getUser();

        $grupo= $entity->getGrupo();

        if($grupo){
            $tutor=$grupo->getProfesor();
        }else{
            $tutor=null;
        }
        $centro =$em->getRepository('BackendBundle:Centro')->findCentro();

        $horario=$centro->getHTutorias();
        $tutorias=$em->getRepository('IntranetBundle:Tutorias')->findTutoriasPendientesAlumno($entity,$responsable);

       
        $tutoriasNuevas=$em->getRepository('IntranetBundle:Seguimiento')->findNuevasTutoriasAlumno($entity,$responsable);

        if(count($tutoriasNuevas)<5){
            $seguimientos_tutorias= $em->getRepository('IntranetBundle:Seguimiento')->findAntiguasTutoriasContadorAlumno($entity, $responsable->getId(), 5-count($tutoriasNuevas));
        }
        else{
            $seguimientos_tutorias=null;
        }
                   
        return $this->render('IntranetBundle:Alumno:tutorias.html.twig', array(
            'entity' => $entity, 
            'grupo' => $grupo,
            'tutor' =>$tutor,
            'h_tutorias' => $horario,
            'tutorias' => $tutorias,
            'tutoriasNuevas' => $tutoriasNuevas,
            'seguimientos_tutorias'=>$seguimientos_tutorias
            ));
    }


    public function seguimientoTutoriaAction(Request $request, $id, $num)
    {
        $this->comprobarHijo($id);
        $em = $this->getDoctrine()->getManager();

        $entity= $em->getRepository('BackendBundle:Alumno')->findOneById($id);

        $seguimiento=$em->getRepository('IntranetBundle:Seguimiento')->findOneById($num);
        $respuestas=$em->getRepository('IntranetBundle:Seguimiento')->findRespuestasTutorias($num);

        //comprobar si tiene tutoria asignada o pendiente de confirmar para mostrarlo

        return $this->render('IntranetBundle:Alumno:seguimiento_tutoria.html.twig', array(
            'entity' => $entity, 
            'seguimiento'=> $seguimiento,
            'respuestas' => $respuestas,
            ));
    }


    public function CargarNuevosSeguimientosTutoriaAction(Request $request, $id, $fecha)
    {
        $em = $this->getDoctrine()->getManager();
        $entity= $em->getRepository('BackendBundle:Alumno')->findOneById($id);
        $responsable = $this->get('security.context')->getToken()->getUser();

        $seguimientosNuevos=$em->getRepository('IntranetBundle:Seguimiento')->findCargaSeguimientosNuevosTutoriasAlumno($fecha, $entity, $responsable->getId(), $entity->getGrupo());

        if(count($seguimientosNuevos)<5){
            $seguimientos= $em->getRepository('IntranetBundle:Seguimiento')->findCargaSeguimientosInicialTutoriasAlumno($entity, $responsable->getId(), $entity->getGrupo(), 5-count($seguimientosNuevos));
        }
        else{
            $seguimientos=null;
        }
        return new JsonResponse(array(
            'seguimientos' => $seguimientos,
            'seguimientosNuevos' => $seguimientosNuevos,
            'html' => $this->renderView('IntranetBundle:Alumno:lista_seguimiento.html.twig', array(
            'seguimientos' => $seguimientos, 'seguimientosNuevos' => $seguimientosNuevos, 'entity'=>$entity, 'tipo' =>'tutorias')),
            'success' => true
            ), 200);
    }


    public function CargarSeguimientosTutoriaAction(Request $request, $id, $iden)
    {
        $em = $this->getDoctrine()->getManager();
        $entity= $em->getRepository('BackendBundle:Alumno')->findOneById($id);
        $responsable = $this->get('security.context')->getToken()->getUser();

        $seguimientos= $em->getRepository('IntranetBundle:Seguimiento')->findCargaSeguimientosTutoriasAlumno($entity, $responsable->getId(), $iden, $entity->getGrupo());

        return new JsonResponse(array(
            'seguimientos' => $seguimientos,
            'seguimientosNuevos' => null,
            'html' => $this->renderView('IntranetBundle:Alumno:lista_seguimiento.html.twig', array(
            'seguimientos' => $seguimientos, 'seguimientosNuevos' => null, 'entity'=>$entity, 'tipo' =>'tutorias')),
            'success' => true
            ), 200);
    }

    ///////////////////////////////////////////
    //               Noticias                //
    ///////////////////////////////////////////   

    public function noticiasAction(Request $request, $id)
    {
        $this->comprobarHijo($id);
        $em = $this->getDoctrine()->getManager();

        $entity= $em->getRepository('BackendBundle:Alumno')->findOneById($id);
        $responsable = $this->get('security.context')->getToken()->getUser();


        if($entity->getCurso()->getNivel()=="Primaria"){
            $noticias= $em->getRepository('ColeBundle:Noticias')->findBy(array('categoria'=>'primaria'), array('fecha'=>'DESC'));
            $noticiasNuevas=$em->getRepository('ColeBundle:Noticias')->findNoticiasNuevasAlumno($entity, $responsable->getId(),"primaria" );
        }
        else{
            $noticias= $em->getRepository('ColeBundle:Noticias')->findBy(array('categoria'=>'infantil'), array('fecha'=>'DESC'));
            $noticiasNuevas=$em->getRepository('ColeBundle:Noticias')->findNoticiasNuevasAlumno($entity, $responsable->getId(),"infantil");
        }
        $paginator  = $this->get('knp_paginator');
        $pagination = $paginator->paginate(
            $noticias, /* query NOT result */
            $request->query->getInt('page', 1)/*page number*/,
            6/*limit per page*/
        );

        return $this->render('IntranetBundle:Alumno:noticias.html.twig', array(
            'entity' => $entity, 
            'noticias_nuevas' => $noticiasNuevas,
            'noticias'=> $noticias,
            'pagination'=> $pagination));
    }


    public function noticiaAction($id,$num)
    {
        $this->comprobarHijo($id);
        $em = $this->getDoctrine()->getManager();

        $entity= $em->getRepository('BackendBundle:Alumno')->findOneById($id);
        $noticia= $em->getRepository('ColeBundle:Noticias')->findOneById($num);
        $responsable = $this->get('security.context')->getToken()->getUser();
     
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
        $aviso= $em->getRepository('IntranetBundle:Avisos')->findnoticiaMostrada($id, $responsable->getId(), $num, "Alumno");
        if($aviso){
            $em->remove($aviso);
        }
        $em->flush();

        return $this->render('IntranetBundle:Alumno:noticia.html.twig', array(
            'entity' => $entity, 
            'noticia'=> $noticia,
            'imagenes' => $imagenes));
    }


    public function comprobarNoticiasNuevasAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $responsable = $this->get('security.context')->getToken()->getUser();
        $entity= $em->getRepository('BackendBundle:Alumno')->findOneById($id);
        $num=0;

        if($entity->getResponsable1()==$responsable){
            $acceso=$entity->getAccesoNoticiasResponsable1();
        }
        else{
            $acceso=$entity->getAccesoNoticiasResponsable2();
        }

        if($entity->getCurso() && $entity->getGrupo()){
            if($acceso){
                if($entity->getCurso()->getNivel()=="Primaria"){
                    $noticias =$em->getRepository('ColeBundle:Noticias')->findNuevasNoticias($acceso,"primaria");
                }
                else{
                    $noticias =$em->getRepository('ColeBundle:Noticias')->findNuevasNoticias($acceso,"infantil");
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

            //Si hay noticias nuevas se añade los id a la tabla Avisos.
            if($noticias){
                foreach($noticias as $noticia){
                    $existencia=$em->getRepository('IntranetBundle:Avisos')->findExistenciaAviso($entity->getId(),$responsable->getId(), "Alumno",$noticia->getId(), "Noticia" );
                    if(!$existencia){
                        $aviso = new Avisos();
                        $aviso->setIdUsuario($entity->getId());
                        $aviso->setIdResponsable($responsable->getId());
                        $aviso->setTipoUsuario("Alumno");
                        $aviso->setIdAviso($noticia->getId());
                        $aviso->setTipoAviso("Noticia");
                        $em->persist($aviso);
                    }
                }

                //Se actualiza la fecha del último acceso del responsable correspondiente.
                if($entity->getResponsable1()==$responsable){
                    $entity->setAccesoNoticiasResponsable1(new \DateTime("now"));
                }
                else{
                    $entity->setAccesoNoticiasResponsable2(new \DateTime("now"));
                }

                $em->persist($entity);
                $em->flush();
            }
            //Se obtiene el número de noticias nuevas.
            $avisos =$em->getRepository('IntranetBundle:Avisos')->findAvisos($entity,$responsable, "Alumno", "Noticia");
            $num=count($avisos);
        }

        return new JsonResponse(array(
            'num'=> $num,
            'id' => $id,
            'success' => true), 200);
    }






}