<?php

namespace Application\ChangementsBundle\Entity;

use CalendR\Extension\Doctrine2\QueryHelper;
use Doctrine\ORM\EntityRepository;
use CalendR\Event\Provider\ProviderInterface;

//use CalendR\Extension\Doctrine2\EventRepository as EventRepositoryTrait;
/**
 * NotesRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class ChangementsRepository extends EntityRepository implements ProviderInterface {

//class ChangementsRepository extends EntityRepository{
    //use EventRepositoryTrait;
    public function myFindaAll() {
        return $this->createQueryBuilder('a')
                        ->orderBy('id')
                        ->getQuery();

        //->getResult();
    }

    /* $queryBuilder = $this->createQueryBuilder('a')
      ->andWhere('a.enabled = TRUE');
      ->leftJoin('LbPlaneteBassBundle:Track', 't', Expr\Join::WITH, 't.categorie = :categorieTrack AND t.enabled = TRUE');
      ->leftJoin('LbPlaneteBassBundle:TrackAlbumReference', 't_ref', Expr\Join::WITH, 't_ref.track = t AND t_ref.album = a');
      ->where('t_ref.id IS NOT NULL');
      ->setParameter('categorieTrack', $categorieTrack);

      $queryBuilder->groupBy('a.title');
      $queryBuilder->orderBy('a.title', 'DESC'); */

  
    public function myFindAll() {
        //$fields = array('d.id', 'd.name', 'o.id');
        //->select($fields)
    //  $fields = array('a', 'b.id','b.nomprojet','c','d','f','h');
//$fields = 'partial d.{id, name}, partial o.{id}';  //if you want to get entity object

        return $this->createQueryBuilder('a')
               //  ->select($fields)
                ->select(array('a,b,c,d,f,h'))
            
                        ->leftJoin('a.idProjet', 'b') 
                        ->leftJoin('a.demandeur', 'c')
                        ->leftJoin('a.idStatus', 'd')
                     //  ->leftJoin('a.idusers', 'e')
                        ->leftJoin('a.picture', 'f')
            //    ->leftJoin('a.idEnvironnement','g')
                ->leftJoin('a.comments','h')
              ->groupby('a.nom')
                
                        ->add('orderBy', 'a.id DESC')
              ->getQuery();

        //   ->leftJoin('a.demandeur', 'c')
        //  ->getQuery();
    }

    public function getEventsQueryBuilder(\DateTime $begin, \DateTime $end, array $options = array()) {
        $qb = $this->createQueryBuilder('e');

        return QueryHelper::addEventQuery($qb, 'e.dateDebut', 'e.dateFin', $begin, $end)
                        ->getQuery()
                        ->getResult();
        ;
    }

    public function getEvents(\DateTime $begin, \DateTime $end, array $options = array()) {
        return $this->getEventsQueryBuilder($begin, $end, $options);
    }

    /* public function createQueryBuilderForGetEvent(array $options)
      {
      // do what you want with the $option array
      return $this->createQueryBuilder('evt')
      ->setMaxResults(10)
      ;
      }


      public function getBeginFieldName()
      {
      return 'evt.beginDate';
      }


      public function getEndFieldName()
      {
      return 'evt.endDate';
      } */
}

