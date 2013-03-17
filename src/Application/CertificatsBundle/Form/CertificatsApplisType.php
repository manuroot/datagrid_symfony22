<?php

namespace Application\CertificatsBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Application\CertificatsBundle\Form\CertificatsProjetType;

class CertificatsApplisType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('nomapplis','text',array('label' => 'Application'))
                ->add('description','text',array('label' => 'Description'));
              //  CertificatsApplisSimpleType
             //   $builder->add('tags', 'collection', array('type' => new TagType()));
       //     ->add('idprojets')
        ;
        
       /* $builder->add('idprojets', 'collection', array(
            'type' => new CertificatsProjetType(),
           'allow_add'    => true,
                 'allow_delete' => true,
                'by_reference' => false,
                  'required' => false
            
            ));*/
        
       /*  $builder->add('idprojets','entity', array(
            'class' => 'Application\CertificatsBundle\Entity\CertificatsProjet',
            'expanded' => true,
            'multiple' => true,
         
            ));*/
        /* $builder->add('idprojets', 'collection', array(
    'type' => new CertificatsProjetType(),
    'prototype' => true,
    'allow_add' => true,
    'allow_delete' => true
));*/
         $builder->add('idprojets','entity', array(
            'class' => 'Application\CertificatsBundle\Entity\CertificatsProjet',
            'property' => 'nomprojet',
            'multiple' => true,
            'required' => true,
            'label' => 'Nom des Projets'
            ));
    }
  
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Application\CertificatsBundle\Entity\CertificatsApplis'
        ));
    }

    public function getName()
    {
        return 'Formulaire_Application';
    }
}
