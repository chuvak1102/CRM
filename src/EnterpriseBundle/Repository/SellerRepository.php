<?php
namespace EnterpriseBundle\Repository;

use Doctrine\ORM\EntityRepository;

class SellerRepository extends EntityRepository {

    public function getSellers()
    {
        return $this->getEntityManager()
            ->createQuery(
                'SELECT s.id, s.name, ss.seller_id
                 FROM EnterpriseBundle:Seller s
                 LEFT JOIN EnterpriseBundle:SellerSettings ss
                 WITH s.id = ss.seller_id'
            )
            ->getResult();
    }
}