<?php

namespace Application\MyNotesBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Application\MyNotesBundle\Entity\Notes;
use Application\MyNotesBundle\Form\NotesType;
use Pagerfanta\Pagerfanta;
use Pagerfanta\Adapter\DoctrineORMAdapter;
use  Pagerfanta\Exception\NotValidCurrentPageException;
use APY\DataGridBundle\Grid\Source\Entity;
use APY\DataGridBundle\Grid\Grid;
use APY\DataGridBundle\Grid\Column\ActionsColumn;
use APY\DataGridBundle\Grid\Action\MassAction;
use APY\DataGridBundle\Grid\Action\DeleteMassAction;
use APY\DataGridBundle\Grid\Action\RowAction;
 
/**
 * Notes controller.
 *
 */
class NotesController extends Controller {

    /**
     * Lists all Notes entities.
     *
     */
    public function indexAction() {
        $em = $this->getDoctrine()->getManager();
         $entities = $em->getRepository('ApplicationMyNotesBundle:Notes')->myFindaAll();
     //   $entities = $em->createQuery('SELECT a FROM ApplicationMyNotesBundle:Notes a'); //(2)


        /* return $this->render('ApplicationMyNotesBundle:Notes:index.html.twig', array(
          'entities' => $entities,
          )); */

        $paginator = $this->get('knp_paginator');
        $pagination = $paginator->paginate(
                $entities, $this->get('request')->query->get('page', 1)/* page number */, 5/* limit per page */
        );
      //  $pagination->setSortableTemplate('ApplicationMyNotesBundle:Notes:index.html.twig');
       $pagination->setTemplate('ApplicationMyNotesBundle:pagination:sliding.html.twig');
//$pagination->setSortableTemplate('MyBundle:Pagination:sortable.html.twig');

// parameters to template
        return $this->render('ApplicationMyNotesBundle:Notes:index.html.twig', array(
                    'pagination' => $pagination,
                ));
//return compact('pagination');
    }
private function mypager($adapter=null,$max=5,$page=1){
        if (isset($adapter)){
         $pagerfanta = new Pagerfanta($adapter);
        $pagerfanta->setMaxPerPage(5);
           
        return $pagerfanta;
           }
        else {return null;}
        
    }
    
     public function indexgenemuAction() {
      
// parameters to template
           $entity = new Notes();
        $form = $this->createForm(new NotesType(), $entity);

        return $this->render('ApplicationMyNotesBundle:Notes:indexgenemu.html.twig', array(
                    'entity' => $entity,
                    'form' => $form->createView(),
                ));
        
    
//return compact('pagination');
    }
    
    //==============================================
    // VIEW ALL ACTEURS
    //==============================================
    public function viewfantaAction($page = null) {
        /* Automatique
          $request = $this->get('request');
          $page = $request->query->get('page',1);
         */
        $em = $this->container->get('doctrine')->getEntityManager();
         $repo = $em->getRepository('ApplicationMyNotesBundle:Notes')->myFindAll();
     //
     //  $entityQuery = $em->getRepository('MyAppFilmothequeBundle:Acteur')->myXFindAll();
        $adapter = new DoctrineORMAdapter($repo);
        $pagerfanta=$this->mypager($adapter);
         try {
            $pagerfanta->setCurrentPage($page);
             $q = $pagerfanta->getCurrentPageResults();
        } catch (NotValidCurrentPageException $e) {
            throw new NotFoundHttpException();
        }
        return $this->container->get('templating')->renderResponse(
                        'ApplicationMyNotesBundle:Notes:index_fanta.html.twig', array(
                    'pagerfanta' => $pagerfanta,
                    'entities' => $q,
                ));
    }
  //==============================================
    // VIEW ALL ACTEURS
    //==============================================
    public function viewapyAction($page = 1) {
        
       //    $em = $this->container->get('doctrine')->getEntityManager();
       //  $source = $em->getRepository('ApplicationMyNotesBundle:Notes');
     
       $source = new Entity('ApplicationMyNotesBundle:Notes');
        // Get a Grid instance
        // $grid = new Grid('grid');
        $grid = $this->container->get('grid');
        // Attach the source to the grid
         $grid->setSource($source);
         $grid->setDefaultOrder('id', 'desc');
      //   $grid->addExport(new XMLExport('XML Export', 'export'));
        // Set the selector of the number of items per page
        $grid->setLimits(array(5, 10, 15));

        // Set the default page
        $grid->setPage(1);
        $grid->addMassAction(new DeleteMassAction());
        // action column
        $actionsColumn = new ActionsColumn('info_column_1', 'Actions 1');
$actionsColumn->setSeparator("<br />");
//$grid->addColumn($actionsColumn);

        
         // Add row actions in the default row actions column
      // $myRowAction = new RowAction('Edit', 'notes_edit');
        
$myRowAction = new RowAction('', 'notes_edit', true, '_self', array('class' => 'editme'));

        $myRowAction->setColumn('info_column1');
        $grid->addRowAction($myRowAction);

        $myRowAction = new RowAction('Delete', 'notes_delete', true, '_self');
        $grid->addRowAction($myRowAction);
         // Return the response of the grid to the template
        return $grid->getGridResponse('ApplicationMyNotesBundle:Notes:indexapy.html.twig');

        
    }
    /**
     * Finds and displays a Notes entity.
     *
     */
    public function showAction($id) {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('ApplicationMyNotesBundle:Notes')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Notes entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return $this->render('ApplicationMyNotesBundle:Notes:show.html.twig', array(
                    'entity' => $entity,
                    'delete_form' => $deleteForm->createView(),));
    }

