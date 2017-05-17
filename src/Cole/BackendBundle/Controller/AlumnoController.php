<?php

namespace Cole\BackendBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;

use Cole\BackendBundle\Entity\Alumno;
use Cole\BackendBundle\Entity\Curso;
use Cole\BackendBundle\Entity\Grupo;
use Cole\BackendBundle\Entity\Expediente;
use Cole\BackendBundle\Entity\Matricula;
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
            $entity->setAccesoNoticias(null);
            if(date("n")>=6){
                $entity->setAnyoAcademico(date("Y")." / ".(date("Y")+1));
            }
            else{
                $entity->setAnyoAcademico((date("Y")-1)." / ".date("Y"));
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
            if($entity->getFoto()!="On" && $entity->getFoto()!="" && $entity->getFoto()!="on"){
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

        if (!file_exists($this->container->getParameter('kernel.root_dir').'/../web/uploads/')) {
            mkdir($this->container->getParameter('kernel.root_dir').'/../web/uploads/', 0777, true);
        }
        if (!file_exists($this->container->getParameter('kernel.root_dir').'/../web/uploads/images/')) {
            mkdir($this->container->getParameter('kernel.root_dir').'/../web/uploads/images/', 0777, true);
        }
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
                $photoDir = $this->container->getParameter('Dir_imagenes_alum'); //Parámetro en config.yml
                $file->move($photoDir, $fileName);
                $entity->setFoto($fileName);
  
                // Comprueba que existe el archivo de la imagen anterior y lo elimina.
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

        $em = $this->getDoctrine()->getManager();
        
        $curso=$em->getRepository('BackendBundle:Curso')->findUltimoCurso();
        $entities = $em->getRepository('BackendBundle:Expediente')->findNoActivos($curso->getCurso(),$curso->getNivel()); 
        //Se obtiene los alumnos activos que están promocionados en el curso anterior y aparecen en el expediente.
        $entities_active = $em->getRepository('BackendBundle:Alumno')->findByActivo(1);
        
        return $this->render('BackendBundle:Alumno:search.html.twig', array(
            'entity' => $entity,
            'form'   => $form->createView(),
            'entities' => $entities,
            'entities_active' => $entities_active,
        ));
    }

    public function SearchOldAction()
    {
        $em = $this->getDoctrine()->getManager();

        $curso=$em->getRepository('BackendBundle:Curso')->findUltimoCurso();
        $entities = $em->getRepository('BackendBundle:Expediente')->findNoActivos($curso->getCurso(),$curso->getNivel()); 
        //Se obtiene los alumnos activos que están promocionados en el curso anterior y aparecen en el expediente.
        $entities_active = $em->getRepository('BackendBundle:Expediente')->findByActivo($curso->getCurso(),$curso->getNivel());

        return $this->render('BackendBundle:Alumno:search_old.html.twig', array(
            'entities' => $entities,
            'entities_active' => $entities_active,
            
        ));
    }

    public function SearchMultipleAction()
    {
        $em = $this->getDoctrine()->getManager();
        $curso=$em->getRepository('BackendBundle:Curso')->findUltimoCurso();
        $entities = $em->getRepository('BackendBundle:Expediente')->findByActivo($curso->getCurso(),$curso->getNivel());

        return $this->render('BackendBundle:Alumno:search_multiple.html.twig', array(
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
        // Tres posibles casos: de 2 grupos a 1, de 3 grupos a 2 y de 3 grupos a 1.
        if($num_grupos_ant==2 && $num_grupos==1){
            $grupo = $em->getRepository('BackendBundle:Grupo')->findGrupoByLetter($entity,"B");
            if (!$grupo) {
                throw $this->createNotFoundException('Unable to find Grupo entity.');
            }
            //Se comprueba si hay asignación de alumnos en el grupo que se va a eliminar.     
            $alumnos= $em->getRepository('BackendBundle:Alumno')->findAlumnosPorCurso_Grupo($entity,$grupo);
            if($alumnos){
                return new JsonResponse(array('data' =>"alumnos"), 200);
            }
            //Se comprueba si hay asignación de profesores en el grupo que se va a eliminar.   
            $profesores= $em->getRepository('BackendBundle:Imparte')->findByGrupo($grupo);
            if($profesores){
                return new JsonResponse(array('data' =>"profesores"), 200);
            }
            return new JsonResponse(array('data' =>null), 200);
        }
        else if($num_grupos_ant==3 && $num_grupos==2){

            $grupo = $em->getRepository('BackendBundle:Grupo')->findGrupoByLetter($entity,"C");
            if (!$entity) {
                throw $this->createNotFoundException('Unable to find Grupo entity.');
            }     
            //Se comprueba si hay asignación de alumnos en el grupo que se va a eliminar.  
            $alumnos= $em->getRepository('BackendBundle:Alumno')->findAlumnosPorGrupo($grupo);
            if($alumnos){
                return new JsonResponse(array('data' =>"alumnos"), 200);
            }
            //Se comprueba si hay asignación de profesores en el grupo que se va a eliminar.   
            $profesores= $em->getRepository('BackendBundle:Imparte')->findByGrupo($grupo);
            if($profesores){
                return new JsonResponse(array('data' =>"profesores"), 200);
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
            //Se comprueba si hay asignación de alumnos en los grupos que se van a eliminar.  
            $alumnos_grupo_3= $em->getRepository('BackendBundle:Alumno')->findAlumnosPorGrupo($grupo_3);
            $alumnos_grupo_2= $em->getRepository('BackendBundle:Alumno')->findAlumnosPorGrupo($grupo_2);

            if(!$alumnos_grupo_2 && !$alumnos_grupo_3){
                return new JsonResponse(array('data' =>null), 200);
            }
            else{
                return new JsonResponse(array('data' =>"alumnos"), 200);

            }
            //Se comprueba si hay asignación de profesores en los grupos que se van a eliminar. 
            $profesores_grupo_3=$em->getRepository('BackendBundle:Imparte')->findByGrupo($grupo_3);
            $profesores_grupo_2=$em->getRepository('BackendBundle:Imparte')->findByGrupo($grupo_2);

            if(!$profesores_grupo_2 && !$profesores_grupo_3){
                return new JsonResponse(array('data' =>null), 200);
            }
            else{
                return new JsonResponse(array('data' =>"profesores"), 200);

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

    public function DatosAlumnoOptativasAction($id)
    {
        $em = $this->getDoctrine()->getManager();
        $entity = $em->getRepository('BackendBundle:Alumno')->findOneById($id);
        $curso=$entity->getCurso();

        $optativas=$em->getRepository('BackendBundle:AsignaturasCursos')->findAsignaturasEspecificasOpcionalesCurso($curso->getId());

        return $this->render('BackendBundle:Alumno:datos_alumno_optativas.html.twig', array(
            'entity' => $entity,
            'optativas'=>$optativas));
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

    // Formulario Edición de Antiguo Alumno a matricular.
    public function OldEditAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('BackendBundle:Alumno')->find($id);
        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Alumno entity.');
        }
        /*
        $año_academico=$entity->getAnyoAcademico();
        if (!$año_academico) {
            throw $this->createNotFoundException('Unable to find Año Academico in Alumno entity.');
        }
        */
        $curso_anterior;
        // Se obtiene el año académico del curso anterior según el mes actual.
        if(date("n")>=6){
            $curso_anterior=(date("Y")-1)." / ".date("Y");
        }
        else{
            $curso_anterior=(date("Y")-2)." / ".(date("Y")-1);
        }

        // Se comprueba si el alumno está promocionado en el expediente para asignar el nuevo curso a matricular.
        // Si tiene registro en el año académico anterior, se le asigna el curso automáticamente,sino debe elegir uno de la lista.
        $expediente = $em->getRepository('BackendBundle:Expediente')->findOneBy(
            array('alumno'=>$entity->getId()),
            array('id' => 'DESC')
        );
        $nuevo_curso=null;
        if($expediente && $expediente->getAnyoAcademico()==$curso_anterior){
            $ultimo_curso=$em->getRepository('BackendBundle:Curso')->findCursoByNivel($expediente->getCurso(),$expediente->getNivel());
            $Orden=$ultimo_curso->getNumOrden();
            // Si promociona se obtiene el siguiente curso a matricular. Si repite se obtiene el mismo curso.
            if($expediente->getPromociona()){
                $Orden++;
            }
            $nuevo_curso=$em->getRepository('BackendBundle:Curso')->findOneByNumOrden($Orden);
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
            'expediente'  => $expediente,
            'nuevo_curso' => $nuevo_curso,
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

        //Se valida que no se matricule en un curso anterior.
        $ultimo_expediente = $em->getRepository('BackendBundle:Expediente')->findOneBy(
            array('alumno'=>$entity->getId()),
            array('id' => 'DESC')
        );
        $antiguo_curso= $em->getRepository('BackendBundle:Curso')->findCursoByNivel($ultimo_expediente->getCurso(), $ultimo_expediente->getNivel());
 
        if($antiguo_curso->getNumOrden()>$curso->getNumOrden()){
                return new JsonResponse(array(
                    'nombre'=>$entity->getNombre()." ".$entity->getApellido1()." ".$entity->getApellido2(),
                    'validate' =>"curso_incorrecto",
                    'success' => true), 200);
        }
        // Se hace las comprobaciones según el mes actual.
        if(date("n")>=6){
            $actual=date("Y")." / ".(date("Y")+1);

            //Se comprueba que no exista ninguna matrícula para el año actual registrada.
            $exp_alum = $em->getRepository('BackendBundle:Matricula')->findPorAño($entity,$actual);
            if($exp_alum){
                return new JsonResponse(array(
                    'nombre'=>$entity->getNombre()." ".$entity->getApellido1()." ".$entity->getApellido2(),
                    'validate' =>"año_incorrecto",
                    'success' => true), 200);
            }
            //Se comprueba que quedan plazas libre en el curso que se quiere matricular.
            $num_matriculas = $em->getRepository('BackendBundle:Matricula')->findNumPorCurso($curso,$actual);
            if($num_matriculas[1]>=(int)$curso->getNumGrupos()*(int)$curso->getRatio()){
                return new JsonResponse(array(
                    'nombre'=>$entity->getNombre()." ".$entity->getApellido1()." ".$entity->getApellido2(),
                    'curso'=>$curso->getCurso()." ".$curso->getNivel(),
                    'validate' =>"plazas_ocupadas",
                    'success' => true), 200);
            }
        }
        else{
            $actual=(date("Y")-1)." / ".date("Y");

            //Se comprueba que no exista ninguna matrícula para el año actual registrada.
            $exp_alum = $em->getRepository('BackendBundle:Matricula')->findPorAño($entity,$actual);
            if($exp_alum){
                return new JsonResponse(array(
                    'nombre'=>$entity->getNombre()." ".$entity->getApellido1()." ".$entity->getApellido2(),
                    'validate' =>"año_incorrecto",
                    'success' => true), 200);
            }
            //Se comprueba que quedan plazas libre en el curso que se quiere matricular.
            $num_matriculas = $em->getRepository('BackendBundle:Matricula')->findNumPorCurso($curso,$actual);
            //Se comprueba que quedan plazas libre en el curso que se quiere matricular.
            if($num_matriculas[1]>=(int)$curso->getNumGrupos()*(int)$curso->getRatio()){
                return new JsonResponse(array(
                    'nombre'=>$entity->getNombre()." ".$entity->getApellido1()." ".$entity->getApellido2(),
                    'curso'=>$curso->getCurso()." ".$curso->getNivel(),
                    'validate' =>"plazas_ocupadas",
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
            $entity->setCurso($curso);

            // Se asigna la misma letra del grupo a los alumnos del curso anterior.
            if($entity->getGrupo() && $entity->getActivo()){
                $letra=$entity->getGrupo()->getLetra();
                $nuevo_grupo=$em->getRepository('BackendBundle:Grupo')->findGrupoByLetter($entity->getCurso(),$letra);
                if($nuevo_grupo){
                    $entity->setGrupo($nuevo_grupo);
                }
                else{
                    $entity->setGrupo(null);
                }
            }else{
                $entity->setGrupo(null);
            }

            if(date("n")>=6){
                $entity->setAnyoAcademico(date("Y")." / ".(date("Y")+1));
            }
            else{
                $entity->setAnyoAcademico((date("Y")-1)." / ".date("Y"));
            }
            $entity->setActivo(1);
            $entity->setNumAlum(null);
            
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

    public function MultipleUpdateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('BackendBundle:Alumno')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Alumno entity.');
        }

        $curso;
        $c=$entity->getCurso();
        $año_academico=$entity->getAnyoAcademico();

        $exp_alum = $em->getRepository('BackendBundle:Expediente')->findPorAño($entity,$año_academico);
        $num_curso=$c->getNumOrden();
        if($exp_alum->getPromociona()=="Promociona"){
            $num_curso++;
            $curso = $em->getRepository('BackendBundle:Curso')->findOneByNumOrden($num_curso);
        }
        else{
            $curso = $em->getRepository('BackendBundle:Curso')->findOneByNumOrden($num_curso);
        }
        
        //Se valida que no se matricule en un curso anterior.
        $antiguo_curso=$entity->getCurso();
 
        if($antiguo_curso->getNumOrden()>$curso->getNumOrden()){
                return new JsonResponse(array(
                    'nombre'=>$entity->getNombre()." ".$entity->getApellido1()." ".$entity->getApellido2(),
                    'validate' =>"curso_incorrecto",
                    'success' => true), 200);
        }
        // Se hace las comprobaciones según el mes actual.
        if(date("n")>=6){
            $actual=date("Y")." / ".(date("Y")+1);

            //Se comprueba que no exista ninguna matrícula para el año actual registrada.
            $exp_alum = $em->getRepository('BackendBundle:Matricula')->findPorAño($entity,$actual);
            if($exp_alum){
                return new JsonResponse(array(
                    'nombre'=>$entity->getNombre()." ".$entity->getApellido1()." ".$entity->getApellido2(),
                    'validate' =>"año_incorrecto",
                    'success' => true), 200);
            }
            //Se comprueba que quedan plazas libre en el curso que se quiere matricular.

            $num_matriculas = $em->getRepository('BackendBundle:Matricula')->findNumPorCurso($curso,$actual);
            if($num_matriculas[1]>=(int)$curso->getNumGrupos()*(int)$curso->getRatio()){
                return new JsonResponse(array(
                    'nombre'=>$entity->getNombre()." ".$entity->getApellido1()." ".$entity->getApellido2(),
                    'curso'=>$curso->getCurso()." ".$curso->getNivel(),
                    'validate' =>"plazas_ocupadas",
                    'success' => true), 200);
            }
        }
        else{
            $actual=(date("Y")-1)." / ".date("Y");

            //Se comprueba que no exista ninguna matrícula para el año actual registrada.
            $exp_alum = $em->getRepository('BackendBundle:Matricula')->findPorAño($entity,$actual);
            if($exp_alum){
                return new JsonResponse(array(
                    'nombre'=>$entity->getNombre()." ".$entity->getApellido1()." ".$entity->getApellido2(),
                    'validate' =>"año_incorrecto",
                    'success' => true), 200);
            }

            $num_matriculas = $em->getRepository('BackendBundle:Matricula')->findNumPorCurso($curso,$actual);
            //Se comprueba que quedan plazas libre en el curso que se quiere matricular.
            if($num_matriculas[1]>=(int)$curso->getNumGrupos()*(int)$curso->getRatio()){
                return new JsonResponse(array(
                    'nombre'=>$entity->getNombre()." ".$entity->getApellido1()." ".$entity->getApellido2(),
                    'curso'=>$curso->getCurso()." ".$curso->getNivel(),
                    'validate' =>"plazas_ocupadas",
                    'success' => true), 200);
            }
        }

        $entity->setCurso($curso);

        // Se asigna la misma letra del grupo a los alumnos del curso anterior.
        if($entity->getGrupo()){
            $letra=$entity->getGrupo()->getLetra();
            $nuevo_grupo=$em->getRepository('BackendBundle:Grupo')->findGrupoByLetter($entity->getCurso(),$letra);
            if($nuevo_grupo){
                $entity->setGrupo($nuevo_grupo);
            }
            else{
                $entity->setGrupo(null);
            }
        }

        if(date("n")>=6){
            $entity->setAnyoAcademico(date("Y")." / ".(date("Y")+1));
        }
        else{
            $entity->setAnyoAcademico((date("Y")-1)." / ".date("Y"));
        }
        $entity->setNumAlum(null);
            
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





}
