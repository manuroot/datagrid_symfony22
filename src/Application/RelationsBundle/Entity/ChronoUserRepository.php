<?php

namespace Application\RelationsBundle\Entity;

use Doctrine\ORM\EntityRepository;

/**
 * NotesRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class ChronoUserRepository extends EntityRepository {

    public function myFindAll() {
        return $this->createQueryBuilder('a')
                ->leftJoin('a.idgroup', 'b')
                        ->getQuery();

        //->getResult();
    }
public function myFindaAll() {
          return $this->createQueryBuilder('a')
                        ->leftJoin('a.project', 'b')
                        ->getQuery();
}
}
