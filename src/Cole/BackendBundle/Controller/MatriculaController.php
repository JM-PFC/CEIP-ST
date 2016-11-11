<?php

namespace Cole\BackendBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;


use Cole\BackendBundle\Entity\Matricula;
use Cole\BackendBundle\Form\MatriculaType;

/**
 * Matricula controller.
 *
 */
class MatriculaController extends Controller
{

    /**
     * Lists all Matricula entities.
     *
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $entities = $em->getRepository('BackendBundle:Matricula')->findAll();

        return $this->render('BackendBundle:Matricula:index.html.twig', array(
            'entities' => $entities,
        ));
    }
    /**
     * Creates a new Matricula entity.
     *
     */
    public function createAction(Request $request)
    {
        $entity = new Matricula();
        $form = $this->createCreateForm($entity);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('matricula_show', array('id' => $entity->getId())));
        }

        return $this->render('BackendBundle:Matricula:new.html.twig', array(
            'entity' => $entity,
            'form'   => $form->createView(),
        ));
    }

    /**
     * Creates a form to create a Matricula entity.
     *
     * @param Matricula $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createCreateForm(Matricula $entity)
    {
        $form = $this->createForm(new MatriculaType(), $entity, array(
            'action' => $this->generateUrl('matricula_create'),
            'method' => 'POST',
        ));

        $form->add('submit', 'submit', array('label' => 'Create'));

        return $form;
    }

    /**
     * Displays a form to create a new Matricula entity.
     *
     */
    public function newAction()
    {
        $entity = new Matricula();
        $form   = $this->createCreateForm($entity);

        return $this->render('BackendBundle:Matricula:new.html.twig', array(
            'entity' => $entity,
            'form'   => $form->createView(),
        ));
    }

    /**
     * Finds and displays a Matricula entity.
     *
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('BackendBundle:Matricula')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Matricula entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return $this->render('BackendBundle:Matricula:show.html.twig', array(
            'entity'      => $entity,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing Matricula entity.
     *
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('BackendBundle:Matricula')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Matricula entity.');
        }

        $editForm = $this->createEditForm($entity);
        $deleteForm = $this->createDeleteForm($id);

        return $this->render('BackendBundle:Matricula:edit.html.twig', array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
    * Creates a form to edit a Matricula entity.
    *
    * @param Matricula $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createEditForm(Matricula $entity)
    {
        $form = $this->createForm(new MatriculaType(), $entity, array(
            'action' => $this->generateUrl('matricula_update', array('id' => $entity->getId())),
            'method' => 'PUT',
        ));

        $form->add('submit', 'submit', array('label' => 'Update'));

        return $form;
    }
    /**
     * Edits an existing Matricula entity.
     *
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('BackendBundle:Matricula')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Matricula entity.');
        }

        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createEditForm($entity);
        $editForm->handleRequest($request);

        if ($editForm->isValid()) {
            $em->flush();

            return $this->redirect($this->generateUrl('matricula_edit', array('id' => $id)));
        }

        return $this->render('BackendBundle:Matricula:edit.html.twig', array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }
    /**
     * Deletes a Matricula entity.
     *
     */
    public function deleteAction(Request $request, $id)
    {
        $form = $this->createDeleteForm($id);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('BackendBundle:Matricula')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find Matricula entity.');
            }

            $em->remove($entity);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('matricula'));
    }

    /**
     * Creates a form to delete a Matricula entity by id.
     *
     * @param mixed $id The entity id
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm($id)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('matricula_delete', array('id' => $id)))
            ->setMethod('DELETE')
            ->add('submit', 'submit', array('label' => 'Delete'))
            ->getForm()
        ;
    }


    public function  MatricularAlumnoAction(Request $request)
    {
        // if request is XmlHttpRequest (AJAX) but not a POSt, throw an exception
        if ($request->isXmlHttpRequest() && !$request->isMethod('POST')) {
            throw new HttpException('XMLHttpRequests/AJAX calls must be POSTed');
        }

        $alum=$this->get('request')->request->get('alumno');

        $em = $this->getDoctrine()->getEntityManager();

        $alumno=$em->getRepository('BackendBundle:Alumno')->findOneById($alum);

        $entity = new Matricula();

        $entity->setAlumno($alumno);
        $entity->setCurso($alumno->getCurso());

        if(date("n")>=6){
            $entity->setAñoAcademico(date("Y")."/".(date("Y")+1));
        }
        else{
            $entity->setAñoAcademico((date("Y")-1)."/".date("Y"));
        }
        $entity->setFecha(new \DateTime("now"));
        
        $em->persist($entity);
        $em->flush();
        
        if ($request->isXmlHttpRequest()) {
                return new JsonResponse(array(
                    'message' => 'Success!',
                    'success' => true), 200);
        } 
    }
}
