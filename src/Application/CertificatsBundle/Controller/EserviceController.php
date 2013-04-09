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


        $user_id = $this->getuserid();
        $em = $this->getDoctrine()->getManager();
        /*
        $user = $this->get('security.context')->getToken()->getUser();
        $user_security = $this->container->get('security.context');
        if( $user_security->isGranted('IS_AUTHENTICATED_REMEMBERED') ){
        //if ($user_security->isGranted('IS_AUTHENTICATED_FULLY')) {
            // authenticated REMEMBERED, FULLY will imply REMEMBERED (NON anonymous)
            $user_id = $user->getId();
        } else {
            $user_id = 0;
        }*/
        /*==========================================
         * myFindAll: user,(messervices=0,mes_demande_service=1)
         ===========================================*/
        $query_s = $em->getRepository('ApplicationCertificatsBundle:Eservice')->myFindAll($user_id,0);
        $query_d = $em->getRepository('ApplicationCertificatsBundle:Eservice')->myFindAll($user_id,1);
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

        
        $paginator1 = $this->get('knp_paginator');
        $pagename1 = 'page1'; // Set custom page variable name
        $page1 = $this->get('request')->query->get($pagename1, 1); // Get custom page variable
        $paginationa = $paginator1->paginate(
                $query_s, $page1, 3, array('pageParameterName' => $pagename1,
                            "sortDirectionParameterName" => "dir1",
                            'sortFieldParameterName' => "sort1")
        );

         $paginator2 = $this->get('knp_paginator');
        $pagename2 = 'page2'; // Set another custom page variable name
        $page2 = $this->get('request')->query->get($pagename2, 1); // Get another custom page variable
        $paginationb = $paginator2->paginate(
                $query_other, $page2, 3, array('pageParameterName' => $pagename2,
                    "sortDirectionParameterName" => "dir2",
                    'sortFieldParameterName' => "sort2")
        );
       
          $paginator3 = $this->get('knp_paginator');
        $pagename_demande = 'page3'; // Set custom page variable name
        $page_demandes = $this->get('request')->query->get($pagename_demande, 1); // Get custom page variable
        $pagination_mesdemandes = $paginator3->paginate(
                $query_d, $page_demandes, 3, array('pageParameterName' => $pagename_demande,
               "sortDirectionParameterName" => "dir3",
                    'sortFieldParameterName' => "sort3")
        );
        
       /* $pagination = $paginator->paginate(
                $query, $this->get('request')->query->get('page', 1), 3
        );*/
        // set an array of custom parameters
