<?php

namespace Cole\BackendBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;

use Cole\BackendBundle\Entity\Alumno;
use Cole\BackendBundle\Entity\Curso;
use Cole\BackendBundle\Entity\Grupo;
use Cole\BackendBundle\Form\AlumnoType;
use Cole\BackendBundle\Form\BusquedaAlumnoType;

/**
 * Alumno controller.
 *
 */
class AlumnoController extends Controller
{

    /**
     * Lists all Alumno entities.
     *
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $entities = $em->getRepository('BackendBundle:Alumno')->findAll();

        return $this->render('BackendBundle:Alumno:index.html.twig', array(
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
     * Creates a new Alumno entity.
     *
     */
    public function createAction(Request $request)
    {
    // if request is XmlHttpRequest (AJAX) but not a POSt, throw an exception
    if ($request->isXmlHttpRequest() && !$request->isMethod('POST')) {
        throw new HttpException('XMLHttpRequests/AJAX calls must be POSTed');
    }

        $entity = new Alumno();
        $form = $this->createCreateForm($entity);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getEntityManager();
            $entity->setFechaAlta(new \DateTime("now"));
            $entity->setCurso($entity->getCursoIngreso());
            $entity->setGrupo(null);
            $entity->setNumAlum(null);
            $entity->setActivo(true);
            if(date("n")>=6){
                $entity->setAñoAcademico(date("Y")."/".(date("Y")+1));
            }
            else{
                $entity->setAñoAcademico((date("Y")-1)."/".date("Y"));
            }
            $role = $em->getRepository('BackendBundle:Role')->find(1);
            $entity->getResponsable1()->setRole($role);
            $entity->getResponsable2()->setRole($role);
            $entity->getResponsable1()->setUsername("u".substr($entity->getResponsable1()->getDni(), 0, -2));
            $entity->getResponsable2()->setUsername("u".substr($entity->getResponsable2()->getDni(), 0, -2));
            
            $factory = $this->get('security.encoder_factory'); 
            $encoder = $factory->getEncoder($entity);
            $password1 = $encoder->encodePassword("u".substr($entity->getResponsable1()->getDni(), 0, -2), $entity->getResponsable1()->getSalt());
            $password2 = $encoder->encodePassword("u".substr($entity->getResponsable2()->getDni(), 0, -2), $entity->getResponsable2()->getSalt());
            $entity->getResponsable1()->setPassword($password1);
            $entity->getResponsable1()->setClaveUsuario("prueba: ".substr($entity->getResponsable1()->getDni(), 0, -2).substr($entity->getResponsable1()->getDni(), -1));

            //Busqueda y asignación del responsable si ya existe en el hijo correspondiente.        
            $responsable1 = $em->getRepository('BackendBundle:Padres')->findResponsable($entity->getResponsable1()->getDni());
            /*if (!$responsable1) {
                throw $this->createNotFoundException('Unable to find Padres entity.');
            }*/
            $responsable2 = $em->getRepository('BackendBundle:Padres')->findResponsable($entity->getResponsable2()->getDni());
            /*if (!$responsable2) {
                throw $this->createNotFoundException('Unable to find Padres entity.');
            }*/

            //En caso de tener sólo un responsable, al segundo se le asigna null.
            if($entity->getResponsable2()->getDni() == "" ){
                $entity->setResponsable2(NULL); 
            }
            else{
                $entity->getResponsable2()->setPassword($password2);
                $entity->getResponsable2()->setClaveUsuario("prueba: ".substr($entity->getResponsable2()->getDni(), 0, -2).substr($entity->getResponsable2()->getDni(), -1));
            }

            if($responsable1){
                $entity->setResponsable1($responsable1); 
            }

            if($responsable2){
                $entity->setResponsable2($responsable2); 
            }

            /* Asignación como 2º responsable el 1º, en caso de no existir segundo. 
                MODIFICADO a NULL abajo.

            if($responsable2){
                $entity->setResponsable2($responsable2); 
            }

            //En caso de tener sólo un responsable, al segundo se le asigna el mismo.
            if($entity->getResponsable2()->getDni() == "" ){
                $entity->setResponsable2($entity->getResponsable1()); 
            }

            $entity->getResponsable1()->setPassword($password1);
            $entity->getResponsable2()->setPassword($password2);

            $entity->getResponsable1()->setClaveUsuario("prueba: ".substr($entity->getResponsable1()->getDni(), 0, -2).substr($entity->getResponsable1()->getDni(), -1));
            $entity->getResponsable2()->setClaveUsuario("prueba: ".substr($entity->getResponsable2()->getDni(), 0, -2).substr($entity->getResponsable2()->getDni(), -1));
            */


            //Se obtiene la foto subida yse guarda en la carpeta destino, asignandole un nombre único.
            $file = $entity->getFoto();
            if($entity->getFoto()!="On" && $entity->getFoto()!="" ){
                $fileName = uniqid().'.'.$file->guessExtension();
                $photoDir = $this->container->getParameter('kernel.root_dir').'/../web/uploads/images';
                $file->move($photoDir, $fileName);
                $entity->setFoto($fileName);
            }
            else{
                $entity->setFoto(NULL);  
            }
            
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();

            if ($request->isXmlHttpRequest()) {
                return new JsonResponse(array(
                    'message' => 'Success!',
                    'alumno' => $entity->getId(),
                    'success' => true), 200);
            }
            return $this->redirect($this->generateUrl('alumno_show', array('id' => $entity->getId())));
        }

