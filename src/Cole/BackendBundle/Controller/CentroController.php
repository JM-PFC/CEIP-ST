<?php

namespace Cole\BackendBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;

use Cole\BackendBundle\Entity\Centro;
use Cole\BackendBundle\Form\CentroType;

/**
 * Centro controller.
 *
 */
class CentroController extends Controller
{

    /**
     * Lists all Centro entities.
     *
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $entities = $em->getRepository('BackendBundle:Centro')->findAll();

        return $this->render('BackendBundle:Centro:index.html.twig', array(
            'entities' => $entities,
        ));
    }
    public function horariosAtencionAction()
    {
        $em = $this->getDoctrine()->getManager();

        $centro =$em->getRepository('BackendBundle:Centro')->findCentro();

        return $this->render('BackendBundle:Centro:horariosAtencion.html.twig', array(
            'inicio' => $centro->getInicioHorario(),
            'fin' => $centro->getFinHorario(),
            'h_secretaria' => $centro->getHSecretaria(),
            'h_direccion' => $centro->getHDireccion(),
            'h_estudios' => $centro->getHEstudios(),
            'h_tutorias' => $centro->getHTutorias(),
        ));
    }

    /**
     * Creates a new Centro entity.
     *
     */
    public function createAction(Request $request)
    {
        $entity = new Centro();
        $form = $this->createCreateForm($entity);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('centro_show', array('id' => $entity->getId())));
        }

        return $this->render('BackendBundle:Centro:new.html.twig', array(
            'entity' => $entity,
            'form'   => $form->createView(),
        ));
    }

    /**
     * Creates a form to create a Centro entity.
     *
     * @param Centro $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createCreateForm(Centro $entity)
    {
        $form = $this->createForm(new CentroType(), $entity, array(
            'action' => $this->generateUrl('centro_create'),
            'method' => 'POST',
        ));

        $form->add('submit', 'submit', array('label' => 'Guardar Datos'));

        return $form;
    }

    /**
     * Displays a form to create a new Centro entity.
     *
     */
    public function newAction()
    {
        $entity = new Centro();
        $form   = $this->createCreateForm($entity);

        return $this->render('BackendBundle:Centro:new.html.twig', array(
            'entity' => $entity,
            'form'   => $form->createView(),
        ));
    }

    /**
     * Finds and displays a Centro entity.
     *
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('BackendBundle:Centro')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Centro entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return $this->render('BackendBundle:Centro:show.html.twig', array(
            'entity'      => $entity,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing Centro entity.
     *
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('BackendBundle:Centro')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Centro entity.');
        }

        $editForm = $this->createEditForm($entity);
        $deleteForm = $this->createDeleteForm($id);

        return $this->render('BackendBundle:Centro:edit.html.twig', array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
    * Creates a form to edit a Centro entity.
    *
    * @param Centro $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createEditForm(Centro $entity)
    {
        $form = $this->createForm(new CentroType(), $entity, array(
            'action' => $this->generateUrl('centro_update', array('id' => $entity->getId())),
            'method' => 'PUT',
        ));

        $form->add('submit', 'submit', array('label' => 'Guardar Cambios'));

        return $form;
    }
    /**
     * Edits an existing Centro entity.
     *
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('BackendBundle:Centro')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Centro entity.');
        }

        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createEditForm($entity);
        $editForm->handleRequest($request);

        if ($editForm->isValid()) {
            $em->flush();

            return $this->redirect($this->generateUrl('centro_edit', array('id' => $id)));
        }

        return $this->render('BackendBundle:Centro:edit.html.twig', array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }
    /**
     * Deletes a Centro entity.
     *
     */
    public function deleteAction(Request $request, $id)
    {
        $form = $this->createDeleteForm($id);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('BackendBundle:Centro')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find Centro entity.');
            }

            $em->remove($entity);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('centro'));
    }

    /**
     * Creates a form to delete a Centro entity by id.
     *
     * @param mixed $id The entity id
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm($id)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('centro_delete', array('id' => $id)))
            ->setMethod('DELETE')
            ->add('submit', 'submit', array('label' => 'Delete'))
            ->getForm()
        ;
    }


    public function fechaCursoAction()
    {
        $fecha_ini=$this->get('request')->request->get('fecha_ini');
        $fecha_fin=$this->get('request')->request->get('fecha_fin');


        list($día_ini,$mes_ini,$año_ini) = split('[/.-]', $fecha_ini);
        list($día_fin,$mes_fin,$año_fin) = split('[/.-]', $fecha_fin);

        $dt_ini = new \DateTime();
        $dt_ini->setDate($año_ini, $mes_ini, $día_ini);

        $dt_fin = new \DateTime();
        $dt_fin->setDate($año_fin, $mes_fin, $día_fin);

        $em = $this->getDoctrine()->getEntityManager();

        $entity = $em->getRepository('BackendBundle:Centro')->findAll();

        $entity[0]->setInicioCurso($dt_ini);
        $entity[0]->setFinCurso($dt_fin);
        
        $em->persist($entity[0]);

        $em->flush();
        return new JsonResponse(array('message' => 'Success!','success' => true), 200);

    }


    public function horarioCentroAction()
    {
        $em = $this->getDoctrine()->getEntityManager();

        $inicio=$this->get('request')->request->get('inicio');
        $fin=$this->get('request')->request->get('fin');

        $centro =$em->getRepository('BackendBundle:Centro')->findCentro();

        $centro->setInicioHorario($inicio);
        $centro->setFinHorario($fin);
        
        $em->persist($centro);

        $em->flush();
        return new JsonResponse(array('message' => 'Success!','success' => true), 200);
    }

    public function registrarHorariosAtencionAction()
    {
        $em = $this->getDoctrine()->getManager();
        $tipo=$this->get('request')->request->get('tipo');
        $horario=$this->get('request')->request->get('contenido');

        $centro =$em->getRepository('BackendBundle:Centro')->findCentro();

        if($tipo=="secretaria"){
            $centro->setHSecretaria($horario);
        }
        else if($tipo=="direccion"){
            $centro->setHDireccion($horario);
        }
        else if($tipo=="estudios"){
            $centro->setHEstudios($horario);
        }
        else{
            $centro->setHTutorias($horario);    
        }
        
        $em->persist($centro);

        $em->flush();
        return new JsonResponse(array('message' => 'Success!','success' => true), 200);
    }


    public function ObtenerCursoAcademicoAction()
    {
        $em = $this->getDoctrine()->getManager();
        $tipo=$this->get('request')->request->get('tipo');
        $horario=$this->get('request')->request->get('contenido');


        // Se obtiene la fecha inicial y final del curso para usar luego el año correspondiente. 
        $ini_curso=$em->getRepository('BackendBundle:Centro')->findInicioCurso();
        $array_ini=explode("-",$ini_curso["inicioCurso"]->format('Y-m-d')); //Conversión de array a String
        $fin_curso=$em->getRepository('BackendBundle:Centro')->findFinCurso();
        $array_fin=explode("-",$fin_curso["finCurso"]->format('Y-m-d'));

        return new JsonResponse(array('message' => 'Success!','inicio' => $array_ini[0], 'fin' => $array_fin[0] ), 200);
    }
    
}
