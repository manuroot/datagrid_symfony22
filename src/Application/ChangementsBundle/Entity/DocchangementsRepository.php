<?php

namespace Application\ChangementsBundle\Entity;

use Doctrine\ORM\EntityRepository;

/**
 * NotesRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class DocchangementsRepository extends EntityRepository {

    public function myFindoldAll() {
        return $this->createQueryBuilder('a')
                        ->getQuery();

        //->getResult();
    }
 public function myFindAll() {
        return $this->createQueryBuilder('a')
                        ->leftJoin('a.idchangement', 'b')
                     
                        //   ->leftJoin('a.demandeur', 'c')
                        ->getQuery();
    }
  

}