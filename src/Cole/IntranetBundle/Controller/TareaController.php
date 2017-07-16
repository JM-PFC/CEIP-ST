<?php

namespace Cole\IntranetBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Cole\IntranetBundle\Entity\Cursa;

use Cole\IntranetBundle\Entity\Tarea;
use Cole\IntranetBundle\Form\TareaType;

/**
 * Tarea controller.
 *
 */
class TareaController extends Controller
{

    /**
     * Lists all Tarea entities.
     *
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $entities = $em->getRepository('IntranetBundle:Tarea')->findAll();

        return $this->render('IntranetBundle:Tarea:index.html.twig', array(
            'entities' => $entities,
        ));
    }
    /**
     * Creates a new Tarea entity.
     *
     */
    public function createAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $profesor = $this->get('security.context')->getToken()->getUser();

        $entity = new Tarea();
        $form = $this->createCreateForm($entity);
        $form->handleRequest($request);

        if ($form->isValid()) {
            //Se añade al formulario una variable extra de tipo entity con checkboxes para seleccionar.
            $extra = $form->get('seleccion')->getData();
            //Se convierte el ArrayCollection en un array normal.
            $arr = $extra->toArray();
            
            $contador=0;
            $grupos=0;
            $array="";

            //Para cada checkbox marcado se crea una nueva reserva y se guarda con el resto de valores seleccionado.
            foreach ($arr as $idgrupo) {
                $grupo = $em->getRepository('BackendBundle:Grupo')->findOneById($idgrupo);
                $grupos++;
                //Se obtiene la asignatura del curso, ya que en el formulario hay varios grupos para una asignaturacon un solo id.
                $asignatura=$em->getRepository('BackendBundle:AsignaturasCursos')->findAsignacionNombre($grupo->getCurso(), $entity->getAsignatura()->getAsignatura()->getNombre()); 


                $tarea = $em->getRepository('IntranetBundle:Tarea')->findTareaByProfesorGrupo($entity->getDescripcion(),$profesor,$grupo, $asignatura);
            

                if($tarea){
                    $array=$array.$grupo->getCurso()->getCurso().$grupo->getLetra()." - ";
                }
                else{
                    $ent = new Tarea();
                    $form = $this->createCreateForm($ent);
                    $form->handleRequest($request);
                    $trimestre= $form->get('trimestre')->getData();

                    $ent->setGrupo($grupo);       
                    $ent->setProfesor($profesor);

                    $ent->setTrimestre($trimestre);

                    //Se obtiene la asignatura del curso, ya que en el formulario hay varios grupos para una asignaturacon un solo id.
                    //$asignatura=$em->getRepository('BackendBundle:AsignaturasCursos')->findAsignacionNombre($grupo->getCurso(), $ent->getAsignatura()->getAsignatura()->getNombre()); 
                    $ent->setAsignatura($asignatura);  
                    $ent->setFecha(new \DateTime("now"));


                    //Se obtiene la lista de alumnos comprobando si es una asignatura opcional o no.
                    if( $entity->getAsignatura()->getAsignatura()->getOpcional() == 0){
                        $alumnos= $em->getRepository('BackendBundle:Alumno')->findByGrupo($grupo);
                    }
                    else{
                        $alumnos= $em->getRepository('BackendBundle:Alumno')->findAlumnosOptativaGrupo($entity->getAsignatura(),$grupo);
                    }

                    foreach ($alumnos as $alumno) {
                        $entidad = new Cursa();
                        $entidad->setNota(null);
                        $entidad->setAlumno($alumno);
                        $entidad->setAsignaturasCursos($entity->getAsignatura());
                        $entidad->setTarea($ent);
                        $ent->getCursa()->add($entidad);
                        $em->persist($entidad);

                    }

                    $em->persist($ent);
                    $em->flush();

                    $contador++;
                }

               /* 
                //Se obtiene la tarea creada para añadirsela al alumno en la relación "cursa".
                $ultimaTarea = $em->getRepository('IntranetBundle:Tarea')->findUltimaTarea($profesor);

                $trimestre= $form->get('trimestre')->getData();
                //Se obtiene la lista de alumnos comprobando si es una asignatura opcional o no.
                if( $ultimaTarea->getAsignatura()->getAsignatura()->getOpcional() == 0){
                    $alumnos= $em->getRepository('BackendBundle:Alumno')->findByGrupo($grupo);
                }
                else{
                    $alumnos= $em->getRepository('BackendBundle:Alumno')->findAlumnosOptativaGrupo($ultimaTarea->getAsignatura(),$grupo);
                }

                foreach ($alumnos as $alumno) {
                    $entidad = new Cursa();
                    $entidad->setTrimestre($trimestre);
                    $entidad->setNota(null);
                    $entidad->setAlumno($alumno);
                    $entidad->setAsignaturasCursos($ultimaTarea->getAsignatura());
                    $entidad->setTarea($ultimaTarea);
                    $em->persist($entidad);
                    $em->flush();
                }
                */
            }

            $cursos_denegados = trim($array, ' - ');
            if($contador>0){
                if($contador>1){
                    $ms = $this->get('translator')->trans('Se ha registrado la tarea en %$contador% grupos correctamente.',array('%$contador%' =>$contador ));
                }
                else{
                    $ms = $this->get('translator')->trans('Se ha registrado la tarea en el grupo correctamente.');
                }
                $this->get('session')->getFlashBag()->add('notice',$ms);
            }

            $ms_danger="";

            if($cursos_denegados && $cursos_denegados != ""){
                if(((int)substr_count($cursos_denegados, "-")+(int)1)>1){
                    $ms_danger = $this->get('translator')->trans('Los grupos %$cursos_denegado% ya dispone de esta tarea.',array('%$cursos_denegado%' =>$cursos_denegados ));
                }
                else{
                    $ms_danger = $this->get('translator')->trans('El grupo %$cursos_denegado% ya dispone de esta tarea.',array('%$cursos_denegado%' =>$cursos_denegados ));
                }
                $this->get('session')->getFlashBag()->add('danger',$ms_danger);
            }

            return $this->redirect($this->generateUrl('intranet_profesor_calificaciones'));
        }

