<?php

namespace Cole\IntranetBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;


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

			$hijos= $em->getRepository('BackendBundle:Alumno')->findByResponsableActivo($usuario);
			return $this->render('IntranetBundle:Default:seleccion.html.twig', array(
            'hijos' => $hijos));
		}
		else{
			throw new AccessDeniedException();
		}
    }

    public function perfilAction()
    {
    	$em = $this->getDoctrine()->getManager();
    	$entity = $this->get('security.context')->getToken()->getUser();

    	return $this->render('IntranetBundle:Default:perfil.html.twig', array(
           	'entity' => $entity
        ));
    }
    
}
