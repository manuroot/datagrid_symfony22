<?php

namespace Application\ChangementsBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Session\Session;
use Application\ChangementsBundle\Entity\Changements;
use Application\ChangementsBundle\Entity\Document;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Application\ChangementsBundle\Form\ChangementsType;
use Application\ChangementsBundle\Entity\GridExport;
use APY\DataGridBundle\Grid\Source\Entity;
use APY\DataGridBundle\Grid\Grid;
use APY\DataGridBundle\Grid\Column\ActionsColumn;
use APY\DataGridBundle\Grid\Action\MassAction;
use APY\DataGridBundle\Grid\Action\DeleteMassAction;
use APY\DataGridBundle\Grid\Action\RowAction;
use APY\DataGridBundle\Grid\Column\TextColumn;
use APY\DataGridBundle\Grid\Column\DateColumn;
use APY\DataGridBundle\Grid\Export\ExcelExport;

/* use Pagerfanta\Pagerfanta;
  use Pagerfanta\Adapter\DoctrineORMAdapter;
  use Pagerfanta\Exception\NotValidCurrentPageException; */

/**
 * Changements controller.
 *
 */
class ChangementsController extends Controller {

    /**
     * Lists all Changements entities.
     *
     */
    public function indexAction() {

        
        
    /*    
   $filters = new Filters();

    $form = $this->createForm(new FiltersType(), $filters);

    $session = $this->getRequest()->getSession();

    if ($session->get('dql') == null) {
        $session->set('dql', "SELECT a FROM ViciousAmateurBundle:Post a WHERE a.is_active = true");
    }

    if ($request->isMethod('POST')) {
        $form->bind($request);

        if ($form->isValid()) {
            $dql = "SELECT a FROM ViciousAmateurBundle:Post a WHERE a.is_active = true";
            $country = $filters->getCountry();
            $city = $filters->getCity();
            $gender = $filters->getGender();
            $sexualOrientation = $filters->getSexualOrientation();

            if (isset($country)) {
                $dql .= " AND a.country = '" . $filters->getCountry() . "'";
            }
            if (isset($city)) {
                $dql .= " AND a.city = '" . $filters->getCity() . "'";
            }
            if (isset($gender)) {
                $dql .= " AND a.gender = '" . $filters->getGender() . "'";
            }
            if (isset($sexualOrientation)) {
                $dql .= " AND a.sexual_orientation = '" . $filters->getSexualOrientation() . "'";
            }

            $session->set('dql', $dql);
        }
    }

    $em = $this->get('doctrine.orm.entity_manager');

    $query = $em->createQuery($session->get('dql'));

    $paginator = $this->get('knp_paginator');
    $pagination = $paginator->paginate(
        $query,
        $this->get('request')->query->get('page', $page),
        5
    );

    return $this->render('ViciousAmateurBundle:Default:index.html.twig', array(
        'form' => $form->createView(),
        'pagination' => $pagination
        )
    );
    */
    
    
    
    
    
    
    
    
        //     $past = date('Y-m-d', strtotime('-30days'));
        //      $currenta = ($row->getField('endTime')->format('Y-m-d'));
        //$current = date('Y-m-d', strtotime('+30days'));
        $current = new \DateTime("2013-06");
        $past = new \DateTime("2013-05");
        //$current = new \DateTime($row->getField('endTime')->format('Y-m-d'));

        /* $em = $this->getDoctrine()->getManager();

          $entities = $em->getRepository('ApplicationChangementsBundle:Changements')->findAll();

          return $this->render('ApplicationChangementsBundle:Changements:index.html.twig', array(
          'entities' => $entities,
          )); */
//$factory = new CalendR\Calendar;
//$factory->getEventManager()->addProvider('myawesomeprovider', 'new MyAwesomeProvider');
        //  $f=$this->get('booking_repository');
        //  $month = $f->getMonth(2012, 6);
        $session = $this->getRequest()->getSession();
        $session->set('buttonretour', 'changements');
        $em = $this->getDoctrine()->getManager();
        $query = $em->getRepository('ApplicationChangementsBundle:Changements')->myFindAll();
        
        
        $query_events = $em->getRepository('ApplicationChangementsBundle:Changements')
                ->getEventsQueryBuilder($past, $current);
/*$nb_events=$em->getRepository('ApplicationChangementsBundle:Changements')
                ->findcount($past, $current);*/
        //    print_r($query_events);
        // exit(1);
        // $nbtags = $query->getPicture()->count();
        $month = $this->get('calendr')->getMonth(2013, 03);
        $events = $this->get('calendr')->getEvents($month);
        $paginator = $this->get('knp_paginator');
        $pagination = $paginator->paginate(
                $query, $this->get('request')->query->get('page', 1)/* page number */, 10/* limit per page */
        );
        $pagination->setTemplate('ApplicationChangementsBundle:pagination:sliding.html.twig');
        return $this->render('ApplicationChangementsBundle:Changements:index.html.twig', array(
                    'pagination' => $pagination,
                    'month' => $this->get('calendr')->getMonth(2013, 03),
                    // 'myweek' =>  $this->get('calendr')->getWeek(2012, 14),
                    'events' => $query_events,
                    'evenement' => $events,
                        // 'current_month' => $month
                ));
    }


