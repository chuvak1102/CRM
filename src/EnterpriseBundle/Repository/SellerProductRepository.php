<?php
namespace EnterpriseBundle\Repository;

use Doctrine\ORM\EntityRepository;

class SellerProductRepository extends EntityRepository {

    public function notFunctionalPieceOfShit($productName){
        return $this->getEntityManager()
            ->createQuery(
                'SELECT s.name
                 FROM EnterpriseBundle:SellerProduct s
                 WHERE MATCH (s.name)
                 AGAINST (:name)
                 LIMIT 10
                '
            )
            ->setParameter('name', $productName)
            ->getResult();
    }

    public function getAnalogs($productName){
        return $this->createQueryBuilder('s')
            ->addSelect("MATCH_AGAINST (s.name, :search 'IN NATURAL MODE') as score")
            ->add('where', 'MATCH_AGAINST (s.name, :search) > 0.5')
            ->setParameter('search', $productName)
            ->orderBy('score', 'desc')
            ->getQuery()
            ->setMaxResults(10)
            ->getResult();
    }
}