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

        $letra=$this->get('request')->request->get('letra');
        $curso=$this->get('request')->request->get('curso');
        $nivel=$this->get('request')->request->get('nivel');
        $aula=$this->get('request')->request->get('aula');

        
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('BackendBundle:Curso')->findCursoByNivel($curso,$nivel);
        if (!$entity) {
                throw $this->createNotFoundException('Unable to find Curso entity.');
            }

        $grupo = $em->getRepository('BackendBundle:Grupo')->findGrupoByLetter($entity,$letra);
        if (!$grupo) {
                throw $this->createNotFoundException('Unable to find Curso entity.');
            }

        if($aula!==""){
            $grupo->setAula($aula);     
        }else{
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








}
