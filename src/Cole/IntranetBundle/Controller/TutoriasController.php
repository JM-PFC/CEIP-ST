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
        $consulta_principal=$form->get("seguimiento")->getData();

        $em = $this->getDoctrine()->getManager();
        if($consulta_principal){
            $consulta = $em->getRepository('IntranetBundle:Seguimiento')->findOneById($consulta_principal->getId());
        }

        if ($form->isValid()) {
            if(!$consulta_principal){
                //Se crea el nuevo seguimiento de la asignación de tutoría.
                $seguimiento = new Seguimiento();
                $seguimiento->setProfesor($entity->getProfesor());
                $seguimiento->setAlumno($entity->getAlumno());
                $seguimiento->setResponsable(null);
                $seguimiento->setAsignatura(null);
                $seguimiento->setGrupo($grupo);
                $seguimiento->setTipo(1);
                $seguimiento->setTipoUser(1);
                $seguimiento->setDescripcion($descripcion);
                $seguimiento->setFecha(new \DateTime("now"));
                $seguimiento->setFechaActualizada(new \DateTime("now"));
                $seguimiento->setSeguimiento(null);
                $seguimiento->setRespuesta(0);
                $seguimiento->setFechaTerminada(null);
                
                $em->persist($seguimiento);
                $em->flush();

                //Se obtiene el seguimiento creado para añadirselo al registro de tutoría.
                $comentario = $em->getRepository('IntranetBundle:Seguimiento')->findUltimoAvisoTutoria($entity->getProfesor()->getId(), $tipo);

                //Se asigna los demás valores de tutorias.
                $entity->setActivo(0);
                $entity->setSeguimiento($comentario);

            }else{
                //Se crea el nuevo seguimiento de la asignación de tutoría.
                $seguimiento = new Seguimiento();
                $seguimiento->setProfesor($entity->getProfesor());
                $seguimiento->setAlumno($entity->getAlumno());
                $seguimiento->setResponsable(null);
                $seguimiento->setAsignatura(null);
                $seguimiento->setGrupo($grupo);
                $seguimiento->setTipo(0);
                $seguimiento->setTipoUser(1);
                $seguimiento->setDescripcion($descripcion);
                $seguimiento->setFecha(new \DateTime("now"));
                $seguimiento->setFechaActualizada(new \DateTime("now"));
                $seguimiento->setSeguimiento($consulta);
                $seguimiento->setRespuesta(0);
                $seguimiento->setFechaTerminada(null);

                $em->persist($seguimiento);
                $em->flush();

                //Se modifica la consulta principal para que se muestre actualizada.
                $consulta->setRespuesta(1);
                //$consulta->setFechaActualizada(new \DateTime("now"));
                $em->persist($consulta);
                $em->flush();

                //Se asigna los demás valores de tutorias.
                $entity->setActivo(0);
                $entity->setSeguimiento($consulta);
            }

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
            'idalumno' => null,
            'consulta' => null,     
    
        ));
    }

    public function asignarTutoriaConsultaAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();
        $entity = new Tutorias();
        $form   = $this->createCreateForm($entity);
        
        $entity = $this->get('security.context')->getToken()->getUser();
        $tutor_grupo= $em->getRepository('BackendBundle:Grupo')->findOneByProfesor($entity);
        $consulta= $em->getRepository('IntranetBundle:Seguimiento')->findOneById($id);

        $alumno= $consulta->getAlumno();

        $inicio =$em->getRepository('BackendBundle:Centro')->findInicioCurso();
        $fin =$em->getRepository('BackendBundle:Centro')->findFinCurso();
   
        return $this->render('IntranetBundle:Profesor:asignarTutorias.html.twig', array(
            'entity' => $entity,
            'form'   => $form->createView(),
            'inicio' => $inicio,
            'fin' => $fin,      
            'grupo' => $tutor_grupo,
            'idalumno' => $alumno->getId(),
            'alumno' => $alumno, 
            'consulta' => $consulta->getId(),
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

    public function eliminarTutoriasAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('IntranetBundle:Tutorias')->findById($id);

        $deleteForm = $this->createDeleteForm($id);

        return $this->render('IntranetBundle:Tutorias:eliminarTutorias.html.twig', array(
            'entity'      => $entity,
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
            $tutoria=$em->getRepository('IntranetBundle:Tutorias')->findOneById($id);
            $alumno=$tutoria->getAlumno();
            $profesor=$tutoria->getProfesor();

            //Se añade un comentario para mostrar como aviso del sistema (FechaActualizada == null)
            $seguimiento = new Seguimiento();
            $seguimiento->setProfesor($alumno->getGrupo()->getProfesor());
            $seguimiento->setAlumno($alumno);
            $seguimiento->setResponsable($tutoria->getResponsable());
            $seguimiento->setAsignatura(null);
            $seguimiento->setGrupo($alumno->getGrupo());
            $seguimiento->setTipo(0);
            if ($this->get('security.context')->isGranted('ROLE_PROFESOR')) {
                $seguimiento->setTipoUser(1);
                if($profesor->getSexo() == "Masculino"){
                    $seguimiento->setDescripcion("El tutor ha cancelado la tutoría asiganda.");
                }else{
                    $seguimiento->setDescripcion("La tutora ha cancelado la tutoría asiganda.");
                }
            }
            else if ($this->get('security.context')->isGranted('ROLE_USUARIO')){
                $seguimiento->setTipoUser(0);
                $seguimiento->setDescripcion("El responsable ha cancelado la tutoría asiganda.");
            }
            $seguimiento->setFecha(new \DateTime("now"));
            $seguimiento->setFechaActualizada(null);
            $seguimiento->setSeguimiento($tutoria->getSeguimiento());
            $seguimiento->setRespuesta(0);
            $seguimiento->setFechaTerminada(null);

            $em->persist($seguimiento);
            $em->flush();

            //Se modifica el valor de respuesta en la consulta principal
            $consulta_principal=$tutoria->getSeguimiento();
            $consulta_principal->setRespuesta(1);
            $em->persist($consulta_principal);
            $em->flush();

            //Se elimina la tutoría pendiente.
            $em->remove($tutoria);
            $em->flush();

            $ms = $this->get('translator')->trans('La tutoría ha sido cancelada.');
            $this->get('session')->getFlashBag()->add('notice',$ms);

            if ($this->get('security.context')->isGranted('ROLE_PROFESOR')) {
                    return $this->redirect($this->generateUrl('intranet_profesor_tutorias'));
            }
            else if ($this->get('security.context')->isGranted('ROLE_USUARIO')){
                    return $this->redirect($this->generateUrl('intranet_alumno_tutorias', array('id'=>$alumno->getId())));
            }
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
            ->add('submit', 'submit', array('label' => "Eliminar", 'attr' => array('class' => 'btn btn-danger')))
            ->getForm()
        ;
    }
}
