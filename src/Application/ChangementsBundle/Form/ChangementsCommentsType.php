<?php

namespace Application\ChangementsBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\HttpFoundation\File\File;
use Doctrine\ORM\EntityRepository;

class ChangementsCommentsType extends AbstractType {

    public function buildForm(FormBuilderInterface $builder, array $options) {
           $builder
           ->add('user',null,array( 'disabled' => true,'label'=>'Utilisateur'))
            ->add('comment',null,array('label'=>'Activité'))
          //  ->add('approved')
         //   ->add('created')
        //    ->add('updated')
         //   ->add('blog')
        ;
            
        
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver) {
        $resolver->setDefaults(array(
            'data_class' => 'Application\ChangementsBundle\Entity\ChangementsComments'
        ));
    }

    public function getName() {
        return 'application_changements_commentstype';
    }

}