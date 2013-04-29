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
  public function newAction($epost_id)
    {
      
        $em = $this->getDoctrine()->getManager();
        $user = $this->get('security.context')->getToken()->getUser();
        $user_security = $this->container->get('security.context');
        $validation=1;
        if ($user_security->isGranted('IS_AUTHENTICATED_FULLY')) {
               $user_id = $user->getId();
        
      
        $current_user = $em->getRepository('ApplicationSonataUserBundle:User')->find($user_id);
         $epost = $this->getEpost($epost_id);
        $comment = new EpostComments();
        $comment->setEpost($epost);
        
        //Creation entity EpostComments
        
       $comment->setUser($current_user);
        $form   = $this->createForm(new EpostCommentsType(), $comment);

        return $this->render('ApplicationEpostBundle:EpostComments:form.html.twig', array(
            'comment' => $comment,
            'form'   => $form->createView(),
            'validation' => $validation,
        ));
    
    } else {
          // throw $this->createNotFoundException('User not connected.');
           $validation=0;
           return $this->render('ApplicationEpostBundle:EpostComments:form.html.twig', array(
                'validation' => $validation,
            
        ));
        }
    }
    public function createAction($epost_id)
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
           
       
       
       $epost = $this->getEpost($epost_id);
        $comment = new EpostComments();
        $comment->setEpost($epost);
     $comment->setUser($current_user);
        $request = $this->getRequest();
         $form   = $this->createForm(new EpostCommentsType(), $comment);
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

    protected function getEpost($epost_id)
    {
        $em = $this->getDoctrine()
                    ->getEntityManager();

        $epost = $em->getRepository('ApplicationEpostBundle:Epost')->find($epost_id);

        if (!$epost) {
            throw $this->createNotFoundException('Unable to find Post.');
        }

        return $epost;
    }

}
