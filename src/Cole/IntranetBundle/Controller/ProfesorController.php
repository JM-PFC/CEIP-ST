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
        $entities=$em->getRepository('BackendBundle:Alumno')->findByGrupoOrdenado($grupo);

        $no_opcionales=$em->getRepository('BackendBundle:Imparte')->findNoOpcionalesProfesorGrupo($profesor, $grupo);   
        $opcional=$em->getRepository('BackendBundle:Imparte')->findOpcionalProfesorGrupo($profesor, $grupo);
        if($no_opcionales && $opcional){
            $tipos="ambos";
        }
        else if($no_opcionales){
            $tipos="no_opcionales";
        }
        else{
            $tipos="opcional";
        } 

        if($opcional){
            $alumnos_optativa=$em->getRepository('BackendBundle:Alumno')->findAlumnosOptativaGrupo($opcional->getAsignatura(), $grupo);
        }
        else{
            $alumnos_optativa=null;
        }

        return $this->render('IntranetBundle:Profesor:datos_alumnos_grupo.html.twig', array(
            'profesor' => $profesor,
            'entities'=>$entities,
            'grupo'=> $grupo,
            'tipos'=> $tipos,
            'opcional'=>$opcional,
            'alumnos_optativa' => $alumnos_optativa,
            'tutor'=>$tutor));
    }

    public function InfoAlumnoAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $profesor = $this->get('security.context')->getToken()->getUser();
        $entity=$em->getRepository('BackendBundle:Alumno')->findOneById($id);
        //Se obtiene la edad del alumno mediante la fecha de nacimiento.
        $edad = date_diff($entity->getFechaNacimiento(), date_create('now'))->y;

        return $this->render('IntranetBundle:Profesor:info_alumno.html.twig', array(
            'profesor' => $profesor,
            'entity'=>$entity,
            'edad'=>$edad));
    }

    public function cursosAction()
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $this->get('security.context')->getToken()->getUser();

        $tutor_grupo= $em->getRepository('BackendBundle:Grupo')->findOneByProfesor($entity);
        $inicio =$em->getRepository('BackendBundle:Centro')->findInicioCurso();
        $fin =$em->getRepository('BackendBundle:Centro')->findFinCurso();

        if($entity->getNivel()=="Primaria"){
            $cursos = $em->getRepository('BackendBundle:Imparte')->findAsignacionesProfesor($entity);
        }
        else{
            //Para los profesores de infantil se asigna sólo el grupo que es tutor.
            $cursos=$tutor_grupo;
        }
  
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

    //Función de prueba para ver los resultados del horario en html.
    public function HorarioAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $alumno= $em->getRepository('BackendBundle:Alumno')->findOneById($id);

        $grupo= $alumno->getGrupo();
        if (!$grupo) {
            throw $this->createNotFoundException('Unable to find Grupo.');
        }
        $inicio =$em->getRepository('BackendBundle:Centro')->findInicioCurso();
        $fin =$em->getRepository('BackendBundle:Centro')->findFinCurso();

        $horarios = $em->getRepository('BackendBundle:Horario')->findAll();

        $entities = $em->getRepository('BackendBundle:Imparte')->findByGrupoConHorario($grupo);

        $imparte= $em->getRepository('BackendBundle:Imparte')->findAsignacionesNoOpcionales($grupo);

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
           
            $array[$horario."-".$dia."-".$registro->getAsignatura()->getAsignatura()->getNombre()."-".$registro->getAsignatura()->getAsignatura()->getAbreviatura()."-".$aula."-".$registro->getGrupo()->getId()]=$registro->getProfesor()->getNombre()." ".$registro->getProfesor()->getApellido1()." ".$registro->getProfesor()->getApellido2();
        }
        //Se obtiene las asignaciones de la asignatura optativa del alumno.
        if($alumno->getOptativa()){
            $imparte= $em->getRepository('BackendBundle:Imparte')->findAsignacionesOptativaAlumno($grupo,$alumno->getOptativa());

            $array_op=[];
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
                $asig_op=$em->getRepository('BackendBundle:AsignaturasCursos')->findAsignaturasEspecificasOpcionalesCurso($grupo->getCurso());
                $num_asig_op=count($asig_op);
                $array_op[$horario."-".$dia."-".$registro->getAsignatura()->getAsignatura()->getNombre()."-".$registro->getAsignatura()->getAsignatura()->getAbreviatura()."-".$aula."-".$num_asig_op]=$registro->getProfesor()->getNombre()." ".$registro->getProfesor()->getApellido1()." ".$registro->getProfesor()->getApellido2();
            }
        }
        else{
            $array_op=null;
        }
        
        if($grupo->getAula()==null){
            $aula=null;
        }
        else{
            $aula=$grupo->getAula();
        }

        return $this->render('IntranetBundle:Alumno:horario_de_clase.html.twig', array(
            'alumno' => $alumno,
            'entities' => $entities,
            'inicio' => $inicio,
            'fin' => $fin,
            'horarios' => $horarios,
            'aula' => $aula,
            'grupo' => $grupo->getCurso()->getCurso()." ".$grupo->getCurso()->getNivel()." - Grupo ".$grupo->getLetra(),
            'imparte' => $array,
            'imparte_op' => $array_op,
            'profesor' => $grupo->getProfesor(),
        ));
        
    }

