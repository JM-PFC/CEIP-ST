<?php

namespace Cole\BackendBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;

use Cole\BackendBundle\Entity\Asignatura;
use Cole\BackendBundle\Entity\AsignaturasCursos;
use Cole\BackendBundle\Form\AsignaturaType;

/**
 * Asignatura controller.
 *
 */
class AsignaturaController extends Controller
{

    /**
     * Lists all Asignatura entities.
     *
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $entities = $em->getRepository('BackendBundle:Asignatura')->findBy(array(),array('nombre'=>'ASC'));

        return $this->render('BackendBundle:Asignatura:index.html.twig', array(
            'entities' => $entities,
        ));
    }

    public function showOptativasAction()
    {
        $em = $this->getDoctrine()->getManager();

                //Se obtiene los alumnos activos que están matriculados y que no tiene asignada ninguna asignatura de las opcionales.
        $entities_no_asig = $em->getRepository('BackendBundle:Alumno')->findOptativaNoAsignada(); 

        //Se obtiene los alumnos activos que están matriculados y que tiene asignada la asignatura opcional.
        $entities_asig = $em->getRepository('BackendBundle:Alumno')->findOptativaAsignada(); 

        return $this->render('BackendBundle:Asignatura:AsignarOptativaAlumno.html.twig', array(
            'entities_no_asig' => $entities_no_asig,
            'entities_asig' => $entities_asig,
        ));
    }





    public function asignaturasCursosAction()
    {
        $em = $this->getDoctrine()->getManager();
        $asignaturas= $em->getRepository('BackendBundle:Asignatura')->findNumAsignaturas();


        //Se comprueba que el número de módulos selecionado no sobrepase el máximo permitido.
        $modulos_dia=$em->getRepository('BackendBundle:Horario')->findClases();
        $modulos_total=count($modulos_dia)*5;

        $entities = $em->getRepository('BackendBundle:Curso')->findBy(array('nivel'=>'Primaria'),array('numOrden'=>'ASC'));
        return $this->render('BackendBundle:Asignatura:asignaturas_cursos_show.html.twig', array(
            'entities' => $entities,
            'numAsignaturas' => (int)$asignaturas[1],
            'modulos_total' => $modulos_total
        ));
    }

    /**
     * Creates a new Asignatura entity.
     *
     */
    public function createAction(Request $request)
    {
      // if request is XmlHttpRequest (AJAX) but not a POSt, throw an exception
      if ($request->isXmlHttpRequest() && !$request->isMethod('POST')) {
        throw new HttpException('XMLHttpRequests/AJAX calls must be POSTed');}

        $entity = new Asignatura();
        $form = $this->createCreateForm($entity);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();

            // Se comprueba que no exista la asignatura en el sistema.
            $asignatura = $em->getRepository('BackendBundle:Asignatura')->findOneByNombre($entity->getNombre());
            if($asignatura){
                return new JsonResponse(array(
                    'error' => 'existe',
                    'success' => true), 200);
            }
            $num = $em->getRepository('BackendBundle:Asignatura')->findByOpcional(1);

            $em->persist($entity);

            if(count($num)>=3 && $entity->getOpcional()==1){
                $entity->setOpcional(0);
                $em->flush();
                return new JsonResponse(array(
                    'opcional' => 'none',
                    'success' => true), 200);
            }

            $em->flush();

            if ($request->isXmlHttpRequest()) {
                return new JsonResponse(array(
                    'message' => 'Success!',
                    'success' => true), 200);
            }

            return $this->redirect($this->generateUrl('asignatura_show', array('id' => $entity->getId())));
        }

        if ($request->isMethod('POST')) {
            return new JsonResponse(array(
            'result' => 0,
            'message' => 'Invalid form',
            'data' => $this->getErrorMessages($form,$form->getName()),
            'success' => false), 400);
        }

