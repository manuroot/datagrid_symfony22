<?php

namespace Application\EservicesBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use Application\EservicesBundle\Entity\EserviceGroup;
use Application\EservicesBundle\Form\EserviceGroupType;

/**
 * EserviceGroup controller.
 *
 */
class EserviceGroupController extends Controller
{
    /**
     * Lists all EserviceGroup entities.
     *
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $entities = $em->getRepository('ApplicationEservicesBundle:EserviceGroup')->findAll();

        return $this->render('ApplicationEservicesBundle:EserviceGroup:index.html.twig', array(
            'entities' => $entities,
        ));
    }

    /**
     * Creates a new EserviceGroup entity.
     *
     */
    public function createAction(Request $request)
    {
        $entity  = new EserviceGroup();
        $form = $this->createForm(new EserviceGroupType(), $entity);
        $form->bind($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('egroup_groupes_show', array('id' => $entity->getId())));
        }

        return $this->render('ApplicationEservicesBundle:EserviceGroup:new.html.twig', array(
            'entity' => $entity,
            'form'   => $form->createView(),
        ));
    }

    /**
     * Displays a form to create a new EserviceGroup entity.
     *
     */
    public function newAction()
    {
        $entity = new EserviceGroup();
        $form   = $this->createForm(new EserviceGroupType(), $entity);

        return $this->render('ApplicationEservicesBundle:EserviceGroup:new.html.twig', array(
            'entity' => $entity,
            'form'   => $form->createView(),
        ));
    }

    /**
     * Finds and displays a EserviceGroup entity.
     *
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('ApplicationEservicesBundle:EserviceGroup')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find EserviceGroup entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return $this->render('ApplicationEservicesBundle:EserviceGroup:show.html.twig', array(
            'entity'      => $entity,
            'delete_form' => $deleteForm->createView(),        ));
    }

    /**
     * Displays a form to edit an existing EserviceGroup entity.
     *
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('ApplicationEservicesBundle:EserviceGroup')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find EserviceGroup entity.');
        }

        $editForm = $this->createForm(new EserviceGroupType(), $entity);
        $deleteForm = $this->createDeleteForm($id);

        return $this->render('ApplicationEservicesBundle:EserviceGroup:edit.html.twig', array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Edits an existing EserviceGroup entity.
     *
     */
    public function updateAction(Request $request, $id)
    {
       /* echo "update groupe<br>";
        exit(1);
        */
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('ApplicationEservicesBundle:EserviceGroup')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find EserviceGroup entity.');
        }

        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createForm(new EserviceGroupType(), $entity);
        $editForm->bind($request);

        if ($editForm->isValid()) {
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('egroup_groupes', array('id' => $id)));
        }

        return $this->render('ApplicationEservicesBundle:EserviceGroup:edit.html.twig', array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a EserviceGroup entity.
     *
     */
    public function deleteAction(Request $request, $id)
    {
        $form = $this->createDeleteForm($id);
        $form->bind($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('ApplicationEservicesBundle:EserviceGroup')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find EserviceGroup entity.');
            }

            $em->remove($entity);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('egroup_groupes'));
    }

    /**
     * Creates a form to delete a EserviceGroup entity by id.
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
