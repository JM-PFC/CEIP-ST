<?php

namespace Cole\ColeBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;

class DefaultController extends Controller
{
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $inicio =$em->getRepository('BackendBundle:Centro')->findInicioCurso();
        $fin =$em->getRepository('BackendBundle:Centro')->findFinCurso();

        $centro =$em->getRepository('BackendBundle:Centro')->findCentro();


        return $this->render('ColeBundle:Default:index.html.twig', array(
            'inicio' => $inicio,
            'fin' => $fin,
            'h_secretaria'=> $centro->getHSecretaria(),
            'h_direccion'=> $centro->getHDireccion(),
            'h_estudios'=> $centro->getHEstudios(),
            ));
    }

    public function datosFooterAction()
    {
        $em = $this->getDoctrine()->getManager();

        $centro =$em->getRepository('BackendBundle:Centro')->findCentro();

        return new JsonResponse(array(
            'nombre'=> $centro->getNombre(),
            'direccion'=> $centro->getDireccion(),
            'localidad'=> $centro->getLocalidad(),
            'cp'=> $centro->getCp(),
            'telefono'=> $centro->getTelefono(),
            'success' => true), 200);
    }
}
