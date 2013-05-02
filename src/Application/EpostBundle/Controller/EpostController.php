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

    private function sidebar_years() {
        $em = $this->container->get('doctrine')->getManager();
        $myarr = array();
        $myarr['current_year'] = date('Y');
        $arr_years = $em->getRepository('ApplicationEpostBundle:Epost')->findaByYear($myarr['current_year']);
        return ($arr_years);
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
        return $pagination;
    }

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
            
            
     // }else {
        return array($user_id, $group_id);
     //   }
    }

    /**
     * @param string $tag
     *
     * @return \Symfony\Component\HttpFoundation\Response
     *
     * @throws \Symfony\Component\HttpKernel\Exception\NotFoundHttpException
     */
    public function tagAction($tag) {

        $query = $em->getRepository('ApplicationEpostBundle:Epost')->myFindAll($user_id);

        $tag = $this->get('sonata.news.manager.tag')->findOneBy(array(
            'slug' => $tag,
            'enabled' => true
                ));

        if (!$tag) {
            throw new NotFoundHttpException('Unable to find the tag');
        }

        if (!$tag->getEnabled()) {
            throw new NotFoundHttpException('Unable to find the tag');
        }

        return $this->renderArchive(array('tag' => $tag), array('tag' => $tag));
    }

    /**
     * @param $category
     *
     * @return \Symfony\Component\HttpFoundation\Response
     *
     * @throws \Symfony\Component\HttpKernel\Exception\NotFoundHttpException
     */
    public function categoryAction($category) {
        $category = $this->get('sonata.news.manager.category')->findOneBy(array(
            'slug' => $category,
            'enabled' => true
                ));

        if (!$category) {
            throw new NotFoundHttpException('Unable to find the category');
        }

        if (!$category->getEnabled()) {
            throw new NotFoundHttpException('Unable to find the category');
        }

        return $this->renderArchive(array('category' => $category), array('category' => $category));
    }

    /**
     * Lists all Epost entities.
     *
     */
    public function indexAction() {

        $em = $this->getDoctrine()->getManager();
        list($user_id, $group_id) = $this->getuserid();
        $session = $this->getRequest()->getSession();
        $session->set('buttonretour', 'epost');
        $all_years = $this->sidebar_years();

        $query = $em->getRepository('ApplicationEpostBundle:Epost')->myFindAll($user_id);
        $query_other = $em->getRepository('ApplicationEpostBundle:Epost')->myFindOtherAll($user_id, $group_id);
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
                    'all_years' => $all_years,
                ));
    }

    // @Secure(roles="ROLE_ADMIN")
    //====================================================================
    // BLOG ADMIN
    //====================================================================
    public function indexAllAction() {
        $em = $this->getDoctrine()->getManager();
        $session = $this->getRequest()->getSession();
        $session->set('buttonretour', 'epost_indexadmin');
        $query = $em->getRepository('ApplicationEpostBundle:Epost')->myFind();
        $paginationa = $this->createpaginator($query, 5);
        $paginationa->setTemplate('ApplicationEpostBundle:pagination:twitter_bootstrap_pagination.html.twig');
        return $this->render('ApplicationEpostBundle:Epost:indexadmin.html.twig', array(
                    'paginationa' => $paginationa,
                ));
    }

    //====================================================================
    // BLOG STANDARD
    //====================================================================

    public function standardblogAction() {
        $em = $this->getDoctrine()->getManager();
        $session = $this->getRequest()->getSession();
        $session->set('buttonretour', 'epost_indexstandard');
        $query = $em->getRepository('ApplicationEpostBundle:Epost')->myFindActif();
        $paginationa = $this->createpaginator($query, 5);
        $paginationa->setTemplate('ApplicationEpostBundle:pagination:twitter_bootstrap_pagination.html.twig');
        return $this->render('ApplicationEpostBundle:Epost:standardblog.html.twig', array(
                    'paginationa' => $paginationa,
                ));
    }

    //====================================================================
    // BLOG POST DU USER CONNECTE
    //====================================================================

    public function indexmespostsAction() {

        $em = $this->getDoctrine()->getManager();
        list($user_id, $group_id) = $this->getuserid();
        if ($group_id==0){
             return $this->render('ApplicationEpostBundle:Epost:choosegroup.html.twig');
        }
        $session = $this->getRequest()->getSession();
        $session->set('buttonretour', 'epost_mesposts');
        $query = $em->getRepository('ApplicationEpostBundle:Epost')->myFindAll($user_id);
        $paginationa = $this->createpaginator($query, 5);
        $paginationa->setTemplate('ApplicationEpostBundle:pagination:twitter_bootstrap_pagination.html.twig');
        return $this->render('ApplicationEpostBundle:Epost:indexmesposts.html.twig', array(
                    'paginationa' => $paginationa,
                ));
    }

    //====================================================================
    // BLOG POST LIE AU GROUP DU USER CONNECTE
    //====================================================================


    public function indexpropositionsAction() {

        $em = $this->getDoctrine()->getManager();
        list($user_id, $group_id) = $this->getuserid();
        if ($group_id==0){
             return $this->render('ApplicationEpostBundle:Epost:choosegroup.html.twig');
        }
        $session = $this->getRequest()->getSession();
        $session->set('buttonretour', 'epost_propositions');
        $query = $em->getRepository('ApplicationEpostBundle:Epost')->myFindOtherAll($user_id, $group_id);
        $paginationa = $this->createpaginator($query, 5);
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
        list($user_id, $group_id) = $this->getuserid();
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
                    'comments' => $comments,
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

        list($user_id, $group_id) = $this->getuserid();
        $proprietaire = $entity->getProprietaire()->getId();
        //echo "u=$user_id  p=$proprietaire<br>";
        //    exit(1);
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
            $myretour = 'epost';
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
        list($user_id, $group_id) = $this->getuserid();

        $session = $this->getRequest()->getSession();
        $session->set('buttonretour', 'epost_indexserch');

        $query = $em->getRepository('ApplicationEpostBundle:Epost')->myFindAll($user_id);
        $query_other = $em->getRepository('ApplicationEpostBundle:Epost')->myFindOtherAll($user_id, $group_id);
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

    public function mediaAction(Request $request) {
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
    public function getSeoPage() {
        return $this->get('sonata.seo.page');
    }

}
