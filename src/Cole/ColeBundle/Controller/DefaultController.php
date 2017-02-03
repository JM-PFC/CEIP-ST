<?php

namespace Cole\ColeBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;

class DefaultController extends Controller
{
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        return $this->render('ColeBundle:Default:index.html.twig');
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
            //Devuelvo el año de inicio y fin del curso para mostrar el curso actual debajo del menú.
            'inicio' => $centro->getInicioCurso()->format('Y'),
            'fin' => $centro->getFinCurso()->format('Y'),
            'success' => true), 200);
    }
}
