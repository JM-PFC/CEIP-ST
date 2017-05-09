<?php

namespace Cole\IntranetBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;

use Cole\BackendBundle\Entity\Alumno;
use Cole\BackendBundle\Form\AlumnoIntranetType;


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

		return $this->render('IntranetBundle:Alumno:index.html.twig', array('entity' => $entity));
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
        //Las asignaciones de asignaturas optativas se dividen entre el número de optativas, para obtener el número de módulos asignados.
        if(count($no_opcionales)+(count($opcionales)/count($num_opcionales))==count($clases)*5){
            $numAsigHorarios=1;
        }
        else{
            $numAsigHorarios=0;
        }

        return $this->render('IntranetBundle:Alumno:curso.html.twig', array(
            'entity' => $entity, 
            'tutor' => $tutor,
            'numAsigHorarios'=>$numAsigHorarios));
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


        return $this->render('IntranetBundle:Alumno:datos_horarios.html.twig', array(
            'imparte' => $array,
            'imparte_op' => $array_op,
            'numAsigHorarios' => $numAsigHorarios,
            'numAsigCurso' => $numAsigCurso,
            'completo' => $completo,
            'entity' => $entity,
            'num' => $num,
            'alumno'=> $alumno->getOptativa()->getId()));
    }

    


}