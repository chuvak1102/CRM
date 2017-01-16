<?php
namespace EnterpriseBundle\Repository;

use Doctrine\ORM\EntityRepository;

class DialogUsersRepository extends EntityRepository {

    public function getDialogsByUser($userId)
    {
        return $this->getEntityManager()
            ->createQuery(
                'SELECT di.id, di.dialog_name, di.created, di.creator
                 FROM EnterpriseBundle:Dialog di
                 LEFT JOIN EnterpriseBundle:DialogUsers us
                 WITH di.id = us.dialog
                 WHERE us.user_id = :user_id
                 GROUP BY di.id, us.hidden'
            )
            ->setParameter('user_id', $userId)
            ->getResult();
    }

    public function getMessagesInLastDialog($lastDialogId)
    {
        return $this->getEntityManager()
            ->createQuery(
                'SELECT (m.id), (m.dialog), (m.message), (m.created), (m.author), (u.fullname), (i.importantFor), (h.hiddenBy), (m.file)
                 FROM EnterpriseBundle:Messages m
                 LEFT JOIN EnterpriseBundle:MessageHidden h
                 WITH m.id = h.message_id
                 LEFT JOIN EnterpriseBundle:MessageImportant i
                 WITH i.message = m.id
                 LEFT JOIN EnterpriseBundle:Users u
                 WITH u.last_dialog = m.dialog
                 WHERE m.dialog = :dialog
                 AND h.hiddenBy IS NULL
                 ORDER BY m.created ASC
                 '
            )
            ->setParameter('dialog', $lastDialogId)
            ->getResult();
    }

    public function getUsersInLastDialog($lastDialogId)
    {
        return $this->getEntityManager()
            ->createQuery(
                'SELECT (u.fullname), (u.id)
                 FROM EnterpriseBundle:DialogUsers d
                 LEFT JOIN EnterpriseBundle:Users u
                 WITH u.id = d.user_id
                 WHERE d.dialog = :dialog'
            )
            ->setParameter('dialog', $lastDialogId)
            ->getResult();
    }

}