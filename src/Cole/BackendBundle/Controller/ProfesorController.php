<?php

namespace Cole\BackendBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;

use Cole\BackendBundle\Entity\Profesor;
use Cole\BackendBundle\Entity\Reserva;
use Cole\BackendBundle\Entity\Imparte;
use Cole\BackendBundle\Entity\Grupo;
use Cole\BackendBundle\Form\ProfesorType;
use Cole\BackendBundle\Form\BusquedaProfesorType;
use Symfony\Component\HttpFoundation\Response;

/**
 * Profesor controller.
 *
 */
class ProfesorController extends Controller
{

    /**
     * Lists all Profesor entities.
     *
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $entities = $em->getRepository('BackendBundle:Profesor')->findAll();

        return $this->render('BackendBundle:Profesor:index.html.twig', array(
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
     * Creates a new Profesor entity.
     *
     */
    public function createAction(Request $request)
    {
    // if request is XmlHttpRequest (AJAX) but not a POSt, throw an exception
      if ($request->isXmlHttpRequest() && !$request->isMethod('POST')) {
        throw new HttpException('XMLHttpRequests/AJAX calls must be POSTed');}
        $horas=$this->get('request')->request->get('horas');
        $lectivas=$this->get('request')->request->get('lectivas');

        $entity = new Profesor();
        $form = $this->createCreateForm($entity);
        $form->handleRequest($request);

        if ($form->isValid()) {  
            $em = $this->getDoctrine()->getEntityManager(); 
            $factory = $this->get('security.encoder_factory'); 
            $encoder = $factory->getEncoder($entity);
            //$password = $encoder->encodePassword($entity->getPassword(), $entity->getSalt());
            $password = $encoder->encodePassword("p".substr($entity->getDni(), 0, -2), $entity->getSalt());
            $entity->setPassword($password);
            $role = $em->getRepository('BackendBundle:Role')->find(2);
            $entity->setRole($role);

            $entity->setFechaAlta(new \DateTime("now"));
     
            $entity->setActivo(true);

            $entity->setUsername("p".substr($entity->getDni(), 0, -2));
            $entity->setClaveUsuario("profe: ".substr($entity->getDni(), 0, -2).substr($entity->getDni(), -1));

            //Se obtiene la foto subida y se guarda en la carpeta destino, asignandole un nombre único.
           
                $file = $entity->getFoto();    
        
            if($entity->getFoto()!=null){
                $fileName = uniqid().'.'.$file->guessExtension();
                //Dir_imagenes_prof está definido como parámetro en congif.yml
                $photoDir = $this->container->getParameter('Dir_imagenes_prof');
                $file->move($photoDir, $fileName);
                $entity->setFoto($fileName);
            }
            $entity->setHoras($horas);
            $entity->setHorasLectivas($lectivas);

            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();

            if ($request->isXmlHttpRequest()) {
                return new JsonResponse(array(
                    'message' => 'Success!',
                    'success' => true), 200);
            }
            return $this->redirect($this->generateUrl('profesor_show', array('id' => $entity->getId())));
        }

        if ($request->isMethod('POST')) {
            return new JsonResponse(array(
            'result' => 0,
            'message' => 'Invalid form',
            'data' => $this->getErrorMessages($form,$form->getName()),
            'success' => false), 400);
        }

        return $this->render('BackendBundle:Profesor:new.html.twig', array(
            'entity' => $entity,
            'form'   => $form->createView(),
        ));
    }


    /**
     * Creates a form to create a Profesor entity.
     *
     * @param Profesor $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createCreateForm(Profesor $entity)
    {
        $form = $this->createForm(new ProfesorType(), $entity, array(
            'action' => $this->generateUrl('profesor_create'),
            'method' => 'POST',
        ));

        $form->add('submit', 'submit', array('label' => 'Enviar'));

        return $form;
    }


    /**
     * Creates a form to create a Profesor entity.
     *
     * @param Profesor $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createCreateSearchForm(Profesor $entity)
    {
        $form = $this->createForm(new BusquedaProfesorType(), $entity, array(
            'action' => $this->generateUrl('profesor_search'),
            'method' => 'POST',
        ));

        $form->add('submit', 'submit', array('label' => 'Buscar'));

        return $form;
    }


    /**
     * Displays a form to create a new Profesor entity.
     *
     */
    public function newAction()
    {
        $entity = new Profesor();
        $form   = $this->createCreateForm($entity);

        if (!file_exists($this->container->getParameter('kernel.root_dir').'/../web/uploads/')) {
            mkdir($this->container->getParameter('kernel.root_dir').'/../web/uploads/', 0777, true);
        }
        if (!file_exists($this->container->getParameter('kernel.root_dir').'/../web/uploads/images/')) {
            mkdir($this->container->getParameter('kernel.root_dir').'/../web/uploads/images/', 0777, true);
        }
        
        return $this->render('BackendBundle:Profesor:new.html.twig', array(
            'entity' => $entity,
            'form'   => $form->createView(),
        ));
    }

