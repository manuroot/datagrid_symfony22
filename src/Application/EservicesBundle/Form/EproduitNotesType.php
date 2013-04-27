<?php

namespace Application\EservicesBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class EproduitNotesType extends AbstractType
{
      private $UserId;
    private $ProduitId;

    public function __construct($userid = null, $produitid = null) {

        $this->UserId = $userid;
        $this->ProduitId = $produitid;
    }
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
         /* ->add('user',null,array( 'disabled' => true,'label'=>'Utilisateur'))
                     ->add('isComment',null,array('label'=>"Demande de Pret",'required' => false))
       
     */    
         $user_id = $this->UserId;
        $produit_id = $this->ProduitId;
       /* echo "userid=--$user_id--";
        exit(1);*/
        	/*$builder->add('user', 'hidden', array(
'data' => $user_id,
)); */
             /* if (isset($user_id)) {
                  $builder->add('user',null,array( 'disabled' => true,'label'=>'Utilisateur'));
              }
              else {
                   $builder->add('user',null,array( 'label'=>'Utilisateur'));
              }*/
          /* if (isset($produit_id)) {
                   $builder->add('produit',null,array( 'disabled' => true,'label'=>'Produit'));
              }
              else {
                 $builder->add('produit',null,array('label'=>'Produit'));
              }*/
       /* $builder
      ->add('ajax_simple', 'genemu_jqueryautocomplete_text', array(
            'route_name' => 'ajax',
            'mapped'=>false,
        ));*/
        $builder
      // ->add('note', 'genemu_jqueryslider')
           // ->add('note','text',array('label'=>'Note du Produit'))
         //       ->add('note', 'genemu_jqueryrating')
        
    //    ->add('note', 'genemu_jqueryrating')
      /*  ->add('readonly_rating', 'genemu_jqueryrating', array(
            'configs' => array(
                'readOnly' => true
            )
        ))*/
      ->add('note', 'genemu_jqueryrating', array(
     'number' => 5,
            'configs' => array(
               
                'readOnly' => false
            )
        ))
        
           
// simple, advanced, bbcode
      
        //   ->add('note', 'genemu_jqueryrating')
                
              /*  ->add('note', 'text', array(
                    'label' => 'Note'))*/
           //     ->add('note', 'genemu_jqueryslider')
            ->add('user',null,array( 'label'=>'Utilisateur'))
            ->add('produit',null,array( 'label'=>'Produit'))
                
            // ->add('geolocation', 'genemu_jquerygeolocation',array('mapped'=>false))
               // ->add('country', 'genemu_jqueryselect2_country',array('mapped'=>false))
        ;
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            
            'data_class' => 'Application\EservicesBundle\Entity\EproduitNotes'
        ));
    }

    public function getName()
    {
        return 'application_eservicesbundle_eproduitnotestype';
    }
}
