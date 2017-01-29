<?php
namespace EnterpriseBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity
 * @ORM\Table(name="dc_seller_product")
 * @ORM\Entity(repositoryClass="EnterpriseBundle\Repository\SellerRepository")
 */
class SellerProduct {

    /**
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="EnterpriseBundle\Entity\Seller", inversedBy="id")
     * @ORM\JoinColumn(name="seller", referencedColumnName="id")
     */
    private $seller;

    /**
     * @ORM\Column(type="string", length=500, nullable=true)
     */
    private $code;

    /**
     * @ORM\Column(type="string", length=500, nullable=true)
     */
    private $catalog;
    /**
     * @ORM\Column(type="string", length=500, nullable=true)
     */
    private $catalog_code;
    /**
     * @ORM\Column(type="string", length=500, nullable=true)
     */
    private $vendor_code;
    /**
     * @ORM\Column(type="string", length=1000, nullable=true)
     */
    private $name;
    /**
     * @ORM\Column(type="string", length=10, nullable=true)
     */
    private $price;
    /**
     * @ORM\Column(type="integer", length=10, nullable=true)
     */
    private $seller_price;
    /**
     * @ORM\Column(type="integer", length=10, nullable=true)
     */
    private $custom_price;
    /**
     * @ORM\Column(type="integer", length=510, nullable=true)
     */
    private $custom_price2;
    /**
     * @ORM\Column(type="string", length=500, nullable=true)
     */
    private $type;
    /**
     * @ORM\Column(type="integer", length=10, nullable=true)
     */
    private $discount;
    /**
     * @ORM\Column(type="string", length=500, nullable=true)
     */
    private $country;
    /**
     * @ORM\Column(type="text", length=1000, nullable=true)
     */
    private $image;
    /**
     * @ORM\Column(type="text", length=5000, nullable=true)
     */
    private $description;
    /**
     * @ORM\Column(type="text", length=1000, nullable=true)
     */
    private $item_page;
    /**
     * @ORM\Column(type="string", length=10, nullable=true)
     */
    private $quantity;



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
     * Set code
     *
     * @param string $code
     *
     * @return Product
     */
    public function setCode($code)
    {
        $this->code = $code;

        return $this;
    }

    /**
     * Get code
     *
     * @return string
     */
    public function getCode()
    {
        return $this->code;
    }

    /**
     * Set catelog
     *
     * @param string $catelog
     *
     * @return Product
     */
    public function setCatalog($catalog)
    {
        $this->catalog = $catalog;

        return $this;
    }

    /**
     * Get catelog
     *
     * @return string
     */
    public function getCatalog()
    {
        return $this->catalog;
    }

    /**
     * Set catalogCode
     *
     * @param string $catalogCode
     *
     * @return Product
     */
    public function setCatalogCode($catalogCode)
    {
        $this->catalog_code = $catalogCode;

        return $this;
    }

    /**
     * Get catalogCode
     *
     * @return string
     */
    public function getCatalogCode()
    {
        return $this->catalog_code;
    }

    /**
     * Set vendorCode
     *
     * @param string $vendorCode
     *
     * @return Product
     */
    public function setVendorCode($vendorCode)
    {
        $this->vendor_code = $vendorCode;

        return $this;
    }

    /**
     * Get vendorCode
     *
     * @return string
     */
    public function getVendorCode()
    {
        return $this->vendor_code;
    }

    /**
     * Set name
     *
     * @param string $name
     *
     * @return Product
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set price
     *
     * @param string $price
     *
     * @return Product
     */
    public function setPrice($price)
    {
        $this->price = $price;

        return $this;
    }

    /**
     * Get price
     *
     * @return string
     */
    public function getPrice()
    {
        return $this->price;
    }

    /**
     * Set sellerPrice
     *
     * @param integer $sellerPrice
     *
     * @return Product
     */
    public function setSellerPrice($sellerPrice)
    {
        $this->seller_price = $sellerPrice;

        return $this;
    }

    /**
     * Get sellerPrice
     *
     * @return integer
     */
    public function getSellerPrice()
    {
        return $this->seller_price;
    }

    /**
     * Set customPrice
     *
     * @param integer $customPrice
     *
     * @return Product
     */
    public function setCustomPrice($customPrice)
    {
        $this->custom_price = $customPrice;

        return $this;
    }

    /**
     * Get customPrice
     *
     * @return integer
     */
    public function getCustomPrice()
    {
        return $this->custom_price;
    }

    /**
     * Set customPrice2
     *
     * @param integer $customPrice2
     *
     * @return Product
     */
    public function setCustomPrice2($customPrice2)
    {
        $this->custom_price2 = $customPrice2;

        return $this;
    }

    /**
     * Get customPrice2
     *
     * @return integer
     */
    public function getCustomPrice2()
    {
        return $this->custom_price2;
    }

    /**
     * Set type
     *
     * @param string $type
     *
     * @return Product
     */
    public function setType($type)
    {
        $this->type = $type;

        return $this;
    }

    /**
     * Get type
     *
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * Set discount
     *
     * @param integer $discount
     *
     * @return Product
     */
    public function setDiscount($discount)
    {
        $this->discount = $discount;

        return $this;
    }

    /**
     * Get discount
     *
     * @return integer
     */
    public function getDiscount()
    {
        return $this->discount;
    }

    /**
     * Set country
     *
     * @param string $country
     *
     * @return Product
     */
    public function setCountry($country)
    {
        $this->country = $country;

        return $this;
    }

    /**
     * Get country
     *
     * @return string
     */
    public function getCountry()
    {
        return $this->country;
    }

    /**
     * Set image
     *
     * @param string $image
     *
     * @return Product
     */
    public function setImage($image)
    {
        $this->image = $image;

        return $this;
    }

    /**
     * Get image
     *
     * @return string
     */
    public function getImage()
    {
        return $this->image;
    }

    /**
     * Set description
     *
     * @param string $description
     *
     * @return Product
     */
    public function setDescription($description)
    {
        $this->description = $description;

        return $this;
    }

    /**
     * Get description
     *
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Set itemPage
     *
     * @param string $itemPage
     *
     * @return Product
     */
    public function setItemPage($itemPage)
    {
        $this->item_page = $itemPage;

        return $this;
    }

    /**
     * Get itemPage
     *
     * @return string
     */
    public function getItemPage()
    {
        return $this->item_page;
    }

    /**
     * Set quantity
     *
     * @param string $quantity
     *
     * @return Product
     */
    public function setQuantity($quantity)
    {
        $this->quantity = $quantity;

        return $this;
    }

    /**
     * Get quantity
     *
     * @return string
     */
    public function getQuantity()
    {
        return $this->quantity;
    }

    /**
     * Set seller
     *
     * @param \EnterpriseBundle\Entity\Seller $seller
     *
     * @return Product
     */
    public function setSeller(\EnterpriseBundle\Entity\Seller $seller = null)
    {
        $this->seller = $seller;

        return $this;
    }

    /**
     * Get seller
     *
     * @return \EnterpriseBundle\Entity\Seller
     */
    public function getSeller()
    {
        return $this->seller;
    }
}
