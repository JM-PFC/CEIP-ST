<?php

namespace Cole\BackendBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;


use Cole\BackendBundle\Entity\Reserva;
use Cole\BackendBundle\Form\ReservaType;

/**
 * Reserva controller.
 *
 */
class ReservaController extends Controller
{

    /**
     * Lists all Reserva entities.
     *
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $entities = $em->getRepository('BackendBundle:Reserva')->findAll();

        return $this->render('BackendBundle:Reserva:index.html.twig', array(
            'entities' => $entities,
        ));
    }
    /**
     * Creates a new Reserva entity.
     *
     */
    public function createAction(Request $request)
    {
        // if request is XmlHttpRequest (AJAX) but not a POSt, throw an exception
        if ($request->isXmlHttpRequest() && !$request->isMethod('POST')) {
            throw new HttpException('XMLHttpRequests/AJAX calls must be POSTed');
        }
        $entity = new Reserva();
        $em = $this->getDoctrine()->getEntityManager();
        
        $fecha=$this->get('request')->request->get('fecha');
        $equipamiento=$this->get('request')->request->get('equipamiento');
        $horas=$this->get('request')->request->get('objDatos');
        $equipa=$em->getRepository('BackendBundle:Equipamiento')->findEquipamientoByName($equipamiento);
        $NoDisponible=array();

        $date=explode("-",$fecha);

        $diaNoLectivo=$this->get("festivos")->ComprobarDiaNoLectivoAction($date[2],$date[1],$date[0]);
        if($diaNoLectivo==false){
          foreach ($horas as $hora ) {
            $clase=$em->getRepository('BackendBundle:Horario')->findOneByHoraClase($hora);
            $unidades= $em->getRepository('BackendBundle:Reserva')->findComprobarUnidades($clase,$equipa, $fecha);
            // Se comprueba que hay unidades disponibles.
            if((int)$unidades[1] < $equipa->getUnidades() && (int)$unidades[1] >= 0){
                // Se obtiene el usuario actual ----------------------------------> De momento sólo reserva el centro (null)

                //Hay que hacer un condicional viendo si el usuario tiene el rol profesor para guardar el iddel profesor,sino no hacer nada(se guarda nulo)
                //$entity->setProfesor($profesor);

                //Se obtiene el id del equipamiento.
                $entity->setEquipamiento($em->getRepository('BackendBundle:Equipamiento')->findEquipamientoByName($equipamiento));
        
                //Se obtiene el id del horario correspondiente.
                $entity->setFecha(new \DateTime($fecha)); 
                    
                //Se obtiene el id del horario correspondiente.
                $entity->setHorario($em->getRepository('BackendBundle:Horario')->findOneByHoraClase($hora));
                $em->persist($entity);
                $em->flush();
            }
            else{
                $NoDisponible[]=$hora;
            }
          }
            if ($request->isXmlHttpRequest()) {
                return new JsonResponse(array(
                    'message' => 'Success!',
                    'data' =>  $NoDisponible,
                    'success' => true), 200);
            } 
        }
        else{
            if ($request->isXmlHttpRequest()) {
                return new JsonResponse(array(
                    'message' => 'festivo',
                    'data' =>  $NoDisponible,
                    'success' => true), 200);
            } 
        }

    }

    /**
     * Creates a form to create a Reserva entity.
     *
     * @param Reserva $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createCreateForm(Reserva $entity)
    {
        $form = $this->createForm(new ReservaType(), $entity, array(
            'action' => $this->generateUrl('reserva_create'),
            'method' => 'POST',
        ));

        $form->add('submit', 'submit', array('label' => 'Create'));

        return $form;
    }

    /**
     * Displays a form to create a new Reserva entity.
     *
     */
    public function newAction()
    {
        $entity = new Reserva();
        $form   = $this->createCreateForm($entity);

        return $this->render('BackendBundle:Reserva:new.html.twig', array(
            'entity' => $entity,
            'form'   => $form->createView(),
        ));
    }

