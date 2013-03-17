<?php

namespace Application\CertificatsBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class ChronoUserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('nomUser')
             ->add('idgroup', 'entity', array(
            //'class' => 'Application\CertificatsBundle\Entity\CertificatsProjet',
            'class' => 'ApplicationCertificatsBundle:ChronoUsergroup',
            'property' => 'nomGroup',
            'multiple' => false,
            'required' => true,
            'label' => 'Groupe',
           'empty_value' => '--- Choisir une option ---'
        ))
        
            ->add('infos')
            ->add('telephone')
            ->add('bureau')
            ->add('email')
        ;
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Application\CertificatsBundle\Entity\ChronoUser'
        ));
    }

    public function getName()
    {
        return 'application_user';
    }
}
