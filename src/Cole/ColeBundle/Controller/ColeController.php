<?php

namespace Cole\ColeBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class ColeController extends Controller
{
    public function estaticaAction($pagina)
    {
    	$texto= 'Este código Twig divide un capítulo en varias páginas 
    	y después las muestra por pantalla ocultando todas ellas salvo la primera. Después, con ayuda de JavaScript puedes añadir los controles que hacen que se vea cada página ocultando las demás.';
        return $this->render('ColeBundle:Cole:'.$pagina.'.html.twig', array('pagina' => $pagina ,'texto'=>$texto));
    }
}
