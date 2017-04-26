<?php

namespace Cole\IntranetBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;


class AlumnoController extends Controller
{

    protected function comprobarHijo($id) 
    {

    	$em = $this->getDoctrine()->getManager();
    	$entity= $em->getRepository('BackendBundle:Alumno')->findOneById($id);
    	if(!$entity){
			throw $this->createNotFoundException('Unable to find Role entity.');
    	}
		$user=$this->get('security.context')->getToken()->getUser();
    	if($entity->getActivo()==1 && ($entity->getResponsable1()==$user || $entity->getResponsable2()==$user)){
    	}
    	else{
			throw $this->createNotFoundException('Unable to find Role entity.');
    	}
    }




    public function indexAction($id)
    {
    	$this->comprobarHijo($id);

    	$em = $this->getDoctrine()->getManager();

		$entity= $em->getRepository('BackendBundle:Alumno')->findOneById($id);

		return $this->render('IntranetBundle:Alumno:index.html.twig', array('entity' => $entity));

    }
}