<?php

namespace Application\EservicesBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Application\EservicesBundle\Entity\EproduitNotes;
use Application\EservicesBundle\Form\EproduitNotesType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

/**
 * EproduitNotes controller.
 *
 */
class EproduitNotesController extends Controller {

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
     * Lists all EproduitNotes entities.
     *
     */
    public function indexAction() {
        $em = $this->getDoctrine()->getManager();

        //$query = $em->getRepository('ApplicationEservicesBundle:EproduitNotes')->findAll();
        $query = $em->getRepository('ApplicationEservicesBundle:EproduitNotes')->myFindAll();

        //$query = $em->getRepository('ApplicationEservicesBundle:Eproduit')->myFindAll($user_id);
        /* return $this->render('ApplicationEservicesBundle:EproduitNotes:index.html.twig', array(
          'entities' => $entities,
          ));

         */


        $paginator = $this->get('knp_paginator');
        $pagename1 = 'page1'; // Set custom page variable name
        $page1 = $this->get('request')->query->get($pagename1, 1); // Get custom page variable
        $paginationa = $paginator->paginate(
                $query, $page1, 10, array('pageParameterName' => $pagename1)
        );


        $paginationa->setTemplate('ApplicationEservicesBundle:pagination:twitter_bootstrap_pagination.html.twig');
        return $this->render('ApplicationEservicesBundle:EproduitNotes:index.html.twig', array(
                    'paginationa' => $paginationa,
                ));
    }

    /**
     * Creates a new EproduitNotes entity.
     *
     */
    public function createAction(Request $request) {
        $em = $this->getDoctrine()->getManager();
        $entity = new EproduitNotes();

        $form = $this->createForm(new EproduitNotesType(), $entity);
        $form->bind($request);

        if ($form->isValid()) {
            $postData = $request->request->get('application_eservicesbundle_eproduitnotestype');

            //  var_dump($postData);
            //  $data = $form->getData();
            /*   print_r($postData);
              exit(1); */
            /* if (!isset($postData->user)){
              $user_id=$this->getuserid();
              $current_user = $em->getRepository('ApplicationSonataUserBundle:User')->find($user_id);
              $entity->setUser($current_user);
              } */
            /*  if (!isset($postData->user)){

              $entity_produit = $em->getRepository('ApplicationEservicesBundle:Eproduit')->findOneById($id);
              if (!$entity_produit) {
              throw $this->createNotFoundException('Ce produit n\'existe pas.');
              } $user_id=$this->getuserid();
              $current_user = $em->getRepository('ApplicationSonataUserBundle:User')->find($user_id);
              $entity->setUser($current_user);
              } */

            //  $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('enotes_show', array('id' => $entity->getId())));
        }

        return $this->render('ApplicationEservicesBundle:EproduitNotes:new.html.twig', array(
                    'entity' => $entity,
                    'form' => $form->createView(),
                ));
    }

    /**
     * Displays a form to create a new EproduitNotes entity.
     *
     */
    public function newAction() {
        $entity = new EproduitNotes();
        $form = $this->createForm(new EproduitNotesType(), $entity);

        return $this->render('ApplicationEservicesBundle:EproduitNotes:new.html.twig', array(
                    'entity' => $entity,
                    'form' => $form->createView(),
                ));
    }

    /**
     * Finds and displays a EproduitNotes entity.
     *
     */
    public function showAction($id) {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('ApplicationEservicesBundle:EproduitNotes')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find EproduitNotes entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return $this->render('ApplicationEservicesBundle:EproduitNotes:show.html.twig', array(
                    'entity' => $entity,
                    'delete_form' => $deleteForm->createView(),));
    }

