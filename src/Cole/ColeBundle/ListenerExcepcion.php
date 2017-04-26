<?php

namespace Cole\ColeBundle;

use Symfony\Component\HttpKernel\Event\GetResponseForExceptionEvent;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Routing\RouterInterface;

class ListenerExcepcion
{

    protected $router;

    public function __construct(RouterInterface $router)
    {
        $this->router = $router;
    }
    public function onKernelException(GetResponseForExceptionEvent $event)
    {
       $event->setResponse(new RedirectResponse($this->router->generate('noencontrada')));
    }
}
?>