<?php

namespace Cole\ColeBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use Cole\ColeBundle\Entity\Noticias;
use Cole\ColeBundle\Form\NoticiasType;
use Symfony\Component\HttpFoundation\JsonResponse;


/**
 * Noticias controller.
 *
 */
class NoticiasController extends Controller
{

    /**
     * Lists all Noticias entities.
     *
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        //Se eliminan todos los directorios modificados anteriormente.
        $Dir = $this->container->getParameter('kernel.root_dir').'/../web/uploads/noticias/galeria/';

        $mask = $Dir."*__v/*.*";
        $D=$Dir."*__v";
        array_map( "unlink", glob( $mask ) );

        foreach (glob($D) as $nombre_dir) {
            rmdir($nombre_dir);
        }
        //Se restablece o se elimina los directorios acabados en _m (directorios creados en editar noticia)
        foreach (glob($Dir."*_m") as $D_m) {
          if(file_exists($D_m.'/')){
            $D=substr($D_m, 0, -2);

            if (!file_exists($D.'/')) {
                rename($D_m.'/', $D.'/');
            }
            else{
                $mask = $Dir."*_m/*.*";
                array_map( "unlink", glob( $mask ) );
                foreach (glob($Dir."*_m") as $nombre_dir) {
                    rmdir($nombre_dir);
                }
            }
          }
        }

        $entities = $em->getRepository('ColeBundle:Noticias')->findAll();

        return $this->render('ColeBundle:Noticias:index.html.twig', array(
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
     * Creates a new Noticias entity.
     *
     */
    public function createAction(Request $request)
    {
        // if request is XmlHttpRequest (AJAX) but not a POSt, throw an exception
        if ($request->isXmlHttpRequest() && !$request->isMethod('POST')) {
            throw new HttpException('XMLHttpRequests/AJAX calls must be POSTed');
        }

        $titulo=$this->get('request')->request->get('titulo');
        $categoria=$this->get('request')->request->get('categoria');
        $imagen=$this->get('request')->request->get('imagen');
        $pos=$this->get('request')->request->get('pos');
        $descripcion=$this->get('request')->request->get('descripcion');
        $galeria=$this->get('request')->request->get('galeria');
        $show=$this->get('request')->request->get('show');

        $error=array();

        // Se comprueba que se han seleccionado todas las opciones para la reserva.
        if(!$titulo || !$descripcion  ){
            if(!$titulo){
                $error[]="- Título de la noticia";
            }
            if(!$descripcion){
                $error[]="- Descripción de la noticia";
            }
            return new JsonResponse(array(
                'error' =>  $error), 200); 
        }

        $entity = new Noticias();
        $em = $this->getDoctrine()->getManager();
        $entity->setFecha(new \DateTime("now"));
        $entity->setTitulo($titulo); 
        $entity->setCategoria($categoria); 
        $entity->setDescripcion($descripcion); 
        $entity->setContador(0); 

        if($imagen!=null){
            $Dir= $this->container->getParameter('kernel.root_dir').'/../web/uploads/noticias/';
            $nuevo = str_replace("v", $pos, $imagen);
            rename($Dir.$imagen, $Dir.$nuevo);
            $entity->setFoto($nuevo); 
        }
        else{
            $entity->setFoto(null); 
        }
        
        if($galeria!=null){
            $Dir= $this->container->getParameter('kernel.root_dir').'/../web/uploads/noticias/galeria/';
            rename($Dir.$galeria."__v/", $Dir.$galeria."/");
            $entity->setGaleria($galeria); 
        }
        else{
            $entity->setGaleria(null); 
        }

        if ($show=="yes") {
            $entity->setMostrarFoto(1);
        }
        else{
            $entity->setMostrarFoto(0);
        }

        $em->persist($entity);
        $em->flush();
        
        if ($request->isXmlHttpRequest()) {
            return new JsonResponse(array(
                'message' => "success",
                'error' =>  $error,
                'success' => true), 200);
        } 
        return $this->redirect($this->generateUrl('noticias_show', array('id' => $entity->getId())));
    }

