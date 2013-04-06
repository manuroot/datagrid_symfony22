<?php

namespace Application\CertificatsBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use Application\CertificatsBundle\Entity\Eproduit;
use Application\CertificatsBundle\Form\EproduitType;

/**
 * Eproduit controller.
 *
 */
class EproduitController extends Controller
{
    /**
     * Lists all Eproduit entities.
     *
     */
    public function indexAction()
    {
        
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

        $query = $em->getRepository('ApplicationCertificatsBundle:Eproduit')->myFindAll($user_id);
          $query_other = $em->getRepository('ApplicationCertificatsBundle:Eproduit')->myFindOtherAll($user_id);
              $paginator = $this->get('knp_paginator');
    //   $query = $em->getRepository('ApplicationCertificatsBundle:Eproduit')->findAll();
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
        //$query = $em->getRepository('ApplicationCertificatsBundle:CertificatsCenter')->myFindaAll();
      
        $paginationa->setTemplate('ApplicationCertificatsBundle:pagination:twitter_bootstrap_pagination.html.twig');
       $paginationb->setTemplate('ApplicationCertificatsBundle:pagination:twitter_bootstrap_pagination.html.twig');
      //  $pagination->setTemplate('ApplicationCertificatsBundle:pagination:sliding.html.twig');
        return $this->render('ApplicationCertificatsBundle:Eproduit:index.html.twig', array(
                 'paginationa' => $paginationa,
            'paginationb' => $paginationb,
        ));
    }

    /**
     * Creates a new Eproduit entity.
     *
     */
    public function createAction(Request $request)
    {
        $entity  = new Eproduit();
        $form = $this->createForm(new EproduitType(), $entity);
        $form->bind($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
             $entity->setUpdatedAt(new \DateTime());
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('eproduit_show', array('id' => $entity->getId())));
        }

        return $this->render('ApplicationCertificatsBundle:Eproduit:new.html.twig', array(
            'entity' => $entity,
            'form'   => $form->createView(),
        ));
    }

    /**
     * Displays a form to create a new Eproduit entity.
     *
     */
    public function newAction()
    {
        $entity = new Eproduit();
     /*   
       $helper = $this->container->get('vich_uploader.templating.helper.uploader_helper');
$path = $helper->asset($entity, 'image');*/
        $form   = $this->createForm(new EproduitType(), $entity);

        return $this->render('ApplicationCertificatsBundle:Eproduit:new.html.twig', array(
            'entity' => $entity,
            
            'form'   => $form->createView(),
        ));
    }

    /**
     * Finds and displays a Eproduit entity.
     *
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('ApplicationCertificatsBundle:Eproduit')->find($id);
 $helper = $this->container->get('vich_uploader.templating.helper.uploader_helper');
//$path = $helper->asset($entity, 'image');
        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Eproduit entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return $this->render('ApplicationCertificatsBundle:Eproduit:show.html.twig', array(
            'entity'      => $entity,
         //   'path' => $path,
            'delete_form' => $deleteForm->createView(),        ));
    }

    /**
     * Displays a form to edit an existing Eproduit entity.
     *
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('ApplicationCertificatsBundle:Eproduit')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Eproduit entity.');
        }

        $editForm = $this->createForm(new EproduitType(), $entity);
        $deleteForm = $this->createDeleteForm($id);

        return $this->render('ApplicationCertificatsBundle:Eproduit:edit.html.twig', array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Edits an existing Eproduit entity.
     *
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('ApplicationCertificatsBundle:Eproduit')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Eproduit entity.');
        }

        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createForm(new EproduitType(), $entity);
        $editForm->bind($request);

        if ($editForm->isValid()) {
             $entity->setUpdatedAt(new \DateTime());
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('eproduit_edit', array('id' => $id)));
        }

        return $this->render('ApplicationCertificatsBundle:Eproduit:edit.html.twig', array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a Eproduit entity.
     *
     */
    public function deleteAction(Request $request, $id)
    {
        $form = $this->createDeleteForm($id);
        $form->bind($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('ApplicationCertificatsBundle:Eproduit')->find($id);

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
    private function createDeleteForm($id)
    {
        return $this->createFormBuilder(array('id' => $id))
            ->add('id', 'hidden')
            ->getForm()
        ;
    }
}
