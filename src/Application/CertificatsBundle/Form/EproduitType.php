<?php

namespace Application\CertificatsBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\HttpFoundation\File\File;
use Doctrine\ORM\EntityRepository;

class EproduitType extends AbstractType {

    public function buildForm(FormBuilderInterface $builder, array $options) {
        $builder
                ->add('name')
                ->add('description', 'textarea', array(
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
            //'class' => 'Application\CertificatsBundle\Entity\CertificatsProjet',
            'class' => 'ApplicationCertificatsBundle:EproduitCategories',
             'query_builder' => function(EntityRepository $em) {
                return $em->createQueryBuilder('u')
                                ->orderBy('u.nom', 'ASC');
            },
            'property' => 'nom',
            'multiple' => false,
            'required' => true,
            'label' => 'Categorie',
           'empty_value' => '--- Choisir une option ---'
        ));
                /* ->add('image', 'file',
array(
'label' => 'Hast du ein Screenshot von der Nachricht'
)
)*/
        ;
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver) {
        $resolver->setDefaults(array(
            'data_class' => 'Application\CertificatsBundle\Entity\Eproduit'
        ));
    }

    public function getName() {
        return 'application_certificatsbundle_eproduittype';
    }

}
