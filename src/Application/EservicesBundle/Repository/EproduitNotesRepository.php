<?php

namespace Application\EservicesBundle\Repository;

use Doctrine\ORM\EntityRepository;

/**
 * NotesRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class EproduitNotesRepository extends EntityRepository {

    public function myFindAll() {
        return $this->createQueryBuilder('a')
                        ->getQuery();

        //->getResult();
    }

    public function getUserNotesForProduit($user_id,$produit_id)
    {
        $qb = $this->createQueryBuilder('c')
                   ->select('c')
                   ->where('c.user = :userId')
                
                   ->addOrderBy('c.isComment')
                   ->addOrderBy('c.created')
                   ->setParameter('produit_id', $produitId);

        if (false === is_null($approved))
            $qb->andWhere('c.approved = :approved')
               ->setParameter('approved', $approved);

        return $qb->getQuery()
                  ->getResult();
    }
   

}