$paginationa->setCustomParameters(array(
    'style' => 'bottom',
    'span_class' => 'whatever'
));
/*
$pagination = $paginator->paginate(
    $query, // target to paginate
    $this->get('request')->query->get('section', 1), // page parameter, now section
    10, // limit per page
    array('pageParameterName' => 'section', 'sortDirectionParameterName' => 'dir')
);*/
//$paginationa->setDefaultPaginatorOptions(array()
        $paginationa->setTemplate('ApplicationCertificatsBundle:pagination:twitter_bootstrap_pagination.html.twig');
        $paginationb->setTemplate('ApplicationCertificatsBundle:pagination:twitter_bootstrap_pagination.html.twig');
        //$pagination->setTemplate('ApplicationMyNotesBundle:pagination:sliding.html.twig');
        return $this->render('ApplicationCertificatsBundle:Eservice:index.html.twig', array(
                    //'pagination' => $pagination,
            'paginationa' => $paginationa,
            'paginationb' => $paginationb,
            'pagination_demandes' => $pagination_mesdemandes,
                ));


        // $em = $this->getDoctrine()->getManager();



        /* return $this->render('ApplicationCertificatsBundle:Eservice:index.html.twig', array(
          'pagination' => $pagination,
          //'entities' => $entities,
          )); */
    }

    
     private function getuserid() {


        $em = $this->getDoctrine()->getManager();
        $user = $this->get('security.context')->getToken()->getUser();
        $user_security = $this->container->get('security.context');
        if( $user_security->isGranted('IS_AUTHENTICATED_REMEMBERED') ){
        //if ($user_security->isGranted('IS_AUTHENTICATED_FULLY')) {
            // authenticated REMEMBERED, FULLY will imply REMEMBERED (NON anonymous)
            $user_id = $user->getId();
        } else {
            $user_id = 0;
        }
        return ($user_id);
     }
    
       public function indexotherservicesAction() {


        $em = $this->getDoctrine()->getManager();
          $user_id = $this->getuserid();
            /*==========================================
         * myFindAll: user,(messervices=0,mes_demande_service=1)
         ===========================================*/
         $query = $em->getRepository('ApplicationCertificatsBundle:Eservice')->myFindOtherForme($user_id);
    $paginator = $this->get('knp_paginator');
      
        $pagename = 'page'; // Set another custom page variable name
        $page = $this->get('request')->query->get($pagename, 1); // Get another custom page variable
        $pagination = $paginator->paginate(
                $query, $page, 3, array('pageParameterName' => $pagename)
        );
       
        $pagination->setTemplate('ApplicationCertificatsBundle:pagination:twitter_bootstrap_pagination.html.twig');
     
        return $this->render('ApplicationCertificatsBundle:Eservice:indexother.html.twig', array(
            'pagination' => $pagination,
                ));
     
    }
    
     public function indexmesservicesAction() {


        $em = $this->getDoctrine()->getManager();
          $user_id = $this->getuserid();
            /*==========================================
         * myFindAll: user,(messervices=0,mes_demande_service=1)
         ===========================================*/
          $query = $em->getRepository('ApplicationCertificatsBundle:Eservice')->myFindAll($user_id,0);
    $paginator = $this->get('knp_paginator');
      
        $pagename = 'page'; // Set another custom page variable name
        $page = $this->get('request')->query->get($pagename, 1); // Get another custom page variable
        $pagination = $paginator->paginate(
                $query, $page, 3, array('pageParameterName' => $pagename)
        );
       
        $pagination->setTemplate('ApplicationCertificatsBundle:pagination:twitter_bootstrap_pagination.html.twig');
     
        return $this->render('ApplicationCertificatsBundle:Eservice:indexservices.html.twig', array(
            'pagination' => $pagination,
                ));
     
    }
    
       public function indexdemandesservicesAction() {


        $em = $this->getDoctrine()->getManager();
          $user_id = $this->getuserid();
            /*==========================================
         * myFindAll: user,(messervices=0,mes_demande_service=1)
         ===========================================*/
        $query = $em->getRepository('ApplicationCertificatsBundle:Eservice')->myFindAll($user_id,1);
           
    $paginator = $this->get('knp_paginator');
      
        $pagename = 'page2'; // Set another custom page variable name
        $page = $this->get('request')->query->get($pagename, 1); // Get another custom page variable
        $pagination = $paginator->paginate(
                $query, $page, 3, array('pageParameterName' => $pagename)
        );
       
        $pagination->setTemplate('ApplicationCertificatsBundle:pagination:twitter_bootstrap_pagination.html.twig');
     
        return $this->render('ApplicationCertificatsBundle:Eservice:indexdemandes.html.twig', array(
            'pagination' => $pagination,
                ));
     
    }
    
    /**
     * Creates a new Eservice entity.
     *
     */
    public function createAction(Request $request) {

        $em = $this->getDoctrine()->getManager();
        $user = $this->get('security.context')->getToken()->getUser();
        $user_security = $this->container->get('security.context');
       if( $user_security->isGranted('IS_AUTHENTICATED_REMEMBERED') ){
      //  if ($user_security->isGranted('IS_AUTHENTICATED_FULLY')) {
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
        if( $user_security->isGranted('IS_AUTHENTICATED_REMEMBERED') ){
       // if ($user_security->isGranted('IS_AUTHENTICATED_FULLY')) {
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
        $user_id = $this->getuserid();
        //pas proprio par defaut
        $ismine=0;
        $entity = $em->getRepository('ApplicationCertificatsBundle:Eservice')->find($id);
      
      
         if (!$entity) {
            throw $this->createNotFoundException('Unable to find Eservice entity.');
        }

          $proprietaire=$entity->getDemandeur()->getId();
      
        /*  print_r($proprietaire);
          print_r($user_id);*/
         // exit(1);
         if ($user_id ==  $proprietaire){
             $ismine=1;
         }
        $deleteForm = $this->createDeleteForm($id);

        return $this->render('ApplicationCertificatsBundle:Eservice:show.html.twig', array(
                    'entity' => $entity,
                       'ismine' => $ismine, 
                    'delete_form' => $deleteForm->createView(),));
    }

    /**
     * Displays a form to edit an existing Eservice entity.
     *
     */
    public function editAction($id) {
        //pas proprio par defaut
        $ismine=0;
        $em = $this->getDoctrine()->getManager();
       $entity = $em->getRepository('ApplicationCertificatsBundle:Eservice')->find($id);
     if (!$entity) {
            throw $this->createNotFoundException('Unable to find Eservice entity.');
        }

        $user_id = $this->getuserid();
        $proprietaire=$entity->getDemandeur()->getId();
        if ($user_id !=  $proprietaire){
            return $this->render('ApplicationCertificatsBundle:Eservice:deny.html.twig', array(
                 ));
         }
          $editForm = $this->createForm(new EserviceType(), $entity);
        $deleteForm = $this->createDeleteForm($id);

        return $this->render('ApplicationCertificatsBundle:Eservice:edit.html.twig', array(
                    'entity' => $entity,
                    'edit_form' => $editForm->createView(),
                    'delete_form' => $deleteForm->createView(),
                ));
    }

    public function contratAction($id) {
        //pas proprio par defaut
        $ismine=0;
        $em = $this->getDoctrine()->getManager();
       $entity = $em->getRepository('ApplicationCertificatsBundle:Eservice')->find($id);
     if (!$entity) {
            throw $this->createNotFoundException('Unable to find Eservice entity.');
        }
        

        $user_id = $this->getuserid();
        $proprietaire=$entity->getDemandeur()->getId();
        if ($user_id ==  $proprietaire){
            return $this->render('ApplicationCertificatsBundle:Eservice:ownerdeny.html.twig', array(
                 ));
         }
          return $this->render('ApplicationCertificatsBundle:Eservice:contrat.html.twig', array(
                    'entity' => $entity,
                    
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
