<?php

namespace Cole\BackendBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;

use Cole\BackendBundle\Entity\Curso;
use Cole\BackendBundle\Entity\Grupo;
use Cole\BackendBundle\Entity\Imparte;
use Cole\BackendBundle\Form\CursoType;

/**
 * Curso controller.
 *
 */
class CursoController extends Controller
{

    /**
     * Lists all Curso entities.
     *
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $entities = $em->getRepository('BackendBundle:Curso')->findAllCursos();

        return $this->render('BackendBundle:Curso:index.html.twig', array(
            'entities' => $entities,
        ));
    }


    public function listaCursosAction()
    {
        $em = $this->getDoctrine()->getManager();

        $cursos = $em->getRepository('BackendBundle:Curso')->findAllCursos();
        

        return $this->render('BackendBundle:Curso:listaCursos.html.twig', array(
            'cursos' => $cursos,
        ));
    }

    public function listaCursosPrimariaAction()
    {
        $em = $this->getDoctrine()->getManager();

        $cursos = $em->getRepository('BackendBundle:Curso')->findCursosByNivel("Primaria");

        return $this->render('BackendBundle:Curso:listaCursos.html.twig', array(
            'cursos' => $cursos,
        ));
    }
    /**
     * Creates a new Curso entity.
     *
     */
    public function createAction(Request $request)
    {
      // if request is XmlHttpRequest (AJAX) but not a POSt, throw an exception
      if ($request->isXmlHttpRequest() && !$request->isMethod('POST')) {
        throw new HttpException('XMLHttpRequests/AJAX calls must be POSTed');}

        $entity = new Curso();
        $form = $this->createCreateForm($entity);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            // Se comprueba que no exista el curso en el sistema.
            $curso = $em->getRepository('BackendBundle:Curso')->findCursoByNivel($entity->getCurso(),$entity->getNivel());
            if($curso){
                return new JsonResponse(array(
                    'error' => 'existe',
                    'success' => true), 200);
            }
            //Añadimos el numéro de orden (números de cursos +1)
            $query = $em->createQueryBuilder()
                ->select('COUNT(c)') 
                ->from('BackendBundle:Curso', 'c')
                ->getQuery();

            $total = $query->getSingleScalarResult();
            $entity->setNumOrden($total+1);
            $entity->setRatio(25);
            $em->persist($entity);
            $em->flush();

            if ($request->isXmlHttpRequest()) {
                return new JsonResponse(array(
                    'message' => 'Success!',
                    'success' => true), 200);
            }

            return $this->redirect($this->generateUrl('curso_show', array('id' => $entity->getId())));
        }

        if ($request->isMethod('POST')) {
            return new JsonResponse(array(
            'result' => 0,
            'message' => 'Invalid form',
            'data' => $this->getErrorMessages($form,$form->getName()),
            'success' => false), 400);
        }

