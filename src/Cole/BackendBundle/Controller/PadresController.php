<?php

namespace Cole\BackendBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

use Cole\BackendBundle\Entity\Padres;
use Cole\BackendBundle\Form\PadresType;

/**
 * Padres controller.
 *
 */
class PadresController extends Controller
{

    /**
     * Lists all Padres entities.
     *
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $entities = $em->getRepository('BackendBundle:Padres')->findAll();

        return $this->render('BackendBundle:Padres:index.html.twig', array(
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
     * Creates a new Padres entity.
     *
     */
    /*public function createAction(Request $request)
    {
        $entity = new Padres();
        $form = $this->createCreateForm($entity);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getEntityManager(); 

            $factory = $this->get('security.encoder_factory'); 
            $encoder = $factory->getEncoder($entity);
            $password = $encoder->encodePassword($entity->getPassword(), $entity->getSalt());
            $entity->setPassword($password);
            $entity->setActivo(false);
            $role = $em->getRepository('BackendBundle:Role')->find(1);
            $entity->setRole($role);


            $entity->setUsername("padre1");
            
            $entity->setClaveUsuario("padre1");
            $entity->setEmail("padre1@hotmail.com");

            $exists = $em->getRepository('Cole\BackendBundle\Entity\Padres')->findBy(array(
    'dni' => $entity->getDni()
));

            if(!$exists){
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();
            }
            
        return $this->redirect($this->generateUrl('padres_new'));
         }

        return $this->render('BackendBundle:Padres:new.html.twig', array(
            'entity' => $entity,
            'form'   => $form->createView(),
        ));
    }*/

    public function createAction(Request $request)
    {
    // if request is XmlHttpRequest (AJAX) but not a POSt, throw an exception
      if ($request->isXmlHttpRequest() && !$request->isMethod('POST')) {
        throw new HttpException('XMLHttpRequests/AJAX calls must be POSTed');}

        $entity = new Padres();
        $form = $this->createCreateForm($entity);
        $form->handleRequest($request);

        if ($form->isValid()) {  
            $em = $this->getDoctrine()->getEntityManager(); 
            $factory = $this->get('security.encoder_factory'); 
            $encoder = $factory->getEncoder($entity);
            //$password = $encoder->encodePassword($entity->getPassword(), $entity->getSalt());
            $password = $encoder->encodePassword("p".substr($entity->getDni(), 0, -2), $entity->getSalt());
            $entity->setPassword($password);
            $role = $em->getRepository('BackendBundle:Role')->find(1);
            if (!$role) {
                throw $this->createNotFoundException('Unable to find Role entity.');
            }
            $entity->setRole($role);
     
            $entity->setActivo(true);

            $entity->setUsername("p".substr($entity->getDni(), 0, -2));
            $entity->setClaveUsuario("padre: ".substr($entity->getDni(), 0, -2).substr($entity->getDni(), -1));

            $exists = $em->getRepository('Cole\BackendBundle\Entity\Padres')->findOneBy(array('dni' => $entity->getDni()));

            if(!$exists){
                $em = $this->getDoctrine()->getManager();
                $em->persist($entity);
                $em->flush();
                $resp=$entity->getId();
            }
            else{
                $resp=$exists->getId();
            }

            if ($request->isXmlHttpRequest()) {
                return new JsonResponse(array(
                    'responsable'=>$resp,
                    'responsableDni'=>$entity->getDni(),
                    'message' => 'Success!',
                    'success' => true), 200);
            }
            return $this->redirect($this->generateUrl('padres_show', array('id' => $entity->getId())));
        }

        if ($request->isMethod('POST')) {
            return new JsonResponse(array(
            'result' => 0,
            'message' => 'Invalid form',
            'data' => $this->getErrorMessages($form,$form->getName()),
            'success' => false), 400);
        }

        return $this->render('BackendBundle:Padres:new.html.twig', array(
            'entity' => $entity,
            'form'   => $form->createView(),
        ));
    }