    /**
     * Creates a form to create a Noticias entity.
     *
     * @param Noticias $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createCreateForm(Noticias $entity)
    {
        $form = $this->createForm(new NoticiasType(), $entity, array(
            'action' => $this->generateUrl('noticias_create'),
            'method' => 'POST',
        ));

        $form->add('submit', 'submit', array('label' => 'Crear'));

        return $form;
    }

    /**
     * Displays a form to create a new Noticias entity.
     *
     */
    public function newAction()
    {
        $entity = new Noticias();
        $form   = $this->createCreateForm($entity);

        if (!file_exists($this->container->getParameter('kernel.root_dir').'/../web/uploads/')) {
            $oldmask = umask(0);
            mkdir($this->container->getParameter('kernel.root_dir').'/../web/uploads/', 0777);
            umask($oldmask);
        }
        if (!file_exists($this->container->getParameter('kernel.root_dir').'/../web/uploads/noticias/')) {
            $oldmask = umask(0);
            mkdir($this->container->getParameter('kernel.root_dir').'/../web/uploads/noticias/', 0777);
            umask($oldmask);
        }
        if (!file_exists($this->container->getParameter('kernel.root_dir').'/../web/uploads/noticias/contenido/')) {
            $oldmask = umask(0);
            mkdir($this->container->getParameter('kernel.root_dir').'/../web/uploads/noticias/contenido/', 0777);
            umask($oldmask);
        }
        if (!file_exists($this->container->getParameter('kernel.root_dir').'/../web/uploads/noticias/galeria/')) {
            $oldmask = umask(0);
            mkdir($this->container->getParameter('kernel.root_dir').'/../web/uploads/noticias/galeria/', 0777);
            umask($oldmask);
        }

        return $this->render('ColeBundle:Noticias:new.html.twig', array(
            'entity' => $entity,
            'form'   => $form->createView(),
        ));
    }

