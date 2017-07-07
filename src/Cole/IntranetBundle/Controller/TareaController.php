<?php

namespace Cole\IntranetBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Cole\IntranetBundle\Entity\Cursa;

use Cole\IntranetBundle\Entity\Tarea;
use Cole\IntranetBundle\Form\TareaType;

/**
 * Tarea controller.
 *
 */
class TareaController extends Controller
{

    /**
     * Lists all Tarea entities.
     *
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $entities = $em->getRepository('IntranetBundle:Tarea')->findAll();

        return $this->render('IntranetBundle:Tarea:index.html.twig', array(
            'entities' => $entities,
        ));
    }
    /**
     * Creates a new Tarea entity.
     *
     */
    public function createAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $profesor = $this->get('security.context')->getToken()->getUser();

        $entity = new Tarea();
        $form = $this->createCreateForm($entity);
        $form->handleRequest($request);

        if ($form->isValid()) {
            //Se añade al formulario una variable extra de tipo entity con checkboxes para seleccionar.
            $extra = $form->get('seleccion')->getData();
            //Se convierte el ArrayCollection en un array normal.
            $arr = $extra->toArray();

            $contador=0;
            $grupos=0;
            $array="";

            //Para cada checkbox marcado se crea una nueva reserva y se guarda con el resto de valores seleccionado.
            foreach ($arr as $idgrupo) {
                $grupo = $em->getRepository('BackendBundle:Grupo')->findOneById($idgrupo);
                $grupos++;
                $tarea = $em->getRepository('IntranetBundle:Tarea')->findTareaByProfesorGrupo($entity->getDescripcion(),$profesor,$grupo);
                if($tarea){
                    $array=$array.$grupo->getCurso()->getCurso().$grupo->getLetra()." - ";
                }
                else{
                    $entity = new Tarea();
                    $form = $this->createCreateForm($entity);
                    $form->handleRequest($request);

                    $entity->setGrupo($grupo);       
                    $entity->setProfesor($profesor);
                    //Se obtiene la asignatura del curso, ya que en el formulario hay varios grupos para una asignaturacon un solo id.
                    $asignatura=$em->getRepository('BackendBundle:AsignaturasCursos')->findAsignacionNombre($grupo->getCurso(), $entity->getAsignatura()->getAsignatura()->getNombre()); 
                    $entity->setAsignatura($asignatura);  

                    $entity->setFecha(new \DateTime("now"));
                    $em->persist($entity);
                    $em->flush();

                    $contador++;
                }

                
                //Se obtiene la tarea creada para añadirsela al alumno en la relación "cursa".
                $ultimaTarea = $em->getRepository('IntranetBundle:Tarea')->findUltimaTarea($profesor);

                $trimestre= $form->get('trimestre')->getData();
                //Se obtiene la lista de alumnos comprobando si es una asignatura opcional o no.
                if( $ultimaTarea->getAsignatura()->getAsignatura()->getOpcional() == 0){
                    $alumnos= $em->getRepository('BackendBundle:Alumno')->findByGrupo($grupo);
                }
                else{
                    $alumnos= $em->getRepository('BackendBundle:Alumno')->findAlumnosOptativaGrupo($ultimaTarea->getAsignatura(),$grupo);
                }

                foreach ($alumnos as $alumno) {
                    $entity = new Cursa();
                    $entity->setTrimestre($trimestre);
                    $entity->setNota(null);
                    $entity->setAlumno($alumno);
                    $entity->setAsignaturasCursos($ultimaTarea->getAsignatura());
                    $entity->setTarea($ultimaTarea);
                    $em->persist($entity);
                    $em->flush();
                }

            }

            $cursos_denegados = trim($array, ' - ');
            if($contador>0){
                if($contador>1){
                    $ms = $this->get('translator')->trans('Se ha registrado la tarea en %$contador% grupos correctamente.',array('%$contador%' =>$contador ));
                }
                else{
                    $ms = $this->get('translator')->trans('Se ha registrado la tarea en el grupo correctamente.');
                }
                $this->get('session')->getFlashBag()->add('notice',$ms);
            }

            $ms_danger="";
            if($cursos_denegados != ""){
                if(((int)substr_count($cursos_denegados, "-")+(int)1)>1){
                    $ms_danger = $this->get('translator')->trans('Los grupos %$cursos_denegado% ya dispone de esta tarea.',array('%$cursos_denegado%' =>$cursos_denegados ));
                }
                else{
                    $ms_danger = $this->get('translator')->trans('El grupo %$cursos_denegado% ya dispone de esta tarea.',array('%$cursos_denegado%' =>$cursos_denegados ));
                }
                $this->get('session')->getFlashBag()->add('danger',$ms_danger);
            }

            return $this->redirect($this->generateUrl('intranet_profesor_calificaciones'));
        }

        return $this->render('intranet_profesor_calificaciones', array(
            'entity' => $entity,
            'form'   => $form->createView(),
        ));
    }

    /**
     * Creates a form to create a Tarea entity.
     *
     * @param Tarea $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createCreateForm(Tarea $entity)
    {
        $form = $this->createForm(new TareaType(), $entity, array(
            'action' => $this->generateUrl('tarea_create'),
            'method' => 'POST',
        ));

        $form->add('submit', 'submit', array('label' => 'Create'));

        return $form;
    }

    /**
     * Displays a form to create a new Tarea entity.
     *
     */
    public function newAction()
    {
        $entity = new Tarea();
        $form   = $this->createCreateForm($entity);

        return $this->render('IntranetBundle:Tarea:new.html.twig', array(
            'entity' => $entity,
            'form'   => $form->createView(),
        ));
    }

    /**
     * Finds and displays a Tarea entity.
     *
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('IntranetBundle:Tarea')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Tarea entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return $this->render('IntranetBundle:Tarea:show.html.twig', array(
            'entity'      => $entity,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing Tarea entity.
     *
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('IntranetBundle:Tarea')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Tarea entity.');
        }

        $editForm = $this->createEditForm($entity);
        $deleteForm = $this->createDeleteForm($id);

        return $this->render('IntranetBundle:Tarea:edit.html.twig', array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
    * Creates a form to edit a Tarea entity.
    *
    * @param Tarea $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createEditForm(Tarea $entity)
    {
        $form = $this->createForm(new TareaType(), $entity, array(
            'action' => $this->generateUrl('tarea_update', array('id' => $entity->getId())),
            'method' => 'PUT',
        ));

        $form->add('submit', 'submit', array('label' => 'Update'));

        return $form;
    }
    /**
     * Edits an existing Tarea entity.
     *
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('IntranetBundle:Tarea')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Tarea entity.');
        }

        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createEditForm($entity);
        $editForm->handleRequest($request);

        if ($editForm->isValid()) {
            $em->flush();

            return $this->redirect($this->generateUrl('tarea_edit', array('id' => $id)));
        }

        return $this->render('IntranetBundle:Tarea:edit.html.twig', array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }
    /**
     * Deletes a Tarea entity.
     *
     */
    public function deleteAction(Request $request, $id)
    {
        $form = $this->createDeleteForm($id);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('IntranetBundle:Tarea')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find Tarea entity.');
            }

            $em->remove($entity);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('tarea'));
    }

    /**
     * Creates a form to delete a Tarea entity by id.
     *
     * @param mixed $id The entity id
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm($id)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('tarea_delete', array('id' => $id)))
            ->setMethod('DELETE')
            ->add('submit', 'submit', array('label' => 'Delete'))
            ->getForm()
        ;
    }
}
