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
use Knp\Menu\ItemInterface as MenuItemInterface;

class EpostAdmin extends Admin {

    /**
     * @var UserManagerInterface
     */
    protected $userManager;

    /**
     * @var Pool
     */
    protected $formatterPool;
    protected $commentManager;

    
    
public function getTemplate($name)
{
    switch ($name) {
        case 'edit':
            return 'ApplicationEpostBundle::base_edit_form.html.twig';
            break;
        default:
            return parent::getTemplate($name);
            break;
    }
}
    /**
     * {@inheritdoc}
     */
    protected function configureShowFields(ShowMapper $showMapper) {

        $showMapper
                ->add('name')
                ->add('tags')
                ->add('notes')
                ->add('proprietaire',null,array('label'=>'User'))
                  ->add('slug')
                ->add('categorie')
                ->add('image')
                ->add('resume',null,array('label'=>'Résumé'))
                ->add('description')
                ->add('createdAt')
                ->add('updatedAt')
        ;
    }

    protected function configureListFields(ListMapper $listMapper) {
        $listMapper
                ->addIdentifier('id')
                ->addIdentifier('name',null,array('label'=>'Nom'))
             //   ->add('name',null,array('label'=>'Nom'))
                 ->add('slug',null,array('label'=>'Slug'))
                ->add('resume')
                ->add('createdAt')
                ->add('updatedAt')
                ->add('proprietaire',null,array('label'=>'User'))
                ->add('idStatus',null,array('label'=>'Status'))
                 ->add('isvisible', null, array('editable' => true))
                
                ->add('commentsEnabled', null, array('editable' => true))
            /*    ->add('_action', 'actions', array(
                'actions' => array(
                'view' => array(),
                'edit' => array(),
                'delete' => array(),
                )
            ))*/
        ;          
                /*  ->add('category')
          ->add('content')
          ->add('author')
          ->add('enabled')
          ->add('_action', 'actions', array(
          'actions' => array(
          'view' => array(),
          'edit' => array(),
          'delete' => array(),
          )
          )) */
        ;
    }

    /*
     * 
     */

    /**
     * {@inheritdoc}
     */
    protected function configureFormFields(FormMapper $formMapper) {
        //   $commentClass = $this->commentManager->getClass();

        /*  parent::configureFormFields($formMapper); */
        /*  $formMapper->remove('rawContent'); */
        $formMapper
                ->with('General')
                 ->add('name')
                
                 ->add('description', 'genemu_tinymce', array('attr' => array('class' => 'tinymce')))
                /*->add('description', 'textarea', array(
                    'label' => 'Description du Post',
                    'attr' => array(
                        'cols' => "60",
                        'rows' => "10",
                      //  'class' => 'tinymce',
                        )))*/
                ->add('resume')
                
                  ->end()
                ->with('Dates')
                ->add('categorie')
                ->add('tags')
                ->add('createdAt')
                ->add('updatedAt')
                        ->end()
                ->with('Divers')
                ->add('proprietaire')
                ->add('idStatus')
                ->add('isvisible')
                ->add('commentsEnabled')
                /*  ->add('rawContent', 'textarea', array(
                  'attr' => array(
                  //  'cols'=>"60",
                  'rows' => "20",
                  'class' => 'tinymce',
                  'data-theme' => 'simple'

                  // simple, advanced, bbcode
                  ))) */
                ->end()
                ->with('ImageMedia')
            ->add('imageMedia', 'sonata_type_model_list', array('required' => false),
                array('link_parameters'=>array('context'=>'default',
               'provider'=>'sonata.media.provider.image')))
                  ->end();

        /* $formMapper->with('ImagePost')
          ->add('image', 'sonata_type_model_list', array('required' => false), array('link_parameters' => array('context' => 'default',
          'provider' => 'sonata.media.provider.image')))
          ->end(); */
    }

    /**
     * {@inheritdoc}
     */
    protected function configureDatagridFilters(DatagridMapper $datagridMapper) {

        $datagridMapper
                ->add('name')
                ->add('resume')
                   ->add('idStatus')
                ->add('isvisible')
             
                 ->add('proprietaire', null, array('field_options' => array('expanded' => false, 'multiple' => false)))
                         
               /* ->add('createdAt')
                ->add('updatedAt')*/
        ;
    }

}