        return $this->render('BackendBundle:Asignatura:new.html.twig', array(
            'entity' => $entity,
            'form'   => $form->createView(),
        ));
    }

    /**
     * Creates a form to create a Asignatura entity.
     *
     * @param Asignatura $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createCreateForm(Asignatura $entity)
    {
        $form = $this->createForm(new AsignaturaType(), $entity, array(
            'action' => $this->generateUrl('asignatura_create'),
            'method' => 'POST',
        ));

        $form->add('submit', 'submit', array('label' => 'Insertar'));

        return $form;
    }

    /**
     * Displays a form to create a new Asignatura entity.
     *
     */
    public function newAction()
    {
        $entity = new Asignatura();
        $form   = $this->createCreateForm($entity);

        return $this->render('BackendBundle:Asignatura:new.html.twig', array(
            'entity' => $entity,
            'form'   => $form->createView(),
        ));
    }

    public function AsignaturasCursoNewAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $troncales = $em->getRepository('BackendBundle:Asignatura')->findBy(array("tipo"=>"Troncal"),array("nombre"=>"ASC"));
        $especificas = $em->getRepository('BackendBundle:Asignatura')->findBy(array("tipo"=>"Específica"),array("nombre"=>"ASC"));
        $entities_troncales = $em->getRepository('BackendBundle:AsignaturasCursos')->findAsignaturasTroncalesCurso($id);
        $entities_especificas = $em->getRepository('BackendBundle:AsignaturasCursos')->findAsignaturasEspecificasNoOpcionalesCurso($id);
        $entities_especificas_opcionales = $em->getRepository('BackendBundle:AsignaturasCursos')->findAsignaturasEspecificasOpcionalesCurso($id);
        $curso= $em->getRepository('BackendBundle:Curso')->findOneById($id);

        return $this->render('BackendBundle:Asignatura:new_asignaturas_curso.html.twig', array(
            'troncales' => $troncales,
            'especificas' => $especificas,
            'entities_troncales' => $entities_troncales,
            'entities_especificas' => $entities_especificas,
            'entities_especificas_opcionales' => $entities_especificas_opcionales,
            'curso' => $curso,
        ));
    }
    

    /**
     * Finds and displays a Asignatura entity.
     *
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('BackendBundle:Asignatura')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Asignatura entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return $this->render('BackendBundle:Asignatura:show.html.twig', array(
            'entity'      => $entity,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing Asignatura entity.
     *
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('BackendBundle:Asignatura')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Asignatura entity.');
        }

        $editForm = $this->createEditForm($entity);
        $deleteForm = $this->createDeleteForm($id);

        return $this->render('BackendBundle:Asignatura:edit.html.twig', array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
    * Creates a form to edit a Asignatura entity.
    *
    * @param Asignatura $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createEditForm(Asignatura $entity)
    {
        $form = $this->createForm(new AsignaturaType(), $entity, array(
            'action' => $this->generateUrl('asignatura_update', array('id' => $entity->getId())),
            'method' => 'PUT',
        ));

        $form->add('submit', 'submit', array('label' => 'Actualizar'));

        return $form;
    }
    /**
     * Edits an existing Asignatura entity.
     *
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $opcional=$this->get('request')->request->get('opcional');

        $entity = $em->getRepository('BackendBundle:Asignatura')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Asignatura entity.');
        }

        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createEditForm($entity);
        $editForm->handleRequest($request);

        if ($editForm->isValid()) {

            // Se comprueba que no exista la asignatura en el sistema.
            $asignatura = $em->getRepository('BackendBundle:Asignatura')->findByNombreEdit($entity->getNombre(),$entity->getId());
            if($asignatura){
                return new JsonResponse(array(
                    'error' => 'existe',
                    'success' => true), 200);
            }
            $num = $em->getRepository('BackendBundle:Asignatura')->findByOpcionalEdit($entity->getId());

            if(count($num)>=3 && $entity->getOpcional()==1){
                $entity->setOpcional(0);
                $em->flush();
                return new JsonResponse(array(
                    'opcional' => 'none',
                    'success' => true), 200);
            }
            //Se comprueba si es una asignatura opcional.
            if($opcional==1){

                $asignaturasCursos=$em->getRepository('BackendBundle:AsignaturasCursos')->findByAsignatura($entity);

                foreach ($asignaturasCursos as $asignaturaCurso) {
                    $imparte=$em->getRepository('BackendBundle:Imparte')->findByAsignatura($asignaturaCurso);
       
                    foreach ($imparte as $imparte) {
                        /*
                        //Se elimina todas las asignaciones de horarios de las asignaturas opcionales que estaban asignadas junto a la que se ha modificado.
                        $asig_op = $em->getRepository('BackendBundle:Imparte')->findAsignacionesOpcionales($imparte->getGrupo());
                        
                        foreach ($asig_op as $asig_op) {
                            $asig_op->SetHorario(null);
                            $asig_op->SetDiaSemanal(null);
                            $em->persist($asig_op);
                            $em->flush();
                        }
                        */
                        //Se elimina todas las asignaciones de profesores de la asignatura modificada.
                        $em->remove($imparte);
                        $em->flush();
                    }
                    //Se elimina la asignación de la asignatura con los cursos asignados.
                    $em->remove($asignaturaCurso);
                    $em->flush();
                }

                //Eliminación del campo "optativa" en los alumnos si es una asignatura opcional.
                $alumnos = $em->getRepository('BackendBundle:Alumno')->findAlumnosConAsigComoOptativa($entity);

                foreach ($alumnos as $alumno ) {
                    $alumno->setOptativa(null);
                }
            }

            $em->flush();
            return $this->redirect($this->generateUrl('asignatura_edit', array('id' => $id)));
        }

        return $this->render('BackendBundle:Asignatura:edit.html.twig', array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }
    /**
     * Deletes a Asignatura entity.
     *
     */
    public function deleteAction(Request $request, $id)
    {
        $form = $this->createDeleteForm($id);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('BackendBundle:Asignatura')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find Asignatura entity.');
            }

            //La eliminación de asignaciones de la tabla "AsignaturasCursos" se realiza desde la entidad con cascade={"remove"}

            //Eliminación de las asignaciones de la tabla imparte.
            $asignaciones = $em->getRepository('BackendBundle:AsignaturasCursos')->findByAsignatura($entity);

            foreach ($asignaciones as $asignaturacurso ) {
                $imparte = $em->getRepository('BackendBundle:Imparte')->findByAsignatura($asignaturacurso);
                foreach ($imparte as $asignacion ) {
                    $em->remove($asignacion);
                }
            }  

            //Eliminación del campo "optativa" en los alumnos si es una asignatura opcional.
            if($entity->getOpcional()==1){
                $alumnos = $em->getRepository('BackendBundle:Alumno')->findAlumnosConAsigComoOptativa($entity);

                foreach ($alumnos as $alumno ) {
                    $alumno->setOptativa(null);
                }
            }

            $em->remove($entity);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('asignatura'));
    }

    /**
     * Creates a form to delete a Asignatura entity by id.
     *
     * @param mixed $id The entity id
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm($id)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('asignatura_delete', array('id' => $id)))
            ->setMethod('DELETE')
            ->add('submit', 'submit', array('label' => 'Eliminar'))
            ->getForm()
        ;
    }

    public function AsignaturasCursoAction($id)
    {
        $em = $this->getDoctrine()->getManager();
        $entity = $em->getRepository('BackendBundle:AsignaturasCursos')->findAsignaturasCursos($id);
        return $this->render('BackendBundle:Asignatura:asignaturas_por_curso.html.twig', array(
            'entity' => $entity));
    }

    public function ComprobarAsignaturasAction()
    {
        $em = $this->getDoctrine()->getManager();
        $data =$em->getRepository('BackendBundle:Asignatura')->findAll();

        return new JsonResponse(array('data' => $data,'success' => true), 200);
    }


    public function AsignarAsignaturasCursoAction()
    {
        $em = $this->getDoctrine()->getManager();

        $idcurso=$this->get('request')->request->get('curso');
        $nuevas=$this->get('request')->request->get('nuevas');
        $asignadas=$this->get('request')->request->get('asignadas');
        $eliminadas=$this->get('request')->request->get('eliminadas');
        $modulos=$this->get('request')->request->get('modulos');
        $num_asig=0;
        $num_elim=0;
        $num_actu=0;

        $data=1;
        if($nuevas==null && $asignadas==null && $eliminadas==null ){
            $data=null;
            return new JsonResponse(array('data' => $data), 200);
        }

        //Se comprueba que el número de módulos selecionado no sobrepase el máximo permitido.
        $modulos_dia=$em->getRepository('BackendBundle:Horario')->findClases();
        $modulos_total=count($modulos_dia)*5;
        if($modulos> $modulos_total){
            return new JsonResponse(array('data' => $modulos_total), 200);
        }

        if($nuevas){
          foreach ($nuevas as $row ) { //$row[0]->ID asignatura  $row[1]->Nº Módulos $row[2]->Libro.
            $asignatura=$em->getRepository('BackendBundle:Asignatura')->findOneById($row[0]);
            $curso=$em->getRepository('BackendBundle:Curso')->findOneById($idcurso);

            $entity = new AsignaturasCursos();
            $entity->setAsignatura($asignatura);
            $entity->setCurso($curso);
            $entity->setNumModulos($row[1]);
            if($row[2]==""){
                $entity->setLibro(null); 
            }
            else{
                $entity->setLibro($row[2]); 
            }
            $em->persist($entity);
            $num_asig++; 

            if($asignatura->getOpcional()==1){
                //Se elimina todas las asignaciones de horarios de las asignaturas opcionales del curso al añadir una nueva asignatura opcional.
                $asig_op = $em->getRepository('BackendBundle:Imparte')->findOpcionalesCurso($curso);
                        
                foreach ($asig_op as $asig_op) {
                    $asig_op->SetHorario(null);
                    $asig_op->SetDiaSemanal(null);
                    $em->persist($asig_op);
                    $em->flush();
                }
            }
          }   
        }

        if($eliminadas){
        //Se eliminan las asignaciones de profesores si existe en alguno de los grupos.(imparte)
          foreach ($eliminadas as $eliminada ) {
            $entity = $em->getRepository('BackendBundle:AsignaturasCursos')->findOneById($eliminada);
            $query = $em->createQuery('DELETE BackendBundle:Imparte i WHERE i.asignatura = :asignatura')->setParameter("asignatura", $entity);
            $query->execute();
          }  
        //Se eliminan las asignaciones de las asignaturas del curso.
          foreach ($eliminadas as $eliminada ) {
            $entity = $em->getRepository('BackendBundle:AsignaturasCursos')->findOneById($eliminada);
            $query = $em->createQuery('DELETE BackendBundle:AsignaturasCursos r WHERE r.id = :Id')->setParameter("Id", $entity->getId());
            $query->execute();
            $num_elim++; 
          }
        }

        if($asignadas){
          foreach ($asignadas as $row ) { //$row[0]->ID asignatura  $row[1]->Nº Módulos $row[2]->Libro $row[3]->cambioModulo.
            $entity = $em->getRepository('BackendBundle:AsignaturasCursos')->findAsignacion($idcurso,$row[0]);
            $entity->setNumModulos($row[1]);
            if($row[2]==""){
                $entity->setLibro(null); 
            }
            else{
                $entity->setLibro($row[2]); 
            }
            $em->persist($entity);
            $num_actu++; 
            if($row[3]==1){
                $imparte= $em->getRepository('BackendBundle:Imparte')->findByAsignatura($entity);
                foreach ($imparte as $imparte ) {
                    $em->remove($imparte);
                }  
            }

          }  
        }
        
        $em->flush();
        
        return new JsonResponse(array(
            'data' => $data,            
            'asignadas' =>  $num_asig,
            'eliminadas' =>  $num_elim,
            'actualizadas' =>  $num_actu,  
            'success' => true), 200);
    }


    public function EliminarAsignacionesAsignaturasAction()
    {
        $em = $this->getDoctrine()->getManager();

        $idcurso=$this->get('request')->request->get('curso');

        $curso = $em->getRepository('BackendBundle:Curso')->findOneById($idcurso);
        if (!$curso) {
            throw $this->createNotFoundException('Unable to find Curso entity.');
        }

        $asignaciones= $em->getRepository('BackendBundle:AsignaturasCursos')->findByCurso($curso);
        
        $data=1;
        if(!$asignaciones){
            $data=null;
            return new JsonResponse(array('data' => $data), 200);
        }
        else{

            foreach ($asignaciones as $asignaturacurso ) {
                $imparte = $em->getRepository('BackendBundle:Imparte')->findByAsignatura($asignaturacurso);
                foreach ($imparte as $asignacion ) {
                    $em->remove($asignacion);
                }
            }  

            foreach ($asignaciones as $asignacion ) {
                $em->remove($asignacion);
            }  
            $em->flush();
            return new JsonResponse(array('data' => $data,'success' => true), 200);
        }
    }


    public function EliminarTodasAsignacionesAsignaturasAction()
    {
        $em = $this->getDoctrine()->getManager();

        $asignaciones= $em->getRepository('BackendBundle:AsignaturasCursos')->findAll();
        
        $data=1;
        if(!$asignaciones){
            $data=null;
            return new JsonResponse(array('data' => $data), 200);
        }
        else{

            foreach ($asignaciones as $asignaturacurso ) {
                $imparte = $em->getRepository('BackendBundle:Imparte')->findByAsignatura($asignaturacurso);
                foreach ($imparte as $asignacion ) {
                    $em->remove($asignacion);
                }
            }  

            foreach ($asignaciones as $asignacion ) {
                $em->remove($asignacion);
            }  
            $em->flush();
            return new JsonResponse(array('data' => $data,'success' => true), 200);
        }
    }

    public function DatosAsignaturaAction($id, $id_grupo)
    {
        $em = $this->getDoctrine()->getManager();
        $grupo= $em->getRepository('BackendBundle:Grupo')->findOneById($id_grupo);

        if($id!=0){
            $asignatura = $em->getRepository('BackendBundle:AsignaturasCursos')->findOneById($id);
            $entity = $em->getRepository('BackendBundle:Imparte')->findByOneGrupoAndAsignatura($grupo,$asignatura);
            if ($entity) {
                return $this->render('BackendBundle:Asignatura:datos_asignatura.html.twig', array(
                'entity' => $entity));        
            }
            return $this->render('BackendBundle:Asignatura:datos_asignatura.html.twig', array(
                'entity' => "")); 
        }
        else{
            $imparte = $em->getRepository('BackendBundle:Imparte')->findOpcionalesGrupo($grupo);
                return $this->render('BackendBundle:Asignatura:datos_asignatura.html.twig', array(
                'entity' => null,
                'imparte' => $imparte));  

        }

    }

    public function OptativasCursoAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entities =$em->getRepository('BackendBundle:AsignaturasCursos')->findAsignaturasEspecificasOpcionalesCurso($id);
        if($entities){
            $num_optativas=count($entities);
        }
        else{
            $num_optativas=0;
        }
        return $this->render('BackendBundle:Asignatura:listaOptativasCurso.html.twig', array(
            'entities' => $entities,
            'num_optativas'=> $num_optativas
        ));
    }


    public function AsignarOptativaAction()
    {
        $em = $this->getDoctrine()->getManager();

        $asignadas=$this->get('request')->request->get('asignadas');
        $num=0;
        $data=1;
        if($asignadas==null){
            $data=null;
            return new JsonResponse(array('data' => $data), 200);
        }

        if($asignadas){
          foreach ($asignadas as $alumno => $optativa) {
            $entity = $em->getRepository('BackendBundle:Alumno')->findOneById($alumno);
            $asignatura=$em->getRepository('BackendBundle:AsignaturasCursos')->findOneById($optativa);
            $entity->setOptativa($asignatura);

            $em->persist($entity);
            $num++; 
          }  
        }
        $em->flush();
        
        return new JsonResponse(array(
            'data' => $data,            
            'num' =>  $num,
            'success' => true), 200);
    }



}
