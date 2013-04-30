<?php

/*
 * This file is part of the Sonata package.
 *
 * (c) Thomas Rabaix <thomas.rabaix@sonata-project.org>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Application\EpostBundle\Admin;

use Sonata\AdminBundle\Admin\Admin;
use Sonata\AdminBundle\Admin\AdminInterface;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Show\ShowMapper;
use Sonata\FormatterBundle\Formatter\Pool as FormatterPool;
use Sonata\NewsBundle\Model\CommentManagerInterface;
// IMPORTANT:
use \Sonata\NewsBundle\Admin\PostAdmin as EPostAdmin;
use Knp\Menu\ItemInterface as MenuItemInterface;

class EpostAdmin extends EPostAdmin
{
    /**
     * @var UserManagerInterface
     */
    protected $userManager;

    /**
     * @var Pool
     */
    protected $formatterPool;

    protected $commentManager;

    
    


    /**
     * {@inheritdoc}
     */
  /*  protected function configureShowFields(ShowMapper $showMapper)
    {
           parent::configureShowFields($showMapper);
      
        $showMapper
           ->add('contentFormatter');
          
                /*$showMapper
            ->add('author')
            ->add('enabled')
            ->add('title')
            ->add('abstract')
            ->add('content', null, array('safe' => true))
            ->add('tags')
        ;*/
 /*  }*/

    /**
     * {@inheritdoc}
     */
    protected function configureFormFields(FormMapper $formMapper)
    {
     //   $commentClass = $this->commentManager->getClass();

         parent::configureFormFields($formMapper);
         $formMapper->remove('rawContent');
          $formMapper
            ->with('General')
          ->add('rawContent', 'textarea', array(
        'attr' => array(
          //  'cols'=>"60",
            'rows'=>"20",
            'class' => 'tinymce',
         'data-theme' => 'simple'
           
// simple, advanced, bbcode
        )))
                    ->end();
          
          /*  $formMapper->with('Image')
                    ->add('image', 'sonata_type_model_list', array(), 
                    array('link_parameters' => array('context' => 'default')));*/
           /* $formMapper->with('Image')
                    ->add('image', 'sonata_type_model', array());*/
          
        /* $formMapper->add('image', 'sonata_media_type', array(
                 'provider' => 'sonata.media.provider.image',
                 'context'  => 'news'
            ))*/
           // ...
       /*     $formMapper->with('Image')
    ->add('image', 'sonata_type_model_list',
            array(),
           // array('required' => false),
            array('link_parameters' => array(
                 'provider' => 'sonata.media.provider.image',
                'context' => 'news')))
             ->end();*/
            
           
            $formMapper->with('ImagePost')
            ->add('image', 'sonata_type_model_list', array('required' => false),
                array('link_parameters'=>array('context'=>'default',
               'provider'=>'sonata.media.provider.image')))
                  ->end();
       /*
->add('galleryHasMedias', 'sonata_type_collection', array(
            'by_reference' => false
        ), array(
            'edit' => 'inline',
            'inline' => 'table',
            'sortable'  => 'position',
            'link_parameters' => array('context' => 'default')
        ))*/
                    //  ->end();
         /* $formMapper->with('Image')
                  ->add('image', 'sonata_type_model_list', array('required' => false), 
                    array('link_parameters' => array('context' => 'default')))*/
                /* ->add('image', 'sonata_type_model', array(), array('edit' => 'list'))*/
          /*->add('image', 'sonata_type_model_list', array(),
                  array(
                       'provider' => 'sonata.media.provider.image',
                      'link_parameters' => array('context' => 'default')))*/
          // ->end();
        /*$formMapper
            ->with('General')
                ->add('enabled', null, array('required' => false))
                ->add('author', 'sonata_type_model')
                ->add('category', 'sonata_type_model_list', array('required' => false))
                ->add('title')
                ->add('abstract', null, array('attr' => array('class' => 'span6', 'rows' => 5)))
                ->add('contentFormatter', 'sonata_formatter_type_selector', array(
                    'source' => 'rawContent',
                    'target' => 'content'
                ))
                ->add('rawContent', 'textarea', array(
        'attr' => array(
          //  'cols'=>"60",
            'rows'=>"20",
            'class' => 'tinymce',
         'data-theme' => 'simple'
           
// simple, advanced, bbcode
        )))
             //   ->add('image', 'sonata_type_model_list', array(), array('link_parameters' => array('context' => 'news')))
            //   ->add('image', 'sonata_type_model_list', array(), array('link_parameters' => array('context' => 'news')))
           //     ->add('rawContent', null, array('attr' => array('class' => 'span10', 'rows' => 20)))
            ->end()
            /*    ->with('podcast')
                ->add('podcast','sonata_type_model', array('required' => false))
            ->end()*/
          /*  ->with('Tags')
                ->add('tags', 'sonata_type_model', array(
                    'required' => false,
                    'expanded' => true,
                    'multiple' => true,
                ))
            ->end()
            ->with('Options')
                ->add('publicationDateStart')
                ->add('commentsCloseAt')
                ->add('commentsEnabled', null, array('required' => false))
                ->add('commentsDefaultStatus', 'choice', array('choices' => $commentClass::getStatusList(), 'expanded' => true))
            ->end()
        ;*/
    }

    /**
     * {@inheritdoc}
     */
   /* protected function configureListFields(ListMapper $listMapper)
    {
            parent::configureListFields($listMapper);
      
       $listMapper
           ->add('contentFormatter');
   */
      /*  $listMapper
            ->addIdentifier('title')
            ->add('author')
            ->add('category')
            ->add('enabled', null, array('editable' => true))
            ->add('tags')
            ->add('commentsEnabled', null, array('editable' => true))
            ->add('commentsCount')
                ->add('contentFormatter')
            ->add('publicationDateStart')
                   
        ;*/
 //  }

    /**
     * {@inheritdoc}
     */
    protected function configureDatagridFilters(DatagridMapper $datagridMapper)
    {
     parent::configureDatagridFilters($datagridMapper);
      /* $datagridMapper
            ->add('title')
            ->add('enabled')
            ->add('tags', null, array('field_options' => array('expanded' => true, 'multiple' => true)))
            ->add('author')
            ->add('with_open_comments', 'doctrine_orm_callback', array(
//                'callback'   => array($this, 'getWithOpenCommentFilter'),
                'callback' => function ($queryBuilder, $alias, $field, $data) {
                    if (!is_array($data) || !$data['value']) {
                        return;
                    }
                    $commentClass = $this->commentManager->getClass();
                    $queryBuilder->leftJoin(sprintf('%s.comments', $alias), 'c');
                    $queryBuilder->andWhere('c.status = :status');
                    $queryBuilder->setParameter('status', $commentClass::STATUS_MODERATE);
                },
                'field_type' => 'checkbox'
            ))
        ;*/
    }

    /**
     * {@inheritdoc}
     *
    public function getWithOpenCommentFilter($queryBuilder, $alias, $field, $value)
    {
        if (!is_array($data) || !$data['value']) {
            return;
        }

        $queryBuilder->leftJoin(sprintf('%s.comments', $alias), 'c');
        $queryBuilder->andWhere('c.status = :status');
        $queryBuilder->setParameter('status', Comment::STATUS_MODERATE);
    }*/

    /**
     * {@inheritdoc}
     */
    protected function configureSideMenu(MenuItemInterface $menu, $action, AdminInterface $childAdmin = null)
    {
           parent::configureSideMenu($menu,$action,$childAdmin);
    }

   

    
}
