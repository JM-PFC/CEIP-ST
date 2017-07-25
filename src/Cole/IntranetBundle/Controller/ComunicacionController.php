<?php

namespace Cole\IntranetBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;

use Cole\IntranetBundle\Entity\Comunicacion;
use Cole\IntranetBundle\Form\ComunicacionType;

/**
 * Comunicacion controller.
 *
 */
class ComunicacionController extends Controller
{

    /**
     * Lists all Comunicacion entities.
     *
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $entities = $em->getRepository('IntranetBundle:Comunicacion')->findAll();

        return $this->render('IntranetBundle:Comunicacion:index.html.twig', array(
            'entities' => $entities,
        ));
    }
    /**
     * Creates a new Comunicacion entity.
     *
     */
    public function createAction(Request $request)
    {
        $entity = new Comunicacion();
        $form = $this->createCreateForm($entity);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();

            $file = $entity->getFichero();
            if($file){
                $fileName = md5(uniqid()).'.'.$file->guessExtension();
                $cvDir = $this->container->getparameter('kernel.root_dir').'/../web/uploads/ficheros';
                $file->move($cvDir, $fileName);
                $entity->setFichero($fileName);  
            }

            if($entity->getAsunto()==null){
                $entity->setAsunto("Sin asunto");
            }
            $entity->setEliminadoEmisor(0);
            $entity->setEliminadoReceptor(0);
            $entity->setFecha(new \DateTime("now"));


            if($entity->getTipoEmisor()==1){
                $emisor= $em->getRepository('BackendBundle:Profesor')->findOneById($entity->getEmisor());
                $entity->setProfesorEmisor($emisor);
            }
            else{
                $emisor= $em->getRepository('BackendBundle:Padres')->findOneById($entity->getEmisor());
                $entity->setResponsableEmisor($emisor);
            }

            if($entity->getTipoReceptor()==1){
                $receptor= $em->getRepository('BackendBundle:Profesor')->findOneById($entity->getReceptor());
                $entity->setProfesorReceptor($receptor);
            }
            else{
                $receptor= $em->getRepository('BackendBundle:Padres')->findOneById($entity->getReceptor());
                $entity->setResponsableReceptor($receptor);
            }

            $em->persist($entity);
            $em->flush();

            $ms = $this->get('translator')->trans('El mensaje se ha enviado correctamente.');
            $this->get('session')->getFlashBag()->add('notice',$ms);
            return $this->redirect($this->generateUrl('intranet_profesor_comunicacion'));
        }

        return $this->render('IntranetBundle:Comunicacion:new.html.twig', array(
            'entity' => $entity,
            'form'   => $form->createView(),
        ));
    }

    /**
     * Creates a form to create a Comunicacion entity.
     *
     * @param Comunicacion $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createCreateForm(Comunicacion $entity)
    {
        $form = $this->createForm(new ComunicacionType(), $entity, array(
            'action' => $this->generateUrl('comunicacion_create'),
            'method' => 'POST',
        ));

        $form->add('submit', 'submit', array('label' => 'Create'));

        return $form;
    }

    /**
     * Displays a form to create a new Comunicacion entity.
     *
     */
    public function newAction()
    {
        $entity = new Comunicacion();
        $form   = $this->createCreateForm($entity);

        return $this->render('IntranetBundle:Comunicacion:new.html.twig', array(
            'entity' => $entity,
            'form'   => $form->createView(),
        ));
    }

    /**
     * Finds and displays a Comunicacion entity.
     *
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('IntranetBundle:Comunicacion')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Comunicacion entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return $this->render('IntranetBundle:Comunicacion:show.html.twig', array(
            'entity'      => $entity,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing Comunicacion entity.
     *
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('IntranetBundle:Comunicacion')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Comunicacion entity.');
        }

        $editForm = $this->createEditForm($entity);
        $deleteForm = $this->createDeleteForm($id);

        return $this->render('IntranetBundle:Comunicacion:edit.html.twig', array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
    * Creates a form to edit a Comunicacion entity.
    *
    * @param Comunicacion $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createEditForm(Comunicacion $entity)
    {
        $form = $this->createForm(new ComunicacionType(), $entity, array(
            'action' => $this->generateUrl('comunicacion_update', array('id' => $entity->getId())),
            'method' => 'PUT',
        ));

        $form->add('submit', 'submit', array('label' => 'Update'));

        return $form;
    }
    /**
     * Edits an existing Comunicacion entity.
     *
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('IntranetBundle:Comunicacion')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Comunicacion entity.');
        }

        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createEditForm($entity);
        $editForm->handleRequest($request);

        if ($editForm->isValid()) {
            $em->flush();

            return $this->redirect($this->generateUrl('comunicacion_edit', array('id' => $id)));
        }

        return $this->render('IntranetBundle:Comunicacion:edit.html.twig', array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }
    /**
     * Deletes a Comunicacion entity.
     *
     */
    public function deleteAction(Request $request, $id)
    {
        $form = $this->createDeleteForm($id);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('IntranetBundle:Comunicacion')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find Comunicacion entity.');
            }

            $em->remove($entity);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('comunicacion'));
    }

    /**
     * Creates a form to delete a Comunicacion entity by id.
     *
     * @param mixed $id The entity id
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm($id)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('comunicacion_delete', array('id' => $id)))
            ->setMethod('DELETE')
            ->add('submit', 'submit', array('label' => 'Delete'))
            ->getForm()
        ;
    }

    public function DestinatariosAction($id)
    {
        $em = $this->getDoctrine()->getManager();
        $user = $this->get('security.context')->getToken()->getUser();

        $profesores=null;
        if ($this->get('security.context')->isGranted('ROLE_USUARIO')){

        }
        else{
            $profesores=$em->getRepository('BackendBundle:Profesor')->findOtrosProfesoresActivos($user->getId());
        }

        return $this->render('IntranetBundle:Comunicacion:destinatarios.html.twig', array(
            'entities'=> $profesores,
            'user'=>$user
        ));
    }


    public function comprobarLeidosAction($id)
    {
        $em = $this->getDoctrine()->getManager();
        $user = $this->get('security.context')->getToken()->getUser();

        $mensaje=$em->getRepository('IntranetBundle:Comunicacion')->findOneById($id);
        $leido=0;
        if($mensaje->getLeido()==0){
            $leido=1;
            $mensaje->setLeido(1);

            $em->persist($mensaje);
            $em->flush();
        }
        
        return new JsonResponse(array('leido' => $leido), 200);
    }

    public function enviarMensajePapeleraAction($id)
    {
        $em = $this->getDoctrine()->getManager();
        $user = $this->get('security.context')->getToken()->getUser();

        $mensaje=$em->getRepository('IntranetBundle:Comunicacion')->findOneById($id);

        $mensaje->setEliminadoReceptor(1);

        $em->persist($mensaje);
        $em->flush();

        
        return new JsonResponse(200);
    }




}
