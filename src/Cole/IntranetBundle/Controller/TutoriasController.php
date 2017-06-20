<?php

namespace Cole\IntranetBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use Cole\IntranetBundle\Entity\Tutorias;
use Cole\IntranetBundle\Form\TutoriasType;
use Cole\IntranetBundle\Entity\Seguimiento;
use Cole\IntranetBundle\Form\SeguimientoType;

/**
 * Tutorias controller.
 *
 */
class TutoriasController extends Controller
{

    /**
     * Lists all Tutorias entities.
     *
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $entities = $em->getRepository('IntranetBundle:Tutorias')->findAll();

        return $this->render('IntranetBundle:Tutorias:index.html.twig', array(
            'entities' => $entities,
        ));
    }
    /**
     * Creates a new Tutorias entity.
     *
     */
    public function createAction(Request $request)
    {
        $entity = new Tutorias();
        $form = $this->createCreateForm($entity);
        $form->handleRequest($request);

        $tipo=$form->get("tipo")->getData();
        $descripcion=$form->get("descripcion")->getData();
        $grupo=$form->get("grupo")->getData();

        if ($form->isValid()) {
            //Se crea el nuevo seguimiento de la asignación de tutoría.
            $seguimiento = new Seguimiento();
            $seguimiento->setProfesor($entity->getProfesor());
            $seguimiento->setAlumno($entity->getAlumno());
            $seguimiento->setResponsable(null);
            $seguimiento->setAsignatura(null);
            $seguimiento->setGrupo($grupo);
            $seguimiento->setTipo($tipo);
            $seguimiento->setTipoUser(1);
            $seguimiento->setDescripcion($descripcion);
            $seguimiento->setFecha(new \DateTime("now"));
            $seguimiento->setFechaActualizada(new \DateTime("now"));
            $seguimiento->setSeguimiento(null);
            $seguimiento->setRespuesta(0);
            $seguimiento->setFechaTerminada(null);

            $em = $this->getDoctrine()->getManager();
            $em->persist($seguimiento);
            $em->flush();

            //Se obtiene el seguimiento creado para añadirselo al registro de tutoría.
            $comentario = $em->getRepository('IntranetBundle:Seguimiento')->findUltimoAvisoTutoria($entity->getProfesor()->getId(), $tipo);

            //Se asigna los demás valores de tutorias.
            $entity->setActivo(0);
            $entity->setSeguimiento($comentario);

            $em->persist($entity);
            $em->flush();

            $ms = $this->get('translator')->trans('Se ha asignado una nueva tutoría pendiente de confirmación.');
            $this->get('session')->getFlashBag()->add('notice',$ms);

            return $this->redirect($this->generateUrl('intranet_profesor_tutorias'));
        }

        return $this->render('IntranetBundle:Profesor:asignarTutorias.html.twig', array(
            'entity' => $entity,
            'form'   => $form->createView(),
        ));
    }

    /**
     * Creates a form to create a Tutorias entity.
     *
     * @param Tutorias $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createCreateForm(Tutorias $entity)
    {
        $form = $this->createForm(new TutoriasType(), $entity, array(
            'action' => $this->generateUrl('tutorias_create'),
            'method' => 'POST',
        ));

        $form->add('submit', 'submit', array('label' => 'Guardar Asignación'));

        return $form;
    }

    /**
     * Displays a form to create a new Tutorias entity.
     *
     */
    public function newAction()
    {
        $em = $this->getDoctrine()->getManager();

        $entity = new Tutorias();
        $form   = $this->createCreateForm($entity);

        $inicio =$em->getRepository('BackendBundle:Centro')->findInicioCurso();
        $fin =$em->getRepository('BackendBundle:Centro')->findFinCurso();

        return $this->render('IntranetBundle:Tutorias:new.html.twig', array(
            'entity' => $entity,
            'form'   => $form->createView(),
            'inicio' => $inicio,
            'fin' => $fin,
        ));
    }

        public function asignarTutoriaShowAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $entity = new Tutorias();
        $form   = $this->createCreateForm($entity);
        
        $entity = $this->get('security.context')->getToken()->getUser();
        $tutor_grupo= $em->getRepository('BackendBundle:Grupo')->findOneByProfesor($entity);


        $inicio =$em->getRepository('BackendBundle:Centro')->findInicioCurso();
        $fin =$em->getRepository('BackendBundle:Centro')->findFinCurso();
   
        return $this->render('IntranetBundle:Profesor:asignarTutorias.html.twig', array(
            'entity' => $entity,
            'form'   => $form->createView(),
            'inicio' => $inicio,
            'fin' => $fin,      
            'grupo' => $tutor_grupo,    
    
        ));
    }


    /**
     * Finds and displays a Tutorias entity.
     *
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('IntranetBundle:Tutorias')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Tutorias entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return $this->render('IntranetBundle:Tutorias:show.html.twig', array(
            'entity'      => $entity,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing Tutorias entity.
     *
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('IntranetBundle:Tutorias')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Tutorias entity.');
        }

        $editForm = $this->createEditForm($entity);
        $deleteForm = $this->createDeleteForm($id);

        return $this->render('IntranetBundle:Tutorias:edit.html.twig', array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
    * Creates a form to edit a Tutorias entity.
    *
    * @param Tutorias $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createEditForm(Tutorias $entity)
    {
        $form = $this->createForm(new TutoriasType(), $entity, array(
            'action' => $this->generateUrl('tutorias_update', array('id' => $entity->getId())),
            'method' => 'PUT',
        ));

        $form->add('submit', 'submit', array('label' => 'Update'));

        return $form;
    }
    /**
     * Edits an existing Tutorias entity.
     *
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('IntranetBundle:Tutorias')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Tutorias entity.');
        }

        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createEditForm($entity);
        $editForm->handleRequest($request);

        if ($editForm->isValid()) {
            $em->flush();

            return $this->redirect($this->generateUrl('tutorias_edit', array('id' => $id)));
        }

        return $this->render('IntranetBundle:Tutorias:edit.html.twig', array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }
    /**
     * Deletes a Tutorias entity.
     *
     */
    public function deleteAction(Request $request, $id)
    {
        $form = $this->createDeleteForm($id);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('IntranetBundle:Tutorias')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find Tutorias entity.');
            }

            $em->remove($entity);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('tutorias'));
    }

    /**
     * Creates a form to delete a Tutorias entity by id.
     *
     * @param mixed $id The entity id
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm($id)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('tutorias_delete', array('id' => $id)))
            ->setMethod('DELETE')
            ->add('submit', 'submit', array('label' => 'Eliminar'))
            ->getForm()
        ;
    }
}
