<?php

namespace Application\EservicesBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use Application\EservicesBundle\Entity\EproduitHistory;
use Application\EservicesBundle\Form\EproduitHistoryType;

/**
 * EproduitHistory controller.
 *
 */
class EproduitHistoryController extends Controller
{
    /**
     * Lists all EproduitHistory entities.
     *
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $entities = $em->getRepository('ApplicationEservicesBundle:EproduitHistory')->findAll();

        return $this->render('ApplicationEservicesBundle:EproduitHistory:index.html.twig', array(
            'entities' => $entities,
        ));
    }

    /**
     * Creates a new EproduitHistory entity.
     *
     */
    public function createAction(Request $request)
    {
        $entity  = new EproduitHistory();
        $form = $this->createForm(new EproduitHistoryType(), $entity);
        $form->bind($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('ep_history_show', array('id' => $entity->getId())));
        }

        return $this->render('ApplicationEservicesBundle:EproduitHistory:new.html.twig', array(
            'entity' => $entity,
            'form'   => $form->createView(),
        ));
    }

    /**
     * Displays a form to create a new EproduitHistory entity.
     *
     */
    public function newAction()
    {
        $entity = new EproduitHistory();
        $form   = $this->createForm(new EproduitHistoryType(), $entity);

        return $this->render('ApplicationEservicesBundle:EproduitHistory:new.html.twig', array(
            'entity' => $entity,
            'form'   => $form->createView(),
        ));
    }

    /**
     * Finds and displays a EproduitHistory entity.
     *
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('ApplicationEservicesBundle:EproduitHistory')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find EproduitHistory entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return $this->render('ApplicationEservicesBundle:EproduitHistory:show.html.twig', array(
            'entity'      => $entity,
            'delete_form' => $deleteForm->createView(),        ));
    }

    /**
     * Displays a form to edit an existing EproduitHistory entity.
     *
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('ApplicationEservicesBundle:EproduitHistory')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find EproduitHistory entity.');
        }

        $editForm = $this->createForm(new EproduitHistoryType(), $entity);
        $deleteForm = $this->createDeleteForm($id);

        return $this->render('ApplicationEservicesBundle:EproduitHistory:edit.html.twig', array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Edits an existing EproduitHistory entity.
     *
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('ApplicationEservicesBundle:EproduitHistory')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find EproduitHistory entity.');
        }

        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createForm(new EproduitHistoryType(), $entity);
        $editForm->bind($request);

        if ($editForm->isValid()) {
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('ep_history_edit', array('id' => $id)));
        }

        return $this->render('ApplicationEservicesBundle:EproduitHistory:edit.html.twig', array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a EproduitHistory entity.
     *
     */
    public function deleteAction(Request $request, $id)
    {
        $form = $this->createDeleteForm($id);
        $form->bind($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('ApplicationEservicesBundle:EproduitHistory')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find EproduitHistory entity.');
            }

            $em->remove($entity);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('history'));
    }

    /**
     * Creates a form to delete a EproduitHistory entity by id.
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