/*
    public function HorarioPdfAction($id)
    {
       $em = $this->getDoctrine()->getManager();

        $alumno= $em->getRepository('BackendBundle:Alumno')->findOneById($id);

        $grupo= $alumno->getGrupo();

        $inicio =$em->getRepository('BackendBundle:Centro')->findInicioCurso();
        $fin =$em->getRepository('BackendBundle:Centro')->findFinCurso();

        $horarios = $em->getRepository('BackendBundle:Horario')->findAll();
        
        $entities = $em->getRepository('BackendBundle:Imparte')->findByGrupoConHorario($grupo);
        $imparte= $em->getRepository('BackendBundle:Imparte')->findAsignacionesNoOpcionales($grupo);

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
           
            $array[$horario."-".$dia."-".$registro->getAsignatura()->getAsignatura()->getNombre()."-".$registro->getAsignatura()->getAsignatura()->getAbreviatura()."-".$aula."-".$registro->getGrupo()->getId()]=$registro->getProfesor()->getNombre()." ".$registro->getProfesor()->getApellido1()." ".$registro->getProfesor()->getApellido2();
        }

        //Se obtiene las asignaciones de la asignatura optativa del alumno.
        if($alumno->getOptativa()){
            $imparte= $em->getRepository('BackendBundle:Imparte')->findAsignacionesOptativaAlumno($grupo,$alumno->getOptativa());

            $array_op=[];
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
                $asig_op=$em->getRepository('BackendBundle:AsignaturasCursos')->findAsignaturasEspecificasOpcionalesCurso($grupo->getCurso());
                $num_asig_op=count($asig_op);
                $array_op[$horario."-".$dia."-".$registro->getAsignatura()->getAsignatura()->getNombre()."-".$registro->getAsignatura()->getAsignatura()->getAbreviatura()."-".$aula."-".$num_asig_op]=$registro->getProfesor()->getNombre()." ".$registro->getProfesor()->getApellido1()." ".$registro->getProfesor()->getApellido2();
            }
        }
        else{
            $array_op=null;
        }

        if($grupo->getAula()==null){
            $aula=null;
        }
        else{
            $aula=$grupo->getAula();
        }

        $html = $this->renderView('IntranetBundle:Alumno:horario_de_clase.html.twig', array(
            'alumno' => $alumno,
            'entities' => $entities,
            'inicio' => $inicio,
            'fin' => $fin,
            'horarios' => $horarios,
            'aula' => $grupo->getAula(),
            'grupo' => $grupo->getCurso()->getCurso()." ".$grupo->getCurso()->getNivel()." - Grupo ".$grupo->getLetra(),
            'imparte' => $array,
            'imparte_op' => $array_op,
            'profesor' => $grupo->getProfesor(),
        ));

        $options = [
            'margin-top'    => 3,
            'margin-right'  => 8,
            'margin-bottom' => 3,
            'margin-left'   => 8,
            //Opciones para orientación horizontal.
            'orientation'=>'Landscape', 
            'default-header'=>false
        ];
        $dato=$alumno->getApellido1()."_".$alumno->getApellido2();
        return new Response(
            $this->get('knp_snappy.pdf')->getOutputFromHtml($html,$options),
            200,
            array(
                'Content-Type'        => 'application/pdf',
                'Content-Disposition' => 'attachment; filename="Horario_'.$dato.'.pdf"'
            )
        );
    }
*/


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
/*
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
*/
        $noticias_nuevas=$entity->getNoticiasNuevas();
        $id_noticias = explode("|", $noticias_nuevas);
        $array=[];
        foreach($id_noticias as $id){
            if($id!=""){
                $array[$id]=$id;
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

    public function noticiaAction($num)
    {

        $em = $this->getDoctrine()->getManager();

        $entity = $this->get('security.context')->getToken()->getUser();

        $noticia= $em->getRepository('ColeBundle:Noticias')->findOneById($num);
     
        $imagenes = array();
        if($noticia->getGaleria()!=null) {
            $photoDir = $this->container->getParameter('kernel.root_dir').'/../web/uploads/noticias/galeria/'.$noticia->getGaleria().'/';
            
            foreach (glob($photoDir."*.*") as $nombre_dir) {
                $nombre_dir = explode("/", $nombre_dir);
                $nombre_dir =end($nombre_dir);
                $imagenes[]=$nombre_dir;
            }
        }

        //Se elimina el id de la noticia mostrada del string en el campo NoticiasNuevas del profesor.
        $string=$entity->getNoticiasNuevas();
        $new_string= str_replace("|".$num."|", "|", $string);
        $entity->setNoticiasNuevas($new_string);
        $em->persist($entity);
        $em->flush();

        return $this->render('IntranetBundle:Profesor:noticia.html.twig', array(
            'entity' => $entity, 
            'noticia'=> $noticia,
            'imagenes' => $imagenes));
    }



    public function comprobarNoticiasNuevasAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity= $em->getRepository('BackendBundle:Profesor')->findOneById($id);
        $num=0;

        if($entity->getAccesoNoticias()){
            $noticias =$em->getRepository('ColeBundle:Noticias')->findNuevasNoticias($entity->getAccesoNoticias(),"profesores");
        }
        else{
            $noticias= $em->getRepository('ColeBundle:Noticias')->findBy(array('categoria'=>'profesores'), array('fecha'=>'DESC'));
        }

        //Si hay noticias nuevas se añade los id en el campo NoticiasNuevas del profesor.
        if($noticias){
            if($entity->getNoticiasNuevas()){
                $string=$entity->getNoticiasNuevas();
            }
            else{
                $string="|";
            }
                
            foreach($noticias as $noticia){
                $string=$string.$noticia->getId()."|";
            }
            //Se actualiza la fecha del último acceso a noticias.
            $entity->setAccesoNoticias(new \DateTime("now"));
            $entity->setNoticiasNuevas($string);
            $em->persist($entity);
            $em->flush();
        }
        //Se obtiene el número de noticias nuevas mediante los id del campo NoticiasNuevas del alumno.
        $noticias_nuevas=$entity->getNoticiasNuevas();
        $id_noticias = explode("|", $noticias_nuevas);

        foreach($id_noticias as $noticia){
            if($noticia!=""){
                $num++;
            }
        }   

        return new JsonResponse(array(
            'num'=> $num,
            'id' => $id,
            'success' => true), 200);
    }



}