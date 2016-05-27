<?php

namespace Cole\BackendBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use Cole\BackendBundle\Entity\Centro;
use Cole\BackendBundle\Form\CentroType;

/**
 * Centro controller.
 *
 */
class CentroController extends Controller
{

    /**
     * Lists all Centro entities.
     *
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $entities = $em->getRepository('BackendBundle:Centro')->findAll();

        return $this->render('BackendBundle:Centro:index.html.twig', array(
            'entities' => $entities,
        ));
    }
    /**
     * Creates a new Centro entity.
     *
     */
    public function createAction(Request $request)
    {
        $entity = new Centro();
        $form = $this->createCreateForm($entity);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('centro_show', array('id' => $entity->getId())));
        }

        return $this->render('BackendBundle:Centro:new.html.twig', array(
            'entity' => $entity,
            'form'   => $form->createView(),
        ));
    }

    /**
     * Creates a form to create a Centro entity.
     *
     * @param Centro $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createCreateForm(Centro $entity)
    {
        $form = $this->createForm(new CentroType(), $entity, array(
            'action' => $this->generateUrl('centro_create'),
            'method' => 'POST',
        ));

        $form->add('submit', 'submit', array('label' => 'Guardar Datos'));

        return $form;
    }

    /**
     * Displays a form to create a new Centro entity.
     *
     */
    public function newAction()
    {
        $entity = new Centro();
        $form   = $this->createCreateForm($entity);

        return $this->render('BackendBundle:Centro:new.html.twig', array(
            'entity' => $entity,
            'form'   => $form->createView(),
        ));
    }

    /**
     * Finds and displays a Centro entity.
     *
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('BackendBundle:Centro')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Centro entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return $this->render('BackendBundle:Centro:show.html.twig', array(
            'entity'      => $entity,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing Centro entity.
     *
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('BackendBundle:Centro')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Centro entity.');
        }

        $editForm = $this->createEditForm($entity);
        $deleteForm = $this->createDeleteForm($id);

        return $this->render('BackendBundle:Centro:edit.html.twig', array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
    * Creates a form to edit a Centro entity.
    *
    * @param Centro $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createEditForm(Centro $entity)
    {
        $form = $this->createForm(new CentroType(), $entity, array(
            'action' => $this->generateUrl('centro_update', array('id' => $entity->getId())),
            'method' => 'PUT',
        ));

        $form->add('submit', 'submit', array('label' => 'Guardar Cambios'));

        return $form;
    }
    /**
     * Edits an existing Centro entity.
     *
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('BackendBundle:Centro')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Centro entity.');
        }

        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createEditForm($entity);
        $editForm->handleRequest($request);

        if ($editForm->isValid()) {
            $em->flush();

            return $this->redirect($this->generateUrl('centro_edit', array('id' => $id)));
        }

        return $this->render('BackendBundle:Centro:edit.html.twig', array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }
    /**
     * Deletes a Centro entity.
     *
     */
    public function deleteAction(Request $request, $id)
    {
        $form = $this->createDeleteForm($id);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('BackendBundle:Centro')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find Centro entity.');
            }

            $em->remove($entity);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('centro'));
    }

    /**
     * Creates a form to delete a Centro entity by id.
     *
     * @param mixed $id The entity id
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm($id)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('centro_delete', array('id' => $id)))
            ->setMethod('DELETE')
            ->add('submit', 'submit', array('label' => 'Delete'))
            ->getForm()
        ;
    }
}
