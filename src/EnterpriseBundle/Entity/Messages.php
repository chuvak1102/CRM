<?php
namespace EnterpriseBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity
 * @ORM\Table(name="messages")
 */
class Messages {

    /**
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="EnterpriseBundle\Entity\Dialogs", inversedBy="id")
     * @ORM\JoinColumn(name="dialog_id", referencedColumnName="id")
     */
    private $dialog_alias;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $message;

    /**
     * @ORM\Column(name="created", type="datetime", nullable=true)
     */
    private $created;

    /**
     * @ORM\Column(type="integer", length=10)
     */
    private $author_id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $author_name;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $hidden;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $important;



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
     * Set message
     *
     * @param string $message
     * @return Messages
     */
    public function setMessage($message)
    {
        $this->message = $message;

        return $this;
    }

    /**
     * Get message
     *
     * @return string 
     */
    public function getMessage()
    {
        return $this->message;
    }

    /**
     * Set created
     *
     * @param \DateTime $created
     * @return Messages
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
     * Set author_id
     *
     * @param integer $authorId
     * @return Messages
     */
    public function setAuthorId($authorId)
    {
        $this->author_id = $authorId;

        return $this;
    }

    /**
     * Get author_id
     *
     * @return integer 
     */
    public function getAuthorId()
    {
        return $this->author_id;
    }

    /**
     * Set author_name
     *
     * @param string $authorName
     * @return Messages
     */
    public function setAuthorName($authorName)
    {
        $this->author_name = $authorName;

        return $this;
    }

    /**
     * Get author_name
     *
     * @return string 
     */
    public function getAuthorName()
    {
        return $this->author_name;
    }

    /**
     * Set hidden
     *
     * @param integer $hidden
     * @return Messages
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

    /**
     * Set dialog_alias
     *
     * @param \EnterpriseBundle\Entity\Dialogs $dialogAlias
     * @return Messages
     */
    public function setDialogAlias(\EnterpriseBundle\Entity\Dialogs $dialogAlias = null)
    {
        $this->dialog_alias = $dialogAlias;

        return $this;
    }

    /**
     * Get dialog_alias
     *
     * @return \EnterpriseBundle\Entity\Dialogs 
     */
    public function getDialogAlias()
    {
        return $this->dialog_alias;
    }

    /**
     * Set important
     *
     * @param string $important
     * @return Messages
     */
    public function setImportant($important)
    {
        $this->important = $important;

        return $this;
    }

    /**
     * Get important
     *
     * @return string 
     */
    public function getImportant()
    {
        return $this->important;
    }
}