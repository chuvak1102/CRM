<?php
namespace EnterpriseBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity
 * @ORM\Table(name="notepad")
 * @ORM\Entity(repositoryClass="EnterpriseBundle\Repository\NotepadRepository")
 */
class Notepad {

    /**
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @ORM\Column(type="integer", length=10)
     */
    private $user_id;

    /**
     * @ORM\Column(type="string", length=1000, nullable=true)
     */
    private $header;

    /**
     * @ORM\Column(type="text", length=10000, nullable=true)
     */
    private $message;

    /**
     * @ORM\Column(name="created", type="datetime", nullable=true)
     */
    private $created;

    /**
     * @ORM\Column(type="integer", length=10)
     */
    private $color;

    /**
     * @ORM\Column(type="integer", length=1)
     */
    private $hidden;


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
     * Set userId
     *
     * @param integer $userId
     *
     * @return Notepad
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
     * Set header
     *
     * @param string $header
     *
     * @return Notepad
     */
    public function setHeader($header)
    {
        $this->header = $header;

        return $this;
    }

    /**
     * Get header
     *
     * @return string
     */
    public function getHeader()
    {
        return $this->header;
    }

    /**
     * Set message
     *
     * @param string $message
     *
     * @return Notepad
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
     *
     * @return Notepad
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
     * Set color
     *
     * @param integer $color
     *
     * @return Notepad
     */
    public function setColor($color)
    {
        $this->color = $color;

        return $this;
    }

    /**
     * Get color
     *
     * @return integer
     */
    public function getColor()
    {
        return $this->color;
    }

    /**
     * Set hidden
     *
     * @param integer $hidden
     *
     * @return Notepad
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