        if ($request->isMethod('POST')) {
            return new JsonResponse(array(
            'result' => 0,
            'message' => 'Invalid form',
            'data' => $this->getErrorMessages($form,$form->getName()),
            'success' => false), 400);
        }

        return $this->render('BackendBundle:Alumno:new.html.twig', array(
            'entity' => $entity,
            'form'   => $form->createView(),
        ));
    }

    /**
     * Creates a form to create a Alumno entity.
     *
     * @param Alumno $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createCreateForm(Alumno $entity)
    {
        $form = $this->createForm(new AlumnoType(), $entity, array(
            'action' => $this->generateUrl('alumno_create'),
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
    private function createCreateSearchForm(Alumno $entity)
    {
        $form = $this->createForm(new BusquedaAlumnoType(), $entity, array(
            'action' => $this->generateUrl('alumno_search'),
            'method' => 'POST',
        ));

        $form->add('submit', 'submit', array('label' => 'Buscar'));

        return $form;
    }

    /**
     * Displays a form to create a new Alumno entity.
     *
     */
    public function newAction()
    {
        $entity = new Alumno();
        $form   = $this->createCreateForm($entity);

        return $this->render('BackendBundle:Alumno:new.html.twig', array(
            'entity' => $entity,
            'form'   => $form->createView(),
        ));
    }

    /**
     * Finds and displays a Alumno entity.
     *
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('BackendBundle:Alumno')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Alumno entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return $this->render('BackendBundle:Alumno:show.html.twig', array(
            'entity'      => $entity,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing Alumno entity.
     *
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('BackendBundle:Alumno')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Alumno entity.');
        }

        $editForm = $this->createEditForm($entity);
        $deleteForm = $this->createDeleteForm($id);

        // Se comprueba que existe el archivo.
        // En caso de perdida se le asigna null para que muestre las imágenes por defecto.
        $photoDir = $this->container->getParameter('Dir_imagenes_alum');
        if(!file_exists( $photoDir.$entity->getFoto() ))
        {
            $entity->setFoto(null);
        }
        

        return $this->render('BackendBundle:Alumno:edit.html.twig', array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
    * Creates a form to edit a Alumno entity.
    *
    * @param Alumno $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createEditForm(Alumno $entity)
    {
        $form = $this->createForm(new AlumnoType(), $entity, array(
            'action' => $this->generateUrl('alumno_update', array('id' => $entity->getId())),
            'method' => 'PUT',
        ));

        $form->add('submit', 'submit', array('label' => 'Guardar cambios'));

        return $form;
    }
    /**
     * Edits an existing Alumno entity.
     *
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('BackendBundle:Alumno')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Alumno entity.');
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
                $photoDir = $this->container->getParameter('Dir_imagenes_alum');
                $file->move($photoDir, $fileName);
                $entity->setFoto($fileName);
  
                // Comprueba que existe el arcivo de la imagen anterior y lo elimina.
                $dir_file=$photoDir.$fileOriginal;
                if(is_file( $dir_file )){
                unlink($dir_file);
                }
            }
            elseif($estado=="actual"){
                $photoDir = $this->container->getParameter('Dir_imagenes_alum');
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
                $photoDir = $this->container->getParameter('Dir_imagenes_alum');

                $dir_file=$photoDir.$fileOriginal;
                if(is_file( $dir_file )){
                    unlink($dir_file);
                }
                $entity->setFoto(NULL);
            }
            if($entity->getResponsable2()->getDni() == "" ){
                $entity->setResponsable2(NULL); 
            }
            $em->flush();

            if ($request->isXmlHttpRequest()) {
                return new JsonResponse(array(
                    'message' => 'Success!',
                    'data' =>$entity->getId(),
                    'success' => true), 200);
            }

            return $this->redirect($this->generateUrl('alum_edit', array('id' => $id)));
        }

        if ($request->isMethod('POST')) {
            return new JsonResponse(array(
            'message' => 'Invalid form',
            'success' => false), 400);
        }

        return $this->render('BackendBundle:Alumno:edit.html.twig', array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }
    /**
     * Deletes a Alumno entity.
     *
     */
    public function deleteAction(Request $request, $id)
    {
        $form = $this->createDeleteForm($id);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('BackendBundle:Alumno')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find Alumno entity.');
            }

            $em->remove($entity);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('alumno'));
    }

    /**
     * Creates a form to delete a Alumno entity by id.
     *
     * @param mixed $id The entity id
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm($id)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('alumno_delete', array('id' => $id)))
            ->setMethod('DELETE')
            ->add('submit', 'submit', array('label' => 'Delete'))
            ->getForm()
        ;
    }


    public function searchAction()
    {
        $entity = new Alumno();
        $form = $this->createCreateSearchForm($entity);

        
        return $this->render('BackendBundle:Alumno:search.html.twig', array(
            'entity' => $entity,
            'form'   => $form->createView(),
        ));
    }

    public function SearchOldAction()
    {
        $em = $this->getDoctrine()->getManager();
        $entities = $em->getRepository('BackendBundle:Alumno')->findByActivo(0); //Hay que comprobar que no hayan terminado los estudios.
        
        return $this->render('BackendBundle:Alumno:search_old.html.twig', array(
            'entities' => $entities,
        ));
    }
    
    public function ListaCursosAction()
    {
        $entity = new Alumno();
        $form = $this->createCreateSearchForm($entity);
        
        return $this->render('BackendBundle:Curso:listaCursos.html.twig');
    }


    public function ComprobarAlumnoAction()
    {
        $nombre=$this->get('request')->request->get('nombre');
        $apellido1=$this->get('request')->request->get('apellido1');
        $apellido2=$this->get('request')->request->get('apellido2');
        $em = $this->getDoctrine()->getEntityManager();
        $alumno= $em->getRepository('BackendBundle:Alumno')->findAlumno($nombre, $apellido1, $apellido2);
        if($alumno){
            return new JsonResponse(array('data' =>$alumno->getId()), 200);
        }
        return new JsonResponse(array('data' =>null), 200);

    }

    public function AlumnosPorCursoAction($id)
    {

        $em = $this->getDoctrine()->getManager();
        if($id==0){
            $entities = $em->getRepository('BackendBundle:Alumno')->findAll();
            return $this->render('BackendBundle:Alumno:lista_alumnos_busqueda_cursos.html.twig', array(
            'entities' => $entities,
            ));
        }
        else{
            $entities= $em->getRepository('BackendBundle:Alumno')->findAlumnosPorCurso($id);
            return $this->render('BackendBundle:Alumno:lista_alumnos_busqueda_cursos.html.twig', array(
            'entities' => $entities,
            ));
        }
    }

    public function AsignarResponsableAction(Request $request)
    {
    // if request is XmlHttpRequest (AJAX) but not a POSt, throw an exception
        if ($request->isXmlHttpRequest() && !$request->isMethod('POST')) {
            throw new HttpException('XMLHttpRequests/AJAX calls must be POSTed');
        }

        $id_alumno=$this->get('request')->request->get('id_alumno');
        $id_responsable=$this->get('request')->request->get('id_responsable');
        $responsable=$this->get('request')->request->get('responsable');
        
        $em = $this->getDoctrine()->getEntityManager();
        $entity = $em->getRepository('BackendBundle:Alumno')->findAlumnoById($id_alumno);
        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Alumno entity.');
        }
        
        //Busqueda y asignación del responsable.        
        $resp = $em->getRepository('BackendBundle:Padres')->findResponsableById($id_responsable);
        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Padres entity.');
        }
            
        if($responsable=="responsable1"){
            $entity->setResponsable1($resp); 
        }
        else{
            $entity->setResponsable2($resp); 
        }
        $em = $this->getDoctrine()->getManager();
        $em->persist($entity);
        $em->flush();

        //Se devuelve los datos del nuevo representante para mostrar en el formulario de editar alumnos.
        $array['dni'] = $resp->getDni();
        $array['nombre'] = $resp->getNombre();
        $array['fechaNacimiento'] = $resp->getFechaNacimiento()->format('d/m/Y');
        $array['profesion'] = $resp->getProfesion();
        $array['estadoCivil'] = $resp->getEstadoCivil();
        $array['movil'] = $resp->getMovil();
        $array['email'] = $resp->getEmail();
        
        if ($request->isXmlHttpRequest()) {
                return new JsonResponse(array(
                    'data'=>$array,
                    'message' => 'Success!',
                    'success' => true), 200);
        }
    }

    public function EliminarResponsableAction(Request $request)
    {
    // if request is XmlHttpRequest (AJAX) but not a POSt, throw an exception
        if ($request->isXmlHttpRequest() && !$request->isMethod('POST')) {
            throw new HttpException('XMLHttpRequests/AJAX calls must be POSTed');
        }

        $id_alumno=$this->get('request')->request->get('id_alumno');
        
        $em = $this->getDoctrine()->getEntityManager();
        $entity = $em->getRepository('BackendBundle:Alumno')->findAlumnoById($id_alumno);
        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Alumno entity.');
        }
            
        $entity->setResponsable2(NULL); 

        $em = $this->getDoctrine()->getManager();
        $em->persist($entity);
        $em->flush();
        
        if ($request->isXmlHttpRequest()) {
                return new JsonResponse(array(
                    'message' => 'Success!',
                    'success' => true), 200);
        }
    }

    
    public function AlumnosAsignadosCursoAction()
    {
        $curso=$this->get('request')->request->get('curso');
        $nivel=$this->get('request')->request->get('nivel');

        $em = $this->getDoctrine()->getEntityManager();

        $entity = $em->getRepository('BackendBundle:Curso')->findCursoByNivel($curso,$nivel);
        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Curso entity.');
        }

        $alumnos= $em->getRepository('BackendBundle:Alumno')->findAlumnosPorCurso($entity);
        if($alumnos){
            return new JsonResponse(array('data' =>$alumnos), 200);
        }
        return new JsonResponse(array('data' =>null), 200);

    }


    public function AlumnosAsignadosGrupoAction()
    {
        $curso=$this->get('request')->request->get('curso');
        $nivel=$this->get('request')->request->get('nivel');
        $num_grupos=$this->get('request')->request->get('num_grupos');
        $num_grupos_ant=$this->get('request')->request->get('num_grupos_ant');

        $em = $this->getDoctrine()->getEntityManager();

        $entity = $em->getRepository('BackendBundle:Curso')->findCursoByNivel($curso,$nivel);
        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Curso entity.');
        }

        if($num_grupos_ant==2 && $num_grupos==1){

            $grupo = $em->getRepository('BackendBundle:Grupo')->findGrupoByLetter($entity,"B");
            if (!$entity) {
                throw $this->createNotFoundException('Unable to find Grupo entity.');
            }     

            $alumnos= $em->getRepository('BackendBundle:Alumno')->findAlumnosPorGrupo($grupo);
            if($alumnos){
                return new JsonResponse(array('data' =>"No se puede actualizar el número de grupos.\n\nExisten alumnos asignados a (poner curso y grupo).\n\n"), 200);
            }
            return new JsonResponse(array('data' =>null), 200);
        }
        else if($num_grupos_ant==3 && $num_grupos==2){

            $grupo = $em->getRepository('BackendBundle:Grupo')->findGrupoByLetter($entity,"C");
            if (!$entity) {
                throw $this->createNotFoundException('Unable to find Grupo entity.');
            }     

            $alumnos= $em->getRepository('BackendBundle:Alumno')->findAlumnosPorGrupo($grupo);
            if($alumnos){
                return new JsonResponse(array('data' =>"No se puede actualizar el número de grupos.\n\nExisten alumnos asignados a (poner curso y grupo).\n\n"), 200);
            }
            return new JsonResponse(array('data' =>null), 200);
        }
        else{

            $grupo_3 = $em->getRepository('BackendBundle:Grupo')->findGrupoByLetter($entity,"C");
            if (!$entity) {
                throw $this->createNotFoundException('Unable to find Grupo entity.');
            }

            $grupo_2 = $em->getRepository('BackendBundle:Grupo')->findGrupoByLetter($entity,"B");
            if (!$entity) {
                throw $this->createNotFoundException('Unable to find Grupo entity.');
            }          

            $alumnos_grupo_3= $em->getRepository('BackendBundle:Alumno')->findAlumnosPorGrupo($grupo_3);
            $alumnos_grupo_2= $em->getRepository('BackendBundle:Alumno')->findAlumnosPorGrupo($grupo_2);

            if($alumnos_grupo_2 && $alumnos_grupo_3){
                return new JsonResponse(array('data' =>"No se puede actualizar el número de grupos.\n\nExisten alumnos asignados a (poner curso y grupo B y C).\n\n"), 200);
            }

            else if(!$alumnos_grupo_2 && !$alumnos_grupo_3){
                return new JsonResponse(array('data' =>null), 200);
            }
            else if($alumnos_grupo_2){
                return new JsonResponse(array('data' =>"No se puede actualizar el número de grupos.\n\nExisten alumnos asignados a (poner curso y grupo B).\n\n"), 200);
                }
        else{
                return new JsonResponse(array('data' =>"No se puede actualizar el número de grupos.\n\nExisten alumnos asignados a (poner curso y grupo C).\n\n"), 200);

                }
        }
    }

    public function DatosAlumnoAction($id)
    {
        $em = $this->getDoctrine()->getManager();
        $entity = $em->getRepository('BackendBundle:Alumno')->findById($id);
        return $this->render('BackendBundle:Alumno:datos_alumno.html.twig', array(
            'entity' => $entity,));
    }

    public function AntiguosAlumnosPorCursoAction($id)
    {

        $em = $this->getDoctrine()->getManager();
        if($id==0){
            $entities = $em->getRepository('BackendBundle:Alumno')->findByActivo(0); //Hay que comprobar que no hayan terminado los estudios.
            return $this->render('BackendBundle:Alumno:lista_antiguos_alumnos_busqueda_cursos.html.twig', array(
            'entities' => $entities,
            ));
        }
        else{
            $entities= $em->getRepository('BackendBundle:Alumno')->findAntiguosAlumnosPorCurso($id);
            return $this->render('BackendBundle:Alumno:lista_antiguos_alumnos_busqueda_cursos.html.twig', array(
            'entities' => $entities,
            ));
        }
    }


    public function OldEditAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('BackendBundle:Alumno')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Alumno entity.');
        }

        $editForm = $this->createEditForm($entity);
        $deleteForm = $this->createDeleteForm($id);

        // Se comprueba que existe el archivo.
        // En caso de perdida se le asigna null para que muestre las imágenes por defecto.
        $photoDir = $this->container->getParameter('Dir_imagenes_alum');
        if(!file_exists( $photoDir.$entity->getFoto() ))
        {
            $entity->setFoto(null);
        }
        

        return $this->render('BackendBundle:Alumno:edit_old.html.twig', array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    public function OldUpdateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('BackendBundle:Alumno')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Alumno entity.');
        }

        $c = $this->get('request')->request->get('curso');
        $curso = $em->getRepository('BackendBundle:Curso')->find($c);
        //Se valida que no matricrule en un curso anterior.
        $antiguo_curso=$entity->getCurso();
 
        if($antiguo_curso->getNumOrden()>$curso->getNumOrden()){
                return new JsonResponse(array(
                    'validate' =>"curso_incorrecto",
                    'success' => true), 200);
        }
        //Se valia que no esté matriculado en el año Académico actual.
        if(date("n")>=6){
            if($entity->getAñoAcademico()==date("Y")."/".(date("Y")+1)){
                return new JsonResponse(array(
                    'validate' =>"año_incorrecto",
                    'success' => true), 200);
            }
        }
        else{
            if($entity->getAñoAcademico()==(date("Y")-1)."/".date("Y")){
                return new JsonResponse(array(
                    'validate' =>"año_incorrecto",
                    'success' => true), 200);
            }
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
                $photoDir = $this->container->getParameter('Dir_imagenes_alum');
                $file->move($photoDir, $fileName);
                $entity->setFoto($fileName);
  
                // Comprueba que existe el arcivo de la imagen anterior y lo elimina.
                $dir_file=$photoDir.$fileOriginal;
                if(is_file( $dir_file )){
                unlink($dir_file);
                }
            }
            elseif($estado=="actual"){
                $photoDir = $this->container->getParameter('Dir_imagenes_alum');
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
                $photoDir = $this->container->getParameter('Dir_imagenes_alum');

                $dir_file=$photoDir.$fileOriginal;
                if(is_file( $dir_file )){
                    unlink($dir_file);
                }
                $entity->setFoto(NULL);
            }
            if($entity->getResponsable2()->getDni() == "" ){
                $entity->setResponsable2(NULL); 
            }
            $entity->setActivo(1);
            $entity->setCurso($curso);
            if(date("n")>=6){
                $entity->setAñoAcademico(date("Y")."/".(date("Y")+1));
            }
            else{
                $entity->setAñoAcademico((date("Y")-1)."/".date("Y"));
            }
            
            $em->persist($entity);
            $em->flush();

            if ($request->isXmlHttpRequest()) {
                return new JsonResponse(array(
                    'message' => 'Success!',
                    'data' =>$entity->getId(),
                    'success' => true), 200);
            }

            return $this->redirect($this->generateUrl('alum_edit', array('id' => $id)));
        }

        if ($request->isMethod('POST')) {
            return new JsonResponse(array(
            'message' => 'Invalid form',
            'success' => false), 400);
        }

        return $this->render('BackendBundle:Alumno:edit.html.twig', array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }








}