    /**
     * Finds and displays a Noticias entity.
     *
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('ColeBundle:Noticias')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Noticias entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return $this->render('ColeBundle:Noticias:show.html.twig', array(
            'entity'      => $entity,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing Noticias entity.
     *
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('ColeBundle:Noticias')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Noticias entity.');
        }

        $editForm = $this->createEditForm($entity);
        $deleteForm = $this->createDeleteForm($id);

        $imagenes = array();
        if($entity->getGaleria()!=null) {
            $photoDir = $this->container->getParameter('kernel.root_dir').'/../web/uploads/noticias/galeria/'.$entity->getGaleria().'/';
            
            foreach (glob($photoDir."*.*") as $nombre_dir) {
                $nombre_dir = explode("/", $nombre_dir);
                $nombre_dir =end($nombre_dir);
                $imagenes[]=$nombre_dir;
            }
        }
        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Noticias entity.');
        }

        return $this->render('ColeBundle:Noticias:edit.html.twig', array(
            'entity'      => $entity,
            'imagenes'    => $imagenes,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
    * Creates a form to edit a Noticias entity.
    *
    * @param Noticias $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createEditForm(Noticias $entity)
    {
        $form = $this->createForm(new NoticiasType(), $entity, array(
            'action' => $this->generateUrl('noticias_update', array('id' => $entity->getId())),
            'method' => 'PUT',
        ));

        $form->add('submit', 'submit', array('label' => 'Guardar Cambios'));

        return $form;
    }
    /**
     * Edits an existing Noticias entity.
     *
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();
        $imagen=$this->get('request')->request->get('imagen');
        $pos=$this->get('request')->request->get('pos');
        $galeria=$this->get('request')->request->get('galeria');
        $show=$this->get('request')->request->get('show');

        $entity = $em->getRepository('ColeBundle:Noticias')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Noticias entity.');
        }

        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createEditForm($entity);
        $editForm->handleRequest($request);

        if ($editForm->isValid()) {
        
            $antiguo=$entity->getFoto(); 

            if($imagen!=""){
                $Dir= $this->container->getParameter('kernel.root_dir').'/../web/uploads/noticias/';
                $array1 = explode("_", $imagen);
                $array2 = explode(".", end($array1));
                $nuevo=$array1[0]."_".$pos.".".end($array2);
                rename($Dir.$imagen, $Dir.$nuevo);
                $entity->setFoto($nuevo); 
            }
            else{
                $entity->setFoto(null); 
            }

            //Se elimina la imagen anterior a la actualizada.
            if($antiguo!=$imagen ){
                $photoDir = $this->container->getParameter('Dir_imagenes_noticias');

                $dir_file=$photoDir.$antiguo;
                if(is_file( $dir_file )){
                    unlink($dir_file);
                }
            }

            $antigua=$entity->getGaleria(); 
            if($galeria!=""){
                $entity->setGaleria($galeria); 
            }
            else{
                $entity->setGaleria(null); 
            }

            if ($show=="yes") {
                $entity->setMostrarFoto(1);
            }
            else{
                $entity->setMostrarFoto(0);
            }

            $em->persist($entity);

            $em->flush();
            $Dir = $this->container->getParameter('kernel.root_dir').'/../web/uploads/noticias/galeria/';

            $mask = $Dir."*_m/*.*";
            array_map( "unlink", glob( $mask ) );
            foreach (glob($Dir."*_m") as $nombre_dir) {
                rmdir($nombre_dir);
            }

            foreach (glob($Dir."*__v") as $nombre_dir) {
            $D=substr($nombre_dir, 0, -3);

            if (!file_exists($D.'/')) {
                rename($nombre_dir.'/', $D.'/');
                }
            }

            if ($request->isXmlHttpRequest()) {
                return new JsonResponse(array(
                    'message' => 'Success!',
                    'success' => true), 200);
            }
            return $this->redirect($this->generateUrl('noticias_edit', array('id' => $id)));
        }

        //Se le indica el método PUT que es el que tiene en la llamada Ajax.
        if ($request->isMethod('PUT')) {
            return new JsonResponse(array(
            'data' => $this->getErrorMessages($editForm,$editForm->getName()),
            'message' => 'Invalid form',
            'success' => false), 400);
        }

        return $this->render('ColeBundle:Noticias:edit.html.twig', array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }
    /**
     * Deletes a Noticias entity.
     *
     */
    public function deleteAction(Request $request, $id)
    {
        /*
        $form = $this->createDeleteForm($id);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('ColeBundle:Noticias')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find Noticias entity.');
            }

            $em->remove($entity);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('noticias'));
        */
        $em = $this->getDoctrine()->getManager();
        $entity = $em->getRepository('ColeBundle:Noticias')->find($id);

        if($entity->getFoto()!=null){
            $photoDir = $this->container->getParameter('Dir_imagenes_noticias');

            $dir_file=$photoDir.$entity->getFoto();
            if(is_file( $dir_file )){
                unlink($dir_file);
            }
        }

        if($entity->getGaleria()!=null){

            $Dir = $this->container->getParameter('kernel.root_dir').'/../web/uploads/noticias/galeria/';
            
            //Se eliminan los anteriores directorios provisionales y todo sus archivos.
            $mask = $Dir.$entity->getGaleria()."/*.*";
            array_map( "unlink", glob( $mask ) ); // Eliminación de archivos.
        
            $D=$Dir.$entity->getGaleria()."/";
            foreach (glob($D) as $nombre_dir) { // Eliminación del directorio.
                rmdir($nombre_dir);
            }
        }

        $em->flush();

        $query = $em->createQuery('DELETE ColeBundle:Noticias r WHERE r.id = :Id')->setParameter("Id", $id);

        $query->execute();
        return new JsonResponse(array('success' => true), 200);

    }

