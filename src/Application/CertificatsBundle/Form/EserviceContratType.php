<?php

namespace Application\CertificatsBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class EserviceContratType extends AbstractType {

    public function buildForm(FormBuilderInterface $builder, array $options) {
        $builder
                ->add('dateFin', 'datetime', array(
                    'label' => 'Date Fin',
                    'widget' => 'single_text',
                    'input' => 'datetime',
                    'format' => 'yyyy-MM-dd HH:mm',
                    'widget_addon' => array(
                        'icon' => 'time',
                        'type' => 'prepend'
                        )))
                ->add('name', 'text', array(
                    'widget_addon' => array(
                        'icon' => 'pencil',
                        'type' => 'prepend'
                    ),
                ))
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
                        )))
                ->add('idStatus', null, array('label' => 'Status'))
                ->add('idusers', null, array('label' => 'Utilisateurs'))
                //->add('demandeur')
                ->add('produits')
        ;
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver) {
        $resolver->setDefaults(array(
            'data_class' => 'Application\CertificatsBundle\Entity\Eservice'
        ));
    }

    public function getName() {
        return 'eservice_form';
    }

}
