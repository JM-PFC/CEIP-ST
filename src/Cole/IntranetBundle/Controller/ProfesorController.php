<?php

namespace Cole\IntranetBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;

use Cole\BackendBundle\Entity\Profesor;
use Cole\ColeBundle\Entity\Noticias;

use Cole\BackendBundle\Form\AlumnoIntranetType;
use Symfony\Component\HttpFoundation\Response;


class ProfesorController extends Controller
{

    public function indexAction()
    {
    	$em = $this->getDoctrine()->getManager();

        $entity = $this->get('security.context')->getToken()->getUser();

		return $this->render('IntranetBundle:Profesor:index.html.twig', array(
            'entity' => $entity,
            'id'=>$entity->getId()));
    }


    public function datosAlumnosGrupoAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $profesor = $this->get('security.context')->getToken()->getUser();
        $grupo=$em->getRepository('BackendBundle:Grupo')->findOneById($id);

        $tutor=$grupo->getProfesor();
        $entities=$em->getRepository('BackendBundle:Alumno')->findByGrupo($grupo);

        return $this->render('IntranetBundle:Profesor:datos_alumnos_grupo.html.twig', array(
            'profesor' => $profesor,
            'entities'=>$entities,
            'grupo'=> $grupo,
            'tutor'=>$tutor));
    }

    public function cursosAction()
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $this->get('security.context')->getToken()->getUser();

        $tutor_grupo= $em->getRepository('BackendBundle:Grupo')->findOneByProfesor($entity);
        $inicio =$em->getRepository('BackendBundle:Centro')->findInicioCurso();
        $fin =$em->getRepository('BackendBundle:Centro')->findFinCurso();

        $cursos = $em->getRepository('BackendBundle:Imparte')->findAsignacionesProfesor($entity);
  

        return $this->render('IntranetBundle:Profesor:cursos.html.twig', array(
            'entity' => $entity, 
            'tutor_grupo' => $tutor_grupo,
            'inicio' => $inicio,
            'fin' =>$fin,
            'cursos'=>$cursos));
    }





    public function HorariosProfesorAction($num)
    {

        $em = $this->getDoctrine()->getManager();

        $profesor = $this->get('security.context')->getToken()->getUser();

        $entity = $em->getRepository('BackendBundle:Horario')->findAll();
        
        $imparte = $em->getRepository('BackendBundle:Imparte')->findByProfesor($profesor);

        $array=[];
        foreach($imparte as $registro){
            if($registro->getAula()){
                $aula=$registro->getAula()->getNombre();
            }
            else{
                $aula="";
            }

            if($registro->getDiaSemanal()){
                $dia=$registro->getDiaSemanal();
            }
            else{
                $dia=""; 
            }

            if($registro->getHorario()){
                $horario=$registro->getHorario()->getId();
            }
            else{
                $horario="";
            }
           
            $array[$horario."-".$dia."-".$registro->getAsignatura()->getAsignatura()->getNombre()."-".$registro->getAsignatura()->getAsignatura()->getAbreviatura()."-".$aula."-".$registro->getGrupo()->getCurso()->getCurso()." ".$registro->getGrupo()->getLetra()]=$registro->getAsignatura()->getId();
        }

        return $this->render('IntranetBundle:Profesor:datos_horario.html.twig', array(
            'entities' => $imparte,
            'imparte' => $array,
            'entity' => $entity,
            'num' => $num
        ));
    }


    public function noticiasAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $this->get('security.context')->getToken()->getUser();

        $noticias= $em->getRepository('ColeBundle:Noticias')->findBy(array('categoria'=>'profesores'), array('fecha'=>'DESC'));

        $paginator  = $this->get('knp_paginator');
        $pagination = $paginator->paginate(
            $noticias, /* query NOT result */
            $request->query->getInt('page', 1)/*page number*/,
            6/*limit per page*/
        );

        if($entity->getAccesoNoticias()){
            $noticias_nuevas =$em->getRepository('ColeBundle:Noticias')->findNuevasNoticias($entity->getAccesoNoticias(),"profesores");
        }
        else{
            $noticias_nuevas= $em->getRepository('ColeBundle:Noticias')->findBy(array('categoria'=>'profesores'), array('fecha'=>'DESC'));

        }

        $array=[];
        if($noticias_nuevas){
            foreach($noticias_nuevas as $noticia){
                $array[$noticia->getId()]=$noticia->getTitulo();
            }
        }

        //Se actualiza la fecha del último acceso a noticias.
        $entity->setAccesoNoticias(new \DateTime("now"));
        $em->persist($entity);
        $em->flush();

        return $this->render('IntranetBundle:Profesor:noticias.html.twig', array(
            'entity' => $entity, 
            'noticias'=> $noticias,
            'noticias_nuevas' => $array,
            'pagination'=> $pagination));
    }

    public function noticiaAction($id)
    {

        $em = $this->getDoctrine()->getManager();

        $entity = $this->get('security.context')->getToken()->getUser();

        $noticia= $em->getRepository('ColeBundle:Noticias')->findOneById($id);
     
        $imagenes = array();
        if($noticia->getGaleria()!=null) {
            $photoDir = $this->container->getParameter('kernel.root_dir').'/../web/uploads/noticias/galeria/'.$noticia->getGaleria().'/';
            
            foreach (glob($photoDir."*.*") as $nombre_dir) {
                $nombre_dir = explode("/", $nombre_dir);
                $nombre_dir =end($nombre_dir);
                $imagenes[]=$nombre_dir;
            }
        }

        return $this->render('IntranetBundle:Profesor:noticia.html.twig', array(
            'entity' => $entity, 
            'noticia'=> $noticia,
            'imagenes' => $imagenes));
    }



    public function comprobarNoticiasNuevasAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity= $em->getRepository('BackendBundle:Profesor')->findOneById($id);

        if($entity->getAccesoNoticias()){
            $noticias =$em->getRepository('ColeBundle:Noticias')->findNuevasNoticias($entity->getAccesoNoticias(),"profesores");
        }
        else{
            $noticias= $em->getRepository('ColeBundle:Noticias')->findBy(array('categoria'=>'profesores'), array('fecha'=>'DESC'));
        }

        if($noticias){
            $num=count($noticias);
        }
        else{
            $num=0;
        }

        return new JsonResponse(array(
            'num'=> $num,
            'id' => $id,
            'success' => true), 200);
    }



}