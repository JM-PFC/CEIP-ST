<?php

namespace Cole\IntranetBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Component\HttpFoundation\Response;


use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;

use Cole\BackendBundle\Entity\Profesor;
use Cole\BackendBundle\Form\ProfesorType;
use Cole\BackendBundle\Entity\Padres;
use Cole\BackendBundle\Form\PadresType;

class DefaultController extends Controller
{

        public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();
        $usuario = $this->get('security.context')->getToken()->getUser();

        if ($this->get('security.context')->isGranted('ROLE_PROFESOR'))
        {
            $profesor=$em->getRepository('BackendBundle:Profesor')->findOneById($usuario);
            return new RedirectResponse($this->generateUrl('intranet_profesor'));
        }
        else if($this->get('security.context')->isGranted('ROLE_USUARIO')){
            //Se obtiene los alumnos que están activo y matriculados en un curso, cuyo responsable tambien está activo.
            $hijos= $em->getRepository('BackendBundle:Alumno')->findByResponsableActivo($usuario);
            
            if(count($hijos)==1){
                foreach($hijos as $entity){
                    return new RedirectResponse($this->generateUrl('intranet_alumno', array('id' => $entity->getId())));
                }
            }
            else{
                return $this->render('IntranetBundle:Default:seleccion.html.twig', array(
                    'hijos' => $hijos));
            }
        }
        else{
            throw new AccessDeniedException();
        }
    }

    public function responsableAction()
    {
    	$em = $this->getDoctrine()->getManager();

    	if($this->get('security.context')->isGranted('ROLE_USUARIO')){
			$usuario = $this->get('security.context')->getToken()->getUser();
            //Se obtiene los alumnos que están activo y matriculados en un curso, cuyo responsable tambien está activo.
			$hijos= $em->getRepository('BackendBundle:Alumno')->findByResponsableActivo($usuario);
			return $this->render('IntranetBundle:Default:seleccion.html.twig', array(
            'hijos' => $hijos));
		}
		else{
			throw new AccessDeniedException();
		}
    }

    private function createEditProfesorForm(Profesor $entity)
    {
        $form = $this->createForm(new ProfesorType(), $entity, array(
            'action' => $this->generateUrl('datos_personales', array('id' => $entity->getId())),
            'method' => 'PUT',
            //'validation_groups' => array('actualizar')
        ));

        $form->add('submit', 'submit', array('label' => 'Guardar cambios'));

        return $form;
    }

    private function createEditPadresForm(Padres $entity)
    {
        $form = $this->createForm(new PadresType(), $entity, array(
            'action' => $this->generateUrl('datos_personales', array('id' => $entity->getId())),
            'method' => 'PUT',
            //'validation_groups' => array('actualizar')
        ));

        $form->add('submit', 'submit', array('label' => 'Guardar cambios'));

        return $form;
    }

    private function createPassProfesorForm(Profesor $entity)
    {
        $form = $this->createForm(new ProfesorType(), $entity, array(
            'action' => $this->generateUrl('actualizar_password', array('id' => $entity->getId())),
            'method' => 'PUT',
        ));

        $form->add('submit', 'submit', array('label' => 'Actualizar'));

        return $form;
    }

    private function createPassPadresForm(Padres $entity)
    {
        $form = $this->createForm(new PadresType(), $entity, array(
            'action' => $this->generateUrl('actualizar_password', array('id' => $entity->getId())),
            'method' => 'PUT',
            //'validation_groups' => array('actualizar')
        ));

        $form->add('submit', 'submit', array('label' => 'Actualizar cambios'));

        return $form;
    }

    private function createDeleteForm($id)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('profesor_delete', array('id' => $id)))
            ->setMethod('DELETE')
            ->add('submit', 'submit', array('label' => 'Delete'))
            ->getForm()
        ;
    }

    public function perfilAction()
    {
    	$em = $this->getDoctrine()->getManager();
    	$entity = $this->get('security.context')->getToken()->getUser();

        if ($this->get('security.context')->isGranted('ROLE_PROFESOR'))
        {
            $editForm = $this->createEditProfesorForm($entity);
            $PassForm = $this->createPassProfesorForm($entity);
        }
        else
        {
            $editForm = $this->createEditPadresForm($entity);
            $PassForm = $this->createPassPadresForm($entity);
        }
        $deleteForm = $this->createDeleteForm($entity->getId());

    	return $this->render('IntranetBundle:Default:perfil.html.twig', array(
           	'entity' => $entity,
            'edit_form'   => $editForm->createView(),
            'form'   => $PassForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    public function DatosPersonalesAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        if ($this->get('security.context')->isGranted('ROLE_PROFESOR'))
        {
            $entity = $em->getRepository('BackendBundle:Profesor')->find($id);
            $editForm = $this->createEditProfesorForm($entity);
            $foto=$entity->getFoto();
        }
        else
        {
            $entity = $em->getRepository('BackendBundle:Padres')->find($id);
            $editForm = $this->createEditPadresForm($entity);
        }
        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Usuario entity.');
        }
        //Se optiene el password para asignarlo de nuevo ya que se modifica la contraseña con el valor de la nueva que está vacío(oculto).
        $password=$entity->getPassword();
        
        $deleteForm = $this->createDeleteForm($id);

        $editForm->handleRequest($request);

        if ($editForm->isValid()) {

            if ($this->get('security.context')->isGranted('ROLE_PROFESOR'))
            {
                $entity->setFoto($foto);
            }
            $entity->setPassword($password);
            
            $em->persist($entity);

            $em->flush();
            $ms = $this->get('translator')->trans('Se han guardado los cambios.');

            $this->get('session')->getFlashBag()->add('notice',$ms);

            return $this->redirect($this->generateUrl('intranet_perfil', array('_locale' => $this->get('request')->getLocale())));
        }
        //var_dump($editForm->getErrorsAsString());die;

        return $this->render('IntranetBundle:Default:perfil.html.twig', array(
            'entity'      => $entity,
            'form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }


    public function ActualizarPasswordAction(Request $request, $id)
    {

        $em = $this->getDoctrine()->getManager();

        if ($this->get('security.context')->isGranted('ROLE_PROFESOR'))
        {
            $entity = $em->getRepository('BackendBundle:Profesor')->find($id);
            $editForm = $this->createEditProfesorForm($entity);
            $foto=$entity->getFoto();
        }
        else
        {
            $entity = $em->getRepository('BackendBundle:Padres')->find($id);
            $editForm = $this->createEditPadresForm($entity);
        }
        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Usuario entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        $editForm->handleRequest($request);

        if ($editForm->isValid()) {

            if ($this->get('security.context')->isGranted('ROLE_PROFESOR'))
            {
                $entity->setFoto($foto);
            }

            $em = $this->getDoctrine()->getEntityManager(); 
            $factory = $this->get('security.encoder_factory'); 
            $encoder = $factory->getEncoder($entity);
            $password = $encoder->encodePassword($entity->getPassword(), $entity->getSalt());
            $entity->setPassword($password);

            $em->persist($entity);

            $em->flush();
            $ms = $this->get('translator')->trans('La contraseña ha sido actualizada.');

            $this->get('session')->getFlashBag()->add('notice',$ms);

            return $this->redirect($this->generateUrl('intranet_perfil', array('_locale' => $this->get('request')->getLocale())));
        }
        //var_dump($editForm->getErrorsAsString());die;

        return $this->render('IntranetBundle:Default:perfil.html.twig', array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }
    public function ListaAlumnosGrupoPdfAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $grupo=$em->getRepository('BackendBundle:Grupo')->findOneById($id);

        $entities=$em->getRepository('BackendBundle:Alumno')->findByGrupoOrdenado($grupo);
        
        $inicio =$em->getRepository('BackendBundle:Centro')->findInicioCurso();
        $fin =$em->getRepository('BackendBundle:Centro')->findFinCurso();
        
        $titulo="Lista de Clase";
        $subtitulo=null;
        $html = $this->renderView('IntranetBundle:Default:lista_alumnos_grupo.html.twig', array(
            'entities' => $entities,
            'grupo' => $grupo
        ));
        $header = $this->renderView('IntranetBundle:Default:header.html.twig', array(
            'inicio' => $inicio,
            'fin' => $fin,
            'grupo' => $grupo,
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

            'footer-center' => '[page] / [topage]',
            'footer-font-size' => 8,
            //'footer-left' => 'Confidential',
            //'page-size' => 'A4',

            'header-spacing' => 5, 
            'footer-spacing' => 10
        ];
        //$iniciales=substr($alumno->getNombre(), 0, 1).substr($alumno->getApellido1(), 0, 1).substr($alumno->getApellido2(), 0, 1);
            $curso=$grupo->getCurso()->getCurso().$grupo->getLetra();
        return new Response(
            $this->get('knp_snappy.pdf')->getOutputFromHtml($html,$options),
            200,
            array(
                'Content-Type'        => 'application/pdf',
                'Content-Disposition' => 'attachment; filename="Alumnos_'. $curso.'.pdf"'
            )
        );  
    }

    public function ListaAlumnosOptativasPdfAction($id)
    {
        $em = $this->getDoctrine()->getManager();
        $html = array();

        $grupo=$em->getRepository('BackendBundle:Grupo')->findOneById($id);
        $profesor = $this->get('security.context')->getToken()->getUser();
        
        $inicio =$em->getRepository('BackendBundle:Centro')->findInicioCurso();
        $fin =$em->getRepository('BackendBundle:Centro')->findFinCurso();
        
        //Se optiene las optativas del grupo.
        $optativas=$em->getRepository('BackendBundle:Imparte')->findOpcionalesSinRepetir($grupo);
        $titulo="Asignaturas Opcionales";
        $subtitulo=null;
        foreach($optativas as $optativa){
            $entities=$em->getRepository('BackendBundle:Alumno')->findAlumnosOptativaGrupo($optativa->getAsignatura(), $grupo);
            $asignatura=$optativa->getAsignatura()->getAsignatura()->getAbreviatura();
            $html[]  = $this->renderView('IntranetBundle:Default:lista_alumnos_optativas.html.twig', array(
                'entities' => $entities,
                'grupo' => $grupo,
                'asignatura' => $asignatura
            ));

        }
        $header = $this->renderView('IntranetBundle:Default:header.html.twig', array(
            'inicio' => $inicio,
            'fin' => $fin,
            'grupo' => $grupo,
            'titulo' => $titulo,
            'subtitulo' =>$subtitulo
        ));

        $options = [
            'margin-top'    => 40,
            'margin-right'  => 7,
            'margin-bottom' => 20,
            'margin-left'   => 7,
            'header-html' => $header,
            //'footer-center' => '[page] / [topage]',
            'footer-font-size' => 8,
            'page-size' => 'A4',
            'header-spacing' => 0, 
            'footer-spacing' => 10
        ];
   
        $curso=$grupo->getCurso()->getCurso().$grupo->getLetra();
        return new Response(
            $this->get('knp_snappy.pdf')->getOutputFromHtml($html,$options),
            200,
            array(
                'Content-Type'        => 'application/pdf',
                'Content-Disposition' => 'attachment; filename="Alumnos_'.$curso.'_optativas.pdf"'
            )
        );  
    }


    public function ListaAlumnosOptativaPdfAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $grupo=$em->getRepository('BackendBundle:Grupo')->findOneById($id);
        $profesor = $this->get('security.context')->getToken()->getUser();
        $grupo_tutorizado= $em->getRepository('BackendBundle:Grupo')->findOneByProfesor($profesor);

        $opcional=$em->getRepository('BackendBundle:Imparte')->findOpcionalProfesorGrupo($profesor, $grupo);
        $entities=$em->getRepository('BackendBundle:Alumno')->findAlumnosOptativaGrupo($opcional->getAsignatura(), $grupo);
        
        $html = $this->renderView('IntranetBundle:Default:lista_alumnos_grupo.html.twig', array(
            'entities' => $entities,
            'grupo' => $grupo
        ));
        
        $titulo="Asignatura Opcional";
        $subtitulo=$opcional->getAsignatura()->getAsignatura()->getAbreviatura();
        $inicio =$em->getRepository('BackendBundle:Centro')->findInicioCurso();
        $fin =$em->getRepository('BackendBundle:Centro')->findFinCurso();

        $header = $this->renderView('IntranetBundle:Default:header.html.twig', array(
            'inicio' => $inicio,
            'fin' => $fin,
            'grupo' => $grupo,
            'titulo' => $titulo,
            'subtitulo' => $subtitulo
        ));
        $options = [
            'margin-top'    => 40,
            'margin-right'  => 7,
            'margin-bottom' => 20,
            'margin-left'   => 7,
            'header-html' => $header,
            'footer-center' => '[page] / [topage]',
            'footer-font-size' => 8,
            'page-size' => 'A4',
            'header-spacing' => 5, 
            'footer-spacing' => 10
        ];
        $curso=$grupo->getCurso()->getCurso().$grupo->getLetra();
        $asignatura=$opcional->getAsignatura()->getAsignatura()->getAbreviatura();
        return new Response(
            $this->get('knp_snappy.pdf')->getOutputFromHtml($html,$options),
            200,
            array(
                'Content-Type'        => 'application/pdf',
                'Content-Disposition' => 'attachment; filename="Alumnos_'.$asignatura.'_'. $curso.'.pdf"'
            )
        );  
    }

    public function HorariosGrupoAction($id, $num)
    {
        $em = $this->getDoctrine()->getManager();
        $grupo = $em->getRepository('BackendBundle:Grupo')->findOneById($id);
        $entity = $em->getRepository('BackendBundle:Horario')->findAll();
        if($grupo->getAula()){
            $aula_grupo=$grupo->getAula()->getNombre();
        }
        else{
           $aula_grupo=null;
        }
        
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

        $imparte= $em->getRepository('BackendBundle:Imparte')->findAsignacionesOpcionales($grupo);

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
           
            $array_op[$horario."-".$dia."-".$registro->getAsignatura()->getAsignatura()->getNombre()."-".$registro->getAsignatura()->getAsignatura()->getAbreviatura()."-".$aula."-".$registro->getGrupo()->getId()]=$registro->getProfesor()->getNombre()." ".$registro->getProfesor()->getApellido1()." ".$registro->getProfesor()->getApellido2();
        }


        return $this->render('IntranetBundle:Default:datos_horario_grupo.html.twig', array(
            'imparte' => $array,
            'imparte_op' => $array_op,
            'entity' => $entity,
            'num' => $num,
            'aula_grupo' =>$aula_grupo));
    }




}
