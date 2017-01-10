<?php
namespace EnterpriseBundle\Entity;

use FOS\UserBundle\Model\User as BaseUser;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="users")
 */
class Users extends BaseUser
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    protected $fullname;

    /**
     * @ORM\Column(type="integer", length=10, nullable=true)
     */
    protected $last_dialog;

    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Set fullname
     *
     * @param string $fullname
     * @return Users
     */
    public function setFullname($fullname)
    {
        $this->fullname = $fullname;

        return $this;
    }

    /**
     * Get fullname
     *
     * @return string 
     */
    public function getFullname()
    {
        return $this->fullname;
    }



    /**
     * Set last_dialog
     *
     * @param string $lastDialog
     * @return Users
     */
    public function setLastDialog($lastDialog)
    {
        $this->last_dialog = $lastDialog;

        return $this;
    }

    /**
     * Get last_dialog
     *
     * @return string 
     */
    public function getLastDialog()
    {
        return $this->last_dialog;
    }
}