    /**
     * Creates a form to delete a Noticias entity by id.
     *
     * @param mixed $id The entity id
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm($id)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('noticias_delete', array('id' => $id)))
            ->setMethod('DELETE')
            ->add('submit', 'submit', array('label' => 'Delete'))
            ->getForm()
        ;
    }

    public function NoticiasRecientesAction()
    {
        $em = $this->getDoctrine()->getEntityManager();

        $noticias = $em->getRepository('ColeBundle:Noticias')->findRecientes();
        
        return $this->render('ColeBundle:Noticias:recientes.html.twig',
                array('noticias' => $noticias));
    }


    public function noticiaAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('ColeBundle:Noticias')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Noticias entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        $imagenes = array();
        if($entity->getGaleria()!=null) {
            $photoDir = $this->container->getParameter('kernel.root_dir').'/../web/uploads/noticias/galeria/'.$entity->getGaleria().'/';
            
            foreach (glob($photoDir."*.*") as $nombre_dir) {
                $nombre_dir = explode("/", $nombre_dir);
                $nombre_dir =end($nombre_dir);
                $imagenes[]=$nombre_dir;
            }
        }

        return $this->render('ColeBundle:Noticias:noticia.html.twig', array(
            'entity'      => $entity,
            'delete_form' => $deleteForm->createView(),
            'imagenes'  => $imagenes,
        ));
    }

    public function noticiasAction()
    {
        $em = $this->getDoctrine()->getEntityManager();

        $noticias = $em->getRepository('ColeBundle:Noticias')->findAll();
        return $this->render('ColeBundle:Noticias:noticias.html.twig',
                array('noticias' => $noticias));
    }

    public function imagenAction()
    {
        $em = $this->getDoctrine()->getManager();

        return $this->render('ColeBundle:Noticias:imagen.html.twig');
    }

    public function galeriaAction()
    {
        $em = $this->getDoctrine()->getManager();

        return $this->render('ColeBundle:Noticias:galeria.html.twig');
    }
    public function imagen_createAction()
    {        
        $em = $this->getDoctrine()->getManager();
        $photoDir = $this->container->getParameter('kernel.root_dir').'/../web/uploads/noticias/';

        //Se eliminan los anteriores archivos provisionales (EJ: 4343541_v.jpeg)
        $mask = $photoDir."*_v.*";
        array_map( "unlink", glob( $mask ) );


        $data = $_POST['image'];
        list($type, $data) = explode(';', $data);
        list(, $data)      = explode(',', $data);
        $data = base64_decode($data);
        $time=time();
        $imageName = $time.'_v.jpeg';
        //$imageName ='uploaded.jpeg';

        file_put_contents($photoDir.$imageName, $data);
        

        return new JsonResponse(array(
            'message' => 'Success!',
            'file' => $imageName,
            'success' => true), 200);
    }

    public function imagen_deleteAction()
    {
        $em = $this->getDoctrine()->getManager();
        $file = $this->get('request')->request->get('file');
        $photoDir = $this->container->getParameter('Dir_imagenes_noticias');

        $dir_file=$photoDir.$file;
        if(is_file( $dir_file )){
            unlink($dir_file);
        }
           
        $em->flush();
        
        return new JsonResponse(array(
            'message' => 'Success!',
            'success' => true), 200);
    }

    public function contadorNoticiasAction()
    {
        $entity = new Noticias();
        $em = $this->getDoctrine()->getManager();

        $id=$this->get('request')->request->get('id');

        $entity = $em->getRepository('ColeBundle:Noticias')->findOneById($id);

        $entity->SetContador(((int)$entity->GetContador())+1);
        $em = $this->getDoctrine()->getManager();
        $em->persist($entity);
        $em->flush();

        return new JsonResponse(array(
            'message' => 'Success!',
            'success' => true), 200);
    }

    public function galeria_createAction()
    {
        $em = $this->getDoctrine()->getManager();
        $titulo=$this->get('request')->request->get('nombre');

        // Se comprueba si existe una galería con el mismo nombre.
        if (file_exists($this->container->getParameter('kernel.root_dir').'/../web/uploads/noticias/galeria/'.$titulo.'/')) {
        return new JsonResponse(array(
            'validate' => 'existe',
            'success' => true), 200);
        }

        $Dir = $this->container->getParameter('kernel.root_dir').'/../web/uploads/noticias/galeria/';
        //Se eliminan los anteriores directorios provisionales y todo sus archivos.
        $mask = $Dir."*_v/*.*";
        array_map( "unlink", glob( $mask ) ); // Eliminación de archivos.
        
        $D=$Dir."*__v";
        foreach (glob($D) as $nombre_dir) { // Eliminación del directorio.
            rmdir($nombre_dir);
        }
      
        if (!file_exists($this->container->getParameter('kernel.root_dir').'/../web/uploads/noticias/galeria/'.$titulo.'__v/')) {
            //Se da los permisos al directorio.
            $oldmask = umask(0);
            mkdir($this->container->getParameter('kernel.root_dir').'/../web/uploads/noticias/galeria/'.$titulo.'__v/', 0777);
            umask($oldmask);
        }

        $photoDir = $this->container->getParameter('kernel.root_dir').'/../web/uploads/noticias/galeria/'.$titulo.'__v/';
        foreach ($_POST as $key => $value) {
            if($key !="nombre"){
                $data = $value;
                list($type, $data) = explode(';', $data);
                list(, $data)      = explode(',', $data);
                $data = base64_decode($data);
                //$time=time();
                $time=time();
                $imageName = str_replace("_", "-".$time.".", $key);
                //$imageName ='uploaded.jpeg';

                file_put_contents($photoDir.$imageName, $data); 
            }
        }

        $array = array();
        foreach (glob($photoDir."*.*") as $nombre_dir) {
            $nombre_dir = explode("/", $nombre_dir);
            $nombre_dir =end($nombre_dir);
            $array[]=$nombre_dir;
        }
        
        $Dir = $titulo.'__v/';

        return new JsonResponse(array(
            'message' => 'Success!',
            'directory' => $Dir,
            'imagenes' => json_encode($array),
            'success' => true), 200);
    }



    public function galeria_editarAction()
    {
        $em = $this->getDoctrine()->getManager();
        $titulo=$this->get('request')->request->get('nombre');
        $antiguo=$this->get('request')->request->get('antiguo');
        
        $time=time();
        
        if (file_exists($this->container->getParameter('kernel.root_dir').'/../web/uploads/noticias/galeria/'.$titulo.'/')) {
        return new JsonResponse(array(
            'validate' => 'existe',
            'success' => true), 200);
        }

        //Se eliminan todos los directorios modificados anteriormente.
        $Dir = $this->container->getParameter('kernel.root_dir').'/../web/uploads/noticias/galeria/';

        $mask = $Dir."*__v-/*.*";
        $D=$Dir."*__v-";
        array_map( "unlink", glob( $mask ) );

        foreach (glob($D) as $nombre_dir) {
            rmdir($nombre_dir);
        }

        if (!file_exists($this->container->getParameter('kernel.root_dir').'/../web/uploads/noticias/galeria/'.$antiguo.'__v-/')) {
            //Se da los permisos al directorio.
            $oldmask = umask(0);
            mkdir($this->container->getParameter('kernel.root_dir').'/../web/uploads/noticias/galeria/'.$antiguo.'__v-/', 0777);
            umask($oldmask);
        }

        $photoDir = $this->container->getParameter('kernel.root_dir').'/../web/uploads/noticias/galeria/'.$antiguo.'__v/';
        $newphotoDir = $this->container->getParameter('kernel.root_dir').'/../web/uploads/noticias/galeria/'.$antiguo.'__v-/';

        foreach ($_POST as $key => $value) {
            if((substr($key, 0, 1) === '0')){
                $imageName = str_replace("_", "-".$time.".", $key);
                $array = explode("/", $value);
                //copy($photoDir.end($array), $newphotoDir.$imageName);
                copy($photoDir.end($array), $newphotoDir.substr($imageName,1));
            }
            else if($key !="nombre" && $key !="antiguo"){
                $data = $value;
                list($type, $data) = explode(';', $data);
                list(, $data)      = explode(',', $data);
                $data = base64_decode($data);
                //$time=time();
                $time=time();
                $imageName = str_replace("_", "-".$time.".", $key);
                //$imageName ='uploaded.jpeg';
                file_put_contents($newphotoDir.$imageName, $data); 
            }
        }


        //Se eliminan los anteriores archivos provisionales (EJ: 4343541_v.jpeg)
        $Dir = $this->container->getParameter('kernel.root_dir').'/../web/uploads/noticias/galeria/';

        $mask = $Dir."*__v/*.*";
        $D=$Dir."*__v";
        array_map( "unlink", glob( $mask ) );

        foreach (glob($D) as $nombre_dir) {
            rmdir($nombre_dir);
        }

        if (!file_exists($this->container->getParameter('kernel.root_dir').'/../web/uploads/noticias/galeria/'.$titulo.'__v/')) {
            //Se da los permisos al directorio.
            $oldmask = umask(0);
            mkdir($this->container->getParameter('kernel.root_dir').'/../web/uploads/noticias/galeria/'.$titulo.'__v/', 0777);
            umask($oldmask);
        }

        $photoDir = $this->container->getParameter('kernel.root_dir').'/../web/uploads/noticias/galeria/'.$antiguo.'__v-/';
        $newphotoDir = $this->container->getParameter('kernel.root_dir').'/../web/uploads/noticias/galeria/'.$titulo.'__v/';

        foreach (glob($photoDir."*.*") as $nombre_dir) {
            $nombre_dir = explode("/", $nombre_dir);
            copy($photoDir.end($nombre_dir), $newphotoDir.end($nombre_dir));
        }

        //Se eliminan los anteriores archivos provisionales (EJ: 4343541_v.jpeg)
        $Dir = $this->container->getParameter('kernel.root_dir').'/../web/uploads/noticias/galeria/';

        $mask = $Dir."*__v-/*.*";
        $D=$Dir."*__v-";
        array_map( "unlink", glob( $mask ) );

        foreach (glob($D) as $nombre_dir) {
            rmdir($nombre_dir);
        }

        $Dir = $titulo.'__v/';

        return new JsonResponse(array(
            'message' => 'Success!',
            'directory' => $Dir,
            'success' => true), 200);
    }


    public function mostrar_galeria_editadaAction()
    {
        $em = $this->getDoctrine()->getManager();
        $galeria=$this->get('request')->request->get('galeria');

        $photoDir = $this->container->getParameter('kernel.root_dir').'/../web/uploads/noticias/galeria/'.$galeria.'__v/';
        $array = array();
        foreach (glob($photoDir."*.*") as $nombre_dir) {
            $nombre_dir = explode("/", $nombre_dir);
            $nombre_dir =end($nombre_dir);
            $array[]=$nombre_dir;
        }
        return new JsonResponse(array(
            'message' => 'Success!',
            'imagenes' => json_encode($array),
            'success' => true), 200);
    }
    public function renombrar_galeriaAction()
    {
        $em = $this->getDoctrine()->getManager();
        $titulo=$this->get('request')->request->get('titulo');

        //Se eliminan todos los directorios modificados anteriormente.
        $Dir = $this->container->getParameter('kernel.root_dir').'/../web/uploads/noticias/galeria/';

        $mask = $Dir."*__v/*.*";
        $D=$Dir."*__v";
        array_map( "unlink", glob( $mask ) );

        foreach (glob($D) as $nombre_dir) {
            rmdir($nombre_dir);
        }

        rename($Dir.$titulo.'/', $Dir.$titulo.'_m/');

        if (!file_exists($this->container->getParameter('kernel.root_dir').'/../web/uploads/noticias/galeria/'.$titulo.'__v/')) {
            //Se da los permisos al directorio.
            $oldmask = umask(0);
            mkdir($this->container->getParameter('kernel.root_dir').'/../web/uploads/noticias/galeria/'.$titulo.'__v/', 0777);
            umask($oldmask);
        }

        $photoDir = $this->container->getParameter('kernel.root_dir').'/../web/uploads/noticias/galeria/'.$titulo.'_m/';
        $newphotoDir = $this->container->getParameter('kernel.root_dir').'/../web/uploads/noticias/galeria/'.$titulo.'__v/';

        foreach (glob($photoDir."*.*") as $nombre_dir) {
            $nombre_dir = explode("/", $nombre_dir);
            copy($photoDir.end($nombre_dir), $newphotoDir.end($nombre_dir));
        }

        /*$Dir = $this->container->getParameter('kernel.root_dir').'/../web/uploads/noticias/galeria/';

        $mask = $Dir."*_v-/*.*";
        $D=$Dir."*_v-";
        array_map( "unlink", glob( $mask ) );

        foreach (glob($D) as $nombre_dir) {
            rmdir($nombre_dir);
        }
*/
    
        return new JsonResponse(array(
            'message' => 'Success!',
            'success' => true), 200);
    }

    public function recuperar_galeriaAction()
    {
        $em = $this->getDoctrine()->getManager();
        $titulo=$this->get('request')->request->get('titulo');

        $Dir= $this->container->getParameter('kernel.root_dir').'/../web/uploads/noticias/galeria/';
        
        if (file_exists($Dir.$titulo.'_m/')) {
            rename($Dir.$titulo.'_m/', $Dir.$titulo.'/');
        }

        return new JsonResponse(array(
            'message' => 'Success!',
            'success' => true), 200);
    }

    public function galerias_deleteAction()
    {
        $Dir = $this->container->getParameter('kernel.root_dir').'/../web/uploads/noticias/galeria/';
        //Se eliminan los anteriores directorios provisionales y todo sus archivos.
        $mask = $Dir."*__v/*.*";
        array_map( "unlink", glob( $mask ) ); // Eliminación de archivos.
        
        $D=$Dir."*__v";
        foreach (glob($D) as $nombre_dir) { // Eliminación del directorio.
            rmdir($nombre_dir);
        }
        
        return new JsonResponse(array(
            'message' => 'Success!',
            'success' => true), 200);
    }



}




