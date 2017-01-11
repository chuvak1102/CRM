<?php
namespace EnterpriseBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * @ORM\Entity
 * @ORM\Table(name="dialogs")
 * @ORM\Entity(repositoryClass="EnterpriseBundle\Repository\DialogsRepository")
 */
class Dialogs {

    /**
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\OneToMany(targetEntity="EnterpriseBundle\Entity\Messages", mappedBy="dialog_alias")
     */
    private $id;

    /**
     * @ORM\Column(type="string")
     */
    private $dialog_alias;

    /**
     * @ORM\Column(type="string", length=100)
     */
    private $dialog_name;

    /**
     * @ORM\Column(name="created", type="datetime", nullable=true)
     */
    private $created;

    /**
     * @ORM\OneToMany(targetEntity="EnterpriseBundle\Entity\Messages", mappedBy="dialog_alias")
     */
    private $messages;

    public function __construct()
    {
        $this->messages = new ArrayCollection();
    }


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
     * Set dialog_alias
     *
     * @param string $dialogAlias
     * @return Dialogs
     */
    public function setDialogAlias($dialogAlias)
    {
        $this->dialog_alias = $dialogAlias;

        return $this;
    }

    /**
     * Get dialog_alias
     *
     * @return string
     */
    public function getDialogAlias()
    {
        return $this->dialog_alias;
    }

    /**
     * Set created
     *
     * @param \DateTime $created
     * @return Dialogs
     */
    public function setCreated($created)
    {
        $this->created = $created;

        return $this;
    }

    /**
     * Get created
     *
     * @return \DateTime
     */
    public function getCreated()
    {
        return $this->created;
    }

    /**
     * Add messages
     *
     * @param \EnterpriseBundle\Entity\Messages $messages
     * @return Dialogs
     */
    public function addMessage(\EnterpriseBundle\Entity\Messages $messages)
    {
        $this->messages[] = $messages;

        return $this;
    }

    /**
     * Remove messages
     *
     * @param \EnterpriseBundle\Entity\Messages $messages
     */
    public function removeMessage(\EnterpriseBundle\Entity\Messages $messages)
    {
        $this->messages->removeElement($messages);
    }

    /**
     * Get messages
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getMessages()
    {
        return $this->messages;
    }

    /**
     * Set dialog_name
     *
     * @param string $dialogName
     * @return Dialogs
     */
    public function setDialogName($dialogName)
    {
        $this->dialog_name = $dialogName;

        return $this;
    }

    /**
     * Get dialog_name
     *
     * @return string 
     */
    public function getDialogName()
    {
        return $this->dialog_name;
    }
}
