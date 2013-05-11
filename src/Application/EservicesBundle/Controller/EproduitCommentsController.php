<?php

namespace Application\EservicesBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Application\EservicesBundle\Entity\Eproduit;
use Application\EservicesBundle\Entity\EproduitComments;

use Application\EservicesBundle\Form\EproduitCommentsType;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

use JMS\SecurityExtraBundle\Annotation\Secure;
use Symfony\Component\HttpFoundation\JsonResponse;


/**
 * Eproduit controller.
 *
 */
class EproduitCommentsController extends Controller {

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
  public function newAction($produit_id)
    {
      
        $em = $this->getDoctrine()->getManager();
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
    }
    public function createAction($produit_id)
    {
        
        
        
        $em = $this->getDoctrine()->getManager();
        $user = $this->get('security.context')->getToken()->getUser();
        $user_security = $this->container->get('security.context');
        if ($user_security->isGranted('IS_AUTHENTICATED_FULLY')) {
            //  if ($user_security->isGranted('IS_AUTHENTICATED_FULLY')) {
            // authenticated REMEMBERED, FULLY will imply REMEMBERED (NON anonymous)
            $user_id = $user->getId();
        } else {
           throw $this->createNotFoundException('User not connected.');
        }
        $current_user = $em->getRepository('ApplicationSonataUserBundle:User')->find($user_id);
           
       
       
       $produit = $this->getProduit($produit_id);
        $comment = new EproduitComments();
        $comment->setProduit($produit);
     $comment->setUser($current_user);
        $request = $this->getRequest();
         $form   = $this->createForm(new EproduitCommentsType(), $comment);
        $form->bindRequest($request);
       
       
     
        if ($form->isValid()) {
             $em = $this->getDoctrine()->getManager();
            //$em = $this->getDoctrine()->getEntityManager();
            $em->persist($comment);
            $em->flush();
            return $this->redirect($this->generateUrl('eproduit_show', array(
                'id' => $comment->getProduit()->getId()))              
            );
        }
       // $produit = $this->getComments($produit_id);

      
      
      
   
        
    }

    protected function getProduit($produit_id)
    {
            $em = $this->getDoctrine()->getManager();
       /* $em = $this->getDoctrine()
                    ->getEntityManager();*/

        $produit = $em->getRepository('ApplicationEservicesBundle:Eproduit')->find($produit_id);

        if (!$produit) {
            throw $this->createNotFoundException('Unable to find Produit.');
        }

        return $produit;
    }

}
