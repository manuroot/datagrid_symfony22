<?php

namespace Application\EpostBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\HttpFoundation\File\File;
use Doctrine\ORM\EntityRepository;

class EpostCommentsType extends AbstractType {

    public function buildForm(FormBuilderInterface $builder, array $options) {
        
           $builder
           ->add('user',null,array( 'disabled' => true,'label'=>'Utilisateur'))
                   //  ->add('isComment',null,array('label'=>"Demande de Pret",'required' => false))
           // ->add('comment')
                   ->add('comment', 'textarea', array(
                       'label'=>'Message',
                    'attr' => array(
                        'cols' => "60",
                       
                        'class' => 'tinymce',
                    'data-theme' => 'simple'
// simple, advanced, bbcode
                        )))
          //  ->add('approved')
         //   ->add('created')
        //    ->add('updated')
         //   ->add('blog')
        ;
            
        
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver) {
        $resolver->setDefaults(array(
            'data_class' => 'Application\EpostBundle\Entity\EpostComments'
        ));
    }

    public function getName() {
        return 'application_certificatsbundle_epostcommentstype';
    }

}
