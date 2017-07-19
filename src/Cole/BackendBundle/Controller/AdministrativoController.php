<?php

namespace Cole\BackendBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;

use Cole\BackendBundle\Entity\Administrativo;
use Cole\BackendBundle\Form\AdministrativoType;
use Cole\BackendBundle\Form\BusquedaAdministrativoType;
/**
 * Administrativo controller.
 *
 */
class AdministrativoController extends Controller
{

    /**
     * Lists all Administrativo entities.
     *
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $entities = $em->getRepository('BackendBundle:Administrativo')->findAll();

        return $this->render('BackendBundle:Administrativo:index.html.twig', array(
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
     * Creates a new Administrativo entity.
     *
     */
    public function createAction(Request $request)
    {
    // if request is XmlHttpRequest (AJAX) but not a POSt, throw an exception
      if ($request->isXmlHttpRequest() && !$request->isMethod('POST')) {
        throw new HttpException('XMLHttpRequests/AJAX calls must be POSTed');}

        $entity = new Administrativo();
        $form = $this->createCreateForm($entity);
        $form->handleRequest($request);

        if ($form->isValid()) {  
            $em = $this->getDoctrine()->getEntityManager(); 
            $factory = $this->get('security.encoder_factory'); 
            $encoder = $factory->getEncoder($entity);
            // Se le asigna de password el dni con la letra en minúscula
            $password = $encoder->encodePassword(substr($entity->getDni(), 0, -2).substr($entity->getDni(), -1), $entity->getSalt());
            $entity->setPassword($password);
            // Se le asigna de nombre de usuario el dni con la letra en minúscula
            $entity->setUsername(substr($entity->getDni(), 0, -2).substr($entity->getDni(), -1));

            if($entity->getTipo() == "Administrativo"){
                $role = $em->getRepository('BackendBundle:Role')->find(5);
            }
            else{
                $role = $em->getRepository('BackendBundle:Role')->find(6);
            }
            $entity->setRole($role);
            $entity->setFechaAlta(new \DateTime("now"));

            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();

            if ($request->isXmlHttpRequest()) {
                return new JsonResponse(array(
                    'message' => 'Success!',
                    'success' => true), 200);
            }
            return $this->redirect($this->generateUrl('administrativo_show', array('id' => $entity->getId())));
        }

        if ($request->isMethod('POST')) {
            return new JsonResponse(array(
            'result' => 0,
            'message' => 'Invalid form',
            'data' => $this->getErrorMessages($form,$form->getName()),
            'success' => false), 400);
        }

        return $this->render('BackendBundle:Administrativo:new.html.twig', array(
            'entity' => $entity,
            'form'   => $form->createView(),
        ));
    }

