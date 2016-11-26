<?php

namespace Cole\BackendBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;

use Cole\BackendBundle\Entity\Profesor;
use Cole\BackendBundle\Form\ProfesorType;
use Cole\BackendBundle\Form\BusquedaProfesorType;

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
        
        return $this->render('BackendBundle:Profesor:search.html.twig', array(
            'entity' => $entity,
            'form'   => $form->createView(),
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

}
