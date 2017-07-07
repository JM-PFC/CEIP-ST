<?php

namespace Cole\ColeBundle;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Cookie;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Bundle\FrameworkBundle\Routing\Router;
use Doctrine\ORM\EntityManager;
use Symfony\Component\DependencyInjection\ContainerAware;
use Symfony\Component\Security\Http\Authentication\AuthenticationSuccessHandlerInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationFailureHandlerInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Http\Logout\LogoutSuccessHandlerInterface;
use Symfony\Component\Security\Core\SecurityContext;
use Cole\BackendBundle\Entity\Log;

class AuthenticationHandler extends ContainerAware implements AuthenticationSuccessHandlerInterface,LogoutSuccessHandlerInterface{

	protected $router;
	protected $em;
    protected $security;  

    public function __construct(RouterInterface $router, EntityManager $entityManager, SecurityContext $security)
    {
        $this->em = $entityManager;
        $this->router = $router;
        $this->security = $security;
    }

	public function onAuthenticationSuccess(Request $request, TokenInterface $token)
    {
    	$usuario = $token->getUser();
        $session = $request->getSession();
        $log = new Log();
        $log->setFecha(new \DateTime('now'));
        $log->setTipo("Entrada");
        $log->setUsuario($usuario);
        if ($this->security->isGranted('ROLE_PROFESOR'))
        {
            $log->setTipoUsuario("Profesor");
        }
        else if($this->security->isGranted('ROLE_USUARIO')){
            $log->setTipoUsuario("Responsable/Alumno");
        }
        $usuario->setLastAccessAnt($usuario->getLastAccess());
        $usuario->setLastAccess(new \DateTime('now'));

    	$this->em->persist($log);
    	$this->em->flush();
        $locale = explode("_", $request->getLocale());
    	return new RedirectResponse($this->router->generate('intranet', array('_locale' => $locale[0])));
    }

    public function onLogoutSuccess(Request $request){
        $usuario =  $this->security->getToken()->getUser();
        $log = new Log();
        $log->setFecha(new \DateTime('now'));
        $log->setTipo("Salida");
        $log->setUsuario($usuario);
        if ($this->security->isGranted('ROLE_PROFESOR'))
        {
            $log->setTipoUsuario("Profesor");
        }
        else if($this->security->isGranted('ROLE_USUARIO')){
            $log->setTipoUsuario("Responsable/Alumno");
        }        $this->em->persist($log);
        $this->em->flush();
        return new RedirectResponse($this->router->generate('index'));
    }
}
?>