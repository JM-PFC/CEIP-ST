<?php

namespace Cole\BackendBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Ps\PdfBundle\Annotation\Pdf;
use Symfony\Component\HttpFoundation\Response;


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
        $descripcion=$this->get('request')->request->get('descripcion');
        $em = $this->getDoctrine()->getEntityManager();
        $fecha= $em->getRepository('BackendBundle:Festivos')->findFestivo($dia, $mes);
        //En Registrar Festivo sólo será válida cuando no exista el festivo.
        //En Editar Festivo será válida cuando no exista el festivo ó cuando exista y tenga la misma descripción.

        if($fecha){
            //Se comprueba que la petición se realiza desde Registrar un Festivo.
            if($descripcion==""){
                return new JsonResponse(array('data' =>$fecha->getId()), 200);
            }
            //Se comprueba si ya existe otra fecha con diferente descripción para Editar un Festivo.
            else if($descripcion!=$fecha->getDescripcion()){
                return new JsonResponse(array('data' =>$fecha->getId()), 200);
            }
            return new JsonResponse(array('data' =>null), 200); //Sálida válida para Editar Festivo.
        }
        else{
            return new JsonResponse(array('data' =>null), 200); //Sálida válida para Regstrar Festivo y Editar Festivo.

        }

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


    public function GenerarPdfFestivosAction()
    {
        $em = $this->getDoctrine()->getManager();

        $inicio =$em->getRepository('BackendBundle:Centro')->findInicioCurso();
        $fin =$em->getRepository('BackendBundle:Centro')->findFinCurso();
        $entities = $em->getRepository('BackendBundle:Festivos')->findFestivosOrdenados();


        $html = $this->renderView('BackendBundle:Festivos:calendario.html.twig', array(
            'entities' => $entities,
            'inicio' => $inicio,
            'fin' => $fin,
        ));

        $options = [
            'margin-top'    => 3,
            'margin-right'  => 8,
            'margin-bottom' => 3,
            'margin-left'   => 8,
        ];

        return new Response(
            $this->get('knp_snappy.pdf')->getOutputFromHtml($html,$options),
            200,
            array(
                'Content-Type'        => 'application/pdf',
                'Content-Disposition' => 'attachment; filename="Calendario.pdf"'
            )
        );
    }

    /*

use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

    public function GenerarPdfFestivosAction()
    {
                $basePath = $this->container->getParameter('kernel.root_dir');
        $generator =  $this->get('knp_snappy.pdf');
       $pdf = null;
       $fileName = $basePath.'/Calendario.pdf'; // change this
               $em = $this->getDoctrine()->getManager();

       $inicio =$em->getRepository('BackendBundle:Centro')->findInicioCurso();
       $fin =$em->getRepository('BackendBundle:Centro')->findFinCurso();
       $html = $this->renderView('BackendBundle:Festivos:calendario.html.twig', array(
                'inicio' => $inicio,
                'fin' => $fin,
               ));
       try {
            $pdf = $generator->getOutputFromHtml($html);
        } catch (\RuntimeException $e) {
            $matches = [];
            if (preg_match('/([^\']+)\'.$/', $e->getMessage(), $matches)) {
                $pdf = file_get_contents($matches[1]);
                unlink($matches[1]);
            } else  {
                throw $e;
            }
        }
       if ($pdf){
          file_put_contents($fileName, $pdf);
          return new BinaryFileResponse($fileName);
       }
       else{
       // error message or smthing
       }
    }

    */

    //Función de prueba para ver los resultados del calendario en html.
    public function GenerarFestivosAction()
    {

        $em = $this->getDoctrine()->getManager();

        $inicio =$em->getRepository('BackendBundle:Centro')->findInicioCurso();
        $fin =$em->getRepository('BackendBundle:Centro')->findFinCurso();
        $entities = $em->getRepository('BackendBundle:Festivos')->findFestivosOrdenados();


        return $this->render('BackendBundle:Festivos:calendario.html.twig', array(
            'inicio' => $inicio,
            'fin' => $fin,
            'entities' => $entities,

        ));
        
    }
    
    
    public function ComprobarDiaNoLectivoAction($dia,$mes,$anyo) 
    {
        // Se comprueba que no es festivo.
        $em = $this->getDoctrine()->getManager();
        $entities = $em->getRepository('BackendBundle:Festivos')->findFestivo($dia, $mes);
        if(!empty($entities)){
            return true;
        }

        // Se comprueba que siendo lunes y el domingo no sea festivo.
        $fecha=$anyo."-".$mes."-".$dia;
        $dw = date('w', strtotime($fecha));

        if($dw==1){
            $dia_anterior = date( 'Y-m-d', strtotime( $fecha.' -1 day' ) );
            $fecha_ant=explode("-",$dia_anterior);
            $DomingoFestivo= $em->getRepository('BackendBundle:Festivos')->findFestivo($fecha_ant[2], $fecha_ant[1]);
            if(!empty($DomingoFestivo)){
                return true;
            }
        }

        // Se comprueba que no sea un día de vacaciones.
        $dw = date('Y-m-d', strtotime($fecha));
        // Se obtiene la fecha inicial y final del curso para usar luego el año correspondiente. 
        $ini_curso=$em->getRepository('BackendBundle:Centro')->findInicioCurso();
        $array_ini=explode("-",$ini_curso["inicioCurso"]->format('Y-m-d')); //Conversión de array a String
        $fin_curso=$em->getRepository('BackendBundle:Centro')->findFinCurso();
        $array_fin=explode("-",$fin_curso["finCurso"]->format('Y-m-d'));

        // Se obtiene las fechas de vacaciones.
        $ini_nav=$em->getRepository('BackendBundle:Festivos')->findFestivosPorDescripcion("Inicio Vacaciones de Navidad");
        $fin_nav=$em->getRepository('BackendBundle:Festivos')->findFestivosPorDescripcion("Fin Vacaciones de Navidad");
        $ini_ss=$em->getRepository('BackendBundle:Festivos')->findFestivosPorDescripcion("Inicio Vacaciones de Semana Santa");
        $fin_ss=$em->getRepository('BackendBundle:Festivos')->findFestivosPorDescripcion("Fin Vacaciones de Semana Santa");
        
        // Se comrpueba si existe vacaciones de Navidad y si un día de esas vacaciones.
        if(!empty($ini_nav) && !empty($fin_nav)) {
            $Fecha_ini_nav = date('Y-m-d', strtotime($array_ini[0]."-".$ini_nav->getNumMes()."-".$ini_nav->getDia()));
            $Fecha_fin_nav = date('Y-m-d', strtotime($array_fin[0]."-".$fin_nav->getNumMes()."-".$fin_nav->getDia()));

            if (($dw >= $Fecha_ini_nav) && ($dw <= $Fecha_fin_nav)){
                return true;
            }
        }
         // Se comrpueba si existe vacaciones de Semana Santa y si un día de esas vacaciones.
        if(!empty($ini_ss) && !empty($fin_ss)){

            $Fecha_ini_ss = date('Y-m-d', strtotime($array_fin[0]."-".$ini_ss->getNumMes()."-".$ini_ss->getDia()));
            $Fecha_fin_ss = date('Y-m-d', strtotime($array_fin[0]."-".$fin_ss->getNumMes()."-".$fin_ss->getDia()));

            if (($dw >= $Fecha_ini_ss) && ($dw <= $Fecha_fin_ss)){
                return true;
            }
        }
        // En caso de que no exista ningún festivo se devolverá false para que se pueda guardar la reserva.
        return false;
    }

    
    public function ComprobarFechasCursoAction() 
    {
        $em = $this->getDoctrine()->getManager();

        // Se obtiene la fecha inicial del curso para comprobar que está registrada. 
        $ini_curso=$em->getRepository('BackendBundle:Centro')->findInicioCurso();
        $array_ini=explode("-",$ini_curso["inicioCurso"]->format('Y-m-d')); 

        //Se comprueba que la fecha inicial no está registrada.
        if($array_ini[1]=="0001"){
            $ini_curso=null;
        }

        // Se obtiene la fecha inicial de las vacaciones para comprobar que están registrada. 
        $ini_nav=$em->getRepository('BackendBundle:Festivos')->findFestivosPorDescripcion("Inicio Vacaciones de Navidad");
        $ini_ss=$em->getRepository('BackendBundle:Festivos')->findFestivosPorDescripcion("Inicio Vacaciones de Semana Santa");
        
        return new JsonResponse(array(
            'curso' =>  $ini_curso,
            'navidad' =>  $ini_nav,
            'semanasanta' =>  $ini_ss,
            'success' => true), 200);
    }


}
