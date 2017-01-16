<?php
namespace EnterpriseBundle\Entity;

use FOS\UserBundle\Model\User as BaseUser;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

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
     * @ORM\OneToMany(targetEntity="EnterpriseBundle\Entity\Dialog", mappedBy="creator")
     */
    protected $dialogs;

    /**
     * @ORM\Column(type="integer", length=10, nullable=true)
     */
    protected $last_dialog;

    public function __construct()
    {
        parent::__construct();
        $this->dialogs = new ArrayCollection();
    }



    /**
     * Set fullname
     *
     * @param string $fullname
     *
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
     * Set lastDialog
     *
     * @param integer $lastDialog
     *
     * @return Users
     */
    public function setLastDialog($lastDialog)
    {
        $this->last_dialog = $lastDialog;

        return $this;
    }

    /**
     * Get lastDialog
     *
     * @return integer
     */
    public function getLastDialog()
    {
        return $this->last_dialog;
    }

    /**
     * Add dialog
     *
     * @param \EnterpriseBundle\Entity\Dialog $dialog
     *
     * @return Users
     */
    public function addDialog(\EnterpriseBundle\Entity\Dialog $dialog)
    {
        $this->dialogs[] = $dialog;

        return $this;
    }

    /**
     * Remove dialog
     *
     * @param \EnterpriseBundle\Entity\Dialog $dialog
     */
    public function removeDialog(\EnterpriseBundle\Entity\Dialog $dialog)
    {
        $this->dialogs->removeElement($dialog);
    }

    /**
     * Get dialogs
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getDialogs()
    {
        return $this->dialogs;
    }
}