    /**
     * Creates a form to create a Padres entity.
     *
     * @param Padres $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createCreateForm(Padres $entity)
    {
        $form = $this->createForm(new PadresType(), $entity, array(
            'action' => $this->generateUrl('padres_create'),
            'method' => 'POST',
        ));

        $form->add('submit', 'submit', array('label' => 'Asignar'));

        return $form;
    }

    /**
     * Displays a form to create a new Padres entity.
     *
     */
    public function newAction()
    {
        $entity = new Padres();
        $form   = $this->createCreateForm($entity);

        return $this->render('BackendBundle:Padres:new.html.twig', array(
            'entity' => $entity,
            'form'   => $form->createView(),
        ));
    }

    /**
     * Finds and displays a Padres entity.
     *
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('BackendBundle:Padres')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Padres entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return $this->render('BackendBundle:Padres:show.html.twig', array(
            'entity'      => $entity,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing Padres entity.
     *
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('BackendBundle:Padres')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Padres entity.');
        }

        $editForm = $this->createEditForm($entity);
        $deleteForm = $this->createDeleteForm($id);

        return $this->render('BackendBundle:Padres:edit.html.twig', array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
    * Creates a form to edit a Padres entity.
    *
    * @param Padres $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createEditForm(Padres $entity)
    {
        $form = $this->createForm(new PadresType(), $entity, array(
            'action' => $this->generateUrl('padres_update', array('id' => $entity->getId())),
            'method' => 'PUT',
        ));

        $form->add('submit', 'submit', array('label' => 'Update'));

        return $form;
    }
    /**
     * Edits an existing Padres entity.
     *
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('BackendBundle:Padres')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Padres entity.');
        }

        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createEditForm($entity);
        $editForm->handleRequest($request);

        if ($editForm->isValid()) {
            $em->flush();

            return $this->redirect($this->generateUrl('padres_edit', array('id' => $id)));
        }

        return $this->render('BackendBundle:Padres:edit.html.twig', array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }
    /**
     * Deletes a Padres entity.
     *
     */
    public function deleteAction(Request $request, $id)
    {
        $form = $this->createDeleteForm($id);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('BackendBundle:Padres')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find Padres entity.');
            }

            $em->remove($entity);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('padres'));
    }

    /**
     * Creates a form to delete a Padres entity by id.
     *
     * @param mixed $id The entity id
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm($id)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('padres_delete', array('id' => $id)))
            ->setMethod('DELETE')
            ->add('submit', 'submit', array('label' => 'Borrar'))
            ->getForm()
        ;
    }


    public function comprobarpadreAction()
    {
        $dni=$this->get('request')->request->get('dni');
        $em = $this->getDoctrine()->getEntityManager();
        $responsable = $em->getRepository('BackendBundle:Padres')->findResponsable($dni);
        if($responsable){
            return new JsonResponse(array(
                'success' => true,
                'message' =>'El DNI introducido pertenece a:'."\n\n", 'name'=>$responsable->getNombre()), 200);
        }
        else{
           return new JsonResponse(array(
               'success' => false),200);
        }
    }
    
    public function ComprobarDniPadreEditadoAction()
    {
        $dni=$this->get('request')->request->get('dni');
        $dni_anterior=$this->get('request')->request->get('dni_anterior');

        $em = $this->getDoctrine()->getEntityManager();
        $responsable = $em->getRepository('BackendBundle:Padres')->findOneByDni($dni_anterior);

        $busqueda = $em->getRepository('BackendBundle:Padres')->findOneByDni($dni);
        if($busqueda && $busqueda->getId()!= $responsable->getId()){
            return new JsonResponse(array('data' =>$busqueda->getDni()), 200);
        }
        return new JsonResponse(array('data' =>null), 200);
    }


    public function obtenerdatosresponsableAction()
    {
        $dni=$this->get('request')->request->get('dni');
        $em = $this->getDoctrine()->getEntityManager();
        $responsable = $em->getRepository('BackendBundle:Padres')->findResponsable($dni);
        if (!$responsable) {
            throw $this->createNotFoundException('Unable to find Padres entity.');
        }
        $array['nombre'] = $responsable->getNombre();
        $array['fechaNacimiento'] = $responsable->getFechaNacimiento()->format('d/m/Y');
        $array['profesion'] = $responsable->getProfesion();
        $array['estadoCivil'] = $responsable->getEstadoCivil();
        $array['movil'] = $responsable->getMovil();
        $array['email'] = $responsable->getEmail();
        
        return new JsonResponse(array('data'=>$array, 200));
    }

}
