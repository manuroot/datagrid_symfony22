<?php

namespace Application\EpostBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Application\EpostBundle\Entity\EpostNotes;
use Application\EpostBundle\Form\EpostNotesType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

/**
 * EpostNotes controller.
 *
 */
class EpostNotesController extends Controller {

    public function ajaxAction(Request $request) {
        //$value = $request->get('term');
        // .... (Search values)
        $search = array('Bar', 'Foo', 'toto', 'truc');

        $response = new Response();
        $response->setContent(json_encode($search));

        return $response;
    }

    /*
     * Recuperer l'id du user et verifie que $user est authentifié
     */

    private function getuserid() {

        $em = $this->getDoctrine()->getManager();
        $user = $this->get('security.context')->getToken()->getUser();
        $user_security = $this->container->get('security.context');
        if ($user_security->isGranted('IS_AUTHENTICATED_FULLY')) {
            //if ($user_security->isGranted('IS_AUTHENTICATED_FULLY')) {
            // authenticated REMEMBERED, FULLY will imply REMEMBERED (NON anonymous)
            $user_id = $user->getId();
        } else {
            $user_id = 0;
        }
        return ($user_id);
    }

    /**
     * Lists all EpostNotes entities.
     *
     */
    public function indexAction() {
        $em = $this->getDoctrine()->getManager();

        //$query = $em->getRepository('ApplicationEpostBundle:EpostNotes')->findAll();
        $query = $em->getRepository('ApplicationEpostBundle:EpostNotes')->myFindAll();

        //$query = $em->getRepository('ApplicationEpostBundle:Eproduit')->myFindAll($user_id);
        /* return $this->render('ApplicationEpostBundle:EpostNotes:index.html.twig', array(
          'entities' => $entities,
          ));

         */


        $paginator = $this->get('knp_paginator');
        $pagename1 = 'page1'; // Set custom page variable name
        $page1 = $this->get('request')->query->get($pagename1, 1); // Get custom page variable
        $paginationa = $paginator->paginate(
                $query, $page1, 10, array('pageParameterName' => $pagename1)
        );


        $paginationa->setTemplate('ApplicationEpostBundle:pagination:twitter_bootstrap_pagination.html.twig');
        return $this->render('ApplicationEpostBundle:EpostNotes:index.html.twig', array(
                    'paginationa' => $paginationa,
                ));
    }

    /**
     * Creates a new EpostNotes entity.
     *
     */
    public function createAction(Request $request) {
        $em = $this->getDoctrine()->getManager();
        $entity = new EpostNotes();

        $form = $this->createForm(new EpostNotesType(), $entity);
        $form->bind($request);

        if ($form->isValid()) {
            $postData = $request->request->get('application_epostbundle_epostnotestype');

            
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('epostnotes_show', array('id' => $entity->getId())));
        }

