<?php

namespace Cole\ColeBundle;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\HttpKernel\Event\GetResponseForExceptionEvent;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Component\Security\Http\Authorization\AccessDeniedHandlerInterface;
use Symfony\Component\Security\Core\SecurityContextInterface;


class AccessDeniedHandler implements AccessDeniedHandlerInterface{

	protected $router;
    protected $securityContext;

    public function __construct(RouterInterface $router,SecurityContextInterface $securityContext)
    {
        $this->router = $router;
        $this->securityContext = $securityContext;
    }

function handle(Request $request, AccessDeniedException $accessDeniedException){
        $empleado = $this->securityContext->getToken()->getUser();
		//return new Response($this->securityContext->getToken()->getUser());
        return new RedirectResponse($this->router->generate('denegada'));
    }
}