    public function calendarAction() {

        //     $past = date('Y-m-d', strtotime('-30days'));
        //      $currenta = ($row->getField('endTime')->format('Y-m-d'));
        //$current = date('Y-m-d', strtotime('+30days'));
        $current = new \DateTime("2013-06");
        $past = new \DateTime("2013-05");
        //$current = new \DateTime($row->getField('endTime')->format('Y-m-d'));

        /* $em = $this->getDoctrine()->getManager();

          $entities = $em->getRepository('ApplicationChangementsBundle:Changements')->findAll();

          return $this->render('ApplicationChangementsBundle:Changements:index.html.twig', array(
          'entities' => $entities,
          )); */
//$factory = new CalendR\Calendar;
//$factory->getEventManager()->addProvider('myawesomeprovider', 'new MyAwesomeProvider');
        //  $f=$this->get('booking_repository');
        //  $month = $f->getMonth(2012, 6);
        $session = $this->getRequest()->getSession();
        $session->set('buttonretour', 'changements');
        $em = $this->getDoctrine()->getManager();
   
        $query_events = $em->getRepository('ApplicationChangementsBundle:Changements')
                ->getEventsQueryBuilder($past, $current);
/*$nb_events=$em->getRepository('ApplicationChangementsBundle:Changements')
                ->findcount($past, $current);*/
        //    print_r($query_events);
        // exit(1);
        // $nbtags = $query->getPicture()->count();
        $month = $this->get('calendr')->getMonth(2013, 03);
        $events = $this->get('calendr')->getEvents($month);
        $paginator = $this->get('knp_paginator');
      
        return $this->render('ApplicationChangementsBundle:Changements:calendar.html.twig', array(
                     'month' => $this->get('calendr')->getMonth(2013, 03),
                    // 'myweek' =>  $this->get('calendr')->getWeek(2012, 14),
                    'events' => $query_events,
                    'evenement' => $events,
                        // 'current_month' => $month
                ));
    }

    
//==============================================
    // VIEW ALL ACTEURS
    //==============================================
    public function indexapyAction($page = 1) {

        $session = $this->getRequest()->getSession();
        // ajoute des messages flash
        $session->set('buttonretour', 'changements_apy');
        $source = new Entity('ApplicationChangementsBundle:Changements');


        $source->manipulateRow(
                function ($row) {
                    // Don't show the row if the price is greater than $maxPrice
                    //  $past = date('Y-m-d');
                    $next = date('Y-m-d', strtotime('+5days'));
                    $currenta = ($row->getField('dateDebut')->format('Y-m-d'));
                    $current = date('Y-m-d', strtotime($currenta));
                    //$current = new \DateTime($row->getField('endTime')->format('Y-m-d'));
                    //$current = date('Y-m-d', strtotime($row->getField('endTime')));
                    /* if ($current < $past) {
                      $row->setColor('#fddddd');
                      } */
                    //elseif ($current < $next) {
                    if ($current < $next) {
                        $row->setColor('#fcf8e3');
                    }

                    return $row;
                }
        );


        $grid = $this->container->get('grid');
        // Attach the source to the grid
        $grid->setSource($source);

        $grid->setId('certificatsgrid');
        //chiant si error
        /*  $grid->addExport(new ExcelExport('Excel Export','changements.xls',array(),'Windows-1252'));
          //$grid->addExport(new ExcelExport($title, $fileName, $params, $charset, $role));
          $grid->addExport(new GridExport('CSV Export in French', 'export', array('delimiter' => ';'), 'Windows-1252'));
          // $grid->addExport(new GridExport('CSV Export', 'export')); */
        $grid->setPersistence(false);
        $grid->setDefaultOrder('id', 'desc');
        // Set the selector of the number of items per page
        $grid->setLimits(array(10));

        // Set the default page
        $grid->setPage($page);
        $grid->addMassAction(new DeleteMassAction());
        $grid->setActionsColumnSize(70);
        $myRowActiona = new RowAction('Edit', 'changements_edit', false, '_self', array('class' => "btn btn-mini btn-warning"));
        $grid->addRowAction($myRowActiona);
        $myRowAction = new RowAction('Delete', 'changements_delete', true, '_self', array('class' => "btn btn-mini btn-danger"));
        //$myRowAction = new RowAction('Delete', 'certificatscenter_delete', true, '_self',array('class' => 'deleteme'));
        $grid->addRowAction($myRowAction);
        // Return the response of the grid to the template
        return $grid->getGridResponse('ApplicationChangementsBundle:Changements:indexapy.html.twig');
    }

