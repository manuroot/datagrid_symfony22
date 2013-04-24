<?php

namespace Application\EservicesBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Doctrine\ORM\EntityRepository;


//use Symfony\Component\Security\Core\Exception\AccessDeniedException;
class EproduitHistoryType extends AbstractType
{
    
    private $isGranted;

public function __construct($roleFlag=null)
{
    
  $this->isGranted = $roleFlag;
}
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        
        //$idUser=$builder->getData()->getId();//le parametre à passer à la fonction
      //  $produit=
        //$user = $this->get('security.context')->getToken()->getUser();
       // $user_security = $this->container->get('security.context');
        //if( $user_security->isGranted('IS_AUTHENTICATED_REMEMBERED') ){
      /*  if ($user_security->isGranted('IS_AUTHENTICATED_FULLY')) {
            // authenticated REMEMBERED, FULLY will imply REMEMBERED (NON anonymous)
            $user_id = $user->getId();
        } else {
            $user_id = 0;
        }*/
        $user_id=$this->isGranted;
        
        
        $builder
         ->add('dateIn', 'datetime', array(
                    'label' => 'Date Début',
                    'widget' => 'single_text',
                    'input' => 'datetime',
                    'format' => 'yyyy-MM-dd HH:mm',
                    'widget_addon' => array(
                        'icon' => 'time',
                        'type' => 'prepend'
                        )))
       ->add('dateOut', 'datetime', array(
                    'label' => 'Date Fin',
                    'widget' => 'single_text',
                    'input' => 'datetime',
                    'format' => 'yyyy-MM-dd HH:mm',
                    'widget_addon' => array(
                        'icon' => 'time',
                        'type' => 'prepend'
                        ))) 
                         ;    
          
           // ->add('preteur')
          // ->add('produit')
              /* 
               * $query = $this->createQueryBuilder('a')
                ->add('orderBy', 'a.id DESC')
                ->where('a.proprietaire = :proprietaire')
                ->leftJoin('a.proprietaire', 'b')
                ->leftJoin('a.categorie', 'c')
                ->leftJoin('a.idStatus', 'd')
                 ->setParameter('proprietaire', $user_id)
                ->getQuery();

        return $query;
               */ 
                
            $builder->add('produit', 'entity', array(
            //'class' => 'Application\EservicesBundle\Entity\CertificatsProjet',
            'class' => 'ApplicationEservicesBundle:Eproduit',
            'query_builder' => function(EntityRepository $em) use ($user_id) {
            return $em->createQueryBuilder('u')
                         ->leftJoin('u.produit', 'a')
                       //  ->leftJoin('a.proprietaire', 'v')
                ->where('a.id=:userid')
                ->setParameter('cp', $user_id)
                ->orderBy('u.name', 'ASC');
            },
            'property' => 'name',
            'multiple' => false,
            'required' => true,
            'label' => 'Produits',
           'empty_value' => '--- Choisir une option ---'
        ))
                    
                    
       
            ->add('emprunteur')
        ;
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Application\EservicesBundle\Entity\EproduitHistory'
        ));
    }

    public function getName()
    {
        return 'eproduit_history_type';
    }
}
