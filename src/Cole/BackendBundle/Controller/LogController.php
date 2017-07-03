<?php

namespace Cole\BackendBundle\Controller;


use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use Cole\BackendBundle\Entity\Log;

/**
 * Log controller.
 *
 */
class LogController extends Controller
{

    /**
     * Lists all Log entities.
     *
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $entities = $em->getRepository('BackendBundle:Log')->findAll();

        return $this->render('BackendBundle:Log:index.html.twig', array(
            'entities' => $entities,
        ));
    }

    /**
     * Finds and displays a Log entity.
     *
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('BackendBundle:Log')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Log entity.');
        }

        return $this->render('BackendBundle:Log:show.html.twig', array(
            'entity'      => $entity,
        ));
    }

}