        return $this->render('ApplicationEpostBundle:EpostNotes:new.html.twig', array(
                    'entity' => $entity,
                    'form' => $form->createView(),
                ));
    }

    /**
     * Displays a form to create a new EpostNotes entity.
     *
     */
    public function newAction() {
        $entity = new EpostNotes();
        $form = $this->createForm(new EpostNotesType(), $entity);

        return $this->render('ApplicationEpostBundle:EpostNotes:new.html.twig', array(
                    'entity' => $entity,
                    'form' => $form->createView(),
                ));
    }

    /**
     * Finds and displays a EpostNotes entity.
     *
     */
    public function showAction($id) {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('ApplicationEpostBundle:EpostNotes')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find EpostNotes entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return $this->render('ApplicationEpostBundle:EpostNotes:show.html.twig', array(
                    'entity' => $entity,
                    'delete_form' => $deleteForm->createView(),));
    }

    public function addnoteAction($id) {

        // recup du user id
     
        $em = $this->getDoctrine()->getManager();

        $user_id = $this->getuserid();

        /* $user = $this->get('security.context')->getToken()->getUser();
          $user_security = $this->container->get('security.context');
          if ($user_security->isGranted('IS_AUTHENTICATED_FULLY')) {
          $user_id = $user->getId();
          } */


        $em = $this->getDoctrine()->getManager();
        $entity_epost = $em->getRepository('ApplicationEpostBundle:Epost')->findOneById($id);
        if (!$entity_epost) {
            throw $this->createNotFoundException('Ce post n\'existe pas.');
        }

        $entity_note = $em->getRepository('ApplicationEpostBundle:EpostNotes')->findOneBy(array(
            'user' => $user_id,
            'epost' => $id));
        // post deja noté
        if ($entity_note) {
            return $this->redirect($this->generateUrl('epostnotes_edit', array(
                                'id' => $entity_note->getId()))
            );
            throw $this->createNotFoundException('Vous avez deja noté ce post.');
        } else {

            $current_user = $em->getRepository('ApplicationSonataUserBundle:User')->find($user_id);

            $entity = new EpostNotes();
            $entity->setEpost($entity_epost);
            $entity->setUser($current_user);

            $newForm = $this->createForm(new EpostNotesType($user_id, $id), $entity);

            return $this->render('ApplicationEpostBundle:EpostNotes:addnote.html.twig', array(
                        'entity' => $entity,
                        'new_form' => $newForm->createView(),
                    ));
        }
    }

    /**
     * Displays a form to edit an existing EpostNotes entity.
     * $id correspond a id de la note
     *
     */
    public function editAction($id) {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('ApplicationEpostBundle:EpostNotes')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find EpostNotes entity.');
        }

        $editForm = $this->createForm(new EpostNotesType(), $entity);
        $deleteForm = $this->createDeleteForm($id);

        return $this->render('ApplicationEpostBundle:EpostNotes:edit.html.twig', array(
                    'entity' => $entity,
                    'edit_form' => $editForm->createView(),
                    'delete_form' => $deleteForm->createView(),
                ));
    }

    public function editadminAction($id) {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('ApplicationEpostBundle:EpostNotes')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find EpostNotes entity.');
        }

        $editForm = $this->createForm(new EpostNotesType(), $entity);
        $deleteForm = $this->createDeleteForm($id);

        return $this->render('ApplicationEpostBundle:EpostNotes:edit.html.twig', array(
                    'entity' => $entity,
                    'edit_form' => $editForm->createView(),
                    'delete_form' => $deleteForm->createView(),
                ));
    }

    /**
     * Edits an existing EpostNotes entity.
     *
     */
    public function updateAction(Request $request, $id) {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('ApplicationEpostBundle:EpostNotes')->find($id);
        $session = $this->getRequest()->getSession();
        if (!$entity) {
            throw $this->createNotFoundException('Unable to find EpostNotes entity.');
        }

        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createForm(new EpostNotesType(), $entity);
        $editForm->bind($request);

        if ($editForm->isValid()) {
            $em->persist($entity);
            $em->flush();
            $id_post=$entity->getId();
            $session->getFlashBag()->add('warning', "Enregistrement $id_post (notes id=$id) update successfull");
            $route_back = $session->get('buttonretour');
           if (isset($route_back))
                return $this->redirect($this->generateUrl($route_back, array('id' => $id)));
            else
             return $this->redirect($this->generateUrl('enotes'));
        }
        //return $this->redirect($this->generateUrl('enotes_edit'));
        //}

        return $this->render('ApplicationEpostBundle:EpostNotes:edit.html.twig', array(
                    'entity' => $entity,
                    'edit_form' => $editForm->createView(),
                    'delete_form' => $deleteForm->createView(),
                ));
    }

    /**
     * Deletes a EpostNotes entity.
     *
     */
    public function deleteAction(Request $request, $id) {
        $form = $this->createDeleteForm($id);
        $form->bind($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('ApplicationEpostBundle:EpostNotes')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find EpostNotes entity.');
            }

            $em->remove($entity);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('enotes'));
    }

    /**
     * Creates a form to delete a EpostNotes entity by id.
     *
     * @param mixed $id The entity id
     *
     * @return Symfony\Component\Form\Form The form
     */
    private function createDeleteForm($id) {
        return $this->createFormBuilder(array('id' => $id))
                        ->add('id', 'hidden')
                        ->getForm()
        ;
    }

    protected function getEpost($epost_id) {
        $em = $this->getDoctrine()
                ->getEntityManager();

        $epost = $em->getRepository('ApplicationEpostBundle:Epost')->find($epost_id);

        if (!$epost) {
            throw $this->createNotFoundException('Unable to find Post.');
        }

        return $epost;
    }

}