    public function RestablecerPasswordAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('BackendBundle:Administrativo')->find($id);
        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Administrativo entity.');
        }

        $factory = $this->get('security.encoder_factory'); 
        $encoder = $factory->getEncoder($entity);
        $entity->setSalt(base_convert(sha1(uniqid(mt_rand(),true)), 16, 36));
        $password = $encoder->encodePassword(substr($entity->getDni(), 0, -2).substr($entity->getDni(),-1), $entity->getSalt());
        $entity->setPassword($password);
        $entity->setLastAccessAnt(null);
        $entity->setLastAccess(null);
        $entity->setPregunta(null);
        $entity->setRespuesta(null);
        $em->persist($entity);
        $em->flush();

        return new JsonResponse(array('success' => true), 200);
    }

    /**
     * Creates a form to create a Administrativo entity.
     *
     * @param Administrativo $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createCreateForm(Administrativo $entity)
    {
        $form = $this->createForm(new AdministrativoType(), $entity, array(
            'action' => $this->generateUrl('administrativo_create'),
            'method' => 'POST',
        ));

        $form->add('submit', 'submit', array('label' => 'Enviar'));

        return $form;
    }

    /**
     * Creates a form to create a Administrativo entity.
     *
     * @param Administrativo $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createCreateSearchForm(Administrativo $entity)
    {
        $form = $this->createForm(new BusquedaAdministrativoType(), $entity, array(
            'action' => $this->generateUrl('administrativo_search'),
            'method' => 'POST',
        ));

        $form->add('submit', 'submit', array('label' => 'Buscar'));

        return $form;
    }


    /**
     * Displays a form to create a new Administrativo entity.
     *
     */
    public function newAction()
    {
        $entity = new Administrativo();
        $form   = $this->createCreateForm($entity);

        return $this->render('BackendBundle:Administrativo:new.html.twig', array(
            'entity' => $entity,
            'form'   => $form->createView(),
        ));
    }

    /**
     * Finds and displays a Administrativo entity.
     *
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('BackendBundle:Administrativo')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Administrativo entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return $this->render('BackendBundle:Administrativo:show.html.twig', array(
            'entity'      => $entity,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing Administrativo entity.
     *
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('BackendBundle:Administrativo')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Administrativo entity.');
        }

        $editForm = $this->createEditForm($entity);
        $deleteForm = $this->createDeleteForm($id);

        return $this->render('BackendBundle:Administrativo:edit.html.twig', array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
    * Creates a form to edit a Administrativo entity.
    *
    * @param Administrativo $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createEditForm(Administrativo $entity)
    {
        $form = $this->createForm(new AdministrativoType(), $entity, array(
            'action' => $this->generateUrl('administrativo_update', array('id' => $entity->getId())),
            'method' => 'PUT',
        ));

        $form->add('submit', 'submit', array('label' => 'Guardar cambios'));

        return $form;
    }
    /**
     * Edits an existing Administrativo entity.
     *
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('BackendBundle:Administrativo')->find($id);
        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Administrativo entity.');
        }

        //Se optiene el password para asignarlo de nuevo ya que se modifica la contraseña con el valor de la nueva que está vacío(oculto).
        $password=$entity->getPassword();

        $administrativo= $em->getRepository('BackendBundle:Role')->findOneByNombre("ROLE_ADMINISTRATIVO");
        $administradorWeb = $em->getRepository('BackendBundle:Role')->findOneByNombre("ROLE_ADMIN_WEB");

        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createEditForm($entity);

        $editForm->handleRequest($request);

        if ($editForm->isValid()) {

            if($entity->getTipo() == "Administrador web"){
                $entity->setRole($administradorWeb);
            }
            else{
                $entity->setRole($administrativo);
            }
            $entity->setPassword($password);
            $em->flush();

            if ($request->isXmlHttpRequest()) {
                return new JsonResponse(array(
                    'message' => 'Success!',
                    'data' =>$entity->getId(),
                    'success' => true), 200);
            }

            return $this->redirect($this->generateUrl('administrativo_edit', array('id' => $id)));
        }

        if ($request->isMethod('POST')) {
            return new JsonResponse(array(
            'message' => 'Invalid form',
            'success' => false), 400);
        }

        return $this->render('BackendBundle:Administrativo:edit.html.twig', array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }
    /**
     * Deletes a Administrativo entity.
     *
     */
    public function deleteAction(Request $request, $id)
    {
        $form = $this->createDeleteForm($id);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('BackendBundle:Administrativo')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find Administrativo entity.');
            }

            $em->remove($entity);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('administrativo'));
    }

    /**
     * Creates a form to delete a Administrativo entity by id.
     *
     * @param mixed $id The entity id
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm($id)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('administrativo_delete', array('id' => $id)))
            ->setMethod('DELETE')
            ->add('submit', 'submit', array('label' => 'Delete'))
            ->getForm()
        ;
    }

    public function searchAction()
    {
        $entity = new Administrativo();
        $form = $this->createCreateSearchForm($entity);
        $em = $this->getDoctrine()->getManager();

        $entities_active = $em->getRepository('BackendBundle:Administrativo')->findAdminActivo();
        $entities = $em->getRepository('BackendBundle:Administrativo')->findAdminInactivo();

        return $this->render('BackendBundle:Administrativo:search.html.twig', array(
            'entity' => $entity,
            'form'   => $form->createView(),
            'entities' => $entities,
            'entities_active' => $entities_active,
        ));
    }

    public function SearchOldAction()
    {
        $em = $this->getDoctrine()->getManager();

        $entities = $em->getRepository('BackendBundle:Administrativo')->findAdminInactivo();
        $entities_active = $em->getRepository('BackendBundle:Administrativo')->findAdminActivo();

        return $this->render('BackendBundle:Administrativo:search_old.html.twig', array(
            'entities' => $entities,
            'entities_active' => $entities_active
        ));
    }

    public function ComprobarDniAdministrativoAction()
    {
        $dni=$this->get('request')->request->get('dni');

        $em = $this->getDoctrine()->getEntityManager();
        $administrativo = $em->getRepository('BackendBundle:Administrativo')->findOneByDni($dni);
        if($administrativo){
            return new JsonResponse(array('data' =>$administrativo->getDni()), 200);
        }
        return new JsonResponse(array('data' =>null), 200);
    }

    public function ComprobarDniAdministrativoEditadoAction()
    {
        $dni=$this->get('request')->request->get('dni');
        $id=$this->get('request')->request->get('id');

        $em = $this->getDoctrine()->getEntityManager();
        $administrativo = $em->getRepository('BackendBundle:Administrativo')->findOneByDni($dni);
        if($administrativo && $administrativo->getId()!=$id){
            return new JsonResponse(array('data' =>$administrativo->getDni()), 200);
        }
        return new JsonResponse(array('data' =>null), 200);

    }

    public function DatosAntiguoAdministrativoAction($id)
    {
        $em = $this->getDoctrine()->getManager();
        $entity = $em->getRepository('BackendBundle:Administrativo')->findById($id);
        return $this->render('BackendBundle:Administrativo:datos_antiguo_administrativo.html.twig', array(
            'entity' => $entity,));
    }

    public function AltaAdministrativoAction()
    {
        $administrativos=$this->get('request')->request->get('array');

        $em = $this->getDoctrine()->getManager();

        foreach ($administrativos as $administrativo ) {
            $entity = $em->getRepository('BackendBundle:Administrativo')->findOneById($administrativo);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find Administrativo entity.');
            }

            $entity->setFechaBaja(null);
            $entity->setFechaAlta(new \DateTime("now"));
            $factory = $this->get('security.encoder_factory'); 
            $encoder = $factory->getEncoder($entity);
            $entity->setSalt(base_convert(sha1(uniqid(mt_rand(),true)), 16, 36));
            $password = $encoder->encodePassword(substr($entity->getDni(), 0, -2).substr($entity->getDni(), -1), $entity->getSalt());
            $entity->setPassword($password);

            $em->persist($entity);                 
            $em->flush();
        }
        return new JsonResponse(array('message' => 'Success!','success' => true), 200);
    }

    public function BajaAdministrativoAction()
    {
        $administrativos=$this->get('request')->request->get('array');

        $em = $this->getDoctrine()->getManager();

        foreach ($administrativos as $administrativo ) {
            $entity = $em->getRepository('BackendBundle:Administrativo')->findOneById($administrativo);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find Administrativo entity.');
            }

            $entity->setFechaBaja(new \DateTime("now"));
            $entity->setPassword(null);
            $entity->setLastAccess(null);

            $em->persist($entity);                 
            $em->flush();
        }
        return new JsonResponse(array('message' => 'Success!','success' => true), 200);
    }

}
