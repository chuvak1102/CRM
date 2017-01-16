<?php
namespace EnterpriseBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * @ORM\Entity
 * @ORM\Table(name="dialog_users")
 * @ORM\Entity(repositoryClass="EnterpriseBundle\Repository\DialogUsersRepository")
 */
class DialogUsers {

    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @ORM\Column(type="integer", length=10, nullable=true)
     */
    private $dialog;

    /**
     * @ORM\Column(type="integer", length=10, nullable=true)
     */
    protected $user_id;

    /**
     * @ORM\Column(type="smallint", length=1, nullable=true)
     */
    protected $hidden = false;


    /**
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set dialog
     *
     * @param integer $dialog
     *
     * @return DialogUsers
     */
    public function setDialog($dialog)
    {
        $this->dialog = $dialog;

        return $this;
    }

    /**
     * Get dialog
     *
     * @return integer
     */
    public function getDialog()
    {
        return $this->dialog;
    }

    /**
     * Set userId
     *
     * @param integer $userId
     *
     * @return DialogUsers
     */
    public function setUserId($userId)
    {
        $this->user_id = $userId;

        return $this;
    }

    /**
     * Get userId
     *
     * @return integer
     */
    public function getUserId()
    {
        return $this->user_id;
    }

    /**
     * Set hidden
     *
     * @param integer $hidden
     *
     * @return DialogUsers
     */
    public function setHidden($hidden)
    {
        $this->hidden = $hidden;

        return $this;
    }

    /**
     * Get hidden
     *
     * @return integer
     */
    public function getHidden()
    {
        return $this->hidden;
    }
}
