<?php
namespace EnterpriseBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity
 * @ORM\Table(name="messages_hidden")
 */
class MessageHidden {

    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @ORM\Column(type="integer", length=10, nullable=true)
     */
    private $message_id;

    /**
     * @ORM\Column(type="integer", length=10, nullable=true)
     */
    protected $hiddenBy = 0;


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
     * @param integer $message
     *
     * @return MessageHidden
     */
    public function setMessage($message)
    {
        $this->message = $message;

        return $this;
    }

    /**
     * Get message
     *
     * @return integer
     */
    public function getMessage()
    {
        return $this->message;
    }

    /**
     * Set hiddenBy
     *
     * @param integer $hiddenBy
     *
     * @return MessageHidden
     */
    public function setHiddenBy($hiddenBy)
    {
        $this->hiddenBy = $hiddenBy;

        return $this;
    }

    /**
     * Get hiddenBy
     *
     * @return integer
     */
    public function getHiddenBy()
    {
        return $this->hiddenBy;
    }

    /**
     * Set messageId
     *
     * @param integer $messageId
     *
     * @return MessageHidden
     */
    public function setMessageId($messageId)
    {
        $this->message_id = $messageId;

        return $this;
    }

    /**
     * Get messageId
     *
     * @return integer
     */
    public function getMessageId()
    {
        return $this->message_id;
    }
}
