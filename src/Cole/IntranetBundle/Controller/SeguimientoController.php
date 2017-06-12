<?php

namespace Cole\IntranetBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;

use Cole\IntranetBundle\Entity\Seguimiento;
use Cole\IntranetBundle\Form\SeguimientoType;

/**
 * Seguimiento controller.
 *
 */
class SeguimientoController extends Controller
{

    /**
     * Lists all Seguimiento entities.
     *
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $entities = $em->getRepository('IntranetBundle:Seguimiento')->findAll();

        return $this->render('IntranetBundle:Seguimiento:index.html.twig', array(
            'entities' => $entities,
        ));
    }
    /**
     * Creates a new Seguimiento entity.
     *
     */
    public function createAction(Request $request)
    {        
        $profesor = $this->get('security.context')->getToken()->getUser();

        $entity = new Seguimiento();
        $form = $this->createCreateForm($entity);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity->setFecha(new \DateTime("now"));
            $entity->setFechaActualizada(new \DateTime("now"));
            $entity->setTipo(1);
            $entity->setTipoUser(1);
            $entity->setRespuesta(0);
            $entity->setProfesor($profesor);

            $em->persist($entity);
            $em->flush();
            $ms = $this->get('translator')->trans('Nuevo seguimiento creado.');
            $this->get('session')->getFlashBag()->add('notice',$ms);

            return $this->redirect($this->generateUrl('intranet_profesor_seguimientos'));
        }

