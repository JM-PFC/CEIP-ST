<?php

namespace Cole\IntranetBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Component\HttpFoundation\Response;


use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;

use Cole\BackendBundle\Entity\Profesor;
use Cole\BackendBundle\Form\ProfesorType;
use Cole\BackendBundle\Entity\Padres;
use Cole\BackendBundle\Form\PadresType;

class DefaultController extends Controller
{
    public function indexAction()
    {
    	$em = $this->getDoctrine()->getManager();

    	if ($this->get('security.context')->isGranted('ROLE_PROFESOR'))
		{
			return $this->render('IntranetBundle:Default:index.html.twig');
		}
		else if($this->get('security.context')->isGranted('ROLE_USUARIO')){
			$usuario = $this->get('security.context')->getToken()->getUser();
            //Se obtiene los alumnos que están activo y matriculados en un curso, cuyo responsable tambien está activo.
			$hijos= $em->getRepository('BackendBundle:Alumno')->findByResponsableActivo($usuario);
			return $this->render('IntranetBundle:Default:seleccion.html.twig', array(
            'hijos' => $hijos));
		}
		else{
			throw new AccessDeniedException();
		}
    }

    private function createEditProfesorForm(Profesor $entity)
    {
        $form = $this->createForm(new ProfesorType(), $entity, array(
            'action' => $this->generateUrl('datos_personales', array('id' => $entity->getId())),
            'method' => 'PUT',
            //'validation_groups' => array('actualizar')
        ));

        $form->add('submit', 'submit', array('label' => 'Guardar cambios'));

        return $form;
    }

    private function createEditPadresForm(Padres $entity)
    {
        $form = $this->createForm(new PadresType(), $entity, array(
            'action' => $this->generateUrl('datos_personales', array('id' => $entity->getId())),
            'method' => 'PUT',
            //'validation_groups' => array('actualizar')
        ));

        $form->add('submit', 'submit', array('label' => 'Guardar cambios'));

        return $form;
    }

    private function createPassProfesorForm(Profesor $entity)
    {
        $form = $this->createForm(new ProfesorType(), $entity, array(
            'action' => $this->generateUrl('actualizar_password', array('id' => $entity->getId())),
            'method' => 'PUT',
        ));

        $form->add('submit', 'submit', array('label' => 'Actualizar'));

        return $form;
    }

    private function createPassPadresForm(Padres $entity)
    {
        $form = $this->createForm(new PadresType(), $entity, array(
            'action' => $this->generateUrl('actualizar_password', array('id' => $entity->getId())),
            'method' => 'PUT',
            //'validation_groups' => array('actualizar')
        ));

        $form->add('submit', 'submit', array('label' => 'Actualizar cambios'));

        return $form;
    }

