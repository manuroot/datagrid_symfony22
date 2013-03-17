<?php

namespace Application\CertificatsBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Application\CertificatsBundle\Entity\Task;
use Application\CertificatsBundle\Form\TaskType;

/**
 * Task controller.
 *
 */
class TaskController extends Controller {

    /**
     * Lists all Task entities.
     *
     */
    public function indexAction() {
        $em = $this->getDoctrine()->getManager();

        $entities = $em->getRepository('ApplicationCertificatsBundle:Task')->findAll();

        return $this->render('ApplicationCertificatsBundle:Task:index.html.twig', array(
                    'entities' => $entities,
                ));
    }

    /**
     * Finds and displays a Task entity.
     *
     */
    public function showAction($id) {
        $em = $this->getDoctrine()->getManager();
        $tagstatus = 'noki';
        $entity = $em->getRepository('ApplicationCertificatsBundle:Task')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Task entity.');
        }

        $deleteForm = $this->createDeleteForm($id);
        //    $nbtags = $entity->getTags()->count();
        if ($nbtags == 1) {
            $tagstatus = 'oki';
        }
        return $this->render('ApplicationCertificatsBundle:Task:show.html.twig', array(
                    'entity' => $entity,
                    'delete_form' => $deleteForm->createView(),
                    'nbtags' => $nbtags,
                    'tagstatus' => $tagstatus,
                ));
    }

    /**
     * Displays a form to create a new Task entity.
     *
     */
    public function newAction() {
        $entity = new Task();
        $form = $this->createForm(new TaskType(), $entity);

        return $this->render('ApplicationCertificatsBundle:Task:new.html.twig', array(
                    'entity' => $entity,
                    'form' => $form->createView(),
                ));
    }

    /**
     * Creates a new Task entity.
     *
     */
    public function createAction(Request $request) {
        $entity = new Task();
        $form = $this->createForm(new TaskType(), $entity);
        $form->bind($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('task_show', array('id' => $entity->getId())));
        }

        return $this->render('ApplicationCertificatsBundle:Task:new.html.twig', array(
                    'entity' => $entity,
                    'form' => $form->createView(),
                ));
    }

    /**
     * Displays a form to edit an existing Task entity.
     *
     */
    public function editAction($id) {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('ApplicationCertificatsBundle:Task')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Task entity.');
        }

        $editForm = $this->createForm(new TaskType(), $entity);
        $deleteForm = $this->createDeleteForm($id);

        return $this->render('ApplicationCertificatsBundle:Task:edit.html.twig', array(
                    'entity' => $entity,
                    'edit_form' => $editForm->createView(),
                    'delete_form' => $deleteForm->createView(),
                ));
    }

    /**
     * Edits an existing Task entity.
     *
     */
    public function updateAction(Request $request, $id) {
        $em = $this->getDoctrine()->getManager();

        $task = $em->getRepository('ApplicationCertificatsBundle:Task')->find($id);

        if (!$task) {
            throw $this->createNotFoundException('Unable to find Task entity.');
        }

        $originalTags = array();
        // Crée un tableau contenant les objets Tag courants de la
        // base de données
        foreach ($task->getTags() as $tag)
            $originalTags[] = $tag;

// Crée un tableau contenant les objets Tag courants de la
        // base de données
        //  $entity->getTags()->removeElement($comment);


        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createForm(new TaskType(), $task);
        $editForm->bind($request);

        if ($editForm->isValid()) {

            // filtre $originalTags pour ne contenir que les tags
            // n'étant plus présents
            foreach ($task->getTags() as $tag) {
                foreach ($originalTags as $key => $toDel) {
                    if ($toDel->getId() === $tag->getId()) {
                        unset($originalTags[$key]);
                    }
                }
            }
            // supprime la relation entre le tag et la « Task »
            foreach ($originalTags as $tag) {
                $todelete = 0;
                if ($tag->getTasks()->count() == 1) {
                    $todelete = 1;
                    //$em->remove($tag);
                }
                // supprime la « Task » du Tag
                $tag->getTasks()->removeElement($task);
                $em->persist($tag);
                if ($todelete == 1) {
                    $em->remove($tag);
                }
            }

            $em->persist($task);
            $em->flush();

            //  $em->persist($entity);
            //  $em->flush();

            return $this->redirect($this->generateUrl('task_edit', array('id' => $id)));
        }

        return $this->render('ApplicationCertificatsBundle:Task:edit.html.twig', array(
                    'entity' => $task,
                    'edit_form' => $editForm->createView(),
                    'delete_form' => $deleteForm->createView(),
                ));
    }

    /**
     * Deletes a Task entity.
     *
     */
    public function deleteAction(Request $request, $id) {
        $form = $this->createDeleteForm($id);
        $form->bind($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('ApplicationCertificatsBundle:Task')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find Task entity.');
            }

            $em->remove($entity);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('task'));
    }

    private function createDeleteForm($id) {
        return $this->createFormBuilder(array('id' => $id))
                        ->add('id', 'hidden')
                        ->getForm()
        ;
    }

}
