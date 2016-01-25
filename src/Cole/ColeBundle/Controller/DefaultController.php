<?php

namespace Cole\ColeBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction()
    {
        return $this->render('ColeBundle:Default:index.html.twig');
    }
}
