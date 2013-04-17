<?php

namespace Application\CertificatsBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Doctrine\ORM\EntityRepository;

class CertificatsCenterCheckType extends AbstractType {

    public function buildForm(FormBuilderInterface $builder, array $options) {
        $builder
                ->add('contenu', 'textarea', array(
                     'attr' => array('cols' => "60", 'rows' => '10')
                   /* array(
                        'placeholder' => 'Working Hours',
                         'cols'=>"100",
                        'rows' => "10",
                    //   'class'=>'ui-spinner-box',
                    )*/
                    ))
                ->add('opecert', 'choice', array('label' => 'Certificat',
                    'multiple' => false,
                    'choices' => array(
                        0 => 'Certificats(crt)',
                        2 => 'Certificats(pem)',
                        'Autorité(crt)',
                        'Autorité(pem)',
                    ),
                    'attr' => array('style' => 'width:300px', 'customattr' => 'customdata')
                ))
                ->add('typecert', 'choice', array('label' => 'Opération',
                    'multiple' => false,
                    'choices' => $this->mycert(),
                    'attr' => array('style' => 'width:300px', 'customattr' => 'customdata')
                ));
     
        ;
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver) {
        $resolver->setDefaults(array(
                //'data_class' => 'Application\CertificatsBundle\Entity\CertificatsCenter'
        ));
    }

    public function getName() {
        return 'checkcert';
    }

    protected function mycert() {
        $liste_operations_certificat = array(
            'View csr' => 'View csr',
            'View crt' => 'View crt',
            'View der' => 'View der',
            'View bundle' => 'View bundle',
            'View key' => 'View key',
            'View p12' => 'View p12',
            'View crl' => 'View crl',
            'Check crt/key' => 'check crt/key',
            'Create p12' => 'Create p12',
            'Bundle crt/key' => 'Bundle crt/key',
            'Decrypt priv_key' => 'Decrypt priv_key',
            'Convert der->pem' => 'Convert der->pem',
            'Convert pem->der' => 'Convert pem->der',
            'Convert p12->crt/key' => 'Convert p12->crt/key',
            'Parse x509' => 'Parse x509',
                //'Download p12'=> 'Download p12',
        );
        return array_keys($liste_operations_certificat);
    }

}
