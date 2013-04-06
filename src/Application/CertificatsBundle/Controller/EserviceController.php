<?php

namespace Application\CertificatsBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Application\CertificatsBundle\Entity\Eservice;
use Application\Sonata\UserBundle\Entity\User;
use Application\CertificatsBundle\Form\EserviceType;

/**
 * Eservice controller.
 *
 */
class EserviceController extends Controller {

    /**
     * Lists all Eservice entities.
     *
     */
    public function indexAction() {



        $em = $this->getDoctrine()->getManager();
        $user = $this->get('security.context')->getToken()->getUser();
        $user_security = $this->container->get('security.context');
        //if( $user_security->isGranted('IS_AUTHENTICATED_REMEMBERED') ){
        if ($user_security->isGranted('IS_AUTHENTICATED_FULLY')) {
            // authenticated REMEMBERED, FULLY will imply REMEMBERED (NON anonymous)
            $user_id = $user->getId();
        } else {
            $user_id = 0;
        }
        //myFindOtherForme
        $query = $em->getRepository('ApplicationCertificatsBundle:Eservice')->myFindAll($user_id);
        $query_other = $em->getRepository('ApplicationCertificatsBundle:Eservice')->myFindOtherForme($user_id);


        //  $query = $em->getRepository('ApplicationCertificatsBundle:Eservice')->findAll();
        /* $user->getUsername();
          $user_id=$user->getId();
          $query = $em->getRepository('ApplicationCertificatsBundle:Eservice')->myFindAll($user_id); */
        /*
          $username = $this->container->get('security.context')->getToken()->getUser();
          $em = $this->container->get('doctrine')->getEntityManager();
          $user = $em->getRepository('SiteUtilisateurBundle:Utilisateur')->find($username);
         */
        //$query = $em->getRepository('ApplicationCertificatsBundle:CertificatsCenter')->myFindaAll();

        $paginator = $this->get('knp_paginator');
        $pagename1 = 'page1'; // Set custom page variable name
        $page1 = $this->get('request')->query->get($pagename1, 1); // Get custom page variable
        $paginationa = $paginator->paginate(
                $query, $page1, 3, array('pageParameterName' => $pagename1)
        );

        $pagename2 = 'page2'; // Set another custom page variable name
        $page2 = $this->get('request')->query->get($pagename2, 1); // Get another custom page variable
        $paginationb = $paginator->paginate(
                $query_other, $page2, 3, array('pageParameterName' => $pagename2)
        );
       
       /* $pagination = $paginator->paginate(
                $query, $this->get('request')->query->get('page', 1), 3
        );*/
        $paginationa->setTemplate('ApplicationCertificatsBundle:pagination:twitter_bootstrap_pagination.html.twig');
        $paginationb->setTemplate('ApplicationCertificatsBundle:pagination:twitter_bootstrap_pagination.html.twig');
        //$pagination->setTemplate('ApplicationMyNotesBundle:pagination:sliding.html.twig');
        return $this->render('ApplicationCertificatsBundle:Eservice:index.html.twig', array(
                    //'pagination' => $pagination,
            'paginationa' => $paginationa,
            'paginationb' => $paginationb,
                ));


        // $em = $this->getDoctrine()->getManager();



        /* return $this->render('ApplicationCertificatsBundle:Eservice:index.html.twig', array(
          'pagination' => $pagination,
          //'entities' => $entities,
          )); */
    }

