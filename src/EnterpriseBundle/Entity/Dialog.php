<?php
namespace EnterpriseBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * @ORM\Entity
 * @ORM\Table(name="dialog")
 * @ORM\Entity(repositoryClass="EnterpriseBundle\Repository\DialogRepository")
 */
class Dialog {

    /**
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=100)
     */
    private $dialog_name;

    /**
     * @ORM\Column(name="created", type="datetime", nullable=true)
     */
    private $created;

    /**
     * @ORM\Column(type="integer", length=10, nullable=true)
     */
    private $creator;

    /**
     * @ORM\OneToMany(targetEntity="EnterpriseBundle\Entity\Messages", mappedBy="dialog")
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
     * Set dialogName
     *
     * @param string $dialogName
     *
     * @return Dialog
     */
    public function setDialogName($dialogName)
    {
        $this->dialog_name = $dialogName;

        return $this;
    }

    /**
     * Get dialogName
     *
     * @return string
     */
    public function getDialogName()
    {
        return $this->dialog_name;
    }

    /**
     * Set created
     *
     * @param \DateTime $created
     *
     * @return Dialog
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
     * Set creator
     *
     * @param integer $creator
     *
     * @return Dialog
     */
    public function setCreator($creator)
    {
        $this->creator = $creator;

        return $this;
    }

    /**
     * Get creator
     *
     * @return integer
     */
    public function getCreator()
    {
        return $this->creator;
    }

    /**
     * Add message
     *
     * @param \EnterpriseBundle\Entity\Messages $message
     *
     * @return Dialog
     */
    public function addMessage(\EnterpriseBundle\Entity\Messages $message)
    {
        $this->messages[] = $message;

        return $this;
    }

    /**
     * Remove message
     *
     * @param \EnterpriseBundle\Entity\Messages $message
     */
    public function removeMessage(\EnterpriseBundle\Entity\Messages $message)
    {
        $this->messages->removeElement($message);
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
}
