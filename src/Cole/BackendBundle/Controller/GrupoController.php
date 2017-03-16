<?php

namespace Cole\BackendBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;

use Cole\BackendBundle\Entity\Grupo;
use Cole\BackendBundle\Entity\Curso;
use Cole\BackendBundle\Entity\Matricula;
use Cole\BackendBundle\Form\GrupoType;

/**
 * Grupo controller.
 *
 */
class GrupoController extends Controller
{

    /**
     * Lists all Grupo entities.
     *
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $entities = $em->getRepository('BackendBundle:Grupo')->findAll();

        return $this->render('BackendBundle:Grupo:index.html.twig', array(
            'entities' => $entities,
        ));
    }
    /**
     * Creates a new Grupo entity.
     *
     */
    public function createAction(Request $request)
    {
        $entity = new Grupo();
        $form = $this->createCreateForm($entity);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('grupo_show', array('id' => $entity->getId())));
        }

        return $this->render('BackendBundle:Grupo:new.html.twig', array(
            'entity' => $entity,
            'form'   => $form->createView(),
        ));
    }

    /**
     * Creates a form to create a Grupo entity.
     *
     * @param Grupo $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createCreateForm(Grupo $entity)
    {
        $form = $this->createForm(new GrupoType(), $entity, array(
            'action' => $this->generateUrl('grupo_create'),
            'method' => 'POST',
        ));

        $form->add('submit', 'submit', array('label' => 'Create'));

        return $form;
    }

    /**
     * Displays a form to create a new Grupo entity.
     *
     */
    public function newAction()
    {
        $entity = new Grupo();
        $form   = $this->createCreateForm($entity);

        return $this->render('BackendBundle:Grupo:new.html.twig', array(
            'entity' => $entity,
            'form'   => $form->createView(),
        ));
    }
    public function AsignarHorarioGrupoNewAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $troncales = $em->getRepository('BackendBundle:Asignatura')->findBy(array("tipo"=>"Troncal"),array("nombre"=>"ASC"));
        $especificas = $em->getRepository('BackendBundle:Asignatura')->findBy(array("tipo"=>"Específica"),array("nombre"=>"ASC"));
        $entities_troncales = $em->getRepository('BackendBundle:AsignaturasCursos')->findAsignaturasTroncalesCurso($id);
        $entities_especificas = $em->getRepository('BackendBundle:AsignaturasCursos')->findAsignaturasEspecificasCurso($id);
        $curso= $em->getRepository('BackendBundle:Curso')->findOneById($id);

        return $this->render('BackendBundle:Grupo:new_horario_grupo.html.twig', array(
            'troncales' => $troncales,
            'especificas' => $especificas,
            'entities_troncales' => $entities_troncales,
            'entities_especificas' => $entities_especificas,
            'curso' => $curso,
        ));
    }
    /**
     * Finds and displays a Grupo entity.
     *
     */
    public function showAction()
    {
        $em = $this->getDoctrine()->getManager();

        $entities = $em->getRepository('BackendBundle:Grupo')->findAllByCurso();

           return $this->render('BackendBundle:Grupo:show.html.twig', array(
            'entities' => $entities,
        ));
    }

    public function AsignarHorarioGrupoShowAction()
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

        $asignaturasCursos= $em->getRepository('BackendBundle:AsignaturasCursos')->findAll();
        if (!$asignaturasCursos) {
            $numAsignaturasCursos=0;
        }
        else{
            $numAsignaturasCursos=1;
        }

        $horarios=$em->getRepository('BackendBundle:Horario')->findAll();
        if (!$horarios) {
            $horario=0;
        }
        else{
            $horario=1;
        }

        $entities=$em->getRepository('BackendBundle:Grupo')->findGruposByNivel("Primaria");

        return $this->render('BackendBundle:Grupo:asignar_horarios_show.html.twig', array(
            'entities' => $entities,
            'numAsignaturas' => (int)$asignaturas[1],
            'numProfesores' => $numProfesores,
            'numAsignaturasCursos' => $numAsignaturasCursos,
            'horario' => $horario,
        ));
    }


    /**
     * Displays a form to edit an existing Grupo entity.
     *
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('BackendBundle:Grupo')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Grupo entity.');
        }

        $editForm = $this->createEditForm($entity);
        $deleteForm = $this->createDeleteForm($id);

        return $this->render('BackendBundle:Grupo:edit.html.twig', array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
    * Creates a form to edit a Grupo entity.
    *
    * @param Grupo $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createEditForm(Grupo $entity)
    {
        $form = $this->createForm(new GrupoType(), $entity, array(
            'action' => $this->generateUrl('grupo_update', array('id' => $entity->getId())),
            'method' => 'PUT',
        ));

        $form->add('submit', 'submit', array('label' => 'Update'));

        return $form;
    }
    /**
     * Edits an existing Grupo entity.
     *
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('BackendBundle:Grupo')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Grupo entity.');
        }

        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createEditForm($entity);
        $editForm->handleRequest($request);

        if ($editForm->isValid()) {
            $em->flush();

            return $this->redirect($this->generateUrl('grupo_edit', array('id' => $id)));
        }

        return $this->render('BackendBundle:Grupo:edit.html.twig', array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }
    /**
     * Deletes a Grupo entity.
     *
     */
    public function deleteAction(Request $request, $id)
    {
        $form = $this->createDeleteForm($id);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('BackendBundle:Grupo')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find Grupo entity.');
            }

            $em->remove($entity);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('grupo'));
    }

    /**
     * Creates a form to delete a Grupo entity by id.
     *
     * @param mixed $id The entity id
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm($id)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('grupo_delete', array('id' => $id)))
            ->setMethod('DELETE')
            ->add('submit', 'submit', array('label' => 'Delete'))
            ->getForm()
        ;
    }

    public function AsignarAulaAction(Request $request)
    {
        // if request is XmlHttpRequest (AJAX) but not a POSt, throw an exception
        if ($request->isXmlHttpRequest() && !$request->isMethod('POST')) {
            throw new HttpException('XMLHttpRequests/AJAX calls must be POSTed');
        }


        $grupo=$this->get('request')->request->get('grupo');
        $aula=$this->get('request')->request->get('aula');

        $em = $this->getDoctrine()->getManager();

        $grupo = $em->getRepository('BackendBundle:Grupo')->findOneById($grupo);
        if (!$grupo) {
                throw $this->createNotFoundException('Unable to find Grupo entity.');
            }
        if($aula!=null){
            $aula = $em->getRepository('BackendBundle:Equipamiento')->findOneById($aula);
            if (!$aula ) {
                throw $this->createNotFoundException('Unable to find Aula entity.');
            }

            $grupo->setAula($aula); 
        }
        else{
            $grupo->setAula(NULL);
        }
    
        $em->persist($grupo);
        $em->flush();
        
        if ($request->isXmlHttpRequest()) {
                return new JsonResponse(array(
                    'message' => 'Success!',
                    'success' => true), 200);
        }
    }

    public function VaciarAulasAction(Request $request)
    {
        // if request is XmlHttpRequest (AJAX) but not a POSt, throw an exception
        if ($request->isXmlHttpRequest() && !$request->isMethod('POST')) {
            throw new HttpException('XMLHttpRequests/AJAX calls must be POSTed');
        }

        $em = $this->getDoctrine()->getManager();

        $grupos = $em->getRepository('BackendBundle:Grupo')->findAll();

        foreach ($grupos as $grupo) {
            $grupo->setAula(NULL);
            $em->persist($grupo);
        }

        $em->flush();
        
        if ($request->isXmlHttpRequest()) {
                return new JsonResponse(array(
                    'message' => 'Success!',
                    'success' => true), 200);
        }
    }

    public function AsignarGrupoAction()
    {
        $em = $this->getDoctrine()->getManager();

        $entities = $em->getRepository('BackendBundle:Grupo')->findAllByCurso();

        // Se obtiene el curso académico actual.
        if(date("n")>=6){
            $actual=date("Y")." / ".(date("Y")+1);
        }
        else{
            $actual=(date("Y")-1)." / ".date("Y");
        }

        $alumnos= $em->getRepository('BackendBundle:Matricula')->findOneByAnyoAcademico($actual);
           return $this->render('BackendBundle:Grupo:AsignarGrupo.html.twig', array(
            'entities' => $entities,
            'alumnos' => $alumnos
        ));
    }

    public function CursoAsignarGrupoAction($curso)
    {

        $em = $this->getDoctrine()->getManager();

        $c = $em->getRepository('BackendBundle:Curso')->findOneById($curso);

        $num_grupos=$c->getNumGrupos();

        // Se obtiene el curso académico actual.
        if(date("n")>=6){
            $actual=date("Y")." / ".(date("Y")+1);
        }
        else{
            $actual=(date("Y")-1)." / ".date("Y");
        }

        $alumnos_sin_grupo= $em->getRepository('BackendBundle:Matricula')->findMatriculadosSinGrupo($c,$actual);
        $alumnos_A= $em->getRepository('BackendBundle:Matricula')->findMatriculadosConGrupo($c,$actual,"A");
        $alumnos_B= $em->getRepository('BackendBundle:Matricula')->findMatriculadosConGrupo($c,$actual,"B");
        $alumnos_C= $em->getRepository('BackendBundle:Matricula')->findMatriculadosConGrupo($c,$actual,"C");

           return $this->render('BackendBundle:Grupo:AsignarGrupoConCurso.html.twig', array(
            'alumnos_A' => $alumnos_A,
            'alumnos_B' => $alumnos_B,
            'alumnos_C' =>$alumnos_C,
            'alumnos_sin_grupo' => $alumnos_sin_grupo,
            'curso' => $curso,
            'num_grupos' => $num_grupos,
            'ratio' => $c->getRatio(),
        ));
    }

    public function AsignarGrupoUpdateAction(Request $request)
    {
        // if request is XmlHttpRequest (AJAX) but not a POSt, throw an exception
        if ($request->isXmlHttpRequest() && !$request->isMethod('POST')) {
            throw new HttpException('XMLHttpRequests/AJAX calls must be POSTed');
        }
        
        $em = $this->getDoctrine()->getEntityManager();

        $alum=$this->get('request')->request->get('alumno');
        $letra=$this->get('request')->request->get('letra');
        $alumno=$em->getRepository('BackendBundle:Alumno')->findOneById($alum);
        if (!$alumno) {
                throw $this->createNotFoundException('Unable to find Alumno entity.');
            }

        $grupo=$em->getRepository('BackendBundle:Grupo')->findGrupoByLetter($alumno->getCurso(),$letra);

        $alumno->setGrupo($grupo);
        $em->persist($alumno);

        $matricula=$em->getRepository('BackendBundle:Matricula')->findPorAño($alumno,$alumno->getAnyoAcademico());
        if (!$matricula) {
                throw $this->createNotFoundException('Unable to find Matricula entity.');
            }
        $matricula->setGrupo($grupo);
        $em->persist($matricula);

        $em->flush();
        
        if ($request->isXmlHttpRequest()) {
                return new JsonResponse(array(
                    'message' => 'Success!',
                    'success' => true), 200);
        }
    }


    public function TutorGrupoAction()
    {
        $em = $this->getDoctrine()->getManager();

        $entities = $em->getRepository('BackendBundle:Grupo')->findAllByCurso();
        $profesores = $em->getRepository('BackendBundle:Profesor')->findByActivo(1);

           return $this->render('BackendBundle:Grupo:tutor_grupo.html.twig', array(
            'entities' => $entities,
            'profesores' => $profesores
        ));
    }

    public function AsignarTutorGrupoAction()
    {
        $em = $this->getDoctrine()->getManager();

        $asignaciones=$this->get('request')->request->get('asignaciones');
        $eliminados=$this->get('request')->request->get('eliminados');

        $data=1;
        if($asignaciones==null && $eliminados==null){
            $data=null;
            return new JsonResponse(array('data' => $data), 200);
        }

        if($asignaciones){
          foreach ($asignaciones as $grupo => $id ) {
            $grupo = $em->getRepository('BackendBundle:Grupo')->findOneById($grupo);
            if (!$grupo) {
                throw $this->createNotFoundException('Unable to find Grupo entity.');
            }
            $profesor = $em->getRepository('BackendBundle:Profesor')->findOneById($id);
            if (!$profesor) {
                throw $this->createNotFoundException('Unable to find Profesor entity.');
            }
            $grupo->setProfesor($profesor);
            $em->persist($grupo);
          }   
        }

        if($eliminados){
          foreach ($eliminados as $grupo ) {
            $grupo = $em->getRepository('BackendBundle:Grupo')->findOneById($grupo);
            if (!$grupo) {
                throw $this->createNotFoundException('Unable to find Grupo entity.');
            }

            $grupo->setProfesor(null);
            $em->persist($grupo);
          }  
        }
        $em->flush();
        return new JsonResponse(array('data' => $data,'success' => true), 200);
    }

    public function HorariosGruposAction($id)
    {
        $em = $this->getDoctrine()->getManager();
        $grupo = $em->getRepository('BackendBundle:Grupo')->findOneById($id);
        $entity = $em->getRepository('BackendBundle:Horario')->findAll();
        $imparte = $em->getRepository('BackendBundle:Imparte')->findByGrupo($id);
        
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

        $asignaciones = $em->getRepository('BackendBundle:Imparte')->findByGrupoConHorario($grupo);
        if($asignaciones){
            $numAsigHorarios=1;
        }
        else{
            $numAsigHorarios=0;
        }

        $asignaciones = $em->getRepository('BackendBundle:AsignaturasCursos')->findByCurso($grupo->getCurso());
        if($asignaciones){
            $numAsigCurso=1;
        }
        else{
            $numAsigCurso=0;
        }

        return $this->render('BackendBundle:Grupo:datos_horarios.html.twig', array(
            'imparte' => $array,
            'numAsigHorarios' => $numAsigHorarios,
            'numAsigCurso' => $numAsigCurso,
            'entity' => $entity));
    }

    

    public function EliminarHorarioGrupoAction()
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
                $asignacion->setHorario(null);
                $asignacion->setDiaSemanal(null);
                $em->persist($asignacion);
            }  
            $em->flush();
            return new JsonResponse(array('data' => $data,'success' => true), 200);
        }
    }

    public function EliminarTodosHorariosGrupoAction()
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
                $asignacion->setHorario(null);
                $asignacion->setDiaSemanal(null);
                $em->persist($asignacion);
            }  
            $em->flush();
            return new JsonResponse(array('data' => $data,'success' => true), 200);
        }
    }




}
