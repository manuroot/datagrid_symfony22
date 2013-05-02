<?php

namespace Application\RelationsBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class DemandeUsergroupAdminType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
        ->add('name')
            ->add('description')
            ->add('isaccepted')
            ->add('idgroup')
            ->add('iduser')
        ;
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Application\RelationsBundle\Entity\DemandeUsergroup'
        ));
    }

    public function getName()
    {
        return 'application_relationsbundle_demandeusergrouptype';
    }
}
