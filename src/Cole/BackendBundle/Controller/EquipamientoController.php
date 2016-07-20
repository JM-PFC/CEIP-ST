<?php

namespace Cole\BackendBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;

use Cole\BackendBundle\Entity\Equipamiento;
use Cole\BackendBundle\Form\EquipamientoType;

/**
 * Equipamiento controller.
 *
 */
class EquipamientoController extends Controller
{

    /**
     * Lists all Equipamiento entities.
     *
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $entities = $em->getRepository('BackendBundle:Equipamiento')->findAll();

        return $this->render('BackendBundle:Equipamiento:index.html.twig', array(
            'entities' => $entities,
        ));
    }

    /**
     * Lists all Instalaciones entities.
     *
     */
    public function index2Action()
    {
        $em = $this->getDoctrine()->getManager();

        $entities = $em->getRepository('BackendBundle:Equipamiento')->findAll();

        return $this->render('BackendBundle:Equipamiento:index2.html.twig', array(
            'entities' => $entities,
        ));
    }
    /**
     * Creates a new Equipamiento entity.
     *
     */
    public function createAction(Request $request)
    {
      // if request is XmlHttpRequest (AJAX) but not a POSt, throw an exception
      if ($request->isXmlHttpRequest() && !$request->isMethod('POST')) {
        throw new HttpException('XMLHttpRequests/AJAX calls must be POSTed');}

        $entity = new Equipamiento();
        $form = $this->createCreateForm($entity);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();

            if ($request->isXmlHttpRequest()) {
                return new JsonResponse(array(
                    'message' => 'Success!',
                    'success' => true), 200);
            }

            return $this->redirect($this->generateUrl('equipamiento_show', array('id' => $entity->getId())));
        }

        if ($request->isMethod('POST')) {
            return new JsonResponse(array(
            'result' => 0,
            'message' => 'Invalid form',
            'success' => false), 400);
        }

        return $this->render('BackendBundle:Equipamiento:new.html.twig', array(
            'entity' => $entity,
            'form'   => $form->createView(),
        ));
    }

    /**
     * Creates a form to create a Equipamiento entity.
     *
     * @param Equipamiento $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createCreateForm(Equipamiento $entity)
    {
        $form = $this->createForm(new EquipamientoType(), $entity, array(
            'action' => $this->generateUrl('equipamiento_create'),
            'method' => 'POST',
        ));

        $form->add('submit', 'submit', array('label' => 'Create'));

        return $form;
    }

    /**
     * Displays a form to create a new Equipamiento entity.
     *
     */
    public function newAction()
    {
        $entity = new Equipamiento();
        $form   = $this->createCreateForm($entity);

        return $this->render('BackendBundle:Equipamiento:new.html.twig', array(
            'entity' => $entity,
            'form'   => $form->createView(),
        ));
    }

    /**
     * Finds and displays a Equipamiento entity.
     *
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('BackendBundle:Equipamiento')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Equipamiento entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return $this->render('BackendBundle:Equipamiento:show.html.twig', array(
            'entity'      => $entity,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing Equipamiento entity.
     *
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('BackendBundle:Equipamiento')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Equipamiento entity.');
        }

        $editForm = $this->createEditForm($entity);
        $deleteForm = $this->createDeleteForm($id);

        return $this->render('BackendBundle:Equipamiento:edit.html.twig', array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
    * Creates a form to edit a Equipamiento entity.
    *
    * @param Equipamiento $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createEditForm(Equipamiento $entity)
    {
        $form = $this->createForm(new EquipamientoType(), $entity, array(
            'action' => $this->generateUrl('equipamiento_update', array('id' => $entity->getId())),
            'method' => 'PUT',
        ));

        $form->add('submit', 'submit', array('label' => 'Update'));

        return $form;
    }
    /**
     * Edits an existing Equipamiento entity.
     *
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('BackendBundle:Equipamiento')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Equipamiento entity.');
        }

        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createEditForm($entity);
        $editForm->handleRequest($request);

        if ($editForm->isValid()) {
            $em->flush();

            return $this->redirect($this->generateUrl('equipamiento_edit', array('id' => $id)));
        }

        return $this->render('BackendBundle:Equipamiento:edit.html.twig', array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }
    /**
     * Deletes a Equipamiento entity.
     *
     */
    public function deleteAction(Request $request, $id)
    {
        $form = $this->createDeleteForm($id);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('BackendBundle:Equipamiento')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find Equipamiento entity.');
            }

            $em->remove($entity);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('equipamiento'));
    }

    /**
     * Creates a form to delete a Equipamiento entity by id.
     *
     * @param mixed $id The entity id
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm($id)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('equipamiento_delete', array('id' => $id)))
            ->setMethod('DELETE')
            ->add('submit', 'submit', array('label' => 'Delete'))
            ->getForm()
        ;
    }
}
