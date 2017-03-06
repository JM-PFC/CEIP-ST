<?php

namespace Cole\BackendBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;

use Cole\BackendBundle\Entity\Imparte;
use Cole\BackendBundle\Form\ImparteType;

/**
 * Imparte controller.
 *
 */
class ImparteController extends Controller
{

    /**
     * Lists all Imparte entities.
     *
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $entities = $em->getRepository('BackendBundle:Imparte')->findAll();

        return $this->render('BackendBundle:Imparte:index.html.twig', array(
            'entities' => $entities,
        ));
    }

    public function AsignarGrupoProfesoresShowAction()
    {
        $em = $this->getDoctrine()->getManager();

        $asignaturas= $em->getRepository('BackendBundle:Asignatura')->findNumAsignaturas();
        $profesores= $em->getRepository('BackendBundle:Profesor')->findByActivo(1);
        if (!$profesores) {
            $numProfesores=0;
        }
        else{
            $numProfesores=1;
        }

        $entities = $em->getRepository('BackendBundle:Grupo')->findGruposByNivel("Primaria");
        return $this->render('BackendBundle:Imparte:profesor_asignaturas_grupos_show.html.twig', array(
            'entities' => $entities,
            'numAsignaturas' => (int)$asignaturas[1],
            'numProfesores' => $numProfesores,
        ));
    }
    public function AsignarGrupoProfesoresNewAction($id)
    {
        $em = $this->getDoctrine()->getManager();
        $grupo= $em->getRepository('BackendBundle:Grupo')->findOneById($id);
        $curso= $grupo->getCurso();

        $entities_troncales = $em->getRepository('BackendBundle:AsignaturasCursos')->findAsignaturasTroncalesCurso($curso->getId());
        $entities_especificas = $em->getRepository('BackendBundle:AsignaturasCursos')->findAsignaturasEspecificasCurso($curso->getId());
        $imparte = $em->getRepository('BackendBundle:Imparte')->findByGrupo($id);
        
        $array=[];
        foreach($imparte as $registro){
            $array[$registro->getAsignatura()->getId()]=$registro->getProfesor()->getNombre()." ".$registro->getProfesor()->getApellido1()." ".$registro->getProfesor()->getApellido2();
        }
        $tutor=$grupo->getProfesor();
        if($tutor){
            $id_tutor=$tutor->getId();   
        }
        else{
            $id_tutor=null; 
        }

        $profesores = $em->getRepository('BackendBundle:Profesor')->findProfesoresDePrimaria();


        return $this->render('BackendBundle:Imparte:new_profesores_asignaturas_grupo.html.twig', array(
            'entities_troncales' => $entities_troncales,
            'entities_especificas' => $entities_especificas,
            'curso' => $curso,
            'grupo' => $grupo,
            'imparte' => $array,
            'tutor' => $tutor,
            'id_tutor'=>$id_tutor,
            'profesores' => $profesores
        ));
    }


    /**
     * Creates a new Imparte entity.
     *
     */
    public function createAction(Request $request)
    {
        $entity = new Imparte();
        $form = $this->createCreateForm($entity);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('imparte_show', array('id' => $entity->getId())));
        }

        return $this->render('BackendBundle:Imparte:new.html.twig', array(
            'entity' => $entity,
            'form'   => $form->createView(),
        ));
    }

    /**
     * Creates a form to create a Imparte entity.
     *
     * @param Imparte $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createCreateForm(Imparte $entity)
    {
        $form = $this->createForm(new ImparteType(), $entity, array(
            'action' => $this->generateUrl('imparte_create'),
            'method' => 'POST',
        ));

        $form->add('submit', 'submit', array('label' => 'Create'));

        return $form;
    }

    /**
     * Displays a form to create a new Imparte entity.
     *
     */
    public function newAction()
    {
        $entity = new Imparte();
        $form   = $this->createCreateForm($entity);

        return $this->render('BackendBundle:Imparte:new.html.twig', array(
            'entity' => $entity,
            'form'   => $form->createView(),
        ));
    }

    /**
     * Finds and displays a Imparte entity.
     *
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('BackendBundle:Imparte')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Imparte entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return $this->render('BackendBundle:Imparte:show.html.twig', array(
            'entity'      => $entity,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing Imparte entity.
     *
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('BackendBundle:Imparte')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Imparte entity.');
        }

        $editForm = $this->createEditForm($entity);
        $deleteForm = $this->createDeleteForm($id);

        return $this->render('BackendBundle:Imparte:edit.html.twig', array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
    * Creates a form to edit a Imparte entity.
    *
    * @param Imparte $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createEditForm(Imparte $entity)
    {
        $form = $this->createForm(new ImparteType(), $entity, array(
            'action' => $this->generateUrl('imparte_update', array('id' => $entity->getId())),
            'method' => 'PUT',
        ));

        $form->add('submit', 'submit', array('label' => 'Update'));

        return $form;
    }
    /**
     * Edits an existing Imparte entity.
     *
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('BackendBundle:Imparte')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Imparte entity.');
        }

        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createEditForm($entity);
        $editForm->handleRequest($request);

        if ($editForm->isValid()) {
            $em->flush();

            return $this->redirect($this->generateUrl('imparte_edit', array('id' => $id)));
        }

        return $this->render('BackendBundle:Imparte:edit.html.twig', array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }
    /**
     * Deletes a Imparte entity.
     *
     */
    public function deleteAction(Request $request, $id)
    {
        $form = $this->createDeleteForm($id);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('BackendBundle:Imparte')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find Imparte entity.');
            }

            $em->remove($entity);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('imparte'));
    }

    /**
     * Creates a form to delete a Imparte entity by id.
     *
     * @param mixed $id The entity id
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm($id)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('imparte_delete', array('id' => $id)))
            ->setMethod('DELETE')
            ->add('submit', 'submit', array('label' => 'Delete'))
            ->getForm()
        ;
    }

    public function DatosImparteAction() 
    {
        $dia_semanal=$this->get('request')->request->get('dia_semanal');
        $ini=$this->get('request')->request->get('ini');
        $fin=$this->get('request')->request->get('fin');
        $profesor=$this->get('request')->request->get('profesor');

        $em = $this->getDoctrine()->getEntityManager();

        $datos= $em->getRepository('BackendBundle:Imparte')->findByDatos($dia_semanal,$ini,$fin,$profesor);

        if($datos){
            return new JsonResponse(array('data' =>"existe",
                'nivel'=>$datos->getGrupo()->getCurso()->getNivel(),
                'curso'=>$datos->getGrupo()->getCurso()->getCurso(),
                'grupo' =>$datos->getGrupo()->getLetra(),
                'asignatura'=>$datos->getAsignatura()->getNombre()), 200);
        }
        else{
            return new JsonResponse(array('data' =>null), 200);
        }
    }

    public function ProfesoresAsignaturasGruposAction($id)
    {
        $em = $this->getDoctrine()->getManager();
        $grupo = $em->getRepository('BackendBundle:Grupo')->findOneById($id);
        $entity = $em->getRepository('BackendBundle:AsignaturasCursos')->findAsignaturasCursos($grupo->getCurso()->getId());
        $imparte = $em->getRepository('BackendBundle:Imparte')->findByGrupo($id);
        
        $array=[];
        foreach($imparte as $registro){
            $array[$registro->getGrupo()->getId()."-".$registro->getAsignatura()->getId()]=$registro->getProfesor()->getNombre()." ".$registro->getProfesor()->getApellido1()." ".$registro->getProfesor()->getApellido2();
        }

        return $this->render('BackendBundle:Imparte:datos_imparte.html.twig', array(
            'imparte' => $array,
            'entity' => $entity));
    }


    public function AsignarGrupoProfesoresAction()
    {
        $em = $this->getDoctrine()->getManager();

        $asignaciones=$this->get('request')->request->get('asignaciones');
        $eliminados=$this->get('request')->request->get('eliminados');
        $idgrupo=$this->get('request')->request->get('grupo');

        $data=1;
        if($asignaciones==null && $eliminados==null){
            $data=null;
            return new JsonResponse(array('data' => $data), 200);
        }

        if($asignaciones){
          foreach ($asignaciones as $asignatura => $id ) {
            $profesor = $em->getRepository('BackendBundle:Profesor')->findOneById($id);
            if (!$profesor) {
                throw $this->createNotFoundException('Unable to find Profesor entity.');
            }
            $asignatura = $em->getRepository('BackendBundle:AsignaturasCursos')->findOneById($asignatura);
            if (!$asignatura) {
                throw $this->createNotFoundException('Unable to find AsignaturasCursos entity.');
            }
            else{
                $imparte= $em->getRepository('BackendBundle:Imparte')->findOneByAsignatura($asignatura);
            }
            if ($imparte) {
                $imparte->setProfesor($profesor);
                $imparte->setAsignatura($asignatura);

                //Validar cambio de profesor con horarios de los cursos asignados. (No puede repetirse hora y día de un profesor)
            }
            else{

                $grupo = $em->getRepository('BackendBundle:Grupo')->findOneById($idgrupo);
                if (!$grupo) {
                 throw $this->createNotFoundException('Unable to find Grupo entity.');
                }

                $imparte = new Imparte();
                $imparte->setProfesor($profesor);
                $imparte->setAsignatura($asignatura);
                $imparte->setGrupo($grupo);
                $imparte->setHorario(null);
                $imparte->setLibro(null);
                $imparte->setDiaSemanal(null);
            }

            $em->persist($imparte);
          }   
        }

        if($eliminados){
          foreach ($eliminados as $asignatura ) {
            $asignatura = $em->getRepository('BackendBundle:AsignaturasCursos')->findOneById($asignatura);
            if (!$asignatura) {
                throw $this->createNotFoundException('Unable to find Asignatura entity.');
            }
            $grupo = $em->getRepository('BackendBundle:Grupo')->findOneById($idgrupo);
            if (!$grupo) {
                throw $this->createNotFoundException('Unable to find Grupo entity.');
            }

            $imparte = $em->getRepository('BackendBundle:Imparte')->findByGrupoAndAsignatura($grupo,$asignatura);

            if (!$imparte) {
                throw $this->createNotFoundException('Unable to find Imparte entity.');
            }
            $em->remove($imparte);
          }  
        }
        $em->flush();
        return new JsonResponse(array('data' => $data,'success' => true), 200);
    }


    public function EliminarAsignacionesGrupoAction()
    {
        $em = $this->getDoctrine()->getManager();

        $idgrupo=$this->get('request')->request->get('grupo');

        $grupo = $em->getRepository('BackendBundle:Grupo')->findOneById($idgrupo);
        if (!$grupo) {
            throw $this->createNotFoundException('Unable to find Grupo entity.');
        }

        $imparte= $em->getRepository('BackendBundle:Imparte')->findByGrupo($grupo);
        
        $data=1;
        if(!$imparte){
            $data=null;
            return new JsonResponse(array('data' => $data), 200);
        }
        else{
            foreach ($imparte as $asignacion ) {
                $em->remove($asignacion);
            }  
            $em->flush();
            return new JsonResponse(array('data' => $data,'success' => true), 200);
        }
    }

    public function EliminarTodasAsignacionesGruposAction()
    {
        $em = $this->getDoctrine()->getManager();

        $imparte= $em->getRepository('BackendBundle:Imparte')->findAll();
        
        $data=1;
        if(!$imparte){
            $data=null;
            return new JsonResponse(array('data' => $data), 200);
        }
        else{
            foreach ($imparte as $asignacion ) {
                $em->remove($asignacion);
            }  
            $em->flush();
            return new JsonResponse(array('data' => $data,'success' => true), 200);
        }
    }

}
