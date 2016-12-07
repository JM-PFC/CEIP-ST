<?php

namespace Cole\BackendBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;

use Cole\BackendBundle\Entity\Eventos;
use Cole\BackendBundle\Form\EventosType;

/**
 * Eventos controller.
 *
 */
class EventosController extends Controller
{

    /**
     * Lists all Eventos entities.
     *
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $entities = $em->getRepository('BackendBundle:Eventos')->findEventosPendientes();

        return $this->render('BackendBundle:Eventos:index.html.twig', array(
            'entities' => $entities,
        ));
    }

    protected function getErrorMessages(\Symfony\Component\Form\Form $form, $name) 
    {
        $errors = array();

        foreach ($form->getErrors() as $key => $error) {
            $errors[] = $error->getMessage();
        }

        foreach ($form->all() as $child) {
            $type = $child->getConfig()->getType()->getName();
            if ($child->count()  && ($type !== 'choice')) {
                $childErrors = $this->getErrorMessages($child, $child->getName());
                if (sizeof($childErrors)) {
                    $errors = array_merge($errors, $childErrors);
                }
            } else {
                if (!$child->isValid()) {
                    if($name=="responsable1" || $name=="responsable2")
                    {
                    $errors[$child->getParent()->getParent()->getName().'_'.$name.'_'.$child->getName()] = $this->getErrorMessages($child, $child->getName());
 
                    }
                    else{
                    $errors[$name.'_'.$child->getName()] = $this->getErrorMessages($child, $child->getName());
                    }
                }
            }
        }

        return $errors;
    }


    /**
     * Creates a new Eventos entity.
     *
     */
    public function createAction(Request $request)
    {
        // if request is XmlHttpRequest (AJAX) but not a POSt, throw an exception
        if ($request->isXmlHttpRequest() && !$request->isMethod('POST')) {
            throw new HttpException('XMLHttpRequests/AJAX calls must be POSTed');
        }

        $entity = new Eventos();
        $em = $this->getDoctrine()->getEntityManager();
        
        $fecha=$this->get('request')->request->get('fecha');
        $titulo=$this->get('request')->request->get('titulo');
        $categoria=$this->get('request')->request->get('categoria');
        $hora=$this->get('request')->request->get('hora');
        $descripcion=$this->get('request')->request->get('descripcion');
        $error=array();

        // Se comprueba que se han seleccionado todas las opciones para la reserva.
        if(!$fecha || !$hora || !$titulo || !$categoria  ){
            if(!$fecha){
                $error[]="- Fecha del evento";
            }
            if(!$titulo){
                $error[]="- Título del evento";
            }
            if(!$hora){
                $error[]="- Horario del evento";
            }
            if(!$descripcion){
                $error[]="- Descripción del evento";
            }
            return new JsonResponse(array(
                    'error' =>  $error), 200); 
        }

        $date=explode("-",$fecha);

        $diaNoLectivo=$this->get("festivos")->ComprobarDiaNoLectivoAction($date[2],$date[1],$date[0]);
        if($diaNoLectivo==false){

            //Se asigna la fecha.
            $entity->setDatetime(new \DateTime($fecha));
            $entity->setTitle($titulo); 
            $entity->setCategoria($categoria); 
            $entity->setHora($hora); 
            $entity->setDescription($descripcion); 
            $entity->setContador(0);       
            $em->persist($entity);
            $em->flush();

            if ($request->isXmlHttpRequest()) {
                return new JsonResponse(array(
                    'message' => "success",
                    'error' =>  $error,
                    'success' => true), 200);
            } 
        }
        else{
            if ($request->isXmlHttpRequest()) {
                return new JsonResponse(array(
                    'message' => 'festivo',
                    'error' =>  $error,
                    'success' => true), 200);
            } 
        }
    }

    /**
     * Creates a form to create a Eventos entity.
     *
     * @param Eventos $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createCreateForm(Eventos $entity)
    {
        $form = $this->createForm(new EventosType(), $entity, array(
            'action' => $this->generateUrl('eventos_create'),
            'method' => 'POST',
        ));

        $form->add('submit', 'submit', array('label' => 'Create'));

        return $form;
    }

    /**
     * Displays a form to create a new Eventos entity.
     *
     */
    public function newAction()
    {

        $em = $this->getDoctrine()->getManager();

        $inicio =$em->getRepository('BackendBundle:Centro')->findInicioCurso();
        $fin =$em->getRepository('BackendBundle:Centro')->findFinCurso();

        $array['inicio_navidad']=$em->getRepository('BackendBundle:Festivos')->findFestivosPorDescripcion("Inicio Vacaciones de Navidad");
        $array['fin_navidad']=$em->getRepository('BackendBundle:Festivos')->findFestivosPorDescripcion("Fin Vacaciones de Navidad");
        $array['inicio_semanasanta']=$em->getRepository('BackendBundle:Festivos')->findFestivosPorDescripcion("Inicio Vacaciones de Semana Santa");
        $array['fin_semanasanta']=$em->getRepository('BackendBundle:Festivos')->findFestivosPorDescripcion("Fin Vacaciones de Semana Santa");

        return $this->render('BackendBundle:Eventos:new.html.twig', array(
            'inicio' => $inicio,
            'fin' => $fin,
            'data'=>$array
        ));
    }

