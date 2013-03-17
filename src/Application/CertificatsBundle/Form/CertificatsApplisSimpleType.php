<?php

namespace Application\CertificatsBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Application\CertificatsBundle\Form\CertificatsProjetType;

class CertificatsApplisSimpleType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('nomapplis','text',array('label' => 'Application'))
            ->add('description','text',array('label' => 'Description'));
       
    }
  
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Application\CertificatsBundle\Entity\CertificatsApplis'
        ));
    }

    public function getName()
    {
        return 'Formulaire_Application_Simple';
    }
}
