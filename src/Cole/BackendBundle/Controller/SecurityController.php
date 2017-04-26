<?php

namespace Cole\BackendBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Security\Core\SecurityContext;

class SecurityController extends Controller
{
	public function loginAction()
	{
		$peticion = $this->getRequest();
		$sesion = $peticion->getSession();

		$error = $peticion->attributes->get(SecurityContext::AUTHENTICATION_ERROR, $sesion->get(SecurityContext::AUTHENTICATION_ERROR));

		return $this->render('BackendBundle:Security:login.html.twig', array(
			'last_username'=> $sesion->get(SecurityContext::LAST_USERNAME),
			'error'       => $error
		));
	}
	public function denegadaAction(){

      return $this->render('BackendBundle:Security:denegada.html.twig');
    }
    
    public function noencontradaAction(){

      return $this->render('BackendBundle:Security:noencontrada.html.twig');
    }

}