    /**
     * Creates a new Eservice entity.
     *
     */
    public function createAction(Request $request) {

        $em = $this->getDoctrine()->getManager();
        $user = $this->get('security.context')->getToken()->getUser();
        $user_security = $this->container->get('security.context');
        //if( $user_security->isGranted('IS_AUTHENTICATED_REMEMBERED') ){
        if ($user_security->isGranted('IS_AUTHENTICATED_FULLY')) {
            // authenticated REMEMBERED, FULLY will imply REMEMBERED (NON anonymous)
            $user_id = $user->getId();
        } else {
            $user_id = 0;
        }
        // $current_user=new \Application\Sonata\UserBundle\Entity\User();
        //  $this->demandeur = $demandeur;

        $entity = new Eservice();
        $form = $this->createForm(new EserviceType(), $entity);
        $form->bind($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $current_user = $em->getRepository('ApplicationSonataUserBundle:User')->find($user_id);
            $entity->setDemandeur($current_user);
            $em->persist($entity);
            $em->flush();

            $session = $this->getRequest()->getSession();
            $nom_modif = $entity->getName();
            $id = $entity->getId();
            $session->getFlashBag()->add('warning', "Enregistrement $nom_modif ($id) ajouté");

            return $this->redirect($this->generateUrl('eservice_show', array('id' => $entity->getId())));
        }

        return $this->render('ApplicationCertificatsBundle:Eservice:new.html.twig', array(
                    'entity' => $entity,
                    'form' => $form->createView(),
                ));
    }

    /**
     * Displays a form to create a new Eservice entity.
     *
     */
    public function newAction() {

        $em = $this->getDoctrine()->getManager();
        $user = $this->get('security.context')->getToken()->getUser();
        $user_security = $this->container->get('security.context');
        //if( $user_security->isGranted('IS_AUTHENTICATED_REMEMBERED') ){
        if ($user_security->isGranted('IS_AUTHENTICATED_FULLY')) {
            // authenticated REMEMBERED, FULLY will imply REMEMBERED (NON anonymous)
            $user_id = $user->getId();
        } else {
            $user_id = 0;
        }
        //   $em = $this->getDoctrine()->getManager();

        $entity = new Eservice();

        $current_user = $em->getRepository('ApplicationSonataUserBundle:User')->find($user_id);
        $entity->setDemandeur($current_user);



        $form = $this->createForm(new EserviceType(), $entity);

        return $this->render('ApplicationCertificatsBundle:Eservice:new.html.twig', array(
                    'entity' => $entity,
                    'form' => $form->createView(),
                ));
    }

    /**
     * Finds and displays a Eservice entity.
     *
     */
    public function showAction($id) {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('ApplicationCertificatsBundle:Eservice')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Eservice entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return $this->render('ApplicationCertificatsBundle:Eservice:show.html.twig', array(
                    'entity' => $entity,
                    'delete_form' => $deleteForm->createView(),));
    }

    /**
     * Displays a form to edit an existing Eservice entity.
     *
     */
    public function editAction($id) {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('ApplicationCertificatsBundle:Eservice')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Eservice entity.');
        }

        $editForm = $this->createForm(new EserviceType(), $entity);
        $deleteForm = $this->createDeleteForm($id);

        return $this->render('ApplicationCertificatsBundle:Eservice:edit.html.twig', array(
                    'entity' => $entity,
                    'edit_form' => $editForm->createView(),
                    'delete_form' => $deleteForm->createView(),
                ));
    }

    /**
     * Edits an existing Eservice entity.
     *
     */
    public function updateAction(Request $request, $id) {
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

            $session = $this->getRequest()->getSession();
            $nom_modif = $entity->getName();
            $session->getFlashBag()->add('warning', "Enregistrement $nom_modif ($id) modifié");

            return $this->redirect($this->generateUrl('eservice'));
        }

        return $this->render('ApplicationCertificatsBundle:Eservice:edit.html.twig', array(
                    'entity' => $entity,
                    'edit_form' => $editForm->createView(),
                    'delete_form' => $deleteForm->createView(),
                ));
    }

    /**
     * Deletes a Eservice entity.
     *
     */
    public function deleteAction(Request $request, $id) {
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
    private function createDeleteForm($id) {
        return $this->createFormBuilder(array('id' => $id))
                        ->add('id', 'hidden')
                        ->getForm()
        ;
    }

}
