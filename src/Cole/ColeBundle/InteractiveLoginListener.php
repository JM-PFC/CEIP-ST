<?php

namespace Cole\ColeBundle;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Http\Event\InteractiveLoginEvent;

use Doctrine\ORM\EntityManager;
use Cole\BackendBundle\Entity\Padres;
use Cole\BackendBundle\Entity\Profesor;
use Symfony\Component\Security\Core\SecurityContext;


class InteractiveLoginListener {

    protected $em;
    protected $request;

    public function __construct(EntityManager $em, Request $request, SecurityContext $security) {

        $this->em = $em;
        $this->request = $request;
        $this->security = $security;
    }

    public function onSecurityInteractiveLogin(InteractiveLoginEvent $event) {

        if ($this->security->isGranted('ROLE_PROFESOR'))
        {
            $user = $event->getAuthenticationToken()->getUser();
            if ($user instanceof Profesor) {
                if($this->request->hasSession()) {
                    $user->setLastAccessAnt($user->getLastAccess());
                    $user->setLastAccess(new \DateTime('now'));
                    $this->em->persist($user);
                    $this->em->flush();
                }
            }
        }
        else
        {
            $user = $event->getAuthenticationToken()->getUser();
            if ($user instanceof Padres) {
                if($this->request->hasSession()) {
                    $user->setLastAccessAnt($user->getLastAccess());
                    $user->setLastAccess(new \DateTime('now'));
                    $this->em->persist($user);
                    $this->em->flush();
                }
            }
        }
    }
}