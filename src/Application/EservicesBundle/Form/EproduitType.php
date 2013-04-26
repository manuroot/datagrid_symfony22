<?php

namespace Application\EservicesBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\HttpFoundation\File\File;
use Doctrine\ORM\EntityRepository;

class EproduitType extends AbstractType {

    public function buildForm(FormBuilderInterface $builder, array $options) {
        $builder
                ->add('name',null,array('label'=>'Nom du Produit'))
                ->add('description', 'textarea', array(
                    'label'=>'Description du Produit',
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
         /*->add('image', 'sonata_media_type', array(
                 'provider' => 'sonata.media.provider.image',
                 'context'  => 'default'
            ));*/
                /*
                 *  $builder->add('logo', 'sonata_media_type', array(
                 'provider' => 'sonata.media.provider.image',
                 'context'  => 'avatar'
            ));
                 * 
                 * 
                 *  ->add('produit', 'entity', array(
            //'class' => 'Application\EservicesBundle\Entity\CertificatsProjet',
            'class' => 'ApplicationEservicesBundle:Eproduit',
             'query_builder' => function(EntityRepository $em) {
                return $em->createQueryBuilder('u')
                        ->where
                                ->orderBy('u.nom', 'ASC');
            },
                 */
                 $builder->add('categorie', 'entity', array(
            //'class' => 'Application\EservicesBundle\Entity\CertificatsProjet',
            'class' => 'ApplicationEservicesBundle:EproduitCategories',
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
                /* ->add('image', 'file',
array(
'label' => 'Hast du ein Screenshot von der Nachricht'
)
)*/
        ;
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver) {
        $resolver->setDefaults(array(
            'data_class' => 'Application\EservicesBundle\Entity\Eproduit'
        ));
    }

    public function getName() {
        return 'application_certificatsbundle_eproduittype';
    }

}
