<?php

namespace Cole\IntranetBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use Cole\IntranetBundle\Entity\Cursa;
use Cole\IntranetBundle\Form\CursaType;

/**
 * Cursa controller.
 *
 */
class CursaController extends Controller
{

    /**
     * Lists all Cursa entities.
     *
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $entities = $em->getRepository('IntranetBundle:Cursa')->findAll();

        return $this->render('IntranetBundle:Cursa:index.html.twig', array(
            'entities' => $entities,
        ));
    }
    /**
     * Creates a new Cursa entity.
     *
     */
    public function createAction(Request $request)
    {
        $entity = new Cursa();
        $form = $this->createCreateForm($entity);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('cursa_show', array('id' => $entity->getId())));
        }

        return $this->render('IntranetBundle:Cursa:new.html.twig', array(
            'entity' => $entity,
            'form'   => $form->createView(),
        ));
    }

    /**
     * Creates a form to create a Cursa entity.
     *
     * @param Cursa $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createCreateForm(Cursa $entity)
    {
        $form = $this->createForm(new CursaType(), $entity, array(
            'action' => $this->generateUrl('cursa_create'),
            'method' => 'POST',
        ));

        $form->add('submit', 'submit', array('label' => 'Create'));

        return $form;
    }

    /**
     * Displays a form to create a new Cursa entity.
     *
     */
    public function newAction()
    {
        $entity = new Cursa();
        $form   = $this->createCreateForm($entity);

        return $this->render('IntranetBundle:Cursa:new.html.twig', array(
            'entity' => $entity,
            'form'   => $form->createView(),
        ));
    }

    /**
     * Finds and displays a Cursa entity.
     *
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('IntranetBundle:Cursa')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Cursa entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return $this->render('IntranetBundle:Cursa:show.html.twig', array(
            'entity'      => $entity,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing Cursa entity.
     *
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('IntranetBundle:Cursa')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Cursa entity.');
        }

        $editForm = $this->createEditForm($entity);
        $deleteForm = $this->createDeleteForm($id);

        return $this->render('IntranetBundle:Cursa:edit.html.twig', array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
    * Creates a form to edit a Cursa entity.
    *
    * @param Cursa $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createEditForm(Cursa $entity)
    {
        $form = $this->createForm(new CursaType(), $entity, array(
            'action' => $this->generateUrl('cursa_update', array('id' => $entity->getId())),
            'method' => 'PUT',
        ));

        $form->add('submit', 'submit', array('label' => 'Update'));

        return $form;
    }
    /**
     * Edits an existing Cursa entity.
     *
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('IntranetBundle:Cursa')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Cursa entity.');
        }

        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createEditForm($entity);
        $editForm->handleRequest($request);

        if ($editForm->isValid()) {
            $em->flush();

            return $this->redirect($this->generateUrl('cursa_edit', array('id' => $id)));
        }

        return $this->render('IntranetBundle:Cursa:edit.html.twig', array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }
    /**
     * Deletes a Cursa entity.
     *
     */
    public function deleteAction(Request $request, $id)
    {
        $form = $this->createDeleteForm($id);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('IntranetBundle:Cursa')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find Cursa entity.');
            }

            $em->remove($entity);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('cursa'));
    }

    /**
     * Creates a form to delete a Cursa entity by id.
     *
     * @param mixed $id The entity id
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm($id)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('cursa_delete', array('id' => $id)))
            ->setMethod('DELETE')
            ->add('submit', 'submit', array('label' => 'Delete'))
            ->getForm()
        ;
    }
}