    /**
     * Finds and displays a Reserva entity.
     *
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('BackendBundle:Reserva')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Reserva entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return $this->render('BackendBundle:Reserva:show.html.twig', array(
            'entity'      => $entity,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing Reserva entity.
     *
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('BackendBundle:Reserva')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Reserva entity.');
        }

        $editForm = $this->createEditForm($entity);
        $deleteForm = $this->createDeleteForm($id);

        return $this->render('BackendBundle:Reserva:edit.html.twig', array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
    * Creates a form to edit a Reserva entity.
    *
    * @param Reserva $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createEditForm(Reserva $entity)
    {
        $form = $this->createForm(new ReservaType(), $entity, array(
            'action' => $this->generateUrl('reserva_update', array('id' => $entity->getId())),
            'method' => 'PUT',
        ));

        $form->add('submit', 'submit', array('label' => 'Update'));

        return $form;
    }
    /**
     * Edits an existing Reserva entity.
     *
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('BackendBundle:Reserva')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Reserva entity.');
        }

        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createEditForm($entity);
        $editForm->handleRequest($request);

        if ($editForm->isValid()) {
            $em->flush();

            return $this->redirect($this->generateUrl('reserva_edit', array('id' => $id)));
        }

        return $this->render('BackendBundle:Reserva:edit.html.twig', array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }
    /**
     * Deletes a Reserva entity.
     *
     */
    public function deleteAction(Request $request, $id)
    {
        $form = $this->createDeleteForm($id);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('BackendBundle:Reserva')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find Reserva entity.');
            }