    /**
     * Finds and displays a Changements entity.
     *
     */
    public function showAction($id) {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('ApplicationChangementsBundle:Changements')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Changements entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return $this->render('ApplicationChangementsBundle:Changements:show.html.twig', array(
                    'entity' => $entity,
                    'delete_form' => $deleteForm->createView(),));
    }

    /**
     * Displays a form to create a new Changements entity.
     *
     */
    public function newAction() {
        $entity = new Changements();
        $form = $this->createForm(new ChangementsType(), $entity);
        // $form->getData()->getNom()->setData('someklklm');
//$entity->setNom("tre");
        return $this->render('ApplicationChangementsBundle:Changements:new.html.twig', array(
                    'entity' => $entity,
                    'form' => $form->createView(),
                ));
    }

    /**
     * Displays a form to create a new Changements entity.
     *
     */
    public function newflowAction() {
        $entity = new Changements();
        //   $form = $this->createForm(new ChangementsFlowType(), $entity);
        // $form->getData()->getNom()->setData('someklklm');
//$entity->setNom("tre");




        $flow = $this->get('application.form.flow.new.changement');
        //     $flow->reset();
// must match the flow's service id
        $flow->bind($entity);

        // form of the current step
        $form = $flow->createForm($entity);
        if ($flow->isValid($form)) {
            $flow->saveCurrentStepData();

            if ($flow->nextStep()) {
                // form for the next step
                $form = $flow->createForm($entity);
            } else {
                // flow finished

                $em = $this->getDoctrine()->getEntityManager();
                $em->persist($entity);
                $em->flush();
                $flow->reset();
                $id = $entity->getId();
                $session = $this->getRequest()->getSession();
                $session->getFlashBag()->add('warning', "Enregistrement $id ajouté avec succès");

                return $this->redirect($this->generateUrl('changements')); // redirect when done
            }
        }


        return $this->render('ApplicationChangementsBundle:Changements:newflow.html.twig', array(
                    'form' => $form->createView(),
                    'flow' => $flow,
                    'entity' => $entity,
                ));
    }

    /**
     * Creates a new Changements entity.
     *
     */
    public function createAction(Request $request) {
        $entity = new Changements();
        //$entity->setNom("tre");
        $form = $this->createForm(new ChangementsType(), $entity);
        $form->bind($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('changements_show', array('id' => $entity->getId())));
        }

