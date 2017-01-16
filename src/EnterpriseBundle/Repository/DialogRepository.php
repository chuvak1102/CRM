<?php
namespace EnterpriseBundle\Repository;

use Doctrine\ORM\EntityRepository;

class DialogRepository extends EntityRepository {

    public function getUsersInDialog($dialogId)
    {
        return $this->getEntityManager()
            ->createQuery(
                'SELECT d FROM EnterpriseBundle:Dialogs d
                  WHERE d.id = :id'
            )
            ->setParameter('id', $dialogId)
            ->getResult();
    }

}