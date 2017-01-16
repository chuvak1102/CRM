<?php
namespace EnterpriseBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity
 * @ORM\Table(name="messages_important")
 */
class MessageImportant {

    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @ORM\Column(type="integer", length=10, nullable=true)
     */
    private $message;

    /**
     * @ORM\Column(type="integer", length=10, nullable=true)
     */
    protected $importantFor;




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
     * @return MessageImportant
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
     * Set importantFor
     *
     * @param integer $importantFor
     *
     * @return MessageImportant
     */
    public function setImportantFor($importantFor)
    {
        $this->importantFor = $importantFor;

        return $this;
    }

    /**
     * Get importantFor
     *
     * @return integer
     */
    public function getImportantFor()
    {
        return $this->importantFor;
    }
}