        return $this->render('ApplicationChangementsBundle:Changements:new.html.twig', array(
                    'entity' => $entity,
                    'form' => $form->createView(),
                ));
    }

    /**
     * Displays a form to edit an existing Changements entity.
     *
     */
    public function editAction($id) {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('ApplicationChangementsBundle:Changements')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Changements entity.');
        }

        $editForm = $this->createForm(new ChangementsType(), $entity);
        $deleteForm = $this->createDeleteForm($id);



        return $this->render('ApplicationChangementsBundle:Changements:edit.html.twig', array(
                    'entity' => $entity,
                    'edit_form' => $editForm->createView(),
                    'delete_form' => $deleteForm->createView(),
                ));
    }

    /**
     * Edits an existing Changements entity.
     *
     */
    public function updateAction(Request $request, $id) {
        $em = $this->getDoctrine()->getManager();
        $entity = $em->getRepository('ApplicationChangementsBundle:Changements')->find($id);
        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Changements entity.');
        }

        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createForm(new ChangementsType(), $entity);
        $editForm->bind($request);

        if ($editForm->isValid()) {
            /*  $data = $editForm->getData();
              var_dump($data);
              exit; */
            /* if ($form->get('file')->getData() != NULL) {//user have uploaded a new file
              $file = $form->get('file')->getData();//get 'UploadedFile' object
              $news->setPath($file->getClientOriginalName());//change field that holds file's path in db to a temporary value,i.e original file name uploaded by user
              } */
            $em->persist($entity);
            $em->flush();
            $session = $this->getRequest()->getSession();
            $session->getFlashBag()->add('warning', "Enregistrement $id update successfull");
            $route_back = $session->get('buttonretour');
            if (isset($route_back))
                return $this->redirect($this->generateUrl($route_back, array('id' => $id)));
            else
                return $this->redirect($this->generateUrl('changements'));
        }

        return $this->render('ApplicationChangementsBundle:Changements:edit.html.twig', array(
                    'entity' => $entity,
                    'edit_form' => $editForm->createView(),
                    'delete_form' => $deleteForm->createView(),
                ));
    }

    /**
     * Deletes a Changements entity.
     *
     */
    public function deleteAction(Request $request, $id) {
        $form = $this->createDeleteForm($id);
        $form->bind($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('ApplicationChangementsBundle:Changements')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find Changements entity.');
            }

            $em->remove($entity);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('changements'));
    }

    private function createDeleteForm($id) {
        return $this->createFormBuilder(array('id' => $id))
                        ->add('id', 'hidden')
                        ->getForm()
        ;
    }

    /**
     * @Template()
     */
    public function uploadAction() {
        $document = new Document();
        $form = $this->createFormBuilder($document)
                ->add('name')
                ->add('file')
                ->getForm()
        ;

        if ($this->getRequest()->isMethod('POST')) {
            $form->bind($this->getRequest());
            if ($form->isValid()) {
                $em = $this->getDoctrine()->getManager();
                //  $document->upload();    
                $em->persist($document);
                $em->flush();
            }
        }
        return $this->render('ApplicationChangementsBundle:Changements:upload.html.twig', array(
                    'form' => $form->createView(),
                ));
    }

    public function downloadAction($filename) {
        $request = $this->get('request');
        $path = $this->get('kernel')->getRootDir() . "/../web/uploads/documents/";

        // Flush in "safe" mode to enforce an Exception if keys are not unique
        try {
            $content = file_get_contents($path . $filename);
        } catch (\ErrorException $e) {
            $session = $this->getRequest()->getSession();
            $session->getFlashBag()->add('error', "Le fichier $filename n 'existe pas");
            return $this->redirect($this->generateUrl('docchangements'));
        }


        //catches all exceptions extended from Exception (which is everything)



        /*  if (!$data = file_get_contents(file_get_contents($path.$filename))) {
          //  $content = file_get_contents($path.$filename);
          //if (!isset($content)){

          } */

        $response = new Response();

        //set headers
        $response->headers->set('Content-Type', 'mime/type');
        $response->headers->set('Content-Disposition', 'attachment;filename="' . $filename);
        $session = $this->getRequest()->getSession();
        $session->getFlashBag()->add('notice', "Le fichier $filename a ete téléchargé");

        $response->setContent($content);
        return $response;
    }

}