    private function createDeleteForm($id)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('profesor_delete', array('id' => $id)))
            ->setMethod('DELETE')
            ->add('submit', 'submit', array('label' => 'Delete'))
            ->getForm()
        ;
    }

    public function perfilAction()
    {
    	$em = $this->getDoctrine()->getManager();
    	$entity = $this->get('security.context')->getToken()->getUser();

        if ($this->get('security.context')->isGranted('ROLE_PROFESOR'))
        {
            $editForm = $this->createEditProfesorForm($entity);
            $PassForm = $this->createPassProfesorForm($entity);
        }
        else
        {
            $editForm = $this->createEditPadresForm($entity);
            $PassForm = $this->createPassPadresForm($entity);
        }
        $deleteForm = $this->createDeleteForm($entity->getId());

    	return $this->render('IntranetBundle:Default:perfil.html.twig', array(
           	'entity' => $entity,
            'edit_form'   => $editForm->createView(),
            'form'   => $PassForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }
/*
    public function DatosPersonalesAction()
    {
        $em = $this->getDoctrine()->getManager();
        $entity = $this->get('security.context')->getToken()->getUser();

       
         $template = $this->forward('IntranetBundle:Default:perfil.html.twig')->getContent();

    $json = json_encode($template);
    $response = new Response($json, 200);
    $response->headers->set('Content-Type', 'application/json');
    return $response;

    }
*/





    public function DatosPersonalesAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        if ($this->get('security.context')->isGranted('ROLE_PROFESOR'))
        {
            $entity = $em->getRepository('BackendBundle:Profesor')->find($id);
            $editForm = $this->createEditProfesorForm($entity);
            $foto=$entity->getFoto();
        }
        else
        {
            $entity = $em->getRepository('BackendBundle:Padres')->find($id);
            $editForm = $this->createEditPadresForm($entity);
        }
        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Usuario entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        $editForm->handleRequest($request);

        if ($editForm->isValid()) {

            if ($this->get('security.context')->isGranted('ROLE_PROFESOR'))
            {
                $entity->setFoto($foto);
            }
            $em->persist($entity);

            $em->flush();
            $ms = $this->get('translator')->trans('Se han guardado los cambios.');

            $this->get('session')->getFlashBag()->add('notice',$ms);

            return $this->redirect($this->generateUrl('intranet_perfil', array('_locale' => $this->get('request')->getLocale())));
        }
        //var_dump($editForm->getErrorsAsString());die;

        return $this->render('IntranetBundle:Default:perfil.html.twig', array(
            'entity'      => $entity,
            'form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }


    public function ActualizarPasswordAction(Request $request, $id)
    {

        $em = $this->getDoctrine()->getManager();

        if ($this->get('security.context')->isGranted('ROLE_PROFESOR'))
        {
            $entity = $em->getRepository('BackendBundle:Profesor')->find($id);
            $editForm = $this->createEditProfesorForm($entity);
            $foto=$entity->getFoto();
        }
        else
        {
            $entity = $em->getRepository('BackendBundle:Padres')->find($id);
            $editForm = $this->createEditPadresForm($entity);
        }
        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Usuario entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        $editForm->handleRequest($request);

        if ($editForm->isValid()) {

            if ($this->get('security.context')->isGranted('ROLE_PROFESOR'))
            {
                $entity->setFoto($foto);
            }

            $em = $this->getDoctrine()->getEntityManager(); 
            $factory = $this->get('security.encoder_factory'); 
            $encoder = $factory->getEncoder($entity);
            $password = $encoder->encodePassword($entity->getPassword(), $entity->getSalt());
            $entity->setPassword($password);

            $em->persist($entity);

            $em->flush();
            $ms = $this->get('translator')->trans('La contraseña ha sido actualizada.');

            $this->get('session')->getFlashBag()->add('notice',$ms);

            return $this->redirect($this->generateUrl('intranet_perfil', array('_locale' => $this->get('request')->getLocale())));
        }
        //var_dump($editForm->getErrorsAsString());die;

        return $this->render('IntranetBundle:Default:perfil.html.twig', array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));

/*
        $em = $this->getDoctrine()->getEntityManager();

        $entity = $em->getRepository('miomioBundle:Empleado')->find($id);
        $pass = $entity->getPassword();
        $salt = $entity->getSalt();
        $encoder = new MessageDigestPasswordEncoder('sha1');

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Empleado entity.');
        }

        $editForm   = $this->createForm(new EmpleadoType(), $entity);
        $deleteForm = $this->createDeleteForm($id);

        $request = $this->getRequest();

        $editForm->bindRequest($request);

        if ($editForm->get('generar')->getData() == true){

                $salt1 = md5(time());
                $psswd = substr( md5(microtime()), 1, 8);

                $message = \Swift_Message::newInstance()
              ->setSubject("Nueva contraseña generada")
              ->setFrom('paradasymfony@alwaysdata.com')
              ->setTo($entity->getEmail())
              ->setBody('Hola '.$entity->getNombre().' '.$entity->getApellido1().' '.$entity->getApellido2().'.<br/><br/>'.
                            'Se ha generado una nueva contraseña en el sistema Optinet.Le adjunto los datos para que usted pueda entrar al sistema:<br/>'.
                            'Nombre usuario:  '.$entity->getUsername().'<br/>'.
                            'Contraseña:     '.$psswd.'<br/><br/>'.
                            'Un saludo.'
                            ,'text/html');
                $this->get('mailer')->send($message);

                $password = $encoder->encodePassword($psswd, $salt1);

               if ($editForm->isValid()) {
                $em->persist($entity);
                $entity->setPassword($password);
                $entity->setSalt($salt1);
                $em->persist($entity);
                $em->flush();
                 $t = $this->get('translator')->trans(
                    'El empleado %nombre% ha sido modificado correctamente.',
                    array('%nombre%' => $entity->getNombre())
                );
                $this->get('session')->setFlash('confirmacion',$t);
                return $this->redirect($this->generateUrl('empleado_edit', array('id' => $id)));
            }
            $t = $this->get('translator')->trans('Se ha producido algún error. Revise los datos.');
            $this->get('session')->setFlash('errorempleado',$t);

            return array(
                'entity'      => $entity,
                'edit_form'   => $editForm->createView(),
                'delete_form' => $deleteForm->createView(),
            );
        }
        else{
            $salt1 = md5(time());
            $password = $encoder->encodePassword($editForm->get('password')->getData(), $salt1);
            $entity->setPassword($password);
            $entity->setSalt($salt1);

            if ($editForm->isValid()) {
               if ($editForm->get('password')->getData() == ''){ 
                    $entity->setPassword($pass);
                    $entity->setSalt($salt);
                }
                $em->persist($entity);
                $em->flush();
                 $t = $this->get('translator')->trans(
                    'El empleado %nombre% ha sido modificado correctamente.',
                    array('%nombre%' => $entity->getNombre())
                );
                $this->get('session')->setFlash('confirmacion',$t);
                return $this->redirect($this->generateUrl('empleado_edit', array('id' => $id)));
            }
            $t = $this->get('translator')->trans('Se ha producido algún error. Revise los datos.');
            $this->get('session')->setFlash('errorempleado',$t);

            return array(
                'entity'      => $entity,
                'edit_form'   => $editForm->createView(),
                'delete_form' => $deleteForm->createView(),
            );
        }
        */
    }


    
}
