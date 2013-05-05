<?php

namespace Application\EpostBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\HttpFoundation\File\File;
use Doctrine\ORM\EntityRepository;

class EpostType extends AbstractType {

    public function buildForm(FormBuilderInterface $builder, array $options) {
        $builder
                ->add('resume', 'textarea', array(
                   // 'help_label' => '(Max: 200 car.)',
                    'label' => 'Resumé du Post',
                    /*'attr' => array(
                        'cols' => "60",
                        )*/
                    ))
               // cyclic dependance ??
               /*  ->add('name', 'genemu_jqueryautocomplete_entity', array(
                    'widget_addon' => array(
                        'icon' => 'pencil',
                        'type' => 'prepend'
                    ),
                    'class' => 'Application\EpostBundle\Entity\Epost',
                    'property' => 'name',
                    'configs' => array(
                        'minLength' => 0,
                    ),
                ))*/
                
                 ->add('name', null, array('label'=>'Nom',
                    'widget_addon' => array(
                        'icon' => 'pencil',
                        'type' => 'prepend'
                    )))
                
                ->add('tags')
                //  ->add('name',null,array('label'=>'Nom du Post'))
                ->add('description', 'textarea', array(
                    'label' => 'Description du Post',
                    'attr' => array(
                        'cols' => "60",
                        'rows' => "20",
                        'class' => 'tinymce',
                        )))

               
                // ->add('services')
                //->add('image')
                ->add('image', 'file', array(
                    'data_class' => 'Symfony\Component\HttpFoundation\File\File',
                    'property_path' => 'image',
                    'required' => false,
                ))
                ->add('isvisible', null, array('label' => "Post Actif"))
                ->add('commentsEnabled', null, array('label' => "Fermer les Commentaires"));

        $builder->add('categorie', 'entity', array(
                    //'class' => 'Application\EpostBundle\Entity\CertificatsProjet',
                    'class' => 'ApplicationEpostBundle:EpostCategories',
                    'query_builder' => function(EntityRepository $em) {
                        return $em->createQueryBuilder('u')
                                ->orderBy('u.name', 'ASC');
                    },
                    'property' => 'name',
                    'multiple' => false,
                    'required' => true,
                    'label' => 'Categorie',
                    'empty_value' => '--- Choisir une option ---'
                ))
          
                            
                ->add('idStatus', null, array('label' => 'Status'))
                ->add('commentsCloseAt', 'datetime', array(
                    'label' => 'Date Fermeture des Commentaires',
                    'widget' => 'single_text',
                    'input' => 'datetime',
                    'format' => 'yyyy-MM-dd HH:mm',
                    'widget_addon' => array(
                        'icon' => 'time',
                        'type' => 'prepend'
                    ),  'required' => false,
                ))
        ;
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver) {
        $resolver->setDefaults(array(
            'data_class' => 'Application\EpostBundle\Entity\Epost'
        ));
    }

    public function getName() {
        return 'application_eposttype';
    }

}
