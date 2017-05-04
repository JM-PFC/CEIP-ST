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





}