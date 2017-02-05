<?php
namespace EnterpriseBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * @ORM\Entity
 * @ORM\Table(name="dc_seller_settings")
 */
class SellerSettings {

    /**
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @ORM\Column(type="integer", length=5)
     */
    private $seller_id;

    /**
     * @ORM\Column(type="integer", length=5, nullable=true)
     */
    private $category;

    /**
     * @ORM\Column(type="integer", length=5, nullable=true)
     */
    private $vendor_code;

    /**
     * @ORM\Column(type="integer", length=5, nullable=true)
     */
    private $name;

    /**
     * @ORM\Column(type="integer", length=5, nullable=true)
     */
    private $price;

    /**
     * @ORM\Column(type="integer", length=5, nullable=true)
     */
    private $description;

    /**
     * @ORM\Column(type="integer", length=5, nullable=true)
     */
    private $short_description;

    /**
     * @ORM\Column(type="integer", length=5, nullable=true)
     */
    private $image;

    /**
     * @ORM\Column(type="array", length=10000, nullable=true)
     */
    private $settings;



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
     * Set sellerId
     *
     * @param integer $sellerId
     *
     * @return SellerSettings
     */
    public function setSellerId($sellerId)
    {
        $this->seller_id = $sellerId;

        return $this;
    }

    /**
     * Get sellerId
     *
     * @return integer
     */
    public function getSellerId()
    {
        return $this->seller_id;
    }

    /**
     * Set category
     *
     * @param integer $category
     *
     * @return SellerSettings
     */
    public function setCategory($category)
    {
        $this->category = $category;

        return $this;
    }

    /**
     * Get category
     *
     * @return integer
     */
    public function getCategory()
    {
        return $this->category;
    }

    /**
     * Set vendorCode
     *
     * @param integer $vendorCode
     *
     * @return SellerSettings
     */
    public function setVendorCode($vendorCode)
    {
        $this->vendor_code = $vendorCode;

        return $this;
    }

    /**
     * Get vendorCode
     *
     * @return integer
     */
    public function getVendorCode()
    {
        return $this->vendor_code;
    }

    /**
     * Set name
     *
     * @param integer $name
     *
     * @return SellerSettings
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return integer
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set price
     *
     * @param integer $price
     *
     * @return SellerSettings
     */
    public function setPrice($price)
    {
        $this->price = $price;

        return $this;
    }

    /**
     * Get price
     *
     * @return integer
     */
    public function getPrice()
    {
        return $this->price;
    }

    /**
     * Set description
     *
     * @param integer $description
     *
     * @return SellerSettings
     */
    public function setDescription($description)
    {
        $this->description = $description;

        return $this;
    }

    /**
     * Get description
     *
     * @return integer
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Set shortDescription
     *
     * @param integer $shortDescription
     *
     * @return SellerSettings
     */
    public function setShortDescription($shortDescription)
    {
        $this->short_description = $shortDescription;

        return $this;
    }

    /**
     * Get shortDescription
     *
     * @return integer
     */
    public function getShortDescription()
    {
        return $this->short_description;
    }

    /**
     * Set image
     *
     * @param integer $image
     *
     * @return SellerSettings
     */
    public function setImage($image)
    {
        $this->image = $image;

        return $this;
    }

    /**
     * Get image
     *
     * @return integer
     */
    public function getImage()
    {
        return $this->image;
    }

    /**
     * Set settings
     *
     * @param array $settings
     *
     * @return SellerSettings
     */
    public function setSettings($settings)
    {
        $this->settings = $settings;

        return $this;
    }

    /**
     * Get settings
     *
     * @return array
     */
    public function getSettings()
    {
        return $this->settings;
    }
}
