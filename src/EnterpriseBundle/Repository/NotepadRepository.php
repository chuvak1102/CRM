<?php
namespace EnterpriseBundle\Repository;

use Doctrine\ORM\EntityRepository;
class NotepadRepository extends EntityRepository{

    public function findBy(array $criteria, array $orderBy = ['created' => 'DESC'], $limit = null, $offset = null)
    {
        return parent::findBy($criteria, $orderBy, $limit, $offset);
    }
}