    /**
     * Finds and displays a Profesor entity.
     *
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('BackendBundle:Profesor')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Profesor entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return $this->render('BackendBundle:Profesor:show.html.twig', array(
            'entity'      => $entity,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing Profesor entity.
     *
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('BackendBundle:Profesor')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Profesor entity.');
        }

        $editForm = $this->createEditForm($entity);
        $deleteForm = $this->createDeleteForm($id);

        // Se comprueba que existe el archivo.
        // En caso de perdida se le asigna null para que muestre las imágenes por defecto.
        $photoDir = $this->container->getParameter('Dir_imagenes_prof');
        if(!file_exists( $photoDir.$entity->getFoto() ))
        {
            $entity->setFoto(null);
        }
        

        return $this->render('BackendBundle:Profesor:edit.html.twig', array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
    * Creates a form to edit a Profesor entity.
    *
    * @param Profesor $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createEditForm(Profesor $entity)
    {
        $form = $this->createForm(new ProfesorType(), $entity, array(
            'action' => $this->generateUrl('profesor_update', array('id' => $entity->getId())),
            'method' => 'PUT',
        ));

        $form->add('submit', 'submit', array('label' => 'Guardar cambios'));

        return $form;
    }
    /**
     * Edits an existing Profesor entity.
     *
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();
        $horas=$this->get('request')->request->get('horas');
        $lectivas=$this->get('request')->request->get('lectivas');

        $entity = $em->getRepository('BackendBundle:Profesor')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Profesor entity.');
        }
        $fileOriginal=$entity->getFoto();

        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createEditForm($entity);

        $editForm->handleRequest($request);

        if ($editForm->isValid()) {


            $estado = $this->get('request')->request->get('estado');

            //Se obtiene la foto subida y se guarda en la carpeta destino, asignandole un nombre único.
            if($estado=="actual" || $estado=="actualizado"){
                $file = $entity->getFoto();    
            }
            else{
                $entity->setFoto(null);  
            }

            if( $estado=="actualizado" && $entity->getFoto()!=null){
                $fileName = uniqid().'.'.$file->guessExtension();
                $photoDir = $this->container->getParameter('Dir_imagenes_prof');
                $file->move($photoDir, $fileName);
                $entity->setFoto($fileName);
  
                // Comprueba que existe el arcivo de la imagen anterior y lo elimina.
                $dir_file=$photoDir.$fileOriginal;
                if(is_file( $dir_file )){
                unlink($dir_file);
                }
            }
            elseif($estado=="actual"){
                $photoDir = $this->container->getParameter('Dir_imagenes_prof');
                $dir_file=$photoDir.$fileOriginal;

                if(is_file( $dir_file )){
                    $entity->setFoto($fileOriginal);
                }
                else{
                    $entity->setFoto(null);
                }
            }
            else
            {
                $photoDir = $this->container->getParameter('Dir_imagenes_prof');

                $dir_file=$photoDir.$fileOriginal;
                if(is_file( $dir_file )){
                    unlink($dir_file);
                }
                $entity->setFoto(NULL);
            }
            $entity->setHoras($horas);
            $entity->setHorasLectivas($lectivas);
            $em->flush();

            if ($request->isXmlHttpRequest()) {
                return new JsonResponse(array(
                    'message' => 'Success!',
                    'data' =>$entity->getId(),
                    'success' => true), 200);
            }

            return $this->redirect($this->generateUrl('profesor_edit', array('id' => $id)));
        }

        if ($request->isMethod('POST')) {
            return new JsonResponse(array(
            'message' => 'Invalid form',
            'success' => false), 400);
        }

        return $this->render('BackendBundle:Profesor:edit.html.twig', array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }
    /**
     * Deletes a Profesor entity.
     *
     */
    public function deleteAction(Request $request, $id)
    {
        $form = $this->createDeleteForm($id);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('BackendBundle:Profesor')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find Profesor entity.');
            }

