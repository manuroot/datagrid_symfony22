<?php

namespace Application\EpostBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Application\EpostBundle\Entity\Epost;
use Application\EpostBundle\Form\EpostType;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Component\Security\Acl\Domain\ObjectIdentity;
use Symfony\Component\Security\Acl\Domain\UserSecurityIdentity;
use Symfony\Component\Security\Acl\Permission\MaskBuilder;
use JMS\SecurityExtraBundle\Annotation\Secure;
use Symfony\Component\HttpFoundation\JsonResponse;

/**
 * Epost controller.
 *
 */
class EpostController extends Controller {

    private function getuserid() {


        $em = $this->getDoctrine()->getManager();
        $user_security = $this->container->get('security.context');
        if ($user_security->isGranted('IS_AUTHENTICATED_FULLY')) {
        $user = $this->get('security.context')->getToken()->getUser();
            //if ($user_security->isGranted('IS_AUTHENTICATED_FULLY')) {
            // authenticated REMEMBERED, FULLY will imply REMEMBERED (NON anonymous)
            $user_id = $user->getId();
            $group_id = $user->getIdgroup()->getId();
        } else {
            $user_id = 0;
            $group_id= 0;
        }
        return array($user_id,$group_id);
    }

    /**
     * Lists all Epost entities.
     *
     */
    public function indexAction() {

        $em = $this->getDoctrine()->getManager();
        list($user_id,$group_id)=$this->getuserid();
        $session = $this->getRequest()->getSession();
        $session->set('buttonretour', 'epost');

        
          $query = $em->getRepository('ApplicationEpostBundle:Epost')->myFindAll($user_id);
        $query_other = $em->getRepository('ApplicationEpostBundle:Epost')->myFindOtherAll($user_id,$group_id);
        $paginator = $this->get('knp_paginator');
        //   $query = $em->getRepository('ApplicationEpostBundle:Epost')->findAll();
        $pagename1 = 'page1'; // Set custom page variable name
        $page1 = $this->get('request')->query->get($pagename1, 1); // Get custom page variable
        $paginationa = $paginator->paginate(
                $query, $page1, 3, array('pageParameterName' => $pagename1,
            "sortDirectionParameterName" => "dir1",
            'sortFieldParameterName' => "sort1")
        );

        $pagename2 = 'page2'; // Set another custom page variable name
        $page2 = $this->get('request')->query->get($pagename2, 1); // Get another custom page variable
        $paginationb = $paginator->paginate(
                $query_other, $page2, 3, array('pageParameterName' => $pagename2,
            "sortDirectionParameterName" => "dir2",
            'sortFieldParameterName' => "sort2")
        );
        //$query = $em->getRepository('ApplicationEpostBundle:CertificatsCenter')->myFindaAll();

        $paginationa->setTemplate('ApplicationEpostBundle:pagination:twitter_bootstrap_pagination.html.twig');
        $paginationb->setTemplate('ApplicationEpostBundle:pagination:twitter_bootstrap_pagination.html.twig');
       
        return $this->render('ApplicationEpostBundle:Epost:index.html.twig', array(
                    'paginationa' => $paginationa,
                    'paginationb' => $paginationb,
                ));
    }

   
    // @Secure(roles="ROLE_ADMIN")
    
    public function indexAllAction() {
        $em = $this->getDoctrine()->getManager();
        $session = $this->getRequest()->getSession();
        $session->set('buttonretour', 'epost_indexadmin');

        $query = $em->getRepository('ApplicationEpostBundle:Epost')->myFind();

        $paginator = $this->get('knp_paginator');
        $pagename1 = 'page1'; // Set custom page variable name
        $page1 = $this->get('request')->query->get($pagename1, 1); // Get custom page variable
        $paginationa = $paginator->paginate(
                $query, $page1, 5, array('pageParameterName' => $pagename1)
        );


        $paginationa->setTemplate('ApplicationEpostBundle:pagination:twitter_bootstrap_pagination.html.twig');
        return $this->render('ApplicationEpostBundle:Epost:indexadmin.html.twig', array(
                    'paginationa' => $paginationa,
                ));
    }

    /**
     *
     */
    public function indexmespostsAction() {

        $em = $this->getDoctrine()->getManager();
        list($user_id,$group_id)=$this->getuserid();
       
        $session = $this->getRequest()->getSession();
        $session->set('buttonretour', 'epost_mesposts');
        $query = $em->getRepository('ApplicationEpostBundle:Epost')->myFindAll($user_id);

        $paginator = $this->get('knp_paginator');
        $pagename1 = 'page1'; // Set custom page variable name
        $page1 = $this->get('request')->query->get($pagename1, 1); // Get custom page variable
        $paginationa = $paginator->paginate(
                $query, $page1, 5, array('pageParameterName' => $pagename1)
        );


        $paginationa->setTemplate('ApplicationEpostBundle:pagination:twitter_bootstrap_pagination.html.twig');
        return $this->render('ApplicationEpostBundle:Epost:indexmesposts.html.twig', array(
                    'paginationa' => $paginationa,
                ));
    }

