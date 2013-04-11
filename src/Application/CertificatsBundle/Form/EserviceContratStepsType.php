<?php

namespace Application\CertificatsBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class EserviceContratStepsType extends AbstractType {

  public function buildForm(FormBuilderInterface $builder, array $options) {

        switch ($options['flowStep']) {
            //====================================================
            // CASE 1   
            //====================================================
            case 1:
                $builder
                        ->add('name', 'text', array(
                            'widget_addon' => array(
                                'icon' => 'pencil',
                                'type' => 'prepend'
                            ),
                        ))
                        ->add('dateDebut', 'date', array(
                            'label' => 'Date début',
                            'widget' => 'single_text',
                            'widget_addon' => array(
                                'icon' => 'time',
                                'type' => 'prepend'
                            ),
                        ))
                     ->add('description', 'textarea', array(
        'attr' => array(
            'cols'=>"60",
          //  'rows'=>"10",
            'class' => 'tinymce',
         'data-theme' => 'simple'
           
// simple, advanced, bbcode
        )))
                         
                       /* ->add('description','textarea',array(
                             'label' => 'Description',
                            //'widget' => 'single_text',
                            'attr' => array('class'=>'mytextarea'),
                        ))*/
                        
                        
                        ;
                break;
               
            
            //====================================================
            // CASE 2   
            //====================================================

            case 2:
                $builder
                
                        ->add('dateFin', 'date', array(
                            'label' => 'Date Fin',
                            'widget' => 'single_text',
                            'widget_addon' => array(
                                'icon' => 'time',
                                'type' => 'prepend'
                            ),
                        ));
                            break;
          
            
            
            case 3:
                $builder
                        ->add('brouzoufs', 'text', array(
                    'widget_addon' => array(
                        'icon' => 'pencil',
                        'type' => 'prepend'
                    ),
                ))
                ->add('description', 'textarea', array(
                    'attr' => array(
                        'cols' => "60",
                        'rows' => "20",
                        'class' => 'tinymce',
                    // 'data-theme' => 'simple'
// simple, advanced, bbcode
                        )));


                        

                break;

             /* case 6:
              $builder->add('confirmation', 'checkbox');
              break; */
        }

        //  ->add('fic')
        //    ->add('nbfiles')
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver) {
        $resolver->setDefaults(array(
            'flowStep' => 1,
            'data_class' => 'Application\CertificatsBundle\Entity\Eservice',
            'cascade_validation' => true,
        ));
    }

    public function getName() {
        return 'changements';
    }

}

