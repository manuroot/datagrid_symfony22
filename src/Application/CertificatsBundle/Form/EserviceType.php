<?php

namespace Application\CertificatsBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class EserviceType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('dateFin')
            ->add('name')
            ->add('description')
            ->add('idusers')
            ->add('demandeur')
        ;
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Application\CertificatsBundle\Entity\Eservice'
        ));
    }

    public function getName()
    {
        return 'application_certificatsbundle_eservicetype';
    }
}