    /**
     * Displays a form to edit an existing EproduitNotes entity.
     *
     */
    /*
     * 
     *  $em = $this->getDoctrine()->getManager();
      $user = $this->get('security.context')->getToken()->getUser();
      $user_security = $this->container->get('security.context');
      $validation=1;
      if ($user_security->isGranted('IS_AUTHENTICATED_FULLY')) {
      $user_id = $user->getId();


      $current_user = $em->getRepository('ApplicationSonataUserBundle:User')->find($user_id);
      $produit = $this->getProduit($produit_id);
      //Creation entity EproduitComments
      $comment = new EproduitComments();
      $comment->setProduit($produit);
      $comment->setUser($current_user);
      $form   = $this->createForm(new EproduitCommentsType(), $comment);

      return $this->render('ApplicationEservicesBundle:EproduitComments:form.html.twig', array(
      'comment' => $comment,
      'form'   => $form->createView(),
      'validation' => $validation,
      ));

      } else {
      // throw $this->createNotFoundException('User not connected.');
      $validation=0;
      return $this->render('ApplicationEservicesBundle:EproduitComments:form.html.twig', array(
      'validation' => $validation,

      ));
      }
     */
    public function addnoteAction($id) {

        // recup du user id
        // test si note existe ($iser_id et $id_produit)

        $em = $this->getDoctrine()->getManager();

        $user_id = $this->getuserid();

        /* $user = $this->get('security.context')->getToken()->getUser();
          $user_security = $this->container->get('security.context');
          if ($user_security->isGranted('IS_AUTHENTICATED_FULLY')) {
          $user_id = $user->getId();
          } */


        $em = $this->getDoctrine()->getManager();
        $entity_produit = $em->getRepository('ApplicationEservicesBundle:Eproduit')->findOneById($id);
        if (!$entity_produit) {
            throw $this->createNotFoundException('Ce produit n\'existe pas.');
        }

        $entity_note = $em->getRepository('ApplicationEservicesBundle:EproduitNotes')->findOneBy(array(
            'user' => $user_id,
            'produit' => $id));
        // produit deja noté
        if ($entity_note) {
            return $this->redirect($this->generateUrl('enotes_edit', array(
                                'id' => $entity_note->getId()))
            );
            throw $this->createNotFoundException('Vous avez deja noté ce produit.');
        } else {

            $current_user = $em->getRepository('ApplicationSonataUserBundle:User')->find($user_id);

            $entity = new EproduitNotes();
            $entity->setProduit($entity_produit);
            $entity->setUser($current_user);

            $newForm = $this->createForm(new EproduitNotesType($user_id, $id), $entity);

            return $this->render('ApplicationEservicesBundle:EproduitNotes:addnote.html.twig', array(
                        'entity' => $entity,
                        'new_form' => $newForm->createView(),
                    ));
        }
    }

    /**
     * Displays a form to edit an existing EproduitNotes entity.
     * $id correspond a id de la note
     *
     */
    public function editAction($id) {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('ApplicationEservicesBundle:EproduitNotes')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find EproduitNotes entity.');
        }

        $editForm = $this->createForm(new EproduitNotesType(), $entity);
        $deleteForm = $this->createDeleteForm($id);

        return $this->render('ApplicationEservicesBundle:EproduitNotes:edit.html.twig', array(
                    'entity' => $entity,
                    'edit_form' => $editForm->createView(),
                    'delete_form' => $deleteForm->createView(),
                ));
    }

    public function editadminAction($id) {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('ApplicationEservicesBundle:EproduitNotes')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find EproduitNotes entity.');
        }

        $editForm = $this->createForm(new EproduitNotesType(), $entity);
        $deleteForm = $this->createDeleteForm($id);

        return $this->render('ApplicationEservicesBundle:EproduitNotes:edit.html.twig', array(
                    'entity' => $entity,
                    'edit_form' => $editForm->createView(),
                    'delete_form' => $deleteForm->createView(),
                ));
    }

    /**
     * Edits an existing EproduitNotes entity.
     *
     */
    public function updateAction(Request $request, $id) {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('ApplicationEservicesBundle:EproduitNotes')->find($id);
        $session = $this->getRequest()->getSession();
        if (!$entity) {
            throw $this->createNotFoundException('Unable to find EproduitNotes entity.');
        }

        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createForm(new EproduitNotesType(), $entity);
        $editForm->bind($request);

        if ($editForm->isValid()) {
            $em->persist($entity);
            $em->flush();
            $id_produit=$entity->getId();
            $session->getFlashBag()->add('warning', "Enregistrement $id_produit (notes id=$id) update successfull");
            $route_back = $session->get('buttonretour');
           if (isset($route_back))
                return $this->redirect($this->generateUrl($route_back, array('id' => $id)));
            else
             return $this->redirect($this->generateUrl('enotes'));
        }
        //return $this->redirect($this->generateUrl('enotes_edit'));
        //}

        return $this->render('ApplicationEservicesBundle:EproduitNotes:edit.html.twig', array(
                    'entity' => $entity,
                    'edit_form' => $editForm->createView(),
                    'delete_form' => $deleteForm->createView(),
                ));
    }

    /**
     * Deletes a EproduitNotes entity.
     *
     */
    public function deleteAction(Request $request, $id) {
        $form = $this->createDeleteForm($id);
        $form->bind($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('ApplicationEservicesBundle:EproduitNotes')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find EproduitNotes entity.');
            }

            $em->remove($entity);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('enotes'));
    }

    /**
     * Creates a form to delete a EproduitNotes entity by id.
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

    protected function getProduit($produit_id) {
        $em = $this->getDoctrine()
                ->getEntityManager();

        $produit = $em->getRepository('ApplicationEservicesBundle:Eproduit')->find($produit_id);

        if (!$produit) {
            throw $this->createNotFoundException('Unable to find Produit.');
        }

        return $produit;
    }

}
