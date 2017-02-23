<?php

namespace Cole\BackendBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;

use Cole\BackendBundle\Entity\Asignatura;
use Cole\BackendBundle\Entity\AsignaturasCursos;
use Cole\BackendBundle\Form\AsignaturaType;

/**
 * Asignatura controller.
 *
 */
class AsignaturaController extends Controller
{

    /**
     * Lists all Asignatura entities.
     *
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $entities = $em->getRepository('BackendBundle:Asignatura')->findBy(array(),array('nombre'=>'ASC'));

        return $this->render('BackendBundle:Asignatura:index.html.twig', array(
            'entities' => $entities,
        ));
    }

    public function asignaturasCursosAction()
    {
        $em = $this->getDoctrine()->getManager();
        $asignaturas= $em->getRepository('BackendBundle:Asignatura')->findNumAsignaturas();

        $entities = $em->getRepository('BackendBundle:Curso')->findBy(array('nivel'=>'Primaria'),array('numOrden'=>'ASC'));
        return $this->render('BackendBundle:Asignatura:asignaturas_cursos_show.html.twig', array(
            'entities' => $entities,
            'numAsignaturas' => (int)$asignaturas[1]
        ));
    }

    /**
     * Creates a new Asignatura entity.
     *
     */
    public function createAction(Request $request)
    {
      // if request is XmlHttpRequest (AJAX) but not a POSt, throw an exception
      if ($request->isXmlHttpRequest() && !$request->isMethod('POST')) {
        throw new HttpException('XMLHttpRequests/AJAX calls must be POSTed');}

        $entity = new Asignatura();
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

            return $this->redirect($this->generateUrl('asignatura_show', array('id' => $entity->getId())));
        }

        if ($request->isMethod('POST')) {
            return new JsonResponse(array(
            'result' => 0,
            'message' => 'Invalid form',
            'data' => $this->getErrorMessages($form,$form->getName()),
            'success' => false), 400);
        }