            $em->remove($entity);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('reserva'));
    }

    /**
     * Creates a form to delete a Reserva entity by id.
     *
     * @param mixed $id The entity id
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm($id)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('reserva_delete', array('id' => $id)))
            ->setMethod('DELETE')
            ->add('submit', 'submit', array('label' => 'Delete'))
            ->getForm()
        ;
    }

    public function instalacionesAction()
    {
        $em = $this->getDoctrine()->getManager();

        $entities = $em->getRepository('BackendBundle:Equipamiento')->findInstalaciones();
        $tipo="instalaciones"; 
        $clases = $em->getRepository('BackendBundle:Horario')->findClases();

        $inicio =$em->getRepository('BackendBundle:Centro')->findInicioCurso();
        $fin =$em->getRepository('BackendBundle:Centro')->findFinCurso();

        $array['inicio_navidad']=$em->getRepository('BackendBundle:Festivos')->findFestivosPorDescripcion("Inicio Vacaciones de Navidad");
        $array['fin_navidad']=$em->getRepository('BackendBundle:Festivos')->findFestivosPorDescripcion("Fin Vacaciones de Navidad");
        $array['inicio_semanasanta']=$em->getRepository('BackendBundle:Festivos')->findFestivosPorDescripcion("Inicio Vacaciones de Semana Santa");
        $array['fin_semanasanta']=$em->getRepository('BackendBundle:Festivos')->findFestivosPorDescripcion("Fin Vacaciones de Semana Santa");

        return $this->render('BackendBundle:Reserva:reserva_instalaciones.html.twig', array(
            'entities' => $entities,
            'clases' => $clases,
            'inicio' => $inicio,
            'fin' => $fin,
            'data'=>$array,
            'tipo'=>$tipo
        ));
    }

    public function equipamientosAction()
    {
        $em = $this->getDoctrine()->getManager();

        $entities = $em->getRepository('BackendBundle:Equipamiento')->findByTipo("Equipamiento");
        $tipo="equipamientos"; 
        $clases = $em->getRepository('BackendBundle:Horario')->findClases();

        $inicio =$em->getRepository('BackendBundle:Centro')->findInicioCurso();
        $fin =$em->getRepository('BackendBundle:Centro')->findFinCurso();

        $array['inicio_navidad']=$em->getRepository('BackendBundle:Festivos')->findFestivosPorDescripcion("Inicio Vacaciones de Navidad");
        $array['fin_navidad']=$em->getRepository('BackendBundle:Festivos')->findFestivosPorDescripcion("Fin Vacaciones de Navidad");
        $array['inicio_semanasanta']=$em->getRepository('BackendBundle:Festivos')->findFestivosPorDescripcion("Inicio Vacaciones de Semana Santa");
        $array['fin_semanasanta']=$em->getRepository('BackendBundle:Festivos')->findFestivosPorDescripcion("Fin Vacaciones de Semana Santa");

        return $this->render('BackendBundle:Reserva:reserva_instalaciones.html.twig', array(
            'entities' => $entities,
            'clases' => $clases,
            'inicio' => $inicio,
            'fin' => $fin,
            'data'=>$array,
            'tipo'=>$tipo
        ));
    }

    public function ComprobarReservasAction() //Se tiene que obtener el usuario logueado para cambiarlo por null abajo
    {
        $equipamiento=$this->get('request')->request->get('equipamiento');
        $fecha=$this->get('request')->request->get('fecha');

        $em = $this->getDoctrine()->getEntityManager();

        $equipamiento=$em->getRepository('BackendBundle:Equipamiento')->findEquipamientoByName($equipamiento);   
        $reserva= $em->getRepository('BackendBundle:Reserva')->findReservasUsuario(null, $equipamiento, $fecha);
        $horarios= $em->getRepository('BackendBundle:Horario')->findClases();
        $longitud = count($horarios);
        $NoDisponible=array();
        for($i=0; $i<$longitud; $i++)
        {
            $unidades= $em->getRepository('BackendBundle:Reserva')->findComprobarUnidades($horarios[$i],$equipamiento, $fecha);

            if(!((int)$unidades[1] < $equipamiento->getUnidades() && (int)$unidades[1] >= 0)){
                $NoDisponible[]= $em->getRepository('BackendBundle:Reserva')->findReservasUnidades($horarios[$i],$equipamiento, $fecha);
            }       
        }

        /*
        $horarios= $em->getRepository('BackendBundle:Horario')->findClases();
        $longitud = count($horarios);

        for($i=0; $i<$longitud; $i++)
        {
            $unidades= $em->getRepository('BackendBundle:Reserva')->findComprobarUnidades($horarios[$i],$equipamiento, $fecha);

            if((int)$unidades[1] < $equipamiento->getUnidades() && (int)$unidades[1] >= 0){
                $NoDisponible[$i]= $em->getRepository('BackendBundle:Reserva')->findReservasUnidades($horarios[$i],$equipamiento, $fecha);
            }
            else{
               $NoDisponible[$i]=null; 
            }         
        }
        print_r($_POST);

        */

        if($reserva){
                if($NoDisponible){
                    return new JsonResponse(array('data' =>$reserva , 'data2' =>$NoDisponible), 200);
                }
                else{
                        return new JsonResponse(array('data' =>$reserva, 'data2' =>null), 200);
                }
        }
        else{
            if($NoDisponible){
                    return new JsonResponse(array('data' =>null , 'data2' =>$NoDisponible), 200);
                }
                else{
                        return new JsonResponse(array('data' =>null, 'data2' =>null), 200);
                }
        }

    }


    public function EquipamientoReservadoAction() 
    {
        $equipamiento=$this->get('request')->request->get('equipamiento');

        $em = $this->getDoctrine()->getEntityManager();

        $equipamiento=$em->getRepository('BackendBundle:Equipamiento')->findEquipamientoByName($equipamiento);   
        $reserva= $em->getRepository('BackendBundle:Reserva')->findByEquipamiento($equipamiento);

        if($reserva){
            return new JsonResponse(array('data' =>$reserva), 200);
        }
        else{
            return new JsonResponse(array('data' =>null), 200);
        }
    }


}