        return $this->render('IntranetBundle:Seguimiento:new.html.twig', array(
            'entity' => $entity,
            'form'   => $form->createView(),
        ));
    }

    //Se crean la respuesta del seguimiento.
    public function createRespuestaAction(Request $request)
    {        
        $user = $this->get('security.context')->getToken()->getUser();

        $entity = new Seguimiento();
        $form = $this->createCreateFormRespuesta($entity);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();

            if ($this->get('security.context')->isGranted('ROLE_PROFESOR')) {
                $entity->setFecha(new \DateTime("now"));
                $entity->setFechaActualizada(new \DateTime("now"));
                $entity->setTipo(0);
                $entity->setTipoUser(1);
                $entity->setRespuesta(0);
            }
            else if ($this->get('security.context')->isGranted('ROLE_USUARIO')){
                $entity->setFecha(new \DateTime("now"));
                $entity->setFechaActualizada(new \DateTime("now"));
                $entity->setTipo(0);
                $entity->setTipoUser(0);
                $entity->setRespuesta(0);
                $entity->setResponsable($user);
            }
            $em->persist($entity);
            //Se indica en el seguimiento principal que tiene respuesta.
            $principal= $em->getRepository('IntranetBundle:Seguimiento')->findOneById($entity->getSeguimiento());
            $principal->setRespuesta(1);
            $em->persist($principal);

            $em->flush();
            $ms = $this->get('translator')->trans('Nueva respuesta añadida.');
            $this->get('session')->getFlashBag()->add('notice',$ms);

            if ($this->get('security.context')->isGranted('ROLE_PROFESOR')) {
                return $this->redirect($this->generateUrl('intranet_profesor_seguimiento', array('num' => $entity->getSeguimiento())));
            }
            else if ($this->get('security.context')->isGranted('ROLE_USUARIO')){
                return $this->redirect($this->generateUrl('intranet_alumno_seguimiento', array('id' =>$entity->getAlumno()->getId() , 'num' => $entity->getSeguimiento())));
            }
        }

        return $this->render('IntranetBundle:Seguimiento:respuestaSeguimiento.html.twig', array(
            'entity' => $entity,
            'form'   => $form->createView(),
        ));
    }

    /**
     * Creates a form to create a Seguimiento entity.
     *
     * @param Seguimiento $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createCreateForm(Seguimiento $entity)
    {
        $form = $this->createForm(new SeguimientoType(), $entity, array(
            'action' => $this->generateUrl('seguimiento_create'),
            'method' => 'POST',
        ));
        $titulo=$this->get("translator")->trans("Enviar");
        $form->add('submit', 'submit', array('label' => $titulo));

        return $form;
    }

    private function createCreateFormRespuesta(Seguimiento $entity)
    {
        $form = $this->createForm(new SeguimientoType(), $entity, array(
            'action' => $this->generateUrl('seguimiento_create_respuesta'),
            'method' => 'POST',
        ));
        $titulo=$this->get("translator")->trans("Enviar");
        $form->add('submit', 'submit', array('label' => $titulo, 'attr' => array('class' => 'btn btn-success')));

        return $form;
    }

    /**
     * Displays a form to create a new Seguimiento entity.
     *
     */
    public function newAction()
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $this->get('security.context')->getToken()->getUser();
        $tutor_grupo= $em->getRepository('BackendBundle:Grupo')->findOneByProfesor($entity);

        if($entity->getNivel()=="Primaria"){
            $cursos = $em->getRepository('BackendBundle:Imparte')->findAsignacionesProfesor($entity);
        }
        else{
            //Para los profesores de infantil se asigna sólo el grupo que es tutor.
            $cursos=$tutor_grupo;
        }
  
        $seguimiento = new Seguimiento();
        $form   = $this->createCreateForm($seguimiento);

        return $this->render('IntranetBundle:Seguimiento:new.html.twig', array(
            'entity' => $entity,
            'seguimiento' => $seguimiento,
            'tutor_grupo' => $tutor_grupo,
            'cursos'=>$cursos,
            'form'   => $form->createView(),
        ));
    }

    public function respuestaSeguimientoAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $this->get('security.context')->getToken()->getUser();
        $principal= $em->getRepository('IntranetBundle:Seguimiento')->findOneById($id);

        $seguimiento = new Seguimiento();
        $form = $this->createCreateFormRespuesta($seguimiento);
        if($principal->getAsignatura() != null){
            return $this->render('IntranetBundle:Seguimiento:respuestaSeguimiento.html.twig', array(
                'entity' => $entity,
                'seguimiento' => $seguimiento,
                'principal' => $principal,
                'form'   => $form->createView(),
            ));
        }
        else{
            return $this->render('IntranetBundle:Seguimiento:respuestaSeguimientoInfantil.html.twig', array(
                'entity' => $entity,
                'seguimiento' => $seguimiento,
                'principal' => $principal,
                'form'   => $form->createView(),
            ));
        }
    }
    

    /**
     * Finds and displays a Seguimiento entity.
     *
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('IntranetBundle:Seguimiento')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Seguimiento entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return $this->render('IntranetBundle:Seguimiento:show.html.twig', array(
            'entity'      => $entity,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing Seguimiento entity.
     *
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('IntranetBundle:Seguimiento')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Seguimiento entity.');
        }

        $editForm = $this->createEditForm($entity);
        $deleteForm = $this->createDeleteForm($id);

        return $this->render('IntranetBundle:Seguimiento:edit.html.twig', array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    public function eliminarSeguimientoAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('IntranetBundle:Seguimiento')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Seguimiento entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return $this->render('IntranetBundle:Seguimiento:eliminarSeguimiento.html.twig', array(
            'entity'      => $entity,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
    * Creates a form to edit a Seguimiento entity.
    *
    * @param Seguimiento $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createEditForm(Seguimiento $entity)
    {
        $form = $this->createForm(new SeguimientoType(), $entity, array(
            'action' => $this->generateUrl('seguimiento_update', array('id' => $entity->getId())),
            'method' => 'PUT',
        ));
        $titulo=$this->get("translator")->trans("Actualizar");
        $form->add('submit', 'submit', array('label' => $titulo, 'attr' => array('class' => 'btn btn-danger')));

        return $form;
    }
    /**
     * Edits an existing Seguimiento entity.
     *
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('IntranetBundle:Seguimiento')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Seguimiento entity.');
        }

        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createEditForm($entity);
        $editForm->handleRequest($request);

        if ($editForm->isValid()) {
            $entity->setFechaActualizada(new \DateTime("now"));
            $em->flush();

            $ms = $this->get('translator')->trans('El seguimiento ha sido actualizado correctamente.');
            $this->get('session')->getFlashBag()->add('notice',$ms);
            return $this->redirect($this->generateUrl('intranet_profesor_seguimientos'));
        }

        return $this->render('IntranetBundle:Seguimiento:edit.html.twig', array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }
    /**
     * Deletes a Seguimiento entity.
     *
     */
    public function deleteAction(Request $request, $id)
    {
        $form = $this->createDeleteForm($id);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('IntranetBundle:Seguimiento')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find Seguimiento entity.');
            }
            //Se elimina todos los avisos nuevos de ese seguimiento.
            $avisos= $em->getRepository('IntranetBundle:Avisos')->findBy(array('idAviso'=>$id, 'tipoAviso'=>"Seguimiento"));
            foreach($avisos as $aviso){
                $em->remove($aviso);
            }
            $em->remove($entity);
            $em->flush();
        }
        $ms = $this->get('translator')->trans('El seguimiento ha sido eliminado correctamente.');
        $this->get('session')->getFlashBag()->add('notice',$ms);
        return $this->redirect($this->generateUrl('intranet_profesor_seguimientos'));
    }

    /**
     * Creates a form to delete a Seguimiento entity by id.
     *
     * @param mixed $id The entity id
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm($id)
    {
        //$eliminar=$this->get('translator')->trans('Eliminar');

        return $this->createFormBuilder()
            ->setAction($this->generateUrl('seguimiento_delete', array('id' => $id)))
            ->setMethod('DELETE')
            ->add('submit', 'submit', array('label' => "Eliminar", 'attr' => array('class' => 'btn btn-danger')))
            ->getForm()
        ;
    }

    public function seguimientoLeidoAction($id, $alumno)
    {
        $em = $this->getDoctrine()->getManager();
        $entity = $this->get('security.context')->getToken()->getUser();


        $aviso= $em->getRepository('IntranetBundle:Avisos')->findseguimientoLeido($alumno, $entity->getId(), $id);
        $em->remove($aviso);
        $em->flush();

        return new JsonResponse(array('success' => true), 200);
    }

    public function seguimientoConsultadoAction($id,$user,$tipo)
    {
        $em = $this->getDoctrine()->getManager();
        $entity = $this->get('security.context')->getToken()->getUser();

        if ($this->get('security.context')->isGranted('ROLE_PROFESOR')) {
            $responsable=null;
        }
        else if ($this->get('security.context')->isGranted('ROLE_USUARIO')){
            $responsable=$entity;
        }

        $aviso= $em->getRepository('IntranetBundle:Avisos')->findseguimientoConsultado($id,$user,$responsable,$tipo);
        $em->remove($aviso);
        $em->flush();

        return new JsonResponse(array('success' => true), 200);
    }




}
