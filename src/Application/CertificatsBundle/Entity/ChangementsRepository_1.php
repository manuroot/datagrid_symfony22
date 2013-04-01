<?php

namespace Application\CertificatsBundle\Entity;
use CalendR\Extension\Doctrine2\QueryHelper;
use Doctrine\ORM\EntityRepository;
use CalendR\Event\Provider\ProviderInterface;
/**
 * NotesRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class ChangementsRepository extends EntityRepository implements ProviderInterface{

    public function myFindaAll() {
        return $this->createQueryBuilder('a')
                ->orderBy('id')
                        ->getQuery();

        //->getResult();
    }

    public function myFindAll() {
        return $this->createQueryBuilder('a')
                        ->leftJoin('a.idProjet', 'b')
                        ->leftJoin('a.idStatus', 'd')
                        ->leftJoin('a.demandeur', 'c')
                 ->add('orderBy', 'a.id DESC')
                        //   ->leftJoin('a.demandeur', 'c')
                        ->getQuery();
    }
 public function getEventsQueryBuilder(\DateTime $begin, \DateTime $end, array $options = array())
    {
        $qb = $this->createQueryBuilder('e');

        return QueryHelper::addEventQuery($qb, 'e.dateDebut', 'e.dateFin', $begin, $end)
            ->getQuery()
            ->getResults()
        ;
    }
     public function getEvents(\DateTime $begin, \DateTime $end, array $options = array())
    {
         return $this->getEventsQueryBuilder($begin, $end,$options);
        /*
         Returns an array of events here. $options is the second argument of
         $factory->getEvents(), so you can filter your event on anything (Calendar id/slug ?)
        */
    }
}
