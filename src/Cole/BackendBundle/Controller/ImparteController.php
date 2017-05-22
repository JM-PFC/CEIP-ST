<?php

namespace Cole\BackendBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;

use Cole\BackendBundle\Entity\Imparte;
use Cole\BackendBundle\Form\ImparteType;

/**
 * Imparte controller.
 *
 */
class ImparteController extends Controller
{

    /**
     * Lists all Imparte entities.
     *
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $entities = $em->getRepository('BackendBundle:Imparte')->findAll();

        return $this->render('BackendBundle:Imparte:index.html.twig', array(
            'entities' => $entities,
        ));
    }

    public function AsignarGrupoProfesoresShowAction()
    {
        $em = $this->getDoctrine()->getManager();

        $asignaturas= $em->getRepository('BackendBundle:Asignatura')->findNumAsignaturas();
        $profesores= $em->getRepository('BackendBundle:Profesor')->findByActivo(1);
        if (!$profesores) {
            $numProfesores=0;
        }
        else{
            $numProfesores=1;
        }

        $entities = $em->getRepository('BackendBundle:Grupo')->findGruposByNivel("Primaria");
        return $this->render('BackendBundle:Imparte:profesor_asignaturas_grupos_show.html.twig', array(
            'entities' => $entities,
            'numAsignaturas' => (int)$asignaturas[1],
            'numProfesores' => $numProfesores,
        ));
    }
    public function AsignarGrupoProfesoresNewAction($id)
    {
        $em = $this->getDoctrine()->getManager();
        $grupo= $em->getRepository('BackendBundle:Grupo')->findOneById($id);
        $curso= $grupo->getCurso();

        $entities_troncales = $em->getRepository('BackendBundle:AsignaturasCursos')->findAsignaturasTroncalesCurso($curso->getId());
        $entities_especificas = $em->getRepository('BackendBundle:AsignaturasCursos')->findAsignaturasEspecificasNoOpcionalesCurso($curso->getId());
        $entities_especificas_opcionales = $em->getRepository('BackendBundle:AsignaturasCursos')->findAsignaturasEspecificasOpcionalesCurso($curso->getId());

        $imparte = $em->getRepository('BackendBundle:Imparte')->findByGrupo($id);
        
        $array=[];
        foreach($imparte as $registro){
            if($registro->getAula()){
                $array[$registro->getAsignatura()->getId()."-".$registro->getProfesor()->getId()."-".$registro->getAula()->getId()]=$registro->getProfesor()->getNombre()." ".$registro->getProfesor()->getApellido1()." ".$registro->getProfesor()->getApellido2();
            }
            else{
                $array[$registro->getAsignatura()->getId()."-".$registro->getProfesor()->getId()."-"]=$registro->getProfesor()->getNombre()." ".$registro->getProfesor()->getApellido1()." ".$registro->getProfesor()->getApellido2();

            }
        }
        $tutor=$grupo->getProfesor();
        if($tutor){
            $id_tutor=$tutor->getId();   
        }
        else{
            $id_tutor=null; 
        }

        $profesores = $em->getRepository('BackendBundle:Profesor')->findProfesoresDePrimaria();


        return $this->render('BackendBundle:Imparte:new_profesores_asignaturas_grupo.html.twig', array(
            'entities_troncales' => $entities_troncales,
            'entities_especificas' => $entities_especificas,
            'entities_especificas_opcionales' => $entities_especificas_opcionales,
            'curso' => $curso,
            'grupo' => $grupo,
            'imparte' => $array,
            'tutor' => $tutor,
            'id_tutor'=>$id_tutor,
            'profesores' => $profesores
        ));
    }


    /**
     * Creates a new Imparte entity.
     *
     */
    public function createAction(Request $request)
    {
        $entity = new Imparte();
        $form = $this->createCreateForm($entity);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('imparte_show', array('id' => $entity->getId())));
        }

        return $this->render('BackendBundle:Imparte:new.html.twig', array(
            'entity' => $entity,
            'form'   => $form->createView(),
        ));
    }

    /**
     * Creates a form to create a Imparte entity.
     *
     * @param Imparte $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createCreateForm(Imparte $entity)
    {
        $form = $this->createForm(new ImparteType(), $entity, array(
            'action' => $this->generateUrl('imparte_create'),
            'method' => 'POST',
        ));

        $form->add('submit', 'submit', array('label' => 'Create'));

        return $form;
    }

    /**
     * Displays a form to create a new Imparte entity.
     *
     */
    public function newAction()
    {
        $entity = new Imparte();
        $form   = $this->createCreateForm($entity);

        return $this->render('BackendBundle:Imparte:new.html.twig', array(
            'entity' => $entity,
            'form'   => $form->createView(),
        ));
    }

    /**
     * Finds and displays a Imparte entity.
     *
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('BackendBundle:Imparte')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Imparte entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return $this->render('BackendBundle:Imparte:show.html.twig', array(
            'entity'      => $entity,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing Imparte entity.
     *
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('BackendBundle:Imparte')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Imparte entity.');
        }

        $editForm = $this->createEditForm($entity);
        $deleteForm = $this->createDeleteForm($id);

        return $this->render('BackendBundle:Imparte:edit.html.twig', array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
    * Creates a form to edit a Imparte entity.
    *
    * @param Imparte $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createEditForm(Imparte $entity)
    {
        $form = $this->createForm(new ImparteType(), $entity, array(
            'action' => $this->generateUrl('imparte_update', array('id' => $entity->getId())),
            'method' => 'PUT',
        ));

        $form->add('submit', 'submit', array('label' => 'Update'));

        return $form;
    }
    /**
     * Edits an existing Imparte entity.
     *
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('BackendBundle:Imparte')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Imparte entity.');
        }

        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createEditForm($entity);
        $editForm->handleRequest($request);

        if ($editForm->isValid()) {
            $em->flush();

            return $this->redirect($this->generateUrl('imparte_edit', array('id' => $id)));
        }

        return $this->render('BackendBundle:Imparte:edit.html.twig', array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }
    /**
     * Deletes a Imparte entity.
     *
     */
    public function deleteAction(Request $request, $id)
    {
        $form = $this->createDeleteForm($id);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('BackendBundle:Imparte')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find Imparte entity.');
            }

            $em->remove($entity);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('imparte'));
    }

    /**
     * Creates a form to delete a Imparte entity by id.
     *
     * @param mixed $id The entity id
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm($id)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('imparte_delete', array('id' => $id)))
            ->setMethod('DELETE')
            ->add('submit', 'submit', array('label' => 'Delete'))
            ->getForm()
        ;
    }

    public function DatosImparteAction() 
    {
        $dia_semanal=$this->get('request')->request->get('dia_semanal');
        $ini=$this->get('request')->request->get('ini');
        $fin=$this->get('request')->request->get('fin');
        $profesor=$this->get('request')->request->get('profesor');

        $em = $this->getDoctrine()->getEntityManager();

        $datos= $em->getRepository('BackendBundle:Imparte')->findByDatos($dia_semanal,$ini,$fin,$profesor);

        if($datos){
            return new JsonResponse(array('data' =>"existe",
                'nivel'=>$datos->getGrupo()->getCurso()->getNivel(),
                'curso'=>$datos->getGrupo()->getCurso()->getCurso(),
                'grupo' =>$datos->getGrupo()->getLetra(),
                'asignatura'=>$datos->getAsignatura()->getNombre()), 200);
        }
        else{
            return new JsonResponse(array('data' =>null), 200);
        }
    }

    public function ProfesoresAsignaturasGruposAction($id)
    {
        $em = $this->getDoctrine()->getManager();
        $grupo = $em->getRepository('BackendBundle:Grupo')->findOneById($id);
        $entity = $em->getRepository('BackendBundle:AsignaturasCursos')->findAsignaturasCursos($grupo->getCurso()->getId());
        $imparte = $em->getRepository('BackendBundle:Imparte')->findByGrupo($id);
        
        $array=[];
        foreach($imparte as $registro){
            if($registro->getAula()){
            $array[$registro->getGrupo()->getId()."-".$registro->getAsignatura()->getId()."-".$registro->getAula()->getNombre()]=$registro->getProfesor()->getNombre()." ".$registro->getProfesor()->getApellido1()." ".$registro->getProfesor()->getApellido2();
            }
            else{
            $array[$registro->getGrupo()->getId()."-".$registro->getAsignatura()->getId()."-"]=$registro->getProfesor()->getNombre()." ".$registro->getProfesor()->getApellido1()." ".$registro->getProfesor()->getApellido2();
            }
        }

        return $this->render('BackendBundle:Imparte:datos_imparte.html.twig', array(
            'imparte' => $array,
            'entity' => $entity));
    }


    public function AsignarGrupoProfesoresAction()
    {
        $em = $this->getDoctrine()->getManager();

        $asignaciones=$this->get('request')->request->get('asignaciones');
        $eliminados=$this->get('request')->request->get('eliminados');
        $idgrupo=$this->get('request')->request->get('grupo');
        $num_asig=0;
        $num_elim=0;
        $num_actu=0;

        $data=1;
        if($asignaciones==null && $eliminados==null){
            $data=null;
            return new JsonResponse(array('data' => $data), 200);
        }

        if($eliminados){
          foreach ($eliminados as $asignatura ) {
            $asignatura = $em->getRepository('BackendBundle:AsignaturasCursos')->findOneById($asignatura);
            if (!$asignatura) {
                throw $this->createNotFoundException('Unable to find Asignatura entity.');
            }
            $grupo = $em->getRepository('BackendBundle:Grupo')->findOneById($idgrupo);
            if (!$grupo) {
                throw $this->createNotFoundException('Unable to find Grupo entity.');
            }
  
            $imparte = $em->getRepository('BackendBundle:Imparte')->findByGrupoAndAsignatura($grupo,$asignatura);
       
            foreach ($imparte as $imparte ){
                $em->remove($imparte);
            }
            $num_elim++;

            //Se comprueba si es una opcional, ya que si se actualiza una opcional hay que eliminar las asignaciones de horario de todas las opcionales.
            if($asignatura->getAsignatura()->getOpcional()==1){
                $imparte = $em->getRepository('BackendBundle:Imparte')->findAsignacionesOpcionales($grupo);
                foreach ($imparte as $imparte) {
                    $imparte->SetHorario(null);
                    $imparte->SetDiaSemanal(null);
                    $em->persist($imparte);
                    $em->flush();
                }
            }    
          }
          $em->flush();
        }

        $error=array();
        $error_opcional=array();
        $tipo_horario = $em->getRepository('BackendBundle:Horario')->findByTipo("manual");
        if(!$tipo_horario){//Horario Automático. Se valida las horas disponibles de los profesores.
            if($asignaciones){
              foreach ($asignaciones as $row ) { //$row[0]->ID asignatura  $row[1]->Id profesor $row[2]->Id aula.
                $profesor = $em->getRepository('BackendBundle:Profesor')->findOneById($row[1]);
                if (!$profesor) {
                    throw $this->createNotFoundException('Unable to find Profesor entity.');
                }
                $grupo = $em->getRepository('BackendBundle:Grupo')->findOneById($idgrupo);
                if (!$grupo) {
                    throw $this->createNotFoundException('Unable to find Grupo entity.');
                }
                $aula = $em->getRepository('BackendBundle:Equipamiento')->findOneById($row[2]);
                if (!$aula && $row[2]==0) {
                    $aula=null;
                }
                $asignatura = $em->getRepository('BackendBundle:AsignaturasCursos')->findOneById($row[0]);
                if (!$asignatura) {
                    throw $this->createNotFoundException('Unable to find AsignaturasCursos entity.');
                }
                else{
                    $imparte= $em->getRepository('BackendBundle:Imparte')->findByGrupoAndAsignatura($grupo, $asignatura);
                }
                //Se comprueba si es una asignatura específica opcional y si el aula ya está asignada en otra específica opcional.
                $aula_asignada=$em->getRepository('BackendBundle:Imparte')->findAulaAsignada($aula, $grupo);
                if($asignatura->getAsignatura()->getOpcional()==1 && $aula_asignada){
                    $name=$profesor->getNombre()." ".$profesor->getApellido1()." ".$profesor->getApellido2();
                    $group=$grupo->getCurso()->getCurso()." ".$grupo->getLetra();
                    $error_opcional[] = array(array($name,$asignatura->getAsignatura()->getNombre(),$group));
                }
                else{
                    //Se valida las horas lectivas disponibles del profesor.
                    $horario=$em->getRepository('BackendBundle:Horario')->findDuracionHorarioAutomatico();
                    $duracion=$horario->getDuracion();
                         
                    $asignaciones=$em->getRepository('BackendBundle:Imparte')->findByProfesor($profesor);

                    $num_modulos=0;
                    foreach ($asignaciones as $asignacion) {
                        $num_modulos+=$asignacion->getAsignatura()->getNumModulos();
                    }
                    $tiempo_impartido=(int)$num_modulos*(int)$duracion;//Se usas [1] para recuerar el valor del count().
                    $tiempo_asignado=$profesor->getHorasLectivas()*60;
                    $tiempo_restante=(int)$tiempo_asignado-(int)$tiempo_impartido;
                    //Se comprueba que el tiempo disponible del profesor sea inferior al tiempo del número de módulos de esa asignatura.
                    //Además se comprueba si es una asignación nueva o en caso de actualización si se ha modificado el profesor para comprobar las horas del profesor.
                    if((!$imparte || ($imparte && $imparte[1]->getProfesor()!=$profesor )) && ($tiempo_restante<((int)$asignatura->getNumModulos()*(int)$duracion))){
                        $name=$profesor->getNombre()." ".$profesor->getApellido1()." ".$profesor->getApellido2();
                        $group=$grupo->getCurso()->getCurso()." ".$grupo->getLetra();
                        $error[] = array(array($name,$asignatura->getAsignatura()->getNombre(),$group));
                    }
                    else{
                        if ($imparte) {

                          foreach ($imparte as $imparte) {

                            $imparte->setProfesor($profesor);
                            $imparte->setAsignatura($asignatura);
                            if($aula!=null){
                                $imparte->setAula($aula);    
                            }
                            else{
                                $imparte->setAula(null);     
                            }

                            $imparte->SetHorario(null);//Se elimina la asignación de horario del profesor editado.
                            $imparte->SetDiaSemanal(null);//Se elimina la asignación del día de clase del profesor editado.
                            $em->persist($imparte);
                            $em->flush();
                          }
                          $num_actu++;

                          //Se comprueba si es una opcional, ya que si se actualiza una opcional hay que eliminar las asignaciones de horario de todas las opcionales.
                          if($asignatura->getAsignatura()->getOpcional()==1){
                            $imparte = $em->getRepository('BackendBundle:Imparte')->findAsignacionesOpcionales($grupo);
                            foreach ($imparte as $imparte) {
                                $imparte->SetHorario(null);
                                $imparte->SetDiaSemanal(null);
                                $em->persist($imparte);
                                $em->flush();
                            }
                          } 

                        }
                        //Se crea la asignación.
                        else{

                          for ($i = 1; $i <= $asignatura->getNumModulos(); $i++) {

                            $grupo = $em->getRepository('BackendBundle:Grupo')->findOneById($idgrupo);
                            if (!$grupo) {
                                throw $this->createNotFoundException('Unable to find Grupo entity.');
                            }

                            $imparte = new Imparte();
                            $imparte->setProfesor($profesor);
                            $imparte->setAsignatura($asignatura);
                            $imparte->setGrupo($grupo);
                            $imparte->setHorario(null);
                            $imparte->setDiaSemanal(null);
                            if($aula!=null){
                                $imparte->setAula($aula);    
                            }
                            else{
                                $imparte->setAula(null);     
                            }
                            $em->persist($imparte); 
                            $em->flush();
                          }
                          $num_asig++; 
                        }
                    }
                }
              }    
            }
        }
        // Horario Manual.
        else{//Se valida al asignar las asignaturas en el horario para calcular las horas de cada módulo al ser duracciones distintas.                     
            if($asignaciones){
              foreach ($asignaciones as $row ) { //$row[0]->ID asignatura  $row[1]->Id profesor $row[2]->Id aula.
                $profesor = $em->getRepository('BackendBundle:Profesor')->findOneById($row[1]);
                if (!$profesor) {
                    throw $this->createNotFoundException('Unable to find Profesor entity.');
                }

                $grupo = $em->getRepository('BackendBundle:Grupo')->findOneById($idgrupo);
                if (!$grupo) {
                    throw $this->createNotFoundException('Unable to find Grupo entity.');
                }
                $aula = $em->getRepository('BackendBundle:Equipamiento')->findOneById($row[2]);
                if (!$aula && $row[2]==0) {
                    $aula=null;
                }
                $asignatura = $em->getRepository('BackendBundle:AsignaturasCursos')->findOneById($row[0]);
                if (!$asignatura) {
                    throw $this->createNotFoundException('Unable to find AsignaturasCursos entity.');
                }
                else{
                    $imparte= $em->getRepository('BackendBundle:Imparte')->findByGrupoAndAsignatura($grupo, $asignatura);
                }

                //Se comprueba si es una asignatura específica opcional y si el aula ya está asignada en otra específica opcional.
                $aula_asignada=$em->getRepository('BackendBundle:Imparte')->findAulaAsignada($aula,$grupo);
                if($asignatura->getAsignatura()->getOpcional()==1 && $aula_asignada){
                    $name=$profesor->getNombre()." ".$profesor->getApellido1()." ".$profesor->getApellido2();
                    $group=$grupo->getCurso()->getCurso()." ".$grupo->getLetra();
                    $error_opcional[] = array(array($name,$asignatura->getAsignatura()->getNombre(),$group));
                }
                else{

                    if ($imparte) {
                        foreach ($imparte as $imparte) {
                            $imparte->setProfesor($profesor);
                            $imparte->setAsignatura($asignatura);
                            if($aula!=null){
                                $imparte->setAula($aula);    
                            }
                            else{
                                $imparte->setAula(null);     
                            }

                            $imparte->SetHorario(null);//Se elimina la asignación de horario del profesor editado.
                            $imparte->SetDiaSemanal(null);//Se elimina la asignación del día de clase del profesor editado.
                            $em->persist($imparte);
                            $em->flush();
                        }
                        $num_actu++; 

                        //Se comprueba si es una opcional, ya que si se actualiza una opcional hay que eliminar las asignaciones de horario de todas las opcionales.
                        if($asignatura->getAsignatura()->getOpcional()==1){
                            $imparte = $em->getRepository('BackendBundle:Imparte')->findAsignacionesOpcionales($grupo);
                            foreach ($imparte as $imparte) {
                                $imparte->SetHorario(null);
                                $imparte->SetDiaSemanal(null);
                                $em->persist($imparte);
                                $em->flush();
                            }
                        }
                    }
                    else{
                        $grupo = $em->getRepository('BackendBundle:Grupo')->findOneById($idgrupo);
                        if (!$grupo) {
                            throw $this->createNotFoundException('Unable to find Grupo entity.');
                        }
                        for ($i = 1; $i <= $asignatura->getNumModulos(); $i++) {

                            $imparte = new Imparte();
                            $imparte->setProfesor($profesor);
                            $imparte->setAsignatura($asignatura);
                            $imparte->setGrupo($grupo);
                            $imparte->setHorario(null);
                            $imparte->setDiaSemanal(null);
                            if($aula!=null){
                                $imparte->setAula($aula);    
                            }
                            else{
                                $imparte->setAula(null);     
                            }
                            $em->persist($imparte);
                            $em->flush();
                        }
                        $num_asig++;              
                    }
                }    
             }
            }
        }

        return new JsonResponse(array(
            'data' => $data, 
            'error' =>  $error,
            'error_opcional' =>  $error_opcional,
            'asignadas' =>  $num_asig,
            'eliminadas' =>  $num_elim,
            'actualizadas' =>  $num_actu,  
            'success' => true), 200);
    }


    public function ComprobarAsignacionesProfesoresAction()
    {
        $em = $this->getDoctrine()->getManager();

        $eliminados=$this->get('request')->request->get('eliminadas');

        $error=array();
        if($eliminados){
          foreach ($eliminados as $asignatura ) {
            $asignatura = $em->getRepository('BackendBundle:AsignaturasCursos')->findOneById($asignatura);
            if (!$asignatura) {
                throw $this->createNotFoundException('Unable to find Asignatura entity.');
            }

            $imparte = $em->getRepository('BackendBundle:Imparte')->findAsignaturasSinRepetir($asignatura);
            if ($imparte) {

                foreach ($imparte as $asignacion ) {
                    $profesor=$asignacion->getProfesor()->getNombre()." ".$asignacion->getProfesor()->getApellido1()." ".$asignacion->getProfesor()->getApellido2();
                    $asignatura=$asignacion->getAsignatura()->getAsignatura()->getNombre();
                    $grupo=$asignacion->getGrupo()->getCurso()->getCurso()." ".$asignacion->getGrupo()->getLetra();
                    $error[] = array(array($profesor,$asignatura,$grupo));
                }
            }
          }  
        }

        return new JsonResponse(array('error' => $error,'success' => true), 200);
    }

    public function EliminarAsignacionesGrupoAction()
    {
        $em = $this->getDoctrine()->getManager();

        $idgrupo=$this->get('request')->request->get('grupo');

        $grupo = $em->getRepository('BackendBundle:Grupo')->findOneById($idgrupo);
        if (!$grupo) {
            throw $this->createNotFoundException('Unable to find Grupo entity.');
        }

        $imparte= $em->getRepository('BackendBundle:Imparte')->findByGrupo($grupo);
        
        $data=1;
        if(!$imparte){
            $data=null;
            return new JsonResponse(array('data' => $data), 200);
        }
        else{
            foreach ($imparte as $asignacion ) {
                $em->remove($asignacion);
            }  
            $em->flush();
            return new JsonResponse(array('data' => $data,'success' => true), 200);
        }
    }

    public function EliminarTodasAsignacionesGruposAction()
    {
        $em = $this->getDoctrine()->getManager();

        $imparte= $em->getRepository('BackendBundle:Imparte')->findAll();
        
        $data=1;
        if(!$imparte){
            $data=null;
            return new JsonResponse(array('data' => $data), 200);
        }
        else{
            foreach ($imparte as $asignacion ) {
                $em->remove($asignacion);
            }  
            $em->flush();
            return new JsonResponse(array('data' => $data,'success' => true), 200);
        }
    }

    public function ObtenerModulosOcupadosAction()
    {
        $em = $this->getDoctrine()->getManager();
        $v_profesor=$this->get('request')->request->get('profesor');
        $v_aula=$this->get('request')->request->get('aula');
        $id_grupo=$this->get('request')->request->get('grupo');
        $grupo = $em->getRepository('BackendBundle:Grupo')->findOneById($id_grupo);

        $array_prof=[];
        $array_aula=[];
        foreach($v_profesor as $idprofesor){
            $profesor = $em->getRepository('BackendBundle:Profesor')->findOneById($idprofesor);
            $imparte_profesor = $em->getRepository('BackendBundle:Imparte')->findOcupadoPorProfesor($profesor, $grupo);

            foreach($imparte_profesor as $registro){
                $array_prof[$registro->getDiaSemanal()]=$registro->getHorario()->getId();
            }
        }

        foreach($v_aula as $idaula){
            $aula = $em->getRepository('BackendBundle:Equipamiento')->findOneById($idaula);
            $imparte_aula = $em->getRepository('BackendBundle:Imparte')->findOcupadoPorAula($aula, $grupo);

            foreach($imparte_aula as $registro){
                $array_aula[$registro->getDiaSemanal()]=$registro->getHorario()->getId();
            }
        }

        return new JsonResponse(array('profesor' => $array_prof,'aula' => $array_aula,'success' => true), 200);
    }


    public function ComprobarHorasProfesorAction()
    {
        $em = $this->getDoctrine()->getManager();

        $horarios=$this->get('request')->request->get('horarios');
        $id_profesor=$this->get('request')->request->get('profesor');
        $id_grupo=$this->get('request')->request->get('grupo');

        $grupo = $em->getRepository('BackendBundle:Grupo')->findOneById($id_grupo);
        $profesor = $em->getRepository('BackendBundle:Profesor')->findOneById($id_profesor);

        $data=null;
        //Se obtiene las asignaciones del profesor en otros grupos para calcular la duración de todas las clases.
        $otros_grupos=$em->getRepository('BackendBundle:Imparte')->findAsignacionesOtrosGrupos($profesor, $grupo);

        $total=0;
        foreach ($otros_grupos as $imparte ) {
            $duracion=$imparte->getHorario()->getDuracion();
            $total=(int)$total+(int)$duracion;
        }
        //Se obtiene la duración de cada módulo asignado en dicho grupo.
        $asignadas=0;
        if($horarios){
            foreach ($horarios as $id_horario) {
                $horario=$em->getRepository('BackendBundle:Horario')->findOneById($id_horario);
                $duracion=$horario->getDuracion();
                $asignadas=(int)$asignadas+(int)$duracion;
            }
        }
        //Se obtiene la duración total impartida por el profesor.
        $tiempo_impartido=(int)$total+(int)$asignadas;
        $tiempo_asignado=$profesor->getHorasLectivas()*60;
        $tiempo_restante=(int)$tiempo_asignado-(int)$tiempo_impartido;
        //Se comprueba si el profesor tiene tiempo disponible.
        if($tiempo_restante>=0){
            $data="";
            return new JsonResponse(array('data' => $data,'success' => true), 200);
        }

        return new JsonResponse(array('data' => $data,'horas' => $profesor->getHorasLectivas(),'profesor' => $profesor->getNombre()." ".$profesor->getApellido1()." ".$profesor->getApellido2(),'success' => true), 200);
    }




}