        return $this->render('intranet_profesor_calificaciones', array(
            'entity' => $entity,
            'form'   => $form->createView(),
        ));
    }





    /**
     * Creates a form to create a Tarea entity.
     *
     * @param Tarea $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createCreateForm(Tarea $entity)
    {
        $form = $this->createForm(new TareaType(), $entity, array(
            'action' => $this->generateUrl('tarea_create'),
            'method' => 'POST',
        ));

        $form->add('submit', 'submit', array('label' => 'Create'));

        return $form;
    }

    /**
     * Displays a form to create a new Tarea entity.
     *
     */
    public function newAction()
    {
        $entity = new Tarea();
        $form   = $this->createCreateForm($entity);

        return $this->render('IntranetBundle:Tarea:new.html.twig', array(
            'entity' => $entity,
            'form'   => $form->createView(),
        ));
    }

    /**
     * Finds and displays a Tarea entity.
     *
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('IntranetBundle:Tarea')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Tarea entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return $this->render('IntranetBundle:Tarea:show.html.twig', array(
            'entity'      => $entity,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing Tarea entity.
     *
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('IntranetBundle:Tarea')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Tarea entity.');
        }

        $editForm = $this->createEditForm($entity);
        $deleteForm = $this->createDeleteForm($id);

        return $this->render('IntranetBundle:Tarea:edit.html.twig', array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    public function eliminarTareaAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('IntranetBundle:Tarea')->find($id);
        $asignatura=$entity->getAsignatura();
        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Seguimiento entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return $this->render('IntranetBundle:Tarea:eliminarTarea.html.twig', array(
            'entity'      => $entity,
            'delete_form' => $deleteForm->createView(),
            'asignatura' => $asignatura
        ));
    }




    /**
    * Creates a form to edit a Tarea entity.
    *
    * @param Tarea $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createEditForm(Tarea $entity)
    {
        $form = $this->createForm(new TareaType(), $entity, array(
            'action' => $this->generateUrl('tarea_update', array('id' => $entity->getId())),
            'method' => 'PUT',
        ));
        $titulo=$this->get("translator")->trans("Guardar");
        $form->add('submit', 'submit', array('label' => $titulo, 'attr' => array('class' => 'btn btn-success')));
        
        $form->add('submit', 'submit', array('label' => 'Guardar'));

        return $form;
    }


    private function createEditNotasForm(Tarea $entity)
    {
        $form = $this->createForm(new TareaType(), $entity, array(
            'action' => $this->generateUrl('notas_update', array('id' => $entity->getId(), 'tipo' => "tarea")),
            'method' => 'PUT',
        ));
        $titulo=$this->get("translator")->trans("Guardar");
        $form->add('submit', 'submit', array('label' => $titulo, 'attr' => array('class' => 'btn btn-success')));

        return $form;
    }

    private function createEditNotasTrimestreForm(Tarea $entity)
    {
        $form = $this->createForm(new TareaType(), $entity, array(
            'action' => $this->generateUrl('notas_update', array('id' => $entity->getId(), 'tipo' => "trimestre")),
            'method' => 'PUT',
        ));
        $titulo=$this->get("translator")->trans("Guardar");
        $form->add('submit', 'submit', array('label' => $titulo, 'attr' => array('class' => 'btn btn-success')));

        return $form;
    }
    /**
     * Edits an existing Tarea entity.
     *
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('IntranetBundle:Tarea')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Tarea entity.');
        }

        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createEditForm($entity);
        $editForm->handleRequest($request);

        if ($editForm->isValid()) {
            $em->flush();

            return $this->redirect($this->generateUrl('tarea_edit', array('id' => $id)));
        }

        return $this->render('IntranetBundle:Tarea:edit.html.twig', array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }


    public function updateNotasAction(Request $request, $id, $tipo)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('IntranetBundle:Tarea')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Tarea entity.');
        }

        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createEditForm($entity);
        $editForm->handleRequest($request);

        if ($editForm->isValid()) {
            $em->flush();

            $ms = $this->get('translator')->trans('Las calificaciones han sido guardadas correctamente.');
            $this->get('session')->getFlashBag()->add('notice',$ms);

            if($tipo=="tarea"){
                return $this->redirect($this->generateUrl('evaluar_asignatura', array('id' => $entity->getGrupo()->getId(), 'asig' => $entity->getAsignatura()->getAsignatura()->getId() )));
            }
            else{
                return $this->redirect($this->generateUrl('intranet_profesor_calificaciones'));
            }
        }

        return $this->render('IntranetBundle:Profesor:evaluar_tarea.html.twig', array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    public function evaluarAsignaturaAction(Request $request, $id, $asig)
    {
        $em = $this->getDoctrine()->getManager();
        $entity = $this->get('security.context')->getToken()->getUser();
        $grupo= $em->getRepository('BackendBundle:Grupo')->findOneById($id);
        $asig= $em->getRepository('BackendBundle:Asignatura')->findOneById($asig);

        $asignatura = $em->getRepository('BackendBundle:AsignaturasCursos')->findAsignacion($grupo->getCurso(),$asig);

        $tareas = $em->getRepository('IntranetBundle:Tarea')->findTareasGrupoAsignatura($grupo, $asignatura);
        $tareas_evaluadas = $em->getRepository('IntranetBundle:Cursa')->findTareasEvaluadas($grupo, $asignatura);
        $tareas_noEvaluadas = $em->getRepository('IntranetBundle:Cursa')->findTareasNoEvaluadas($grupo, $asignatura);

        // Se obtiene la fecha inicial y final del curso para usar luego el año correspondiente. 
        $ini_curso=$em->getRepository('BackendBundle:Centro')->findInicioCurso();
        $array_ini=explode("-",$ini_curso["inicioCurso"]->format('Y-m-d')); //Conversión de array a String
        $fin_curso=$em->getRepository('BackendBundle:Centro')->findFinCurso();
        $array_fin=explode("-",$fin_curso["finCurso"]->format('Y-m-d'));

        // Se obtiene las fechas de inicio de las vacaciones.
        $ini_nav=$em->getRepository('BackendBundle:Festivos')->findFestivosPorDescripcion("Inicio Vacaciones de Navidad");
        $ini_ss=$em->getRepository('BackendBundle:Festivos')->findFestivosPorDescripcion("Inicio Vacaciones de Semana Santa");
        
        $Fecha_ini_nav = date('Y-m-d', strtotime($array_ini[0]."-".$ini_nav->getNumMes()."-".$ini_nav->getDia()));
        $Fecha_ini_ss = date('Y-m-d', strtotime($array_fin[0]."-".$ini_ss->getNumMes()."-".$ini_ss->getDia()));

        $hoy=date("Y-m-d");

        if ($hoy <= $Fecha_ini_nav){
            $trimestre=1;
        }
        else if($hoy >= $Fecha_ini_ss){
            $trimestre=3;
        }
        else{
            $trimestre=2;
        }

        return $this->render('IntranetBundle:Profesor:evaluar_asignatura.html.twig', array(
            'entity' => $entity,
            'grupo' => $grupo,
            'asignatura' => $asig,
            'tareas_evaluadas' => $tareas_evaluadas,
            'tareas_noEvaluadas' => $tareas_noEvaluadas,
            'trimestre'=>$trimestre
        ));
    }


    public function evaluarTareaAction(Request $request, $id, $asig, $tarea)
    {
        $em = $this->getDoctrine()->getManager();
        $entity = $this->get('security.context')->getToken()->getUser();
        $grupo= $em->getRepository('BackendBundle:Grupo')->findOneById($id);
        $asig= $em->getRepository('BackendBundle:Asignatura')->findOneById($asig);

        $asignatura = $em->getRepository('BackendBundle:AsignaturasCursos')->findAsignacion($grupo->getCurso(),$asig);
        $task = $em->getRepository('IntranetBundle:Tarea')->findOneById($tarea);
        $alumnos=$em->getRepository('IntranetBundle:Cursa')->findOneByTarea($task);
        $tareas_evaluadas = $em->getRepository('IntranetBundle:Cursa')->findTareasEvaluadas($grupo, $asignatura);
        $tareas_noEvaluadas = $em->getRepository('IntranetBundle:Cursa')->findTareasNoEvaluadas($grupo, $asignatura);

        // Se obtiene la fecha inicial y final del curso para usar luego el año correspondiente. 
        $ini_curso=$em->getRepository('BackendBundle:Centro')->findInicioCurso();
        $array_ini=explode("-",$ini_curso["inicioCurso"]->format('Y-m-d')); //Conversión de array a String
        $fin_curso=$em->getRepository('BackendBundle:Centro')->findFinCurso();
        $array_fin=explode("-",$fin_curso["finCurso"]->format('Y-m-d'));

        // Se obtiene las fechas de inicio de las vacaciones.
        $ini_nav=$em->getRepository('BackendBundle:Festivos')->findFestivosPorDescripcion("Inicio Vacaciones de Navidad");
        $ini_ss=$em->getRepository('BackendBundle:Festivos')->findFestivosPorDescripcion("Inicio Vacaciones de Semana Santa");
        
        $Fecha_ini_nav = date('Y-m-d', strtotime($array_ini[0]."-".$ini_nav->getNumMes()."-".$ini_nav->getDia()));
        $Fecha_ini_ss = date('Y-m-d', strtotime($array_fin[0]."-".$ini_ss->getNumMes()."-".$ini_ss->getDia()));

        $hoy=date("Y-m-d");

        if ($hoy <= $Fecha_ini_nav){
            $trimestre=1;
        }
        else if($hoy >= $Fecha_ini_ss){
            $trimestre=3;
        }
        else{
            $trimestre=2;
        }

        $editForm = $this->createEditNotasForm($task);

        return $this->render('IntranetBundle:Profesor:evaluar_tarea.html.twig', array(
            'entity' => $entity,
            'grupo' => $grupo,
            'asignatura' => $asig,
            'tareas_evaluadas' => $tareas_evaluadas,
            'tareas_noEvaluadas' => $tareas_noEvaluadas,
            'trimestre'=>$trimestre,
            'edit_form'   => $editForm->createView(),
            'alumnos' => $alumnos,
            'tarea' => $tarea
        ));
    }


    public function evaluarTrimestreAction(Request $request, $id, $asig)
    {
        $em = $this->getDoctrine()->getManager();
        $entity = $this->get('security.context')->getToken()->getUser();
        $grupo= $em->getRepository('BackendBundle:Grupo')->findOneById($id);
        $asig= $em->getRepository('BackendBundle:Asignatura')->findOneById($asig);

        $asignatura = $em->getRepository('BackendBundle:AsignaturasCursos')->findAsignacion($grupo->getCurso(),$asig);

        // Se obtiene la fecha inicial y final del curso para usar luego el año correspondiente. 
        $ini_curso=$em->getRepository('BackendBundle:Centro')->findInicioCurso();
        $array_ini=explode("-",$ini_curso["inicioCurso"]->format('Y-m-d')); //Conversión de array a String
        $fin_curso=$em->getRepository('BackendBundle:Centro')->findFinCurso();
        $array_fin=explode("-",$fin_curso["finCurso"]->format('Y-m-d'));

        // Se obtiene las fechas de inicio de las vacaciones.
        $ini_nav=$em->getRepository('BackendBundle:Festivos')->findFestivosPorDescripcion("Inicio Vacaciones de Navidad");
        $ini_ss=$em->getRepository('BackendBundle:Festivos')->findFestivosPorDescripcion("Inicio Vacaciones de Semana Santa");
        
        $Fecha_ini_nav = date('Y-m-d', strtotime($array_ini[0]."-".$ini_nav->getNumMes()."-".$ini_nav->getDia()));
        $Fecha_ini_ss = date('Y-m-d', strtotime($array_fin[0]."-".$ini_ss->getNumMes()."-".$ini_ss->getDia()));

        $hoy=date("Y-m-d");

        if ($hoy <= $Fecha_ini_nav){
            $trimestre=1;
        }
        else if($hoy >= $Fecha_ini_ss){
            $trimestre=3;
        }
        else{
            $trimestre=2;
        }

        $tarea = $em->getRepository('IntranetBundle:Tarea')->findFinalTrimestre($grupo, $asignatura, $trimestre);

        //Se obtiene la lista de alumnos comprobando si es una asignatura opcional o no.
        if( $asignatura->getAsignatura()->getOpcional() == 0){
            $alumnos= $em->getRepository('BackendBundle:Alumno')->findByGrupo($grupo);
        }
        else{
            $alumnos= $em->getRepository('BackendBundle:Alumno')->findAlumnosOptativaGrupo($asignatura,$grupo);
        }

        //Se comprueba si existe el registro de evaluación del trimestre en el sistema para crearlo.
        if(!$tarea){

            $tarea = new Tarea();
            $tarea->setGrupo($grupo);       
            $tarea->setProfesor($entity);

            $tarea->setTrimestre($trimestre);
            $tarea->setAsignatura($asignatura);  
            $tarea->setFecha(new \DateTime("now"));
            $tarea->setDescripcion("Evaluación_Trimestral");

            foreach ($alumnos as $alumno) {
                $entidad = new Cursa();
                $entidad->setNota(null);
                $entidad->setAlumno($alumno);
                $entidad->setAsignaturasCursos($asignatura);
                $entidad->setTarea($tarea);
                $tarea->getCursa()->add($entidad);
                $em->persist($entidad);
            }
                    
            $em->persist($tarea);
            $em->flush();
        }
        else{
            //Obtenermos los registros asignados a la evaluación del trimestre.
            $alumnos_cursa = $em->getRepository('IntranetBundle:Cursa')->findByTarea($tarea);

            //Se comprueba si todos los alumnos tienen el registro de evaluación del trimestre. En caso contrario se crea para los alumnos que no lo tengan.
            if(count($alumnos_cursa)!=count($alumnos)){
                foreach ($alumnos as $alumno) {
                    $cursa= $em->getRepository('IntranetBundle:Cursa')->findByAlumnoTrimestre($alumno, $trimestre);
                    if(!$cursa){
                        $entidad = new Cursa();
                        $entidad->setNota(null);
                        $entidad->setAlumno($alumno);
                        $entidad->setAsignaturasCursos($asignatura);
                        $entidad->setTarea($tarea);
                        $tarea->getCursa()->add($entidad);
                        $em->persist($entidad);
                        $em->persist($tarea);
                        $em->flush();
                    }
                }
            }
        }
        $editForm = $this->createEditNotasTrimestreForm($tarea);

        $trimestre1= $em->getRepository('IntranetBundle:Cursa')->findByAsignacionesTrimestre($grupo, $asignatura,1);
        $trimestre2= $em->getRepository('IntranetBundle:Cursa')->findByAsignacionesTrimestre($grupo, $asignatura,2);
        $trimestre3= $em->getRepository('IntranetBundle:Cursa')->findByAsignacionesTrimestre($grupo, $asignatura,3);

        $array1=[];
        foreach($trimestre1 as $registro){

            //Se calcula la media de todas las tareas del trimestre.
            $asignaciones=$em->getRepository('IntranetBundle:Cursa')->findByTareasAlumnoTrimestre($registro->getAlumno(),1);
            $contador=0;
            $media=0;
            foreach($asignaciones as $registro){
                $media=$media+(int)$registro->getNota();
                $contador++;
            }
            //Se redondea el valor.
            if($media==0 || $contador==0 )
                $media = 0;
            else
                $media=round($media/$contador);

            if($registro->getNota()){
                $nota=$registro->getNota();
            }
            else{
                $nota="";
            }
            $array1[$registro->getAlumno()->getId()."-".$media]=$nota;
        }

        $array2=[];
        foreach($trimestre2 as $registro){

            //Se calcula la media de todas las tareas del trimestre.
            $asignaciones=$em->getRepository('IntranetBundle:Cursa')->findByTareasAlumnoTrimestre($registro->getAlumno(),2);
            $contador=0;
            $media=0;
            foreach($asignaciones as $registro){
                $media=$media+(int)$registro->getNota();
                $contador++;
            }

            //Se redondea el valor.
            if($media==0 || $contador==0 )
                $media = 0;
            else
                $media=round($media/$contador);

            if($registro->getNota()){
                $nota=$registro->getNota();
            }
            else{
                $nota="";
            }
            $array2[$registro->getAlumno()->getId()."-".$media]=$nota;
        }

        $array3=[];
        foreach($trimestre3 as $registro){

            //Se calcula la media de todas las tareas del trimestre.
            $asignaciones=$em->getRepository('IntranetBundle:Cursa')->findByTareasAlumnoTrimestre($registro->getAlumno(),3);
            $contador=0;
            $media=0;
            foreach($asignaciones as $registro){
                $media=$media+(int)$registro->getNota();
                $contador++;
            }
            //Se redondea el valor.
            if($media==0 || $contador==0 )
                $media = 0;
            else
                $media=round($media/$contador);

            $array3[$registro->getAlumno()->getId()]=$media;
        }

        return $this->render('IntranetBundle:Profesor:evaluar_trimestre.html.twig', array(
            'entity' => $entity,
            'grupo' => $grupo,
            'asignatura' => $asig,
            'asignaturaCurso' => $asignatura,
            'edit_form'   => $editForm->createView(),
            'trimestre'=>$trimestre,
            'alumnos' => $alumnos,
            'trimestre1' => $array1,
            'trimestre2' => $array2, 
            'trimestre3' => $array3  
 
        ));
    }

    /**
     * Deletes a Tarea entity.
     *
     */
    public function deleteAction(Request $request, $id)
    {
        $form = $this->createDeleteForm($id);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('IntranetBundle:Tarea')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find Tarea entity.');
            }

            $em->remove($entity);
            $em->flush();
        }
        $ms = $this->get('translator')->trans('Se ha eliminado la tarea correctamente.');
        $this->get('session')->getFlashBag()->add('notice',$ms);
        return $this->redirect($this->generateUrl('intranet_profesor_calificaciones'));
    }

    /**
     * Creates a form to delete a Tarea entity by id.
     *
     * @param mixed $id The entity id
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm($id)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('tarea_delete', array('id' => $id)))
            ->setMethod('DELETE')
            ->add('submit', 'submit', array('label' => "Eliminar", 'attr' => array('class' => 'btn btn-danger')))
            ->getForm()
        ;
    }
}