            $em->remove($entity);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('profesor'));
    }

    /**
     * Creates a form to delete a Profesor entity by id.
     *
     * @param mixed $id The entity id
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm($id)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('profesor_delete', array('id' => $id)))
            ->setMethod('DELETE')
            ->add('submit', 'submit', array('label' => 'Delete'))
            ->getForm()
        ;
    }

    public function searchAction()
    {
        $entity = new Profesor();
        $form = $this->createCreateSearchForm($entity);
        $em = $this->getDoctrine()->getManager();

        $entities = $em->getRepository('BackendBundle:Profesor')->findByActivo(0);
        $entities_active = $em->getRepository('BackendBundle:Profesor')->findByActivo(1);

        return $this->render('BackendBundle:Profesor:search.html.twig', array(
            'entity' => $entity,
            'form'   => $form->createView(),
            'entities' => $entities,
            'entities_active' => $entities_active,
        ));
    }

    public function SearchOldAction()
    {
        $em = $this->getDoctrine()->getManager();

        $entities = $em->getRepository('BackendBundle:Profesor')->findByActivo(0);
        $entities_active = $em->getRepository('BackendBundle:Profesor')->findByActivo(1);

        return $this->render('BackendBundle:Profesor:search_old.html.twig', array(
            'entities' => $entities,
            'entities_active' => $entities_active
        ));
    }
    public function ComprobarProfesorAction()
    {
        $nombre=$this->get('request')->request->get('nombre');
        $apellido1=$this->get('request')->request->get('apellido1');
        $apellido2=$this->get('request')->request->get('apellido2');
        $em = $this->getDoctrine()->getEntityManager();
        $profesor = $em->getRepository('BackendBundle:Profesor')->findProfesor($nombre, $apellido1, $apellido2);
        if($profesor){
            return new JsonResponse(array('data' =>$profesor->getId()), 200);
        }
        return new JsonResponse(array('data' =>null), 200);

    }
    
    public function ComprobarDniProfesorAction()
    {
        $dni=$this->get('request')->request->get('dni');

        $em = $this->getDoctrine()->getEntityManager();
        $profesor = $em->getRepository('BackendBundle:Profesor')->findOneByDni($dni);
        if($profesor){
            return new JsonResponse(array('data' =>$profesor->getDni()), 200);
        }
        return new JsonResponse(array('data' =>null), 200);
    }

    public function ComprobarDniProfesorEditadoAction()
    {
        $dni=$this->get('request')->request->get('dni');
        $id=$this->get('request')->request->get('id');

        $em = $this->getDoctrine()->getEntityManager();
        $profesor = $em->getRepository('BackendBundle:Profesor')->findOneByDni($dni);
        if($profesor && $profesor->getId()!=$id){
            return new JsonResponse(array('data' =>$profesor->getDni()), 200);
        }
        return new JsonResponse(array('data' =>null), 200);

    }

    public function ProfesoresPorCursoAction($id)
    {

        $em = $this->getDoctrine()->getManager();
        if($id==0){
            $entities = $em->getRepository('BackendBundle:Profesor')->findAll();
            return $this->render('BackendBundle:Profesor:lista_profesores_busqueda_cursos.html.twig', array(
            'entities' => $entities,
            ));
        }
        else{
            $entities= $em->getRepository('BackendBundle:Profesor')->findProfesoresPorCurso($id);
            return $this->render('BackendBundle:Profesor:lista_profesores_busqueda_cursos.html.twig', array(
            'entities' => $entities,
            ));
        }
    }

    public function DatosProfesorAction($id)
    {
        $em = $this->getDoctrine()->getManager();
        $entity = $em->getRepository('BackendBundle:Profesor')->findById($id);
        return $this->render('BackendBundle:Profesor:datos_profesor.html.twig', array(
            'entity' => $entity,));
    }

    public function DatosAntiguoProfesorAction($id)
    {
        $em = $this->getDoctrine()->getManager();
        $entity = $em->getRepository('BackendBundle:Profesor')->findById($id);
        return $this->render('BackendBundle:Profesor:datos_antiguo_profesor.html.twig', array(
            'entity' => $entity,));
    }

    public function AltaProfesorAction()
    {
        $profesores=$this->get('request')->request->get('array');

        $em = $this->getDoctrine()->getManager();

        foreach ($profesores as $profesor ) {
            $entity = $em->getRepository('BackendBundle:Profesor')->find($profesor);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find Profesor entity.');
            }

            $contenido=$entity->getObservaciones();
            $contenido=$contenido."\n- Alta del antiguo profesor  |  Alta anterior: ".$entity->getFechaAlta()->format("d-m-Y")
            ."      Baja anterior: ".$entity->getFechaBaja()->format("d-m-Y");
            $entity->setObservaciones($contenido);
            $entity->setFechaBaja(null);
            $entity->setFechaAlta(new \DateTime("now"));
            $entity->setActivo(true);

            $em->persist($entity);                 
            $em->flush();
        }
        return new JsonResponse(array('message' => 'Success!','success' => true), 200);
    }

    public function BajaProfesorAction()
    {
        $profesores=$this->get('request')->request->get('array');

        $em = $this->getDoctrine()->getManager();

        foreach ($profesores as $profesor ) {
            $entity = $em->getRepository('BackendBundle:Profesor')->find($profesor);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find Profesor entity.');
            }

            $query = $em->createQuery('DELETE BackendBundle:Imparte i WHERE i.profesor = :Id')->setParameter("Id", $entity->getId());
            $query->execute();

            $query = $em->createQuery('DELETE BackendBundle:Reserva r WHERE r.profesor = :Id')->setParameter("Id", $entity->getId());
            $query->execute();

            $grupo = $em->getRepository('BackendBundle:Grupo')->findOneByProfesor($entity->getId());
            if ($grupo) {
                $grupo->setProfesor(null);
                $em->persist($grupo);  
            }

            $entity->setFechaBaja(new \DateTime("now"));
            $entity->setActivo(false);

            $em->persist($entity);                 
            $em->flush();
        }
        return new JsonResponse(array('message' => 'Success!','success' => true), 200);
    }

    public function ClasesImpartidasAction()
    {
        $em = $this->getDoctrine()->getManager();

        $entities = $em->getRepository('BackendBundle:Profesor')->findProfesoresDePrimaria();
        if (!$entities) {
            $numProfesores=0;
        }
        else{
            $numProfesores=1;
        }

        $horarios=$em->getRepository('BackendBundle:Horario')->findAll();
        if (!$horarios) {
            $horario=0;
        }
        else{
            $horario=1;
        }

        return $this->render('BackendBundle:Profesor:clases_impartidas.html.twig', array(
            'entities' => $entities,
            'horario' => $horario,
            'numProfesores' => $numProfesores,
        ));
    }

    public function HorarioProfesorAction($id)
    {
        $em = $this->getDoctrine()->getManager();
        $profesor = $em->getRepository('BackendBundle:Profesor')->findOneById($id);
        $entity = $em->getRepository('BackendBundle:Horario')->findAll();
        
        $imparte = $em->getRepository('BackendBundle:Imparte')->findByProfesor($id);

        $array=[];
        foreach($imparte as $registro){
            if($registro->getAula()){
                $aula=$registro->getAula()->getNombre();
            }
            else{
                $aula="";
            }

            if($registro->getDiaSemanal()){
                $dia=$registro->getDiaSemanal();
            }
            else{
                $dia=""; 
            }

            if($registro->getHorario()){
                $horario=$registro->getHorario()->getId();
            }
            else{
                $horario="";
            }
           
            $array[$horario."-".$dia."-".$registro->getAsignatura()->getAsignatura()->getNombre()."-".$registro->getAsignatura()->getAsignatura()->getAbreviatura()."-".$aula."-".$registro->getGrupo()->getCurso()->getCurso()."-".$registro->getGrupo()->getLetra()."-".$registro->getProfesor()->getId()]=$registro->getProfesor()->getNombre()." ".$registro->getProfesor()->getApellido1()." ".$registro->getProfesor()->getApellido2();
        }

        return $this->render('BackendBundle:Profesor:datos_horarios.html.twig', array(
            'imparte' => $array,
            'entity' => $entity));
    }


    //Función de prueba para ver los resultados del horario en html.
    public function HorarioDelProfesorAction($id)
    {

        $em = $this->getDoctrine()->getManager();

        $profesor= $em->getRepository('BackendBundle:Profesor')->findOneById($id);

        $inicio =$em->getRepository('BackendBundle:Centro')->findInicioCurso();
        $fin =$em->getRepository('BackendBundle:Centro')->findFinCurso();

        $horarios = $em->getRepository('BackendBundle:Horario')->findAll();

        $grupo_tutor= $em->getRepository('BackendBundle:Grupo')->findOneByProfesor($id);

        $imparte = $em->getRepository('BackendBundle:Imparte')->findByProfesor($id);

        $array=[];
        foreach($imparte as $registro){
            if($registro->getAula()){
                $aula=$registro->getAula()->getNombre();
            }
            else{
                $aula="";
            }

            if($registro->getDiaSemanal()){
                $dia=$registro->getDiaSemanal();
            }
            else{
                $dia=""; 
            }

            if($registro->getHorario()){
                $horario=$registro->getHorario()->getId();
            }
            else{
                $horario="";
            }
           
            $array[$horario."-".$dia."-".$registro->getAsignatura()->getAsignatura()->getNombre()."-".$registro->getAsignatura()->getAsignatura()->getAbreviatura()."-".$aula."-".$registro->getGrupo()->getCurso()->getCurso()." ".$registro->getGrupo()->getLetra()]=$registro->getProfesor()->getNombre()." ".$registro->getProfesor()->getApellido1()." ".$registro->getProfesor()->getApellido2();
        }

        return $this->render('BackendBundle:Profesor:horario_del_profesor.html.twig', array(
            'entities' => $imparte,
            'inicio' => $inicio,
            'fin' => $fin,
            'profesor' => $registro->getProfesor()->getNombre()." ".$registro->getProfesor()->getApellido1()." ".$registro->getProfesor()->getApellido2(),
            'row_profesor' => $profesor,
            'horarios' => $horarios,
            'imparte' => $array,
            'grupo_tutor' => $grupo_tutor
        ));
    }

    
    public function HorarioDelProfesorPdfAction($id)
    {

        $em = $this->getDoctrine()->getManager();

        $profesor= $em->getRepository('BackendBundle:Profesor')->findOneById($id);

        $inicio =$em->getRepository('BackendBundle:Centro')->findInicioCurso();
        $fin =$em->getRepository('BackendBundle:Centro')->findFinCurso();

        $horarios = $em->getRepository('BackendBundle:Horario')->findAll();

        $grupo_tutor= $em->getRepository('BackendBundle:Grupo')->findOneByProfesor($id);

        $imparte = $em->getRepository('BackendBundle:Imparte')->findByProfesor($id);

        $array=[];
        foreach($imparte as $registro){
            if($registro->getAula()){
                $aula=$registro->getAula()->getNombre();
            }
            else{
                $aula="";
            }

            if($registro->getDiaSemanal()){
                $dia=$registro->getDiaSemanal();
            }
            else{
                $dia=""; 
            }

            if($registro->getHorario()){
                $horario=$registro->getHorario()->getId();
            }
            else{
                $horario="";
            }
           
            $array[$horario."-".$dia."-".$registro->getAsignatura()->getAsignatura()->getNombre()."-".$registro->getAsignatura()->getAsignatura()->getAbreviatura()."-".$aula."-".$registro->getGrupo()->getCurso()->getCurso()." ".$registro->getGrupo()->getLetra()]=$registro->getProfesor()->getNombre()." ".$registro->getProfesor()->getApellido1()." ".$registro->getProfesor()->getApellido2();
        }

        $html = $this->renderView('BackendBundle:Profesor:horario_del_profesor.html.twig', array(
            'entities' => $imparte,
            'inicio' => $inicio,
            'fin' => $fin,
            'profesor' => $profesor->getNombre()." ".$profesor->getApellido1()." ".$profesor->getApellido2(),
            'row_profesor' => $profesor,
            'horarios' => $horarios,
            'imparte' => $array,
            'grupo_tutor' => $grupo_tutor
        ));

        $options = [
            'margin-top'    => 3,
            'margin-right'  => 8,
            'margin-bottom' => 3,
            'margin-left'   => 8,
            //Opciones para orientación horizontal.
            'orientation'=>'Landscape', 
            'default-header'=>false
        ];
        $dato=substr($profesor->getNombre(), 0, 1).substr($profesor->getApellido1(), 0, 1).substr($profesor->getApellido2(), 0, 1);
        return new Response(
            $this->get('knp_snappy.pdf')->getOutputFromHtml($html,$options),
            200,
            array(
                'Content-Type'        => 'application/pdf',
                'Content-Disposition' => 'attachment; filename="Horario_Docente_'.$dato.'.pdf"'
            )
        );
    }



}