    /**
     * Finds and displays a Eventos entity.
     *
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('BackendBundle:Eventos')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Eventos entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return $this->render('BackendBundle:Eventos:show.html.twig', array(
            'entity'      => $entity,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing Eventos entity.
     *
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('BackendBundle:Eventos')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Eventos entity.');
        }

        $inicio =$em->getRepository('BackendBundle:Centro')->findInicioCurso();
        $fin =$em->getRepository('BackendBundle:Centro')->findFinCurso();

        $array['inicio_navidad']=$em->getRepository('BackendBundle:Festivos')->findFestivosPorDescripcion("Inicio Vacaciones de Navidad");
        $array['fin_navidad']=$em->getRepository('BackendBundle:Festivos')->findFestivosPorDescripcion("Fin Vacaciones de Navidad");
        $array['inicio_semanasanta']=$em->getRepository('BackendBundle:Festivos')->findFestivosPorDescripcion("Inicio Vacaciones de Semana Santa");
        $array['fin_semanasanta']=$em->getRepository('BackendBundle:Festivos')->findFestivosPorDescripcion("Fin Vacaciones de Semana Santa");

        $editForm = $this->createEditForm($entity);
        $deleteForm = $this->createDeleteForm($id);

        return $this->render('BackendBundle:Eventos:edit.html.twig', array(
            'inicio' => $inicio,
            'fin' => $fin,
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
    * Creates a form to edit a Eventos entity.
    *
    * @param Eventos $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createEditForm(Eventos $entity)
    {
        $form = $this->createForm(new EventosType(), $entity, array(
            'action' => $this->generateUrl('eventos_update', array('id' => $entity->getId())),
            'method' => 'PUT',
        ));

        $form->add('submit', 'submit', array('label' => 'Guardar cambios'));

        return $form;
    }
    /**
     * Edits an existing Eventos entity.
     *
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('BackendBundle:Eventos')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Eventos entity.');
        }

        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createEditForm($entity);
        $editForm->handleRequest($request);

        if ($editForm->isValid()) {

            $em->flush();

            if ($request->isXmlHttpRequest()) {
                return new JsonResponse(array(
                    'message' => 'Success!',
                    'success' => true), 200);
            }

            return $this->redirect($this->generateUrl('eventos_edit', array('id' => $id)));
        }
        //Se le indica el método PUT que es el que tiene en la llamada Ajax.
        if ($request->isMethod('PUT')) {
            return new JsonResponse(array(
            'data' => $this->getErrorMessages($editForm,$editForm->getName()),
            'message' => 'Invalid form',
            'success' => false), 400);
        }

        return $this->render('BackendBundle:Eventos:edit.html.twig', array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }
    /**
     * Deletes a Eventos entity.
     *
     */
    public function deleteAction(Request $request, $id)
    {
        
/*
        $form = $this->createDeleteForm($id);
        $form->handleRequest($request);


        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('BackendBundle:Eventos')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find Eventos entity.');
            }

            $em->remove($entity);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('eventos'));

        */
        $em = $this->getDoctrine()->getManager();
        $query = $em->createQuery('DELETE BackendBundle:Eventos r WHERE r.id = :Id')->setParameter("Id", $id);

        $query->execute();
        return new JsonResponse(array('success' => true), 200);

    }

    /**
     * Creates a form to delete a Eventos entity by id.
     *
     * @param mixed $id The entity id
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm($id)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('eventos_delete', array('id' => $id)))
            ->setMethod('DELETE')
            ->add('submit', 'submit', array('label' => 'Delete'))
            ->getForm()
        ;
    }

    public function obtenerEventosAction()
    {

        $entity = new Eventos();
        $em = $this->getDoctrine()->getManager();

        $entities = $em->getRepository('BackendBundle:Eventos')->findEventosGenerales("general");


        //$date = strtotime(date(“Y-m-d H:i:s”));000
        $arr = array(array("title" => '- Event Title 1',"description" => "Morbi leo risus, porta ac consectetur ac, vestibulum at eros.","datetime"=>new \DateTime("now")),
                    array("title" => '- Event Title 2',"description" => "Morbi leo risus, porta ac consectetur ac, vestibulum at eros.","datetime"=>new \DateTime("now"))
        );
        return new JsonResponse(array(
            'message' => 'Success!',
            'data' =>$entities,
            'success' => true), 200);
    }








}