        return $this->render('BackendBundle:Asignatura:new.html.twig', array(
            'entity' => $entity,
            'form'   => $form->createView(),
        ));
    }

    /**
     * Creates a form to create a Asignatura entity.
     *
     * @param Asignatura $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createCreateForm(Asignatura $entity)
    {
        $form = $this->createForm(new AsignaturaType(), $entity, array(
            'action' => $this->generateUrl('asignatura_create'),
            'method' => 'POST',
        ));

        $form->add('submit', 'submit', array('label' => 'Insertar'));

        return $form;
    }

    /**
     * Displays a form to create a new Asignatura entity.
     *
     */
    public function newAction()
    {
        $entity = new Asignatura();
        $form   = $this->createCreateForm($entity);

        return $this->render('BackendBundle:Asignatura:new.html.twig', array(
            'entity' => $entity,
            'form'   => $form->createView(),
        ));
    }

    public function AsignaturasCursoNewAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $troncales = $em->getRepository('BackendBundle:Asignatura')->findBy(array("tipo"=>"Troncal"),array("nombre"=>"ASC"));
        $especificas = $em->getRepository('BackendBundle:Asignatura')->findBy(array("tipo"=>"Específica"),array("nombre"=>"ASC"));
        $entities_troncales = $em->getRepository('BackendBundle:AsignaturasCursos')->findAsignaturasTroncalesCurso($id);
        $entities_especificas = $em->getRepository('BackendBundle:AsignaturasCursos')->findAsignaturasEspecificasCurso($id);
        $curso= $em->getRepository('BackendBundle:Curso')->findOneById($id);

        return $this->render('BackendBundle:Asignatura:new_asignaturas_curso.html.twig', array(
            'troncales' => $troncales,
            'especificas' => $especificas,
            'entities_troncales' => $entities_troncales,
            'entities_especificas' => $entities_especificas,
            'curso' => $curso,
        ));
    }
    

    /**
     * Finds and displays a Asignatura entity.
     *
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('BackendBundle:Asignatura')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Asignatura entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return $this->render('BackendBundle:Asignatura:show.html.twig', array(
            'entity'      => $entity,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing Asignatura entity.
     *
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('BackendBundle:Asignatura')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Asignatura entity.');
        }

        $editForm = $this->createEditForm($entity);
        $deleteForm = $this->createDeleteForm($id);

        return $this->render('BackendBundle:Asignatura:edit.html.twig', array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
    * Creates a form to edit a Asignatura entity.
    *
    * @param Asignatura $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createEditForm(Asignatura $entity)
    {
        $form = $this->createForm(new AsignaturaType(), $entity, array(
            'action' => $this->generateUrl('asignatura_update', array('id' => $entity->getId())),
            'method' => 'PUT',
        ));

        $form->add('submit', 'submit', array('label' => 'Actualizar'));

        return $form;
    }
    /**
     * Edits an existing Asignatura entity.
     *
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('BackendBundle:Asignatura')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Asignatura entity.');
        }

        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createEditForm($entity);
        $editForm->handleRequest($request);

        if ($editForm->isValid()) {
            $em->flush();

            return $this->redirect($this->generateUrl('asignatura_edit', array('id' => $id)));
        }

        return $this->render('BackendBundle:Asignatura:edit.html.twig', array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }
    /**
     * Deletes a Asignatura entity.
     *
     */
    public function deleteAction(Request $request, $id)
    {
        $form = $this->createDeleteForm($id);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('BackendBundle:Asignatura')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find Asignatura entity.');
            }

            $em->remove($entity);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('asignatura'));
    }

    /**
     * Creates a form to delete a Asignatura entity by id.
     *
     * @param mixed $id The entity id
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm($id)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('asignatura_delete', array('id' => $id)))
            ->setMethod('DELETE')
            ->add('submit', 'submit', array('label' => 'Eliminar'))
            ->getForm()
        ;
    }

    public function AsignaturasCursoAction($id)
    {
        $em = $this->getDoctrine()->getManager();
        $entity = $em->getRepository('BackendBundle:AsignaturasCursos')->findAsignaturasCursos($id);
        return $this->render('BackendBundle:Asignatura:asignaturas_por_curso.html.twig', array(
            'entity' => $entity));
    }

    public function ComprobarAsignaturasAction()
    {
        $em = $this->getDoctrine()->getManager();
        $data =$em->getRepository('BackendBundle:Asignatura')->findAll();

        return new JsonResponse(array('data' => $data,'success' => true), 200);
    }


    public function AsignarAsignaturasCursoAction()
    {
        $em = $this->getDoctrine()->getManager();

        $idcurso=$this->get('request')->request->get('curso');
        $nuevas=$this->get('request')->request->get('nuevas');
        $asignadas=$this->get('request')->request->get('asignadas');
        $eliminadas=$this->get('request')->request->get('eliminadas');
        $data=1;
        if($nuevas==null && $asignadas==null && $eliminadas==null ){
            $data=null;
            return new JsonResponse(array('data' => $data), 200);
        }
        if($nuevas){
          foreach ($nuevas as $key => $value ) {
            $asignatura=$em->getRepository('BackendBundle:Asignatura')->findOneById($key);
            $curso=$em->getRepository('BackendBundle:Curso')->findOneById($idcurso);

            $entity = new AsignaturasCursos();
            $entity->setAsignatura($asignatura);
            $entity->setCurso($curso);
            $entity->setNumModulos($value);
            $em->persist($entity);
          }   
        }

        if($asignadas){
          foreach ($asignadas as $key => $value ) {

            $entity = $em->getRepository('BackendBundle:AsignaturasCursos')->findAsignacion($idcurso,$key);
            $entity->setNumModulos($value);
            $em->persist($entity);
          }  
        }
        
        $em->flush();

        if($eliminadas){
          foreach ($eliminadas as $eliminada ) {
            $entity = $em->getRepository('BackendBundle:AsignaturasCursos')->findAsignacion($idcurso,$eliminada);
            $query = $em->createQuery('DELETE BackendBundle:AsignaturasCursos r WHERE r.id = :Id')->setParameter("Id", $entity->getId());
            $query->execute();
          }  
        }
        return new JsonResponse(array('data' => $data,'success' => true), 200);
    }



}