    public function indexpropositionsAction() {

        $em = $this->getDoctrine()->getManager();
         list($user_id,$group_id)=$this->getuserid();
         $session = $this->getRequest()->getSession();
        $session->set('buttonretour', 'epost_propositions');
        $query = $em->getRepository('ApplicationEpostBundle:Epost')->myFindOtherAll($user_id,$group_id);

        $paginator = $this->get('knp_paginator');
        $pagename1 = 'page1'; // Set custom page variable name
        $page1 = $this->get('request')->query->get($pagename1, 1); // Get custom page variable
        $paginationa = $paginator->paginate(
                $query, $page1, 5, array('pageParameterName' => $pagename1)
        );


        $paginationa->setTemplate('ApplicationEpostBundle:pagination:twitter_bootstrap_pagination.html.twig');
        return $this->render('ApplicationEpostBundle:Epost:indexpropositions.html.twig', array(
                    'paginationa' => $paginationa,
                ));
    }

    /**
     * Creates a new Epost entity.
     *
     */
    public function createAction(Request $request) {
        $entity = new Epost();
        $form = $this->createForm(new EpostType(), $entity);
        $form->bind($request);
        $user = $this->get('security.context')->getToken()->getUser();
        $user_security = $this->container->get('security.context');
        if ($user_security->isGranted('IS_AUTHENTICATED_REMEMBERED')) {
            //  if ($user_security->isGranted('IS_AUTHENTICATED_FULLY')) {
            // authenticated REMEMBERED, FULLY will imply REMEMBERED (NON anonymous)
            $user_id = $user->getId();
        } else {
            $user_id = 0;
        }
          $session = $this->getRequest()->getSession();
      //  $session->get('buttonretour', 'eproduit');
         $myretour = $session->get('buttonretour');

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $current_user = $em->getRepository('ApplicationSonataUserBundle:User')->find($user_id);
            $entity->setProprietaire($current_user);
            $entity->setUpdatedAt(new \DateTime());
            $em->persist($entity);
            $em->flush();

            $session = $this->getRequest()->getSession();
            $nom_modif = $entity->getName();
            $id = $entity->getId();
            $session->getFlashBag()->add('warning', "Enregistrement $nom_modif ($id) ajouté");
            return $this->redirect($this->generateUrl('epost_show', array('id' => $entity->getId())));
        }

