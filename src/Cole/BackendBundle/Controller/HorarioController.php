<?php

namespace Cole\BackendBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;

use Cole\BackendBundle\Entity\Horario;
use Cole\BackendBundle\Entity\Reserva;
use Cole\BackendBundle\Entity\Imparte;
use Cole\BackendBundle\Form\HorarioType;

/**
 * Horario controller.
 *
 */
class HorarioController extends Controller
{

    /**
     * Lists all Horario entities.
     *
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $entities = $em->getRepository('BackendBundle:Horario')->findAll();

        return $this->render('BackendBundle:Horario:index.html.twig', array(
            'entities' => $entities,
        ));
    }
    /**
     * Creates a new Horario entity.
     *
     */
    public function createAction(Request $request)
    {
        $entity = new Horario();
        $form = $this->createCreateForm($entity);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('horario_show', array('id' => $entity->getId())));
        }

        return $this->render('BackendBundle:Horario:new.html.twig', array(
            'entity' => $entity,
            'form'   => $form->createView(),
        ));
    }

    /**
     * Creates a form to create a Horario entity.
     *
     * @param Horario $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createCreateForm(Horario $entity)
    {
        $form = $this->createForm(new HorarioType(), $entity, array(
            'action' => $this->generateUrl('horario_create'),
            'method' => 'POST',
        ));

        $form->add('submit', 'submit', array('label' => 'Create'));

        return $form;
    }

    /**
     * Displays a form to create a new Horario entity.
     *
     */
    public function newAction()
    {
        $entity = new Horario();
        $form   = $this->createCreateForm($entity);

        return $this->render('BackendBundle:Horario:new.html.twig', array(
            'entity' => $entity,
            'form'   => $form->createView(),
        ));
    }

    /**
     * Finds and displays a Horario entity.
     *
     */
    public function showAction()
    {
        $em = $this->getDoctrine()->getManager();
        $centro =$em->getRepository('BackendBundle:Centro')->findCentro();

        $entities = $em->getRepository('BackendBundle:Horario')->findAll();
           return $this->render('BackendBundle:Horario:show.html.twig', array(
            'entities' => $entities,
            'inicio' => $centro->getInicioHorario(),
            'fin' => $centro->getFinHorario()
        ));
    }

    /**
     * Displays a form to edit an existing Horario entity.
     *
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('BackendBundle:Horario')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Horario entity.');
        }

        $editForm = $this->createEditForm($entity);
        $deleteForm = $this->createDeleteForm($id);

        return $this->render('BackendBundle:Horario:edit.html.twig', array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
    * Creates a form to edit a Horario entity.
    *
    * @param Horario $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createEditForm(Horario $entity)
    {
        $form = $this->createForm(new HorarioType(), $entity, array(
            'action' => $this->generateUrl('horario_update', array('id' => $entity->getId())),
            'method' => 'PUT',
        ));

        $form->add('submit', 'submit', array('label' => 'Update'));

        return $form;
    }
    /**
     * Edits an existing Horario entity.
     *
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('BackendBundle:Horario')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Horario entity.');
        }

        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createEditForm($entity);
        $editForm->handleRequest($request);

        if ($editForm->isValid()) {
            $em->flush();

            return $this->redirect($this->generateUrl('horario_edit', array('id' => $id)));
        }

        return $this->render('BackendBundle:Horario:edit.html.twig', array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }
    /**
     * Deletes a Horario entity.
     *
     */
    public function deleteAction(Request $request)
    {
        // if request is XmlHttpRequest (AJAX) but not a POSt, throw an exception
        if ($request->isXmlHttpRequest() && !$request->isMethod('POST')) {
            throw new HttpException('XMLHttpRequests/AJAX calls must be POSTed');
        }
        
        $em = $this->getDoctrine()->getEntityManager();
        $entities = $em->getRepository('BackendBundle:Horario')->findAll();
        if ($entities) {
            foreach ($entities  as $entity ) {
                $em->remove($entity);
            }
        }
        $em->flush();

        if ($request->isXmlHttpRequest()) {
                return new JsonResponse(array(
                    'message' => 'Success!',
                    'success' => true), 200);
        } 
    }

    /**
     * Creates a form to delete a Horario entity by id.
     *
     * @param mixed $id The entity id
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm($id)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('horario_delete', array('id' => $id)))
            ->setMethod('DELETE')
            ->add('submit', 'submit', array('label' => 'Delete'))
            ->getForm()
        ;
    }
    public function  NuevoHorarioAction(Request $request)
    {
        // if request is XmlHttpRequest (AJAX) but not a POSt, throw an exception
        if ($request->isXmlHttpRequest() && !$request->isMethod('POST')) {
            throw new HttpException('XMLHttpRequests/AJAX calls must be POSTed');
        }

        $cadena=$this->get('request')->request->get('cadena');
        $cont=$this->get('request')->request->get('cont');
        $tipo=$this->get('request')->request->get('tipo');

        $datos = explode("-", $cadena);
        
        $em = $this->getDoctrine()->getEntityManager();
        $asignaciones=$em->getRepository('BackendBundle:Imparte')->findConHorario();
        if ($asignaciones) {
            foreach ($asignaciones  as $asignacion ) {
                $asignacion->SetHorario(null);
                $asignacion->SetDiaSemanal(null);
            }
        }

        $entities = $em->getRepository('BackendBundle:Horario')->findAll();
        if ($entities) {
            foreach ($entities  as $entity ) {
                $em->remove($entity);
            }
        }
        $em->flush();
        for ($i=0, $j=0; $i < $cont ; $i++) { 
            $clase= new Horario();
            $clase->setHoraClase($datos[$j]);
            $j++;
            $clase->setInicio(new \DateTime($datos[$j]));
            $j++;
            $clase->setFin(new \DateTime($datos[$j]));
            $j++;
            $clase->setDuracion($datos[$j]);
            $j++;

            $clase->setTipo($tipo);
            $em->persist($clase);
            $em->flush();
        }
        
        if ($request->isXmlHttpRequest()) {
                return new JsonResponse(array(
                    'message' => 'Success!',
                    'success' => true), 200);
        } 
    }
    
    public function EditarHorarioAction(Request $request)
    {
        // if request is XmlHttpRequest (AJAX) but not a POSt, throw an exception
        if ($request->isXmlHttpRequest() && !$request->isMethod('POST')) {
            throw new HttpException('XMLHttpRequests/AJAX calls must be POSTed');
        }

        $clase=$this->get('request')->request->get('clase');
        $ini=$this->get('request')->request->get('ini');
        $fin=$this->get('request')->request->get('fin');
        $duracion=$this->get('request')->request->get('duracion');
        
        $em = $this->getDoctrine()->getEntityManager();
        $entity = $em->getRepository('BackendBundle:Horario')->findHoraClase($clase);
        if (!$entity) {
                throw $this->createNotFoundException('Unable to find Curso entity.');
            }

        $em = $this->getDoctrine()->getManager();
        if($ini!=="" && $fin!==""){
            $entity->setInicio(new \DateTime($ini));
            $entity->setFin(new \DateTime($fin));    
            $entity->setTipo("manual");
            $entity->setDuracion($duracion);    
    
        }else{
            $entity->setInicio(NULL);     
            $entity->setFin(NULL);     
        }

        $em->persist($entity);
        $em->flush();
        
        if ($request->isXmlHttpRequest()) {
                return new JsonResponse(array(
                    'message' => 'Success!',
                    'success' => true), 200);
        }
    }


    public function HorarioEscolarActualAction()
    {

        $em = $this->getDoctrine()->getManager();

            $entities = $em->getRepository('BackendBundle:Horario')->findAll();
            return $this->render('BackendBundle:Horario:horario_escolar.html.twig', array(
            'entities' => $entities,
            ));
    }

    public function RegistrarHorarioCentroAction()
    {
        $em = $this->getDoctrine()->getManager();

        $centro =$em->getRepository('BackendBundle:Centro')->findCentro();

        return $this->render('BackendBundle:Horario:edit_horario_centro.html.twig', array(
            'inicio' => $centro->getInicioHorario(),
            'fin' => $centro->getFinHorario()
            ));
    }


    public function ComprobacionesHorarioNuevoAction()
    {
        $em = $this->getDoctrine()->getManager();
        $num_imparte="";

        $imparte=$em->getRepository('BackendBundle:Imparte')->findConHorario();

        if ($imparte) {
            $num_imparte="hay";
        }

        $num_reserva="";
        $reserva=$em->getRepository('BackendBundle:Reserva')->findAll();

        if ($reserva) {
             $num_reserva="hay";
        }

        return new JsonResponse(array('imparte' => $num_imparte,'reserva' => $num_reserva, 'success' => true), 200);
    }

}
