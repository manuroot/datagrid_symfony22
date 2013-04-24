<?php

namespace Application\EservicesBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Application\EservicesBundle\Entity\Eproduit;
use Application\EservicesBundle\Form\EproduitType;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Component\Security\Acl\Domain\ObjectIdentity;
use Symfony\Component\Security\Acl\Domain\UserSecurityIdentity;
use Symfony\Component\Security\Acl\Permission\MaskBuilder;
use JMS\SecurityExtraBundle\Annotation\Secure;
use Symfony\Component\HttpFoundation\JsonResponse;

/**
 * Eproduit controller.
 *
 */
class EproduitController extends Controller {

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
     * Lists all Eproduit entities.
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
            $group_id = $user->getIdgroup()->getId();
        } else {
             $user_id = 0;
            $group_id=0;
        }
        $session = $this->getRequest()->getSession();
        $session->set('buttonretour', 'eproduit');

 /*       
 echo "group=" . $group_id . "<br>";
 exit(1);*/
     /*      
          if ($user_security->isGranted('IS_AUTHENTICATED_FULLY')) {
            // authenticated REMEMBERED, FULLY will imply REMEMBERED (NON anonymous)
            $user_id = $user->getId();
            $group_id = $user->getIdgroup();
        } else {
            $user_id = 0;
            $group_id=0;
            
        }
        $session = $this->getRequest()->getSession();
        $session->set('buttonretour', 'eproduit_indexserch');

        $query = $em->getRepository('ApplicationEservicesBundle:Eproduit')->myFindAll($user_id);
        $query_other = $em->getRepository('ApplicationEservicesBundle:Eproduit')->myFindOtherAll($user_id,$group_id);

        
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

        */
        
        
        
        
        
        
        
        $query = $em->getRepository('ApplicationEservicesBundle:Eproduit')->myFindAll($user_id);
        $query_other = $em->getRepository('ApplicationEservicesBundle:Eproduit')->myFindOtherAll($user_id,$group_id);
        $paginator = $this->get('knp_paginator');
        //   $query = $em->getRepository('ApplicationEservicesBundle:Eproduit')->findAll();
        $pagename1 = 'page1'; // Set custom page variable name
        $page1 = $this->get('request')->query->get($pagename1, 1); // Get custom page variable
        $paginationa = $paginator->paginate(
                $query, $page1, 3, array('pageParameterName' => $pagename1,
            "sortDirectionParameterName" => "dir1",
            'sortFieldParameterName' => "sort1")
        );

        $pagename2 = 'page2'; // Set another custom page variable name
        $page2 = $this->get('request')->query->get($pagename2, 1); // Get another custom page variable
        $paginationb = $paginator->paginate(
                $query_other, $page2, 3, array('pageParameterName' => $pagename2,
            "sortDirectionParameterName" => "dir2",
            'sortFieldParameterName' => "sort2")
        );
        //$query = $em->getRepository('ApplicationEservicesBundle:CertificatsCenter')->myFindaAll();

        $paginationa->setTemplate('ApplicationEservicesBundle:pagination:twitter_bootstrap_pagination.html.twig');
        $paginationb->setTemplate('ApplicationEservicesBundle:pagination:twitter_bootstrap_pagination.html.twig');
        //  $pagination->setTemplate('ApplicationEservicesBundle:pagination:sliding.html.twig');
        return $this->render('ApplicationEservicesBundle:Eproduit:index.html.twig', array(
                    'paginationa' => $paginationa,
                    'paginationb' => $paginationb,
                ));
    }

    /**
     * @Secure(roles="ROLE_ADMIN")
     */
    public function indexAllAction() {
        $em = $this->getDoctrine()->getManager();
        $user = $this->get('security.context')->getToken()->getUser();
        $user_security = $this->container->get('security.context');
        if ($user_security->isGranted('IS_AUTHENTICATED_FULLY')) {
            // authenticated REMEMBERED, FULLY will imply REMEMBERED (NON anonymous)
            $user_id = $user->getId();
        } else {
            $user_id = 0;
        }
        $session = $this->getRequest()->getSession();
        $session->set('buttonretour', 'eproduit_indexadmin');

        $query = $em->getRepository('ApplicationEservicesBundle:Eproduit')->myFind();

        $paginator = $this->get('knp_paginator');
        $pagename1 = 'page1'; // Set custom page variable name
        $page1 = $this->get('request')->query->get($pagename1, 1); // Get custom page variable
        $paginationa = $paginator->paginate(
                $query, $page1, 5, array('pageParameterName' => $pagename1)
        );


        $paginationa->setTemplate('ApplicationEservicesBundle:pagination:twitter_bootstrap_pagination.html.twig');
        return $this->render('ApplicationEservicesBundle:Eproduit:indexadmin.html.twig', array(
                    'paginationa' => $paginationa,
                ));
    }

    /**
     *
     */
    public function indexmesproduitsAction() {

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
        $session = $this->getRequest()->getSession();
        $session->set('buttonretour', 'eproduit_mesproduits');
        $query = $em->getRepository('ApplicationEservicesBundle:Eproduit')->myFindAll($user_id);

        $paginator = $this->get('knp_paginator');
        $pagename1 = 'page1'; // Set custom page variable name
        $page1 = $this->get('request')->query->get($pagename1, 1); // Get custom page variable
        $paginationa = $paginator->paginate(
                $query, $page1, 5, array('pageParameterName' => $pagename1)
        );


        $paginationa->setTemplate('ApplicationEservicesBundle:pagination:twitter_bootstrap_pagination.html.twig');
        return $this->render('ApplicationEservicesBundle:Eproduit:indexmesproduits.html.twig', array(
                    'paginationa' => $paginationa,
                ));
    }

    public function indexpropositionsAction() {

        $em = $this->getDoctrine()->getManager();
        $user = $this->get('security.context')->getToken()->getUser();
        $user_security = $this->container->get('security.context');
        //if( $user_security->isGranted('IS_AUTHENTICATED_REMEMBERED') ){
      if ($user_security->isGranted('IS_AUTHENTICATED_FULLY')) {
            // authenticated REMEMBERED, FULLY will imply REMEMBERED (NON anonymous)
             $user_id = $user->getId();
            $group_id = $user->getIdgroup()->getId();
        } else {
             $user_id = 0;
            $group_id=0;
        }
        $session = $this->getRequest()->getSession();
        $session->set('buttonretour', 'eproduit_propositions');
        $query = $em->getRepository('ApplicationEservicesBundle:Eproduit')->myFindOtherAll($user_id,$group_id);

        $paginator = $this->get('knp_paginator');
        $pagename1 = 'page1'; // Set custom page variable name
        $page1 = $this->get('request')->query->get($pagename1, 1); // Get custom page variable
        $paginationa = $paginator->paginate(
                $query, $page1, 5, array('pageParameterName' => $pagename1)
        );


        $paginationa->setTemplate('ApplicationEservicesBundle:pagination:twitter_bootstrap_pagination.html.twig');
        return $this->render('ApplicationEservicesBundle:Eproduit:indexpropositions.html.twig', array(
                    'paginationa' => $paginationa,
                ));
    }

    /**
     * Creates a new Eproduit entity.
     *
     */
    public function createAction(Request $request) {
        $entity = new Eproduit();
        $form = $this->createForm(new EproduitType(), $entity);
        $form->bind($request);
        $user = $this->get('security.context')->getToken()->getUser();
        $user_security = $this->container->get('security.context');
        if ($user_security->isGranted('IS_AUTHENTICATED_REMEMBERED')) {
            //  if ($user_security->isGranted('IS_AUTHENTICATED_FULLY')) {
            // authenticated REMEMBERED, FULLY will imply REMEMBERED (NON anonymous)
            $user_id = $user->getId();
        } else {
            $user_id = 0;
        }
          $session = $this->getRequest()->getSession();
      //  $session->get('buttonretour', 'eproduit');
         $myretour = $session->get('buttonretour');

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $current_user = $em->getRepository('ApplicationSonataUserBundle:User')->find($user_id);
            $entity->setProprietaire($current_user);
            $entity->setUpdatedAt(new \DateTime());
            $em->persist($entity);
            $em->flush();

            $session = $this->getRequest()->getSession();
            $nom_modif = $entity->getName();
            $id = $entity->getId();
            $session->getFlashBag()->add('warning', "Enregistrement $nom_modif ($id) ajouté");
            return $this->redirect($this->generateUrl('eproduit_show', array('id' => $entity->getId())));
        }

        return $this->render('ApplicationEservicesBundle:Eproduit:new.html.twig', array(
                    'entity' => $entity,
             'btnretour' => $myretour,
                    'form' => $form->createView(),
                ));
    }

    /**
     * Displays a form to create a new Eproduit entity.
     *
     */
    public function newAction() {
        $entity = new Eproduit();
        /*
          $helper = $this->container->get('vich_uploader.templating.helper.uploader_helper');
          $path = $helper->asset($entity, 'image'); */
        $form = $this->createForm(new EproduitType(), $entity);
        $session = $this->getRequest()->getSession();
        $myretour = $session->get('buttonretour');
        return $this->render('ApplicationEservicesBundle:Eproduit:new.html.twig', array(
                    'entity' => $entity,
                    'btnretour' => $myretour,
                    'form' => $form->createView(),
                ));
    }

    /**
     * Finds and displays a Eproduit entity.
     *
     */
    public function showAction($id) {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('ApplicationEservicesBundle:Eproduit')->find($id);
        $helper = $this->container->get('vich_uploader.templating.helper.uploader_helper');
//$path = $helper->asset($entity, 'image');
        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Eproduit entity.');
        }
        
        $comments = $em->getRepository('ApplicationEservicesBundle:EproduitComments')
                   ->getCommentsForProduit($entity->getId());
        
        
        $session = $this->getRequest()->getSession();
        $myretour = $session->get('buttonretour');

        $deleteForm = $this->createDeleteForm($id);

        return $this->render('ApplicationEservicesBundle:Eproduit:show.html.twig', array(
                    'entity' => $entity,
                    'btnretour' => $myretour,
                    'comments'  => $comments,
                    //   'path' => $path,
                    'delete_form' => $deleteForm->createView(),));
    }

    /**
     * Displays a form to edit an existing Eproduit entity.
     *
     */
    public function editAction($id) {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('ApplicationEservicesBundle:Eproduit')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Eproduit entity.');
        }
        $session = $this->getRequest()->getSession();
        $myretour = $session->get('buttonretour');
             
        $user_id = $this->getuserid();
        $proprietaire = $entity->getProprietaire()->getId();
        if ($user_id != $proprietaire) {
            return $this->render('ApplicationEservicesBundle:Eservice:deny.html.twig', array(
                    ));
        }

        $editForm = $this->createForm(new EproduitType(), $entity);
        $deleteForm = $this->createDeleteForm($id);

        return $this->render('ApplicationEservicesBundle:Eproduit:edit.html.twig', array(
                    'entity' => $entity,
                    'btnretour' => $myretour,
                    'edit_form' => $editForm->createView(),
                    'delete_form' => $deleteForm->createView(),
                ));
    }

    /**
     * Edits an existing Eproduit entity.
     *
     */
    public function updateAction(Request $request, $id) {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('ApplicationEservicesBundle:Eproduit')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Eproduit entity.');
        }

        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createForm(new EproduitType(), $entity);
        $editForm->bind($request);
            $session = $this->getRequest()->getSession();
            $myretour = $session->get('buttonretour');
            if (!isset($myretour))
                $myretour='eproduit';
        if ($editForm->isValid()) {
            $entity->setUpdatedAt(new \DateTime());
            $em->persist($entity);
            $em->flush();
           
            $session->getFlashBag()->add('warning', "Enregistrement $id update successfull");
            $route_back = $session->get('buttonretour');
            if (isset($route_back))
                return $this->redirect($this->generateUrl($route_back, array('id' => $id)));
            else
                return $this->redirect($this->generateUrl('eproduit', array('id' => $id)));
        }

        return $this->render('ApplicationEservicesBundle:Eproduit:edit.html.twig', array(
                    'entity' => $entity,
            'btnretour' => $myretour,
                    'edit_form' => $editForm->createView(),
                    'delete_form' => $deleteForm->createView(),
                ));
    }

    /**
     * Deletes a Eproduit entity.
     *
     */
    public function deleteAction(Request $request, $id) {
        $form = $this->createDeleteForm($id);
        $form->bind($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('ApplicationEservicesBundle:Eproduit')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find Eproduit entity.');
            }

            $em->remove($entity);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('eproduit'));
    }

    /**
     * Creates a form to delete a Eproduit entity by id.
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

    public function searchProduitAction() {
        $request = $this->getRequest();

        if ($request->isXmlHttpRequest() && $request->getMethod() == 'POST') {
            $em = $this->getDoctrine()->getEntityManager();
            $id = '';
            $applis = array();
            $cert_app = array();

            $id = $request->request->get('id_projet');
            $projet = $em->getRepository('ApplicationEservicesBundle:CertificatsProjet')->find($id);

            $id_cert = $request->request->get('id_cert');
            if (isset($id_cert) && $id_cert != "create") {
                //    var_dump($id_cert);
                $cert = $em->getRepository('ApplicationEservicesBundle:CertificatsCenter')->find($id_cert);
                foreach ($cert->getIdapplis() as $appli) {
                    array_push($cert_app, $appli->getId());
                }
                $applis['cert'] = $cert_app;
            }
            foreach ($projet->getIdapplis() as $appli) {
                //$applis[] = array($appli);
                $applis['applis'][$appli->getId()] = $appli->getNomapplis();
                //      $applis[] = array($appli->getId(), $appli->getNomapplis());
            }

            //    $appli=array(3,4);
            $response = new Response(json_encode($applis));
            $response->headers->set('Content-Type', 'application/json');

            return $response;
        }
        // return new Response();
    }

    public function indexsearchAction() {

        $em = $this->getDoctrine()->getManager();
        $user = $this->get('security.context')->getToken()->getUser();
        $user_security = $this->container->get('security.context');
        //if( $user_security->isGranted('IS_AUTHENTICATED_REMEMBERED') ){
        if ($user_security->isGranted('IS_AUTHENTICATED_FULLY')) {
            // authenticated REMEMBERED, FULLY will imply REMEMBERED (NON anonymous)
            $user_id = $user->getId();
            $group_id = $user->getIdgroup();
        } else {
            $user_id = 0;
            $group_id=0;
            
        }
        $session = $this->getRequest()->getSession();
        $session->set('buttonretour', 'eproduit_indexserch');

        $query = $em->getRepository('ApplicationEservicesBundle:Eproduit')->myFindAll($user_id);
        $query_other = $em->getRepository('ApplicationEservicesBundle:Eproduit')->myFindOtherAll($user_id,$group_id);
        $paginator = $this->get('knp_paginator');
        //   $query = $em->getRepository('ApplicationEservicesBundle:Eproduit')->findAll();
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
        //$query = $em->getRepository('ApplicationEservicesBundle:CertificatsCenter')->myFindaAll();

        $paginationa->setTemplate('ApplicationEservicesBundle:pagination:twitter_bootstrap_pagination.html.twig');
        $paginationb->setTemplate('ApplicationEservicesBundle:pagination:twitter_bootstrap_pagination.html.twig');
        //  $pagination->setTemplate('ApplicationEservicesBundle:pagination:sliding.html.twig');
        return $this->render('ApplicationEservicesBundle:Eproduit:search.html.twig', array(
                    'paginationa' => $paginationa,
                    'paginationb' => $paginationb,
                ));
        ;
    }

}
