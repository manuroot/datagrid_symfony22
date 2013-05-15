<?php

namespace Application\EpostBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Application\EpostBundle\Entity\EpostNotes;
use Application\EpostBundle\Entity\EpostGlobalNotes;
use Application\EpostBundle\Form\EpostNotesType;
use Application\EpostBundle\Form\EpostNotesAdminType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

/**
 * EpostNotes controller.
 *
 */
class EpostNotesController extends Controller {

    public function ajaxAction(Request $request) {
        //$value = $request->get('term');
        // .... (Search values)
        $search = array('Bar', 'Foo', 'toto', 'truc');

        $response = new Response();
        $response->setContent(json_encode($search));

        return $response;
    }

    /*
     * Recuperer l'id du user et verifie que $user est authentifié
     */

    private function getuserid() {


        $em = $this->getDoctrine()->getManager();
        $user_security = $this->container->get('security.context');
        // authenticated REMEMBERED, FULLY will imply REMEMBERED (NON anonymous)
        if ($user_security->isGranted('IS_AUTHENTICATED_FULLY')) {
            $user = $this->get('security.context')->getToken()->getUser();
            $user_id = $user->getId();
            $group = $user->getIdgroup();
            if (isset($group)) {
                $group_id = $group->getId();
            } else {
                $group_id = 0;
            }
        } else {
            $user_id = 0;
            $group_id = 0;
        }


        // }else {
        return array($user_id, $group_id);
        //   }
    }

    private function createpaginator($query, $num_perpage = 5) {

        $paginator = $this->get('knp_paginator');
        $pagename = 'page'; // Set custom page variable name
        $page = $this->get('request')->query->get($pagename, 1); // Get custom page variable
        $pagination = $paginator->paginate(
                $query, $page, $num_perpage, array('pageParameterName' => $pagename,
            "sortDirectionParameterName" => "dir",
            'sortFieldParameterName' => "sort")
        );
        $pagination->setTemplate('ApplicationEpostBundle:pagination:twitter_bootstrap_pagination.html.twig');

        return $pagination;
    }

    /**
     * Lists all EpostNotes entities.
     * sur les posts du groupe ??
     */
    public function indexAction() {
        $em = $this->getDoctrine()->getManager();
        $query = $em->getRepository('ApplicationEpostBundle:EpostNotes')->myFindAll();
        $paginationa = $this->createpaginator($query, 10);
        return $this->render('ApplicationEpostBundle:EpostNotes:index.html.twig', array(
                    'paginationa' => $paginationa,
                ));
    }

    
      /**
     * Lists all EpostNotes entities.
     *
     */
    public function indexMyNotesAction() {
        $em = $this->getDoctrine()->getManager();
        $query = $em->getRepository('ApplicationEpostBundle:EpostNotes')->myFindAll();
        $paginationa = $this->createpaginator($query, 10);
        return $this->render('ApplicationEpostBundle:EpostNotes:index.html.twig', array(
                    'paginationa' => $paginationa,
                ));
    }
    
    
    public function addnoteAction($id) {

        //id du post !!!
        // recup du user id
        // trop compliqué

        $em = $this->getDoctrine()->getManager();
        $id_post=$id;
        list($user_id, $group_id) = $this->getuserid();
        $em = $this->getDoctrine()->getManager();
        $entity_epost = $em->getRepository('ApplicationEpostBundle:Epost')->findOneById($id_post);
        if (!$entity_epost) {
            throw $this->createNotFoundException('Ce post n\'existe pas.');
        }

        if ($user_id == 0) {
            throw $this->createNotFoundException('Ce user n\'est pas connecté.');
        }
        $entity_note_user = $em->getRepository('ApplicationEpostBundle:EpostNotes')->findOneBy(array(
            'user' => $user_id,
            'epost' => $id_post));
        // post deja noté on edite la note du user pour maj
        if ($entity_note_user) {
            return $this->redirect($this->generateUrl('epostnotes_edit', array(
                                'id' => $entity_note_user->getId()))
            );
            // throw $this->createNotFoundException('Vous avez deja noté ce post.');
        }
        // pas de note user pour ce post, on cree la note user et on la relie au post
        else {

            $entity_note_user = new EpostNotes();
            $entity_user = $em->getRepository('ApplicationSonataUserBundle:User')->find($user_id);

             $entity_note_user->setNote(0);
            $entity_note_user->setUser($entity_user);
            $entity_note_user->setEpost($entity_epost);
            // Ajout de la note du user pour ce post
            // gestion par em
            $em->persist($entity_note_user);
            // execution de la requete en base
            $em->flush();
            // MAJ note globale
             return $this->redirect($this->generateUrl('epostnotes_edit', array(
                                'id' => $entity_note_user->getId()))
            );
        }
    }

    /**
     * Creates a new EpostNotes entity.
     *
     */
    public function createAction(Request $request) {
        $em = $this->getDoctrine()->getManager();
        $entity = new EpostNotes();

        $form = $this->createForm(new EpostNotesType(), $entity);
        $form->bind($request);

        if ($form->isValid()) {
            $postData = $request->request->get('application_epostbundle_epostnotestype');


            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('epostnotes_show', array('id' => $entity->getId())));
        }

