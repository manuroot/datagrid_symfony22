<?php

namespace Application\CertificatsBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use Application\CertificatsBundle\Entity\Eservice;
use Application\CertificatsBundle\Form\EserviceType;

/**
 * Eservice controller.
 *
 */
class EserviceController extends Controller
{
    /**
     * Lists all Eservice entities.
     *
     */
    public function indexAction()
    {
        
        

        $em = $this->getDoctrine()->getManager();
        $query = $em->getRepository('ApplicationCertificatsBundle:Eservice')->findAll();
        
        //$query = $em->getRepository('ApplicationCertificatsBundle:CertificatsCenter')->myFindaAll();
        $paginator = $this->get('knp_paginator');
        $pagination = $paginator->paginate(
                $query, $this->get('request')->query->get('page', 1)/* page number */, 10/* limit per page */
        );
        $pagination->setTemplate('ApplicationCertificatsBundle:pagination:sliding.html.twig');
        //$pagination->setTemplate('ApplicationMyNotesBundle:pagination:sliding.html.twig');
        return $this->render('ApplicationCertificatsBundle:Eservice:index.html.twig', array(
                    'pagination' => $pagination,
                ));
        
        
       // $em = $this->getDoctrine()->getManager();

       

       /* return $this->render('ApplicationCertificatsBundle:Eservice:index.html.twig', array(
             'pagination' => $pagination,
            //'entities' => $entities,
        ));*/
    }

    /**
     * Creates a new Eservice entity.
     *
     */
    public function createAction(Request $request)
    {
        $entity  = new Eservice();
        $form = $this->createForm(new EserviceType(), $entity);
        $form->bind($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('eservice_show', array('id' => $entity->getId())));
        }

        return $this->render('ApplicationCertificatsBundle:Eservice:new.html.twig', array(
            'entity' => $entity,
            'form'   => $form->createView(),
        ));
    }

    /**
     * Displays a form to create a new Eservice entity.
     *
     */
    public function newAction()
    {
        $entity = new Eservice();
        $form   = $this->createForm(new EserviceType(), $entity);

        return $this->render('ApplicationCertificatsBundle:Eservice:new.html.twig', array(
            'entity' => $entity,
            'form'   => $form->createView(),
        ));
    }

    /**
     * Finds and displays a Eservice entity.
     *
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('ApplicationCertificatsBundle:Eservice')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Eservice entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return $this->render('ApplicationCertificatsBundle:Eservice:show.html.twig', array(
            'entity'      => $entity,
            'delete_form' => $deleteForm->createView(),        ));
    }

    /**
     * Displays a form to edit an existing Eservice entity.
     *
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('ApplicationCertificatsBundle:Eservice')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Eservice entity.');
        }

        $editForm = $this->createForm(new EserviceType(), $entity);
        $deleteForm = $this->createDeleteForm($id);

        return $this->render('ApplicationCertificatsBundle:Eservice:edit.html.twig', array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Edits an existing Eservice entity.
     *
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('ApplicationCertificatsBundle:Eservice')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Eservice entity.');
        }

        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createForm(new EserviceType(), $entity);
        $editForm->bind($request);

        if ($editForm->isValid()) {
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('eservice_edit', array('id' => $id)));
        }

        return $this->render('ApplicationCertificatsBundle:Eservice:edit.html.twig', array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a Eservice entity.
     *
     */
    public function deleteAction(Request $request, $id)
    {
        $form = $this->createDeleteForm($id);
        $form->bind($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('ApplicationCertificatsBundle:Eservice')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find Eservice entity.');
            }

            $em->remove($entity);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('eservice'));
    }

    /**
     * Creates a form to delete a Eservice entity by id.
     *
     * @param mixed $id The entity id
     *
     * @return Symfony\Component\Form\Form The form
     */
    private function createDeleteForm($id)
    {
        return $this->createFormBuilder(array('id' => $id))
            ->add('id', 'hidden')
            ->getForm()
        ;
    }
}