        return $this->render('BackendBundle:Curso:new.html.twig', array(
            'entity' => $entity,
            'form'   => $form->createView(),
        ));
    }

    /**
     * Creates a form to create a Curso entity.
     *
     * @param Curso $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createCreateForm(Curso $entity)
    {
        $form = $this->createForm(new CursoType(), $entity, array(
            'action' => $this->generateUrl('curso_create'),
            'method' => 'POST',
        ));

        $form->add('submit', 'submit', array('label' => 'Insertar'));

        return $form;
    }

    /**
     * Displays a form to create a new Curso entity.
     *
     */
    public function newAction()
    {
        $entity = new Curso();
        $form   = $this->createCreateForm($entity);

        return $this->render('BackendBundle:Curso:new.html.twig', array(
            'entity' => $entity,
            'form'   => $form->createView(),
        ));
    }

    /**
     * Finds and displays a Curso entity.
     *
     */
    public function showAction()
    {
        $em = $this->getDoctrine()->getManager();

        $entities = $em->getRepository('BackendBundle:Curso')->findAllCursos();

        return $this->render('BackendBundle:Curso:show.html.twig', array(
            'entities' => $entities,
        ));
    }

    /**
     * Displays a form to edit an existing Curso entity.
     *
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('BackendBundle:Curso')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Curso entity.');
        }

        $editForm = $this->createEditForm($entity);
        $deleteForm = $this->createDeleteForm($id);

        return $this->render('BackendBundle:Curso:edit.html.twig', array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
    * Creates a form to edit a Curso entity.
    *
    * @param Curso $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createEditForm(Curso $entity)
    {
        $form = $this->createForm(new CursoType(), $entity, array(
            'action' => $this->generateUrl('curso_update', array('id' => $entity->getId())),
            'method' => 'PUT',
        ));

        $form->add('submit', 'submit', array('label' => 'Actualizar'));

        return $form;
    }
    /**
     * Edits an existing Curso entity.
     *
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('BackendBundle:Curso')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Curso entity.');
        }

        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createEditForm($entity);
        $editForm->handleRequest($request);

        if ($editForm->isValid()) {
            // Se comprueba que no exista el curso en el sistema.
            $curso = $em->getRepository('BackendBundle:Curso')->findCursoByNivel($entity->getCurso(),$entity->getNivel());
            if($curso){
                return new JsonResponse(array(
                    'error' => 'existe',
                    'success' => true), 200);
            }
            
            $em->flush();

            return $this->redirect($this->generateUrl('curso_edit', array('id' => $id)));
        }

        return $this->render('BackendBundle:Curso:edit.html.twig', array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }
    /**
     * Deletes a Curso entity.
     *
     */
    public function deleteAction(Request $request, $id)
    {
        $form = $this->createDeleteForm($id);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();

            $entity = $em->getRepository('BackendBundle:Curso')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find Curso entity.');
            }
            /* Para validar si hay alumnos matriculados en el curso actual si finalmente hace falta 
            comprobarlo desde la tabla y no desde el campos curso de la tabla Alumno

            if(date("n")>=6){
                $actual=date("Y")." / ".(date("Y")+1);
            }
            else{
                $actual=(date("Y")-1)." / ".date("Y");
            }

            $matriculas=$em->getRepository('BackendBundle:Matricula')->findNumPorCurso($entity,$actual);

            if($matriculas){
            return new JsonResponse(array(
                    'validate' =>"matricula",
                    'success' => true), 200);
            }
            */

            $em->remove($entity);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('curso'));
    }

    /**
     * Creates a form to delete a Curso entity by id.
     *
     * @param mixed $id The entity id
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm($id)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('curso_delete', array('id' => $id)))
            ->setMethod('DELETE')
            ->add('submit', 'submit', array('label' => 'Eliminar'))
            ->getForm()
        ;
    }

    public function AsignarNumeroGruposAction(Request $request)
    {
        // if request is XmlHttpRequest (AJAX) but not a POSt, throw an exception
        if ($request->isXmlHttpRequest() && !$request->isMethod('POST')) {
            throw new HttpException('XMLHttpRequests/AJAX calls must be POSTed');
        }

        $num_grupos=$this->get('request')->request->get('num_grupos');
        $curso=$this->get('request')->request->get('curso');
        $nivel=$this->get('request')->request->get('nivel');
        
        $em = $this->getDoctrine()->getEntityManager();
        $entity = $em->getRepository('BackendBundle:Curso')->findCursoByNivel($curso,$nivel);
        if (!$entity) {
                throw $this->createNotFoundException('Unable to find Curso entity.');
            }
        $num_grupos_ant=$entity->getNumGrupos();

        $em = $this->getDoctrine()->getManager();

        if($num_grupos==$num_grupos_ant && $num_grupos==1){

            $grupo = $em->getRepository('BackendBundle:Grupo')->findGrupoByLetter($curso,"A");
            if (!$grupo) {
                $grupo_1= new Grupo();
                $grupo_1->setCurso($entity);
                $grupo_1->setLetra("A"); 
            }
            $em->persist($grupo_1);
        }
        else if($num_grupos>$num_grupos_ant){
            if($num_grupos_ant==1){

            $grupo = $em->getRepository('BackendBundle:Grupo')->findGrupoByLetter($curso,"B");
            if (!$grupo) {
                $grupo_2= new Grupo();
                $grupo_2->setCurso($entity);
                $grupo_2->setLetra("B"); 
            }

                $em->persist($grupo_2);

                if($num_grupos==3){

                $grupo = $em->getRepository('BackendBundle:Grupo')->findGrupoByLetter($curso,"C");
                if (!$grupo) {
                    $grupo_3= new Grupo();
                    $grupo_3->setCurso($entity);
                    $grupo_3->setLetra("C"); 
                }
                    $em->persist($grupo_3);
                }
            }
            else{
                
                $grupo = $em->getRepository('BackendBundle:Grupo')->findGrupoByLetter($curso,"C");
                if (!$grupo) {
                    $grupo_3= new Grupo();
                    $grupo_3->setCurso($entity);
                    $grupo_3->setLetra("C"); 
                }

                $em->persist($grupo_3);
            }    
        }
        else{
            if($num_grupos_ant==3){
                $grupo_3 = $em->getRepository('BackendBundle:Grupo')->findGrupoByLetter($entity,"C");
                $em->remove($grupo_3);

                if($num_grupos==1){
                    $grupo_2 = $em->getRepository('BackendBundle:Grupo')->findGrupoByLetter($entity,"B");
                    $em->remove($grupo_2);
                }
            }
            else{
                $grupo_2 = $em->getRepository('BackendBundle:Grupo')->findGrupoByLetter($entity,"B");
                $em->remove($grupo_2);
            }
        }

        $entity->setNumGrupos($num_grupos);     

        $em->persist($entity);
        $em->flush();
        
        if ($request->isXmlHttpRequest()) {
                return new JsonResponse(array(
                    'message' => 'Success!',
                    'success' => true), 200);
        }
    }

    public function ListarCursosAction()
    {
        $em = $this->getDoctrine()->getManager();

        $entities = $em->getRepository('BackendBundle:Curso')->findBy([],array('numOrden'=>'ASC'));

        return $this->render('BackendBundle:Curso:listar.html.twig', array(
            'entities' => $entities,
        ));
    }

    public function OrdenarCursosAction(Request $request)
    {
        // if request is XmlHttpRequest (AJAX) but not a POSt, throw an exception
        if ($request->isXmlHttpRequest() && !$request->isMethod('POST')) {
            throw new HttpException('XMLHttpRequests/AJAX calls must be POSTed');
        }

        $i = 1;
        $em = $this->getDoctrine()->getManager();
        // Obtenemos cada valor del array con la variable "curso" en $_POST(curso[]=2&curso[]=1....)
        foreach ($_POST['curso'] as $curso) {
            // Actualizamos el número de orden de cada curso.
            $qb = $em->createQueryBuilder();
            $q = $qb->update('BackendBundle:Curso', 'c')
                ->set('c.numOrden', $i)
                ->where('c.id = ?1')
                ->setParameter(1, $curso)
                ->getQuery();
            $q->execute();

            $i++;
        }

        if ($request->isXmlHttpRequest()) {
                return new JsonResponse(array(
                    'message' => 'Success!',
                    'success' => true), 200);
        }
    }


    public function RatioCursosAction()
    {
        $em = $this->getDoctrine()->getManager();

        $entities = $em->getRepository('BackendBundle:Curso')->findAllCursos();

        return $this->render('BackendBundle:Curso:ratio.html.twig', array(
            'entities' => $entities,
        ));
    }


    public function AsignarRatioAction(Request $request)
    {
        // if request is XmlHttpRequest (AJAX) but not a POSt, throw an exception
        if ($request->isXmlHttpRequest() && !$request->isMethod('POST')) {
            throw new HttpException('XMLHttpRequests/AJAX calls must be POSTed');
        }

        $curso=$this->get('request')->request->get('curso');
        $nivel=$this->get('request')->request->get('nivel');
        $ratio=$this->get('request')->request->get('ratio');
        
        $em = $this->getDoctrine()->getEntityManager();
        $entity = $em->getRepository('BackendBundle:Curso')->findCursoByNivel($curso,$nivel);
        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Curso entity.');
        }

        $em = $this->getDoctrine()->getManager();
        if($ratio!=="" && $ratio>=1 && $ratio<=35){
            $entity->setRatio($ratio);     
        }

        $em->persist($entity);
        $em->flush();
        
        if ($request->isXmlHttpRequest()) {
                return new JsonResponse(array(
                    'message' => 'Success!',
                    'success' => true), 200);
        }
    }


    public function ObtenerRatioMaxAction(Request $request)
    {
        // if request is XmlHttpRequest (AJAX) but not a POSt, throw an exception
        if ($request->isXmlHttpRequest() && !$request->isMethod('POST')) {
            throw new HttpException('XMLHttpRequests/AJAX calls must be POSTed');
        }
        
        $em = $this->getDoctrine()->getEntityManager();
        $entity = $em->getRepository('BackendBundle:Curso')->findAllCursos();
        $array;
        foreach ($entity as $curso) {
            $grupos = $em->getRepository('BackendBundle:Grupo')->findGruposByCurso($curso);
            $ratio=0;
            foreach ($grupos as $grupo) {
                $alumnos_grupo = $em->getRepository('BackendBundle:Alumno')->findByGrupo($grupo);
                if((int)$ratio<(int)count($alumnos_grupo)){
                   $ratio=count($alumnos_grupo);
                }
            }
            $array[$curso->getId()]=$ratio;
        }
        
        if ($request->isXmlHttpRequest()) {
                return new JsonResponse(array(
                    'ratio' => $array,
                    'success' => true), 200);
        }
    }

    public function CursosSinPlazasAction(Request $request)
    {
        // if request is XmlHttpRequest (AJAX) but not a POSt, throw an exception
        if ($request->isXmlHttpRequest() && !$request->isMethod('POST')) {
            throw new HttpException('XMLHttpRequests/AJAX calls must be POSTed');
        }
        
        $em = $this->getDoctrine()->getEntityManager();
        $entity = $em->getRepository('BackendBundle:Curso')->findAllCursos();
        $array=null;
        foreach ($entity as $curso) {
            $matriculados = $em->getRepository('BackendBundle:Alumno')->findAlumnosPorCurso($curso);
            $max=(int)$curso->getNumGrupos()*(int)$curso->getRatio();

            if (count($matriculados)>=$max) {
                $array[]=$curso->getId();
            }
        }
        
        if ($request->isXmlHttpRequest()) {
                return new JsonResponse(array(
                    'cursos' => $array,
                    'success' => true), 200);
        }
    }

}
