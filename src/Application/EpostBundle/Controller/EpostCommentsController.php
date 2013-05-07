<?php

namespace Application\EpostBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Application\EpostBundle\Entity\Epost;
use Application\EpostBundle\Entity\EpostComments;
use Application\EpostBundle\Form\EpostCommentsType;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use JMS\SecurityExtraBundle\Annotation\Secure;
use Symfony\Component\HttpFoundation\JsonResponse;

/**
 * Epost controller.
 *
 */
class EpostCommentsController extends Controller {

    private function getuserid() {


        $em = $this->getDoctrine()->getManager();
        $user_security = $this->container->get('security.context');
        // authenticated REMEMBERED, FULLY will imply REMEMBERED (NON anonymous)
        if ($user_security->isGranted('IS_AUTHENTICATED_FULLY')) {
            $user = $this->get('security.context')->getToken()->getUser();
            $user_id = $user->getId();
            $group = $user->getIdgroup();
            if (isset($group)) {
                $group_id = $group->getId();
            } else {
                $group_id = 0;
            }
        } else {
            $user_id = 0;
            $group_id = 0;
        }


        // }else {
        return array($user_id, $group_id);
        //   }
    }

    public function newAction($epost_id) {

        $em = $this->getDoctrine()->getManager();
         $validation = 1;
        list($user_id, $group_id) = $this->getuserid();
        if ($user_id != 0 && $group_id != 0) {
            $current_user = $em->getRepository('ApplicationSonataUserBundle:User')->find($user_id);
            $epost = $this->getEpost($epost_id);
            $comment = new EpostComments();
            $comment->setEpost($epost);
            $comment->setUser($current_user);
            $form = $this->createForm(new EpostCommentsType(), $comment);

            return $this->render('ApplicationEpostBundle:EpostComments:form.html.twig', array(
                        'comment' => $comment,
                        'form' => $form->createView(),
                        'validation' => $validation,
                    ));
        } else {
             $validation = 0;
            return $this->render('ApplicationEpostBundle:EpostComments:form.html.twig', array(
                        'validation' => $validation,
                    ));
        }
    }

    public function createAction($epost_id) {
        $em = $this->getDoctrine()->getManager();
        //  $em = $this->container->get('doctrine')->getManager();
        list($user_id, $group_id) = $this->getuserid();
        if ($user_id == 0) {
            throw $this->createNotFoundException('User not connected.');
        }
        if ($group_id == 0) {
            throw $this->createNotFoundException('User has no group.');
        }
        $current_user = $em->getRepository('ApplicationSonataUserBundle:User')->find($user_id);
        $epost = $this->getEpost($epost_id);
        $comment = new EpostComments();
        $comment->setEpost($epost);
        $comment->setUser($current_user);
        $request = $this->getRequest();
        $form = $this->createForm(new EpostCommentsType(), $comment);
        $form->bindRequest($request);
        if ($form->isValid()) {
            $em = $this->getDoctrine()->getEntityManager();
            $em->persist($comment);
            $em->flush();
            return $this->redirect($this->generateUrl('epost_show', array(
                                'id' => $comment->getEpost()->getId()))
            );
        }
        // $produit = $this->getComments($produit_id);
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
     //myFindBlogComments($user_id) {
    
 //====================================================================
    // BLOG ADMIN
    //====================================================================
    public function indexownerblogAction() {
        $em = $this->getDoctrine()->getManager();
          list($user_id, $group_id) = $this->getuserid();
        $session = $this->getRequest()->getSession();
        $session->set('buttonretour', 'epost_indexadmin');
        
        if ($user_id !=0 ){
        $query = $em->getRepository('ApplicationEpostBundle:EpostComments')->myFindBlogComments($user_id);
        $paginationa = $this->createpaginator($query, 5);
        $paginationa->setTemplate('ApplicationEpostBundle:pagination:twitter_bootstrap_pagination.html.twig');
        return $this->render('ApplicationEpostBundle:EpostComments:indexownerblog.html.twig', array(
                    'paginationa' => $paginationa,
                ));
        }else {
              throw $this->createNotFoundException('User not connected.');
        }
    }
 //====================================================================
    // BLOG ADMIN
    //====================================================================
    public function indexownerAction() {
        $em = $this->getDoctrine()->getManager();
          list($user_id, $group_id) = $this->getuserid();
        $session = $this->getRequest()->getSession();
        $session->set('buttonretour', 'epost_indexadmin');
        
        if ($user_id !=0 ){
        $query = $em->getRepository('ApplicationEpostBundle:EpostComments')->myFindAll($user_id);
        $paginationa = $this->createpaginator($query, 5);
        $paginationa->setTemplate('ApplicationEpostBundle:pagination:twitter_bootstrap_pagination.html.twig');
        return $this->render('ApplicationEpostBundle:EpostComments:indexowner.html.twig', array(
                    'paginationa' => $paginationa,
                ));
        }else {
              throw $this->createNotFoundException('User not connected.');
        }
    }
      private function createpaginator($query, $num_perpage = 5) {

        $paginator = $this->get('knp_paginator');
        $pagename = 'page'; // Set custom page variable name
        $page = $this->get('request')->query->get($pagename, 1); // Get custom page variable
        $pagination = $paginator->paginate(
                $query, $page, $num_perpage, array('pageParameterName' => $pagename,
            "sortDirectionParameterName" => "dir",
            'sortFieldParameterName' => "sort")
        );
        return $pagination;
    }
}
