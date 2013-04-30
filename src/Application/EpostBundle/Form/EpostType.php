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
                    'label' => 'Resumé du Post',
                    'attr' => array(
                        'cols' => "60",
                         )))
                ->add('name', 'genemu_jqueryautocomplete_entity', array(
                    'class' => 'Application\EpostBundle\Entity\Epost',
                    'property' => 'name',
                    'configs' => array(
                        'minLength' => 0,
                    ),
                ))
                //  ->add('name',null,array('label'=>'Nom du Post'))
               
                
                ->add('description', 'textarea', array(
                    'label' => 'Description du Post',
                    'attr' => array(
                        'cols' => "60",
                        'class' => 'tinymce',
                        )))

                // ->add('services')
                //->add('image')
                ->add('image', 'file', array(
                    'data_class' => 'Symfony\Component\HttpFoundation\File\File',
                    'property_path' => 'image',
                    'required' => false,
                ));
        
        
        $builder->add('categorie', 'entity', array(
                    //'class' => 'Application\EpostBundle\Entity\CertificatsProjet',
                    'class' => 'ApplicationEpostBundle:EpostCategories',
                    'query_builder' => function(EntityRepository $em) {
                        return $em->createQueryBuilder('u')
                                ->orderBy('u.nom', 'ASC');
                    },
                    'property' => 'nom',
                    'multiple' => false,
                    'required' => true,
                    'label' => 'Categorie',
                    'empty_value' => '--- Choisir une option ---'
                ))
                ->add('idStatus', null, array('label' => 'Status'))
       
        ;
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver) {
        $resolver->setDefaults(array(
            'data_class' => 'Application\EpostBundle\Entity\Epost'
        ));
    }

    public function getName() {
        return 'application_certificatsbundle_eposttype';
    }

}
