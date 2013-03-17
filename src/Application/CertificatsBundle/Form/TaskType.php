<?php

namespace Application\CertificatsBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class TaskType extends AbstractType {

    public function buildForm(FormBuilderInterface $builder, array $options) {
        $builder
                ->add('nom')
                ->add('tags', 'collection', array(
                    'type' => new TagType(),
                    'allow_add' => true,
                    'allow_delete' => true,
                    'prototype' => true,
                    // Post update
                    'by_reference' => false,
                ))
        ;
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver) {
        $resolver->setDefaults(array(
            'data_class' => 'Application\CertificatsBundle\Entity\Task'
        ));
    }

    public function getName() {
        return 'application_certificatsbundle_tasktype';
    }

}
