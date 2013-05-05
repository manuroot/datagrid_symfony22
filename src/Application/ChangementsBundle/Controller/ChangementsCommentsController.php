<?php

namespace Application\ChangementsBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Application\ChangementsBundle\Entity\Changements;
use Application\ChangementsBundle\Entity\ChangementsComments;

use Application\ChangementsBundle\Form\ChangementsCommentsType;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

use JMS\SecurityExtraBundle\Annotation\Secure;
use Symfony\Component\HttpFoundation\JsonResponse;


/**
 * Eproduit controller.
 *
 */
class ChangementsCommentsController extends Controller {

      private function getuserid() {


        $em = $this->getDoctrine()->getManager();
        $user_security = $this->container->get('security.context');
        // authenticated REMEMBERED, FULLY will imply REMEMBERED (NON anonymous)
        if ($user_security->isGranted('IS_AUTHENTICATED_FULLY')) {
            $user = $this->get('security.context')->getToken()->getUser();
            $user_id = $user->getId();
            $group = $user->getIdgroup();
            if (isset($group)){
                  $group_id=$group->getId();
            }
            else {
                $group_id=0;
            }
        } else {
            $user_id = 0;
            $group_id = 0;
        }
         return array($user_id, $group_id);
 
    }
  public function newAction($changement_id)
    {
      
        $em = $this->getDoctrine()->getManager();
        list($user_id, $group_id) = $this->getuserid();
       $validation=0;
       $comment=null;
        if ($user_id !=0) {
        $validation=1;
        $current_user = $em->getRepository('ApplicationSonataUserBundle:User')->find($user_id);
        $changement = $this->getChangement($changement_id);
        $comment = new ChangementsComments();
        $comment->setChangement($changement);
       $comment->setUser($current_user);
        $form   = $this->createForm(new ChangementsCommentsType(), $comment);
        $formview=$form->createView();
        }
       
        return $this->render('ApplicationChangementsBundle:ChangementsComments:form.html.twig', array(
            'comment' => $comment,
            'form'   => $formview,
            'validation' => $validation,
        ));
    }
    
    
     public function showAction($id)
    {
      
        $em = $this->getDoctrine()->getManager();
        list($user_id, $group_id) = $this->getuserid();
  
        $validation=1;
        $changement = $this->getChangement($id);
        if ($user_id !=0) {
        $current_user = $em->getRepository('ApplicationSonataUserBundle:User')->find($user_id);
      
        //Creation entity EproduitComments
        
        $comment = new ChangementsComments();
        $comment->setChangement($changement);
       $comment->setUser($current_user);
        $form   = $this->createForm(new ChangementsCommentsType(), $comment);

        return $this->render('ApplicationChangementsBundle:ChangementsComments:show.html.twig', array(
            'entity' => $changement,
            'comment' => $comment,
            'form'   => $form->createView(),
            'validation' => $validation,
        ));
    
    } else {
          // throw $this->createNotFoundException('User not connected.');
           $validation=0;
           return $this->render('ApplicationChangementsBundle:ChangementsComments:show.html.twig', array(
               'entity' => $changement,
                'validation' => $validation,
            
        ));
        }
    }
    
    
    public function createAction($changement_id)
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
           
       /*  $comment = new ChangementsComments();
        $comment->setChangement($changement);
       $comment->setUser($current_user);
        $form   = $this->createForm(new ChangementsCommentsType(), $comment);
*/
       
       $changement = $this->getChangement($changement_id);
        $comment = new ChangementsComments();
        $comment->setChangement($changement);
        //logged user:
     $comment->setUser($current_user);
        $request = $this->getRequest();
        $form   = $this->createForm(new ChangementsCommentsType(), $comment);
        $form->bindRequest($request);
       
       
     
        if ($form->isValid()) {
            $em = $this->getDoctrine()->getEntityManager();
            $em->persist($comment);
            $em->flush();
            return $this->redirect($this->generateUrl('changements_comment_show', array(
                'id' => $comment->getChangement()->getId()))              
            );
        }
       // $produit = $this->getComments($produit_id);

      
      
      
   
        
    }

    protected function getChangement($changement_id)
    {
        $em = $this->getDoctrine()
                    ->getEntityManager();

        $changements = $em->getRepository('ApplicationChangementsBundle:Changements')->find($changement_id);

        if (!$changements) {
            throw $this->createNotFoundException('Unable to find Changement.');
        }

        return $changements;
    }

}
