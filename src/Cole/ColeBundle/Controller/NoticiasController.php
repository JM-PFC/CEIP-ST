<?php

namespace Cole\ColeBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use Cole\ColeBundle\Entity\Noticias;
use Cole\ColeBundle\Form\NoticiasType;
use Symfony\Component\HttpFoundation\JsonResponse;


/**
 * Noticias controller.
 *
 */
class NoticiasController extends Controller
{

    /**
     * Lists all Noticias entities.
     *
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $entities = $em->getRepository('ColeBundle:Noticias')->findAll();

        return $this->render('ColeBundle:Noticias:index.html.twig', array(
            'entities' => $entities,
        ));
    }
    /**
     * Creates a new Noticias entity.
     *
     */
    public function createAction(Request $request)
    {
        $entity = new Noticias();
        $form = $this->createCreateForm($entity);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('noticias_show', array('id' => $entity->getId())));
        }

        return $this->render('ColeBundle:Noticias:new.html.twig', array(
            'entity' => $entity,
            'form'   => $form->createView(),
        ));
    }

    /**
     * Creates a form to create a Noticias entity.
     *
     * @param Noticias $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createCreateForm(Noticias $entity)
    {
        $form = $this->createForm(new NoticiasType(), $entity, array(
            'action' => $this->generateUrl('noticias_create'),
            'method' => 'POST',
        ));

        $form->add('submit', 'submit', array('label' => 'Crear'));

        return $form;
    }

    /**
     * Displays a form to create a new Noticias entity.
     *
     */
    public function newAction()
    {
        $entity = new Noticias();
        $form   = $this->createCreateForm($entity);

        return $this->render('ColeBundle:Noticias:new.html.twig', array(
            'entity' => $entity,
            'form'   => $form->createView(),
        ));
    }

    /**
     * Finds and displays a Noticias entity.
     *
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('ColeBundle:Noticias')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Noticias entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return $this->render('ColeBundle:Noticias:show.html.twig', array(
            'entity'      => $entity,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing Noticias entity.
     *
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('ColeBundle:Noticias')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Noticias entity.');
        }

        $editForm = $this->createEditForm($entity);
        $deleteForm = $this->createDeleteForm($id);

        return $this->render('ColeBundle:Noticias:edit.html.twig', array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
    * Creates a form to edit a Noticias entity.
    *
    * @param Noticias $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createEditForm(Noticias $entity)
    {
        $form = $this->createForm(new NoticiasType(), $entity, array(
            'action' => $this->generateUrl('noticias_update', array('id' => $entity->getId())),
            'method' => 'PUT',
        ));

        $form->add('submit', 'submit', array('label' => 'Update'));

        return $form;
    }
    /**
     * Edits an existing Noticias entity.
     *
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('ColeBundle:Noticias')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Noticias entity.');
        }

        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createEditForm($entity);
        $editForm->handleRequest($request);

        if ($editForm->isValid()) {
            $em->flush();

            return $this->redirect($this->generateUrl('noticias_edit', array('id' => $id)));
        }

        return $this->render('ColeBundle:Noticias:edit.html.twig', array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }
    /**
     * Deletes a Noticias entity.
     *
     */
    public function deleteAction(Request $request, $id)
    {
        $form = $this->createDeleteForm($id);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('ColeBundle:Noticias')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find Noticias entity.');
            }

            $em->remove($entity);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('noticias'));
    }

    /**
     * Creates a form to delete a Noticias entity by id.
     *
     * @param mixed $id The entity id
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm($id)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('noticias_delete', array('id' => $id)))
            ->setMethod('DELETE')
            ->add('submit', 'submit', array('label' => 'Delete'))
            ->getForm()
        ;
    }

    public function NoticiasRecientesAction()
    {
        $em = $this->getDoctrine()->getEntityManager();

        $noticias = $em->getRepository('ColeBundle:Noticias')->findRecientes();
        return $this->render('ColeBundle:Noticias:recientes.html.twig',
                array('noticias' => $noticias));
    }


    public function noticiaAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('ColeBundle:Noticias')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Noticias entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return $this->render('ColeBundle:Noticias:noticia.html.twig', array(
            'entity'      => $entity,
            'delete_form' => $deleteForm->createView(),
        ));
    }

        public function noticiasAction()
    {
        $em = $this->getDoctrine()->getEntityManager();

        $noticias = $em->getRepository('ColeBundle:Noticias')->findAll();
        return $this->render('ColeBundle:Noticias:noticias.html.twig',
                array('noticias' => $noticias));
    }


    

}