    /**
     * Displays a form to create a new Notes entity.
     *
     */
    public function newAction() {
        $entity = new Notes();
        $form = $this->createForm(new NotesType(), $entity);

        return $this->render('ApplicationMyNotesBundle:Notes:new.html.twig', array(
                    'entity' => $entity,
                    'form' => $form->createView(),
                ));
    }

    /**
     * Creates a new Notes entity.
     *
     */
    public function createAction(Request $request) {
        $entity = new Notes();
        $form = $this->createForm(new NotesType(), $entity);
        $form->bind($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('notes_show', array('id' => $entity->getId())));
        }

        return $this->render('ApplicationMyNotesBundle:Notes:new.html.twig', array(
                    'entity' => $entity,
                    'form' => $form->createView(),
                ));
    }

    /**
     * Displays a form to edit an existing Notes entity.
     *
     */
    public function editAction($id) {
        $em = $this->getDoctrine()->getManager();
        $entity = $em->getRepository('ApplicationMyNotesBundle:Notes')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Notes entity.');
        }

        $editForm = $this->createForm(new NotesType(), $entity);
        $deleteForm = $this->createDeleteForm($id);

        return $this->render('ApplicationMyNotesBundle:Notes:edit.html.twig', array(
                    'entity' => $entity,
                    'edit_form' => $editForm->createView(),
                    'delete_form' => $deleteForm->createView(),
                ));
    }

    /**
     * Edits an existing Notes entity.
     *
     */
    public function updateAction(Request $request, $id) {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('ApplicationMyNotesBundle:Notes')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Notes entity.');
        }

        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createForm(new NotesType(), $entity);
        $editForm->bind($request);

        if ($editForm->isValid()) {
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('notes_edit', array('id' => $id)));
        }

        return $this->render('ApplicationMyNotesBundle:Notes:edit.html.twig', array(
                    'entity' => $entity,
                    'edit_form' => $editForm->createView(),
                    'delete_form' => $deleteForm->createView(),
                ));
    }

    /**
     * Deletes a Notes entity.
     *
     */
    
       //==============================================
    // SUPPRIMER ACTEUR
    //==============================================
    public function deleteAction($id) {
        $em = $this->container->get('doctrine')->getEntityManager();
        $note = $em->find('ApplicationMyNotesBundle:Notes', $id);
        if (!$note) {
            throw new NotFoundHttpException("Note non trouvée");
        }
        $em->remove($note);
        $em->flush();
         return $this->redirect($this->generateUrl('notes'));
       // return new RedirectResponse($this->container->get('router')->generate('notesfanta'));
    }
    
    
    public function deleteActionqq(Request $request, $id) {
        $form = $this->createDeleteForm($id);
        $form->bind($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('ApplicationMyNotesBundle:Notes')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find Notes entity.');
            }

            $em->remove($entity);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('notes'));
    }

    private function createDeleteForm($id) {
        return $this->createFormBuilder(array('id' => $id))
                        ->add('id', 'hidden')
                        ->getForm()
        ;
    }

}
