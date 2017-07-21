<?php

namespace Cole\IntranetBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use Cole\IntranetBundle\Entity\Ausencia;
use Cole\IntranetBundle\Form\AusenciaType;

/**
 * Ausencia controller.
 *
 */
class AusenciaController extends Controller
{

    /**
     * Lists all Ausencia entities.
     *
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $entities = $em->getRepository('IntranetBundle:Ausencia')->findAll();

        return $this->render('IntranetBundle:Ausencia:index.html.twig', array(
            'entities' => $entities,
        ));
    }
    /**
     * Creates a new Ausencia entity.
     *
     */
    public function createAction(Request $request)
    {
        $profesor = $this->get('security.context')->getToken()->getUser();

        $entity = new Ausencia();
        $form = $this->createCreateForm($entity);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();

           //Se añade al formulario una variable extra de tipo entity con checkboxes para seleccionar.
            $extra = $form->get('faltas')->getData();
            //Se convierte el ArrayCollection en un array normal.
            $arr_faltas = $extra->toArray();

            $extra = $form->get('retrasos')->getData();
            $arr_retrasos = $extra->toArray();

            //Para cada checkbox marcado se crea una nueva ausencia y se guarda la falta con el resto de valores seleccionado.
            foreach ($arr_faltas as $alumno) {
                $entity = new Ausencia();
                $form = $this->createCreateForm($entity);
                $form->handleRequest($request);

                $alum = $em->getRepository('BackendBundle:Alumno')->findOneById($alumno);
                $entity->setAlumno($alum);  
                $entity->setTipo("Falta");      

                $em->persist($entity);
                $em->flush();
            }

            //Para cada checkbox marcado se crea una nueva ausencia y se guarda el retraso con el resto de valores seleccionado.
            foreach ($arr_retrasos as $alumno) {
                $entity = new Ausencia();
                $form = $this->createCreateForm($entity);
                $form->handleRequest($request);

                $alum = $em->getRepository('BackendBundle:Alumno')->findOneById($alumno);
                $entity->setAlumno($alum);  
                $entity->setTipo("Retraso");      

                $em->persist($entity);
                $em->flush();
            }

            $ms = $this->get('translator')->trans('Se ha guardado las ausencias de los alumnos correctamente.');
            $this->get('session')->getFlashBag()->add('notice',$ms);

            return $this->redirect($this->generateUrl('intranet_profesor_ausencia'));
        }

        return $this->render('IntranetBundle:Ausencia:new.html.twig', array(
            'entity' => $entity,
            'form'   => $form->createView(),
        ));
    }


    /**
     * Creates a form to create a Ausencia entity.
     *
     * @param Ausencia $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createCreateForm(Ausencia $entity)
    {
        $form = $this->createForm(new AusenciaType(), $entity, array(
            'action' => $this->generateUrl('ausencia_create'),
            'method' => 'POST',
        ));

        $form->add('submit', 'submit', array('label' => 'Create'));

        return $form;
    }

    /**
     * Displays a form to create a new Ausencia entity.
     *
     */
    public function newAction()
    {
        $entity = new Ausencia();
        $form   = $this->createCreateForm($entity);

        return $this->render('IntranetBundle:Ausencia:new.html.twig', array(
            'entity' => $entity,
            'form'   => $form->createView(),
        ));
    }

    /**
     * Finds and displays a Ausencia entity.
     *
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('IntranetBundle:Ausencia')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Ausencia entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return $this->render('IntranetBundle:Ausencia:show.html.twig', array(
            'entity'      => $entity,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing Ausencia entity.
     *
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('IntranetBundle:Ausencia')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Ausencia entity.');
        }

        $editForm = $this->createEditForm($entity);
        $deleteForm = $this->createDeleteForm($id);

        return $this->render('IntranetBundle:Ausencia:edit.html.twig', array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }


    public function eliminarAusenciaAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('IntranetBundle:Ausencia')->find($id);

        $deleteForm = $this->createDeleteForm($id);

        return $this->render('IntranetBundle:Ausencia:eliminarAusencia.html.twig', array(
            'entity'      => $entity,
            'delete_form' => $deleteForm->createView()
        ));
    }


    /**
    * Creates a form to edit a Ausencia entity.
    *
    * @param Ausencia $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createEditForm(Ausencia $entity)
    {
        $form = $this->createForm(new AusenciaType(), $entity, array(
            'action' => $this->generateUrl('ausencia_update', array('id' => $entity->getId())),
            'method' => 'PUT',
        ));

        $form->add('submit', 'submit', array('label' => 'Update'));

        return $form;
    }
    /**
     * Edits an existing Ausencia entity.
     *
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('IntranetBundle:Ausencia')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Ausencia entity.');
        }

        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createEditForm($entity);
        $editForm->handleRequest($request);

        if ($editForm->isValid()) {
            $em->flush();

            return $this->redirect($this->generateUrl('ausencia_edit', array('id' => $id)));
        }

        return $this->render('IntranetBundle:Ausencia:edit.html.twig', array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }
    /**
     * Deletes a Ausencia entity.
     *
     */
    public function deleteAction(Request $request, $id)
    {
        $form = $this->createDeleteForm($id);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('IntranetBundle:Ausencia')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find Ausencia entity.');
            }

            $em->remove($entity);
            $em->flush();
        }

        $ms = $this->get('translator')->trans('Se ha eliminado la falta de asistencia o restraso correctamente.');
        $this->get('session')->getFlashBag()->add('notice',$ms);
        return $this->redirect($this->generateUrl('intranet_profesor_ausencia'));
    }

    /**
     * Creates a form to delete a Ausencia entity by id.
     *
     * @param mixed $id The entity id
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm($id)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('ausencia_delete', array('id' => $id)))
            ->setMethod('DELETE')
            ->add('submit', 'submit', array('label' => "Eliminar", 'attr' => array('class' => 'btn btn-danger')))
            ->getForm()
        ;
    }
}
