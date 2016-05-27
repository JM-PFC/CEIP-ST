<?php

namespace Cole\BackendBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;


use Cole\BackendBundle\Entity\Festivos;
use Cole\BackendBundle\Entity\Centro;
use Cole\BackendBundle\Form\FestivosType;

/**
 * Festivos controller.
 *
 */
class FestivosController extends Controller
{

    /**
     * Lists all Festivos entities.
     *
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $entities = $em->getRepository('BackendBundle:Festivos')->findAll();
        $inicio =$em->getRepository('BackendBundle:Centro')->findInicioCurso();
        $fin =$em->getRepository('BackendBundle:Centro')->findFinCurso();

        $array['inicio_navidad']=$em->getRepository('BackendBundle:Festivos')->findFestivosPorDescripcion("Inicio Vacaciones de Navidad");
        $array['fin_navidad']=$em->getRepository('BackendBundle:Festivos')->findFestivosPorDescripcion("Fin Vacaciones de Navidad");
        $array['inicio_semanasanta']=$em->getRepository('BackendBundle:Festivos')->findFestivosPorDescripcion("Inicio Vacaciones de Semana Santa");
        $array['fin_semanasanta']=$em->getRepository('BackendBundle:Festivos')->findFestivosPorDescripcion("Fin Vacaciones de Semana Santa");


        return $this->render('BackendBundle:Festivos:index.html.twig', array(
            'entities' => $entities,
            'inicio' => $inicio,
            'fin' => $fin,
            'data'=>$array
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
     * Creates a new Festivos entity.
     *
     */
    public function createAction(Request $request)
    {
      // if request is XmlHttpRequest (AJAX) but not a POSt, throw an exception
      if ($request->isXmlHttpRequest() && !$request->isMethod('POST')) {
        throw new HttpException('XMLHttpRequests/AJAX calls must be POSTed');}

        $entity = new Festivos();
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

            return $this->redirect($this->generateUrl('festivos_show', array('id' => $entity->getId())));
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
     * Creates a form to create a Festivos entity.
     *
     * @param Festivos $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createCreateForm(Festivos $entity)
    {
        $form = $this->createForm(new FestivosType(), $entity, array(
            'action' => $this->generateUrl('festivos_create'),
            'method' => 'POST',
        ));

        $form->add('submit', 'submit', array('label' => 'Insertar'));

        return $form;
    }

    /**
     * Displays a form to create a new Festivos entity.
     *
     */
    public function newAction()
    {
        $entity = new Festivos();
        $form   = $this->createCreateForm($entity);

        return $this->render('BackendBundle:Festivos:new.html.twig', array(
            'entity' => $entity,
            'form'   => $form->createView(),
        ));
    }

    /**
     * Finds and displays a Festivos entity.
     *
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('BackendBundle:Festivos')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Festivos entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return $this->render('BackendBundle:Festivos:show.html.twig', array(
            'entity'      => $entity,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing Festivos entity.
     *
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('BackendBundle:Festivos')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Festivos entity.');
        }

        $editForm = $this->createEditForm($entity);
        $deleteForm = $this->createDeleteForm($id);

        return $this->render('BackendBundle:Festivos:edit.html.twig', array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
    * Creates a form to edit a Festivos entity.
    *
    * @param Festivos $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createEditForm(Festivos $entity)
    {
        $form = $this->createForm(new FestivosType(), $entity, array(
            'action' => $this->generateUrl('festivos_update', array('id' => $entity->getId())),
            'method' => 'PUT',
        ));

        $form->add('submit', 'submit', array('label' => 'Actualizar'));

        return $form;
    }
    /**
     * Edits an existing Festivos entity.
     *
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('BackendBundle:Festivos')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Festivos entity.');
        }

        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createEditForm($entity);
        $editForm->handleRequest($request);

        if ($editForm->isValid()) {
            $em->flush();

            return $this->redirect($this->generateUrl('festivos_edit', array('id' => $id)));
        }

        return $this->render('BackendBundle:Festivos:edit.html.twig', array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }
    /**
     * Deletes a Festivos entity.
     *
     */
    public function deleteAction(Request $request, $id)
    {
        $form = $this->createDeleteForm($id);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('BackendBundle:Festivos')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find Festivos entity.');
            }

            $em->remove($entity);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('festivos'));
    }

    /**
     * Creates a form to delete a Festivos entity by id.
     *
     * @param mixed $id The entity id
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm($id)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('festivos_delete', array('id' => $id)))
            ->setMethod('DELETE')
            ->add('submit', 'submit', array('label' => 'Eliminar'))
            ->getForm()
        ;
    }

    public function ComprobarFestivoAction()
    {
        $dia=$this->get('request')->request->get('dia');
        $mes=$this->get('request')->request->get('mes');
        $em = $this->getDoctrine()->getEntityManager();
        $fecha= $em->getRepository('BackendBundle:Festivos')->findFestivo($dia, $mes);
        if($fecha){
            return new JsonResponse(array('data' =>$fecha->getId()), 200);
        }
        return new JsonResponse(array('data' =>null), 200);

    }

    public function FestivosPorTipoAction($id)
    {
        $em = $this->getDoctrine()->getManager();
        if($id=="Nacional"){
            $entities= $em->getRepository('BackendBundle:Festivos')->findFestivosPorTipo($id);
            return $this->render('BackendBundle:Festivos:lista_festivos_tipo.html.twig', array(
            'entities' => $entities,
            ));
        }
        else if($id=="Local"){
            $entities= $em->getRepository('BackendBundle:Festivos')->findFestivosPorTipo($id);
            return $this->render('BackendBundle:Festivos:lista_festivos_tipo.html.twig', array(
            'entities' => $entities,
            ));
        }
        else {
            $entities= $em->getRepository('BackendBundle:Festivos')->findFestivosPorTipo($id);
            return $this->render('BackendBundle:Festivos:lista_festivos_tipo.html.twig', array(
            'entities' => $entities,
            ));
        }
    }

    public function FestivosPorMesAction($id)
    {
        $em = $this->getDoctrine()->getManager();
        $entities= $em->getRepository('BackendBundle:Festivos')->findFestivosPorMes($id);
        $inicio =$em->getRepository('BackendBundle:Centro')->findInicioCurso();
        $fin =$em->getRepository('BackendBundle:Centro')->findFinCurso();
        return $this->render('BackendBundle:Festivos:lista_festivos_mes.html.twig', array(
        'entities' => $entities,
        'inicio' => $inicio,
        'fin' => $fin));
    }

    public function  DiasFestivosAction()
    {
        $mes=$this->get('request')->request->get('mes');
        $em = $this->getDoctrine()->getEntityManager();
        $fecha= $em->getRepository('BackendBundle:Festivos')->findDiasFestivos($mes);

        $inicio_vacaciones=$em->getRepository('BackendBundle:Festivos')->findDiaVacaciones($mes,"Inicio Vacaciones");
        $fin_vacaciones=$em->getRepository('BackendBundle:Festivos')->findDiaVacaciones($mes,"Fin Vacaciones");

        return new JsonResponse(array('data' =>$fecha,'inicio_vacaciones' =>$inicio_vacaciones,'fin_vacaciones' =>$fin_vacaciones ), 200);
    }

    public function  RegistrarDiaVacacionesAction()
    {
        $id=$this->get('request')->request->get('id');
        $dia=$this->get('request')->request->get('dia');
        $mes=$this->get('request')->request->get('mes');
        $año=$this->get('request')->request->get('año');

        $num_mes=array('Enero'=>'01','Febrero'=>'02','Marzo'=>'03','Abril'=>'04','Mayo'=>'05','Junio'=>'06','Julio'=>'07','Agosto'=>'08','Septiembre'=>'09','Octubre'=>'10','Noviembre'=>'11','Diciembre'=>'12');

        $em = $this->getDoctrine()->getEntityManager();
        if($id=="inicio_navidad"){
            $festivo=$em->getRepository('BackendBundle:Festivos')->findFestivosPorDescripcion("Inicio Vacaciones de Navidad");
            $descripcion="Inicio Vacaciones de Navidad";
        }
        else if($id=="fin_navidad"){
            $festivo=$em->getRepository('BackendBundle:Festivos')->findFestivosPorDescripcion("Fin Vacaciones de Navidad");
            $descripcion="Fin Vacaciones de Navidad";
        }
        else if($id=="inicio_semanasanta"){
            $festivo=$em->getRepository('BackendBundle:Festivos')->findFestivosPorDescripcion("Inicio Vacaciones de Semana Santa");           
            $descripcion="Inicio Vacaciones de Semana Santa";
        }
        else if($id=="fin_semanasanta"){
            $festivo=$em->getRepository('BackendBundle:Festivos')->findFestivosPorDescripcion("Fin Vacaciones de Semana Santa");          
            $descripcion="Fin Vacaciones de Semana Santa";
        }            

        if ($festivo){
            $festivo->setDia($dia);
            $festivo->setMes($mes);
            $festivo->setNumMes($num_mes[$mes]);

            $em->persist($festivo);
        }
        else{
            $festivo_nuevo = new Festivos();

            $festivo_nuevo->setDia($dia);
            $festivo_nuevo->setMes($mes);
            $festivo_nuevo->setNumMes($num_mes[$mes]);
            $festivo_nuevo->setDescripcion($descripcion);
            $festivo_nuevo->setTipo("Curso");

            $em->persist($festivo_nuevo);
        }

        $em->flush();
        return new JsonResponse(array('message' => 'Success!','success' => true), 200);
    }

    public function  EliminarDiaVacacionesAction()
    {
        $id=$this->get('request')->request->get('id');

        $em = $this->getDoctrine()->getEntityManager();
        if($id=="inicio_navidad"){
            $festivo=$em->getRepository('BackendBundle:Festivos')->findFestivosPorDescripcion("Inicio Vacaciones de Navidad");
        }
        else if($id=="fin_navidad"){
            $festivo=$em->getRepository('BackendBundle:Festivos')->findFestivosPorDescripcion("Fin Vacaciones de Navidad");
        }
        else if($id=="inicio_semanasanta"){
            $festivo=$em->getRepository('BackendBundle:Festivos')->findFestivosPorDescripcion("Inicio Vacaciones de Semana Santa");           
        }
        else if($id=="fin_semanasanta"){
            $festivo=$em->getRepository('BackendBundle:Festivos')->findFestivosPorDescripcion("Fin Vacaciones de Semana Santa");          
        }            

        $em->remove($festivo);
        $em->flush();
        return new JsonResponse(array('message' => 'Success!','success' => true), 200);
    }

    public function RegistrarCursoAction()
    {
        $em = $this->getDoctrine()->getManager();

        $inicio =$em->getRepository('BackendBundle:Centro')->findInicioCurso();
        $fin =$em->getRepository('BackendBundle:Centro')->findFinCurso();


        return $this->render('BackendBundle:Festivos:edit_curso.html.twig', array(
            'inicio' => $inicio,
            'fin' => $fin,
            ));
    }
    
    


}