        return $this->render('ApplicationEpostBundle:EpostNotes:new.html.twig', array(
                    'entity' => $entity,
                    'form' => $form->createView(),
                ));
    }

    /**
     * Displays a form to create a new EpostNotes entity.
     *
     */
    public function newAction() {
        $entity = new EpostNotes();
        $form = $this->createForm(new EpostNotesAdminType(), $entity);

        return $this->render('ApplicationEpostBundle:EpostNotes:new.html.twig', array(
                    'entity' => $entity,
                    'form' => $form->createView(),
                ));
    }

    /**
     * Finds and displays a EpostNotes entity.
     *
     */
    public function showAction($id) {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('ApplicationEpostBundle:EpostNotes')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find EpostNotes entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return $this->render('ApplicationEpostBundle:EpostNotes:show.html.twig', array(
                    'entity' => $entity,
                    'delete_form' => $deleteForm->createView(),));
    }

    /**
     * Displays a form to edit an existing EpostNotes entity.
     * $id correspond a id de la note
     *
     */
    public function editAction($id) {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('ApplicationEpostBundle:EpostNotes')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find EpostNotes entity.');
        }

        
      
       /* if ($user_id == 0) {
            $mymessage = "Vous n'estes pas connecté";
            return $this->render('ApplicationRelationsBundle:EserviceGroup:deny.html.twig', array(
                        'mymessage' => $mymessage,
                    ));
        }
        if ($group_id == 0) {
            $mymessage = "Vous n'appartenez a aucun groupe";
            return $this->render('ApplicationRelationsBundle:EserviceGroup:deny.html.twig', array(
                        'mymessage' => $mymessage,
                    ));
        }*/
        
        
        
        
       list($user_id, $group_id) = $this->getuserid();
       $user_note_id=$entity->getUser()->getId();
        if ($user_id != $user_note_id){
            $mymessage = "Vous n'etes pas le proprietaire de cette note";
            return $this->render('ApplicationEpostBundle::deny.html.twig', array(
                        'mymessage' => $mymessage,
                    ));
        }
           
            
            
        $editForm = $this->createForm(new EpostNotesType(), $entity);
        $deleteForm = $this->createDeleteForm($id);

        return $this->render('ApplicationEpostBundle:EpostNotes:edit.html.twig', array(
                    'entity' => $entity,
                    'edit_form' => $editForm->createView(),
                    'delete_form' => $deleteForm->createView(),
                ));
    }

    public function editadminAction($id) {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('ApplicationEpostBundle:EpostNotes')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find EpostNotes entity.');
        }

        $editForm = $this->createForm(new EpostNotesType(), $entity);
        $deleteForm = $this->createDeleteForm($id);

        return $this->render('ApplicationEpostBundle:EpostNotes:edit.html.twig', array(
                    'entity' => $entity,
                    'edit_form' => $editForm->createView(),
                    'delete_form' => $deleteForm->createView(),
                ));
    }

    /**
     * Edits an existing EpostNotes entity.
     * id de la note
     */
    public function updateAction(Request $request, $id) {
        $em = $this->getDoctrine()->getManager();
        $entity = $em->getRepository('ApplicationEpostBundle:EpostNotes')->find($id);
        $session = $this->getRequest()->getSession();
        if (!$entity) {
            throw $this->createNotFoundException('Unable to find EpostNotes entity.');
        }

        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createForm(new EpostNotesType(), $entity);
        $editForm->bind($request);

        if ($editForm->isValid()) {
            // Ajout de la note du user pour ce post
            // gestion par em
            $em->persist($entity);
            // execution de la requete en base
            $em->flush();
            $id_post = $entity->getEpost()->getId();
            // MAJ note globale
            $entity_epost = $em->getRepository('ApplicationEpostBundle:Epost')->find($id_post);
            $calcul_note = $em->getRepository('ApplicationEpostBundle:EpostNotes')->getNotesForEpost($id_post);
            $id_globalnote = $entity_epost->getGlobalNote();
            //si pas de note globale, on l'ajoute
            if (!isset($id_globalnote)) {
                $entity_globalnote = new EpostGlobalNotes();
            } else {
                $entity_globalnote = $em->getRepository('ApplicationEpostBundle:EpostGlobalNotes')->find($id_globalnote->getId());
            }
            $entity_globalnote->setGlobalnote($calcul_note);
            $em->persist($entity_globalnote);
            $em->flush();
            // et on link avec le post
            $entity_epost->setGlobalnote($entity_globalnote);
            $em->persist($entity_epost);
            $em->flush();

            $session->getFlashBag()->add('warning', "Enregistrement $id_post (notes id=$id) update successfull");
            $route_back = $session->get('buttonretour');
            /* if (isset($route_back))
              return $this->redirect($this->generateUrl($route_back, array('id' => $id)));
              else */
            return $this->redirect($this->generateUrl('epostnotes'));
        }
        return $this->render('ApplicationEpostBundle:EpostNotes:edit.html.twig', array(
                    'entity' => $entity,
                    'edit_form' => $editForm->createView(),
                    'delete_form' => $deleteForm->createView(),
                ));
    }

    /**
     * Deletes a EpostNotes entity.
     *
     */
    public function deleteAction(Request $request, $id) {
        $form = $this->createDeleteForm($id);
        $form->bind($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('ApplicationEpostBundle:EpostNotes')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find EpostNotes entity.');
            }

            $em->remove($entity);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('enotes'));
    }

    /**
     * Creates a form to delete a EpostNotes entity by id.
     *
     * @param mixed $id The entity id
     *
     * @return Symfony\Component\Form\Form The form
     */
    private function createDeleteForm($id) {
        return $this->createFormBuilder(array('id' => $id))
                        ->add('id', 'hidden')
                        ->getForm()
        ;
    }

    protected function getEpost($epost_id) {
        $em = $this->getDoctrine()
                ->getManager();
        //->getEntityManager();

        $epost = $em->getRepository('ApplicationEpostBundle:Epost')->find($epost_id);

        if (!$epost) {
            throw $this->createNotFoundException('Unable to find Post.');
        }

        return $epost;
    }

}