        return $this->render('ApplicationEpostBundle:Epost:new.html.twig', array(
                    'entity' => $entity,
             'btnretour' => $myretour,
                    'form' => $form->createView(),
                ));
    }

    /**
     * Displays a form to create a new Epost entity.
     *
     */
    public function newAction() {
        $entity = new Epost();
        /*
          $helper = $this->container->get('vich_uploader.templating.helper.uploader_helper');
          $path = $helper->asset($entity, 'image'); */
        $form = $this->createForm(new EpostType(), $entity);
        $session = $this->getRequest()->getSession();
        $myretour = $session->get('buttonretour');
        return $this->render('ApplicationEpostBundle:Epost:new.html.twig', array(
                    'entity' => $entity,
                    'btnretour' => $myretour,
                    'form' => $form->createView(),
                ));
    }

    
    
    /**
     * Finds and displays a Epost entity.
     *
     */
    public function showAction($id) {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('ApplicationEpostBundle:Epost')->find($id);
        $helper = $this->container->get('vich_uploader.templating.helper.uploader_helper');
//$path = $helper->asset($entity, 'image');
        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Epost entity.');
        }
        
        $comments = $em->getRepository('ApplicationEpostBundle:EpostComments')
                   ->getCommentsForPost($entity->getId());
        
        
        $session = $this->getRequest()->getSession();
        $myretour = $session->get('buttonretour');

        $deleteForm = $this->createDeleteForm($id);

        return $this->render('ApplicationEpostBundle:Epost:show.html.twig', array(
                    'entity' => $entity,
                    'btnretour' => $myretour,
                    'comments'  => $comments,
                    //   'path' => $path,
                    'delete_form' => $deleteForm->createView(),));
    }

    /**
     * Displays a form to edit an existing Epost entity.
     *
     */
    public function editAction($id) {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('ApplicationEpostBundle:Epost')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Epost entity.');
        }
        $session = $this->getRequest()->getSession();
        $myretour = $session->get('buttonretour');
             
        $user_id = $this->getuserid();
        $proprietaire = $entity->getProprietaire()->getId();
        if ($user_id != $proprietaire) {
            return $this->render('ApplicationEpostBundle:Epost:deny.html.twig', array(
                    ));
        }

        $editForm = $this->createForm(new EpostType(), $entity);
        $deleteForm = $this->createDeleteForm($id);

        return $this->render('ApplicationEpostBundle:Epost:edit.html.twig', array(
                    'entity' => $entity,
                    'btnretour' => $myretour,
                    'edit_form' => $editForm->createView(),
                    'delete_form' => $deleteForm->createView(),
                ));
    }

    /**
     * Edits an existing Epost entity.
     *
     */
    public function updateAction(Request $request, $id) {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('ApplicationEpostBundle:Epost')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Epost entity.');
        }

        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createForm(new EpostType(), $entity);
        $editForm->bind($request);
            $session = $this->getRequest()->getSession();
            $myretour = $session->get('buttonretour');
            if (!isset($myretour))
                $myretour='epost';
        if ($editForm->isValid()) {
            $entity->setUpdatedAt(new \DateTime());
            $em->persist($entity);
            $em->flush();
           
            $session->getFlashBag()->add('warning', "Enregistrement $id update successfull");
            $route_back = $session->get('buttonretour');
            if (isset($route_back))
                return $this->redirect($this->generateUrl($route_back, array('id' => $id)));
            else
                return $this->redirect($this->generateUrl('epost', array('id' => $id)));
        }

        return $this->render('ApplicationEpostBundle:Epost:edit.html.twig', array(
                    'entity' => $entity,
            'btnretour' => $myretour,
                    'edit_form' => $editForm->createView(),
                    'delete_form' => $deleteForm->createView(),
                ));
    }

    /**
     * Deletes a Epost entity.
     *
     */
    public function deleteAction(Request $request, $id) {
        $form = $this->createDeleteForm($id);
        $form->bind($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('ApplicationEpostBundle:Epost')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find Epost entity.');
            }

            $em->remove($entity);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('epost'));
    }

    /**
     * Creates a form to delete a Epost entity by id.
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

    public function searchPostAction() {
        $request = $this->getRequest();

        if ($request->isXmlHttpRequest() && $request->getMethod() == 'POST') {
            $em = $this->getDoctrine()->getEntityManager();
            $id = '';
            $applis = array();
            $cert_app = array();

            $id = $request->request->get('id_projet');
            $projet = $em->getRepository('ApplicationEpostBundle:CertificatsProjet')->find($id);

            $id_cert = $request->request->get('id_cert');
            if (isset($id_cert) && $id_cert != "create") {
                //    var_dump($id_cert);
                $cert = $em->getRepository('ApplicationEpostBundle:CertificatsCenter')->find($id_cert);
                foreach ($cert->getIdapplis() as $appli) {
                    array_push($cert_app, $appli->getId());
                }
                $applis['cert'] = $cert_app;
            }
            foreach ($projet->getIdapplis() as $appli) {
                //$applis[] = array($appli);
                $applis['applis'][$appli->getId()] = $appli->getNomapplis();
                //      $applis[] = array($appli->getId(), $appli->getNomapplis());
            }

            //    $appli=array(3,4);
            $response = new Response(json_encode($applis));
            $response->headers->set('Content-Type', 'application/json');

            return $response;
        }
        // return new Response();
    }

    public function indexsearchAction() {

        $em = $this->getDoctrine()->getManager();
       list($user_id,$group_id)=$this->getuserid();
    
        $session = $this->getRequest()->getSession();
        $session->set('buttonretour', 'epost_indexserch');

        $query = $em->getRepository('ApplicationEpostBundle:Epost')->myFindAll($user_id);
        $query_other = $em->getRepository('ApplicationEpostBundle:Epost')->myFindOtherAll($user_id,$group_id);
        $paginator = $this->get('knp_paginator');
        //   $query = $em->getRepository('ApplicationEpostBundle:Epost')->findAll();
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
        //$query = $em->getRepository('ApplicationEpostBundle:CertificatsCenter')->myFindaAll();

        $paginationa->setTemplate('ApplicationEpostBundle:pagination:twitter_bootstrap_pagination.html.twig');
        $paginationb->setTemplate('ApplicationEpostBundle:pagination:twitter_bootstrap_pagination.html.twig');
        //  $pagination->setTemplate('ApplicationEpostBundle:pagination:sliding.html.twig');
        return $this->render('ApplicationEpostBundle:Epost:search.html.twig', array(
                    'paginationa' => $paginationa,
                    'paginationb' => $paginationb,
                ));
        ;
    }

    
     public function mediaAction(Request $request)
{
// preset a default value
$media = $this->get('sonata.media.manager.media')->create();
$media->setBinaryContent('http://www.youtube.com/watch?v=qTVfFmENgPU');
 
// create the target object
$mediaPreview = new MediaPreview();
$mediaPreview->setMedia($media);
 
// create the form
$builder = $this->createFormBuilder($mediaPreview);
$builder->add('media', 'sonata_media_type', array(
'provider' => 'sonata.media.provider.youtube',
'context' => 'default'
));
 
$form = $builder->getForm();
 
// bind and transform the media's binary content into real content
if ($request->getMethod() == 'POST') {
$form->bindRequest($request);
 
$this->getSeoPage()
->setTitle($media->getName())
->addMeta('property', 'og:description', $media->getDescription())
->addMeta('property', 'og:type', 'video')
;
}
 
return $this->render('SonataDemoBundle:Demo:media.html.twig', array(
'form' => $form->createView(),
'media' => $mediaPreview->getMedia()
));
}
 
/**
* @return \Sonata\SeoBundle\Seo\SeoPageInterface
*/
public function getSeoPage()
{
return $this->get('sonata.seo.page');
}
}
