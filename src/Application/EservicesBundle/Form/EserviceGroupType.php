<?php

namespace Application\EservicesBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class EserviceGroupType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('nomGroup',null,array('label'=> 'Nom du groupe'))
            ->add('description',null,array('label'=> 'Description'))
            ->add('email')
        ;
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Application\EservicesBundle\Entity\EserviceGroup'
        ));
    }

    public function getName()
    {
        return 'application_eservicesbundle_eservicegrouptype';
    }
}
