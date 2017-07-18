<?php

namespace Cole\BackendBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;


use Cole\BackendBundle\Entity\Matricula;
use Cole\BackendBundle\Entity\Grupo;
use Cole\BackendBundle\Form\MatriculaType;

/**
 * Matricula controller.
 *
 */
class MatriculaController extends Controller
{

    /**
     * Lists all Matricula entities.
     *
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $entities = $em->getRepository('BackendBundle:Matricula')->findAll();

        return $this->render('BackendBundle:Matricula:index.html.twig', array(
            'entities' => $entities,
        ));
    }

    public function showMatriculasAction()
    {
        $em = $this->getDoctrine()->getManager();

        $entities = $em->getRepository('BackendBundle:Matricula')->findAll();

        return $this->render('BackendBundle:Matricula:anular_matricula.html.twig', array(
            'entities' => $entities,
        ));
    }
    /**
     * Creates a new Matricula entity.
     *
     */
    public function createAction(Request $request)
    {
        $entity = new Matricula();
        $form = $this->createCreateForm($entity);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('matricula_show', array('id' => $entity->getId())));
        }

        return $this->render('BackendBundle:Matricula:new.html.twig', array(
            'entity' => $entity,
            'form'   => $form->createView(),
        ));
    }

    /**
     * Creates a form to create a Matricula entity.
     *
     * @param Matricula $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createCreateForm(Matricula $entity)
    {
        $form = $this->createForm(new MatriculaType(), $entity, array(
            'action' => $this->generateUrl('matricula_create'),
            'method' => 'POST',
        ));

        $form->add('submit', 'submit', array('label' => 'Create'));

        return $form;
    }

    /**
     * Displays a form to create a new Matricula entity.
     *
     */
    public function newAction()
    {
        $entity = new Matricula();
        $form   = $this->createCreateForm($entity);

        return $this->render('BackendBundle:Matricula:new.html.twig', array(
            'entity' => $entity,
            'form'   => $form->createView(),
        ));
    }

    /**
     * Finds and displays a Matricula entity.
     *
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('BackendBundle:Matricula')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Matricula entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return $this->render('BackendBundle:Matricula:show.html.twig', array(
            'entity'      => $entity,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing Matricula entity.
     *
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('BackendBundle:Matricula')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Matricula entity.');
        }

        $editForm = $this->createEditForm($entity);
        $deleteForm = $this->createDeleteForm($id);

        return $this->render('BackendBundle:Matricula:edit.html.twig', array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
    * Creates a form to edit a Matricula entity.
    *
    * @param Matricula $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createEditForm(Matricula $entity)
    {
        $form = $this->createForm(new MatriculaType(), $entity, array(
            'action' => $this->generateUrl('matricula_update', array('id' => $entity->getId())),
            'method' => 'PUT',
        ));

        $form->add('submit', 'submit', array('label' => 'Update'));

        return $form;
    }
    /**
     * Edits an existing Matricula entity.
     *
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('BackendBundle:Matricula')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Matricula entity.');
        }

        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createEditForm($entity);
        $editForm->handleRequest($request);

        if ($editForm->isValid()) {
            $em->flush();

            return $this->redirect($this->generateUrl('matricula_edit', array('id' => $id)));
        }

        return $this->render('BackendBundle:Matricula:edit.html.twig', array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }
    /**
     * Deletes a Matricula entity.
     *
     */
    public function deleteAction(Request $request, $id)
    {
        $form = $this->createDeleteForm($id);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('BackendBundle:Matricula')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find Matricula entity.');
            }

            $em->remove($entity);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('matricula'));
    }

    /**
     * Creates a form to delete a Matricula entity by id.
     *
     * @param mixed $id The entity id
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm($id)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('matricula_delete', array('id' => $id)))
            ->setMethod('DELETE')
            ->add('submit', 'submit', array('label' => 'Delete'))
            ->getForm()
        ;
    }


    public function  MatricularAlumnoAction(Request $request)
    {
        // if request is XmlHttpRequest (AJAX) but not a POSt, throw an exception
        if ($request->isXmlHttpRequest() && !$request->isMethod('POST')) {
            throw new HttpException('XMLHttpRequests/AJAX calls must be POSTed');
        }

        $alum=$this->get('request')->request->get('alumno');

        $em = $this->getDoctrine()->getEntityManager();

        $alumno=$em->getRepository('BackendBundle:Alumno')->findOneById($alum);
        $entity = new Matricula();

        $entity->setAlumno($alumno);
        $entity->setCurso($alumno->getCurso());
        $entity->setGrupo($alumno->getGrupo());

        if(date("n")>=6){
            $entity->setAnyoAcademico(date("Y")." / ".(date("Y")+1));
        }
        else{
            $entity->setAnyoAcademico((date("Y")-1)." / ".date("Y"));
        }
        $entity->setFecha(new \DateTime("now"));

            $factory = $this->get('security.encoder_factory'); 
            $encoder = $factory->getEncoder($entity);


        //Se comprueba si los responsables están activos. En caso que no lo estén se activan y se restablece la contraseña.
        if((int)$alumno->getResponsable1()->getActivo()== 0 ){
            $alumno->getResponsable1()->setActivo(1); 
            
            $password1 = $encoder->encodePassword("u".substr($alumno->getResponsable1()->getDni(), 0, -2), $alumno->getResponsable1()->getSalt());
            $alumno->getResponsable1()->setPassword($password1);    
        }
        //En el caso del segundo responsable se comprueba si existe.
        if($alumno->getResponsable2() && (int)$alumno->getResponsable2()->getActivo()==0 ){
            $alumno->getResponsable2()->setActivo(1); 

            $password2 = $encoder->encodePassword("u".substr($alumno->getResponsable2()->getDni(), 0, -2), $alumno->getResponsable2()->getSalt());
            $alumno->getResponsable2()->setPassword($password2); 
        }
        $em->persist($alumno);
        $em->persist($entity);
        $em->flush();
        
        if ($request->isXmlHttpRequest()) {
                return new JsonResponse(array(
                    'curso' => $entity->getCurso()->getId(),
                    'success' => true), 200);
        } 
    }


    public function  NumMatriculasAction(Request $request)
    {
        // if request is XmlHttpRequest (AJAX) but not a POSt, throw an exception
        if ($request->isXmlHttpRequest() && !$request->isMethod('POST')) {
            throw new HttpException('XMLHttpRequests/AJAX calls must be POSTed');
        }

        $em = $this->getDoctrine()->getEntityManager();

        $num_matriculas=$em->getRepository('BackendBundle:Matricula')->findNumMatriculas();


        if ($request->isXmlHttpRequest()) {
                return new JsonResponse(array(
                    'matriculas' =>  (int)$num_matriculas[1],
                    'success' => true), 200);
        } 
    }

    public function  AnularMatriculaAction()
    {
        $em = $this->getDoctrine()->getEntityManager();

        $id=$this->get('request')->request->get('id');

        $alumno=$em->getRepository('BackendBundle:Alumno')->findOneById($id);
        if($alumno->getGrupo()){
            $grupo=$alumno->getGrupo();
            $numAlum=$alumno->getNumAlum();
        }
        else{
            $grupo=null;
            $numAlum=null;
        }
        
        //Se comprueba si es nuevo alumno o antiguo.
        $expediente=$em->getRepository('BackendBundle:Expediente')->findByAlumno($alumno);
        //nuevo alumno
        if(!$expediente){

            if($alumno->getResponsable1()){
                //Se busca todos los alumnos que contiene como primer responsable el del alumno actual.
                $busqueda=$em->getRepository('BackendBundle:Alumno')->findByResponsables($alumno->getResponsable1());
                // Si no tiene más hermanos en el sistema se elimina el responsable.
                if(count($busqueda)==1){
                    $responsable=$em->getRepository('BackendBundle:Padres')->findOneById($alumno->getResponsable1());
                    $alumno->setResponsable1(null);
                    $em->remove($responsable);
                    $em->flush();
                }
                // Si existe algún alummno con el mismo responsable, se comprueba si está activo el alumno para dejar activo al responsable.
                else{
                    $alumno->getResponsable1()->setActivo(0);
                    foreach ($busqueda as $entity) {
                        if($entity->getActivo()==1 && $entity->getId()!=$id){
                            $alumno->getResponsable1()->setActivo(1);
                            break;
                        }
                    }  
                }
            }
            if($alumno->getResponsable2()){
                //Se busca todos los alumnos que contiene como segundo responsable el del alumno actual.
                $busqueda=$em->getRepository('BackendBundle:Alumno')->findByResponsables($alumno->getResponsable2());
                // Si no tiene más hermanos en el sistema se elimina el responsable.
                if(count($busqueda)==1){
                    $responsable=$em->getRepository('BackendBundle:Padres')->findOneById($alumno->getResponsable2());
                    $alumno->setResponsable2(null);
                    $em->remove($responsable);
                    $em->flush();
                }
                // Si existe algún alummno con el mismo responsable, se comprueba si está activo el alumno para dejar activo al responsable.
                else{
                    $alumno->getResponsable2()->setActivo(0);
                    foreach ($busqueda as $entity) {
                        if($entity->getActivo()==1 && $entity->getId()!=$id ){
                            $alumno->getResponsable2()->setActivo(1);
                            break;
                        }
                    }    
                }
            }
            $em->persist($alumno);

            // Se elimina el registro en la tabla matrículas.
            $matricula=$em->getRepository('BackendBundle:Matricula')->findOneByAlumno($alumno);
            $em->remove($matricula);
            //Se elimina el nuevoalumno del sistema.
            $em->remove($alumno);
        }
        //antiguo alumno
        else{
            if($alumno->getResponsable1()){
                //Se busca todos los alumnos que contiene como primer responsable el del alumno actual.
                $busqueda=$em->getRepository('BackendBundle:Alumno')->findByResponsables($alumno->getResponsable1());
                // Si no tiene más hermanos en el sistema se desactiva el responsable.
                if(count($busqueda)==1){
                    $alumno->getResponsable1()->setActivo(0);
                }
                // Si existe algún alummno con el mismo responsable, se comprueba si está activo el alumno para dejar activo al responsable.
                else{
                    $alumno->getResponsable1()->setActivo(0);
                    foreach ($busqueda as $entity) {
                        if($entity->getActivo()==1 && $entity->getId()!=$id){
                            $alumno->getResponsable1()->setActivo(1);
                            break;
                        }
                    }  
                }
            }
            if($alumno->getResponsable2()){
                //Se busca todos los alumnos que contiene como segundo responsable el del alumno actual.
                $busqueda=$em->getRepository('BackendBundle:Alumno')->findByResponsables($alumno->getResponsable2());
                // Si no tiene más hermanos en el sistema se desactiva el responsable.
                if(count($busqueda)==1){
                    $alumno->getResponsable2()->setActivo(0);
                }
                // Si existe algún alummno con el mismo responsable, se comprueba si está activo el alumno para dejar activo al responsable.
                else{
                    $alumno->getResponsable2()->setActivo(0);
                    foreach ($busqueda as $entity) {
                        if($entity->getActivo()==1 && $entity->getId()!=$id ){
                            $alumno->getResponsable2()->setActivo(1);
                            break;
                        }
                    }    
                }
            }
            // Se elimina el registro en la tabla matrículas.
            $matricula=$em->getRepository('BackendBundle:Matricula')->findOneByAlumno($alumno);
            $em->remove($matricula);

            // Se desactiva el alumno y se elimina los datos de la matrícula actual.
            $alumno->SetCurso(Null);
            $alumno->SetGrupo(Null);
            $alumno->SetNumAlum(Null);
            $alumno->SetAnyoAcademico(Null);
            $alumno->SetActivo(0);

            $em->persist($alumno);
        }
        $em->flush();

        //Si el alumno estaba asignado a un grupo se restablece el número de alumno del resto.
        if($grupo){
            $alumnos=$em->getRepository('BackendBundle:Alumno')->findByGrupoOrdenado($grupo);
            //Se comprueba si era el último para no hacer nada.
            if($numAlum!=count($alumnos)){
                $cont=1;
                foreach ($alumnos as $alum) {
                    $alum->SetNumAlum($cont);
                    $cont++;
                    $em->persist($alum);
                    $em->flush();
                } 
            }
        }

        return new JsonResponse(array(
            'success' => true), 200);
    }






}
