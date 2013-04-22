<?php

namespace Application\CertificatsBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use Application\CertificatsBundle\Entity\CertificatsProjet;
use Application\CertificatsBundle\Form\CertificatsProjetType;

use Symfony\Component\HttpFoundation\Session\Session;
use Pagerfanta\Pagerfanta;
use Pagerfanta\Adapter\DoctrineORMAdapter;
use Pagerfanta\Exception\NotValidCurrentPageException;
use Application\CertificatsBundle\Entity\CertificatsCenter;
use Application\CertificatsBundle\Form\CertificatsCenterType;
use APY\DataGridBundle\Grid\Source\Entity;
use APY\DataGridBundle\Grid\Grid;
use APY\DataGridBundle\Grid\Column\ActionsColumn;
use APY\DataGridBundle\Grid\Action\MassAction;
use APY\DataGridBundle\Grid\Action\DeleteMassAction;
use APY\DataGridBundle\Grid\Action\RowAction;
use APY\DataGridBundle\Grid\Column\TextColumn;
use APY\DataGridBundle\Grid\Column\DateColumn;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Component\Security\Acl\Domain\ObjectIdentity;
use Symfony\Component\Security\Acl\Domain\UserSecurityIdentity;
use Symfony\Component\Security\Acl\Permission\MaskBuilder;
use JMS\SecurityExtraBundle\Annotation\Secure;
/**
 * CertificatsProjet controller.
 *
 */
class CertificatsProjetController extends Controller
{
    /**
     * Lists all CertificatsProjet entities.
     *
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();
        $entities = $em->getRepository('ApplicationCertificatsBundle:CertificatsProjet')->findAll();
         $paginator = $this->get('knp_paginator');
        $pagination = $paginator->paginate(
                $entities, $this->get('request')->query->get('page', 1)/* page number */, 10/* limit per page */
        );
        $pagination->setTemplate('ApplicationCertificatsBundle:pagination:sliding.html.twig');
        return $this->render('ApplicationCertificatsBundle:CertificatsProjet:index.html.twig', array(
            'pagination' => $pagination,
        ));
    }

    /**
     * Finds and displays a CertificatsProjet entity.
     *
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('ApplicationCertificatsBundle:CertificatsProjet')->find($id);
        $changements=$em->getRepository('ApplicationChangementsBundle:Changements')->findByIdProjet($id);
        
        
   $applis=$entity->getIdapplis();
     
        if (!$entity) {
            throw $this->createNotFoundException('Unable to find CertificatsProjet entity.');
        }
        
        // find a group of products based on an arbitrary column value
         $repo_certs= $em->getRepository('ApplicationCertificatsBundle:CertificatsCenter');
        $certificats = $repo_certs->findByProject($id);

        $deleteForm = $this->createDeleteForm($id);

        return $this->render('ApplicationCertificatsBundle:CertificatsProjet:show.html.twig', array(
            'entity'      => $entity,
            'delete_form' => $deleteForm->createView(),
            'applis'=>$applis,
            'certificats' => $certificats,
            'changements'=>$changements,
            ));
    }

    /**
     * Displays a form to create a new CertificatsProjet entity.
     *
     */
    public function newAction()
    {
        $entity = new CertificatsProjet();
        $form   = $this->createForm(new CertificatsProjetType(), $entity);

        return $this->render('ApplicationCertificatsBundle:CertificatsProjet:new.html.twig', array(
            'entity' => $entity,
            'form'   => $form->createView(),
        ));
    }

    /**
     * Creates a new CertificatsProjet entity.
     *
     */
    public function createAction(Request $request)
    {
        $entity  = new CertificatsProjet();
        $form = $this->createForm(new CertificatsProjetType(), $entity);
        $form->bind($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('projets_show', array('id' => $entity->getId())));
        }

        return $this->render('ApplicationCertificatsBundle:CertificatsProjet:new.html.twig', array(
            'entity' => $entity,
            'form'   => $form->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing CertificatsProjet entity.
     *
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('ApplicationCertificatsBundle:CertificatsProjet')->find($id);

        $applis=$entity->getIdapplis();
        if (!$entity) {
            throw $this->createNotFoundException('Unable to find CertificatsProjet entity.');
        }

        $editForm = $this->createForm(new CertificatsProjetType(), $entity);
        $deleteForm = $this->createDeleteForm($id);

        return $this->render('ApplicationCertificatsBundle:CertificatsProjet:edit.html.twig', array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
            'applis' => $applis,
        ));
    }

    /**
     * Edits an existing CertificatsProjet entity.
     *
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('ApplicationCertificatsBundle:CertificatsProjet')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find CertificatsProjet entity.');
        }

        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createForm(new CertificatsProjetType(), $entity);
        $editForm->bind($request);

        if ($editForm->isValid()) {
            $em->persist($entity);
            $em->flush();
            $nom=$entity->getNomprojet();
  $session = $this->getRequest()->getSession();
            $session->getFlashBag()->add('warning', "Enregistrement $nom(id=$id) update successfull");
 
            return $this->redirect($this->generateUrl('projets_edit', array('id' => $id)));
        }

        return $this->render('ApplicationCertificatsBundle:CertificatsProjet:edit.html.twig', array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a CertificatsProjet entity.
     *
     */
    public function deleteAction(Request $request, $id)
    {
        $form = $this->createDeleteForm($id);
        $form->bind($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('ApplicationCertificatsBundle:CertificatsProjet')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find CertificatsProjet entity.');
            }

            $em->remove($entity);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('projets'));
    }

    private function createDeleteForm($id)
    {
        return $this->createFormBuilder(array('id' => $id))
            ->add('id', 'hidden')
            ->getForm()
        ;
    }
}
