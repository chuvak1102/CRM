<?php
namespace EnterpriseBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity
 * @ORM\Table(name="dc_catalog")
 * @ORM\Entity(repositoryClass="EnterpriseBundle\Repository\CatalogRepository")
 */
class Catalog {

    /**
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;
    
    /**
     * @ORM\ManyToOne(targetEntity="EnterpriseBundle\Entity\Category", inversedBy="id")
     * @ORM\JoinColumn(name="category", referencedColumnName="id")
     */
    private $category;

    /**
     * @ORM\Column(type="text", length=500, nullable=true)
     */
    private $category_name;

    /**
     * @ORM\Column(type="text", length=500, nullable=true)
     */
    private $vendor_code;

    /**
     * @ORM\Column(type="text", length=500, nullable=true)
     */
    private $name;

    /**
     * @ORM\Column(type="text", length=500, nullable=true)
     */
    private $alias;

    /**
     * @ORM\Column(type="text", length=500, nullable=true)
     */
    private $price;

    /**
     * @ORM\Column(type="text", length=10000, nullable=true)
     */
    private $description;

    /**
     * @ORM\Column(type="text", length=1000, nullable=true)
     */
    private $short_description;

    /**
     * @ORM\Column(type="text", length=500, nullable=true)
     */
    private $image;

    /**
     * @ORM\Column(type="array", length=10000, nullable=true)
     */
    private $advanced;




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
     * Set categoryName
     *
     * @param string $categoryName
     *
     * @return Catalog
     */
    public function setCategoryName($categoryName)
    {
        $this->category_name = $categoryName;

        return $this;
    }

    /**
     * Get categoryName
     *
     * @return string
     */
    public function getCategoryName()
    {
        return $this->category_name;
    }

    /**
     * Set vendorCode
     *
     * @param string $vendorCode
     *
     * @return Catalog
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
     * @return Catalog
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
     * Set alias
     *
     * @param string $alias
     *
     * @return Catalog
     */
    public function setAlias($alias)
    {
        $this->alias = $alias;

        return $this;
    }

    /**
     * Get alias
     *
     * @return string
     */
    public function getAlias()
    {
        return $this->alias;
    }

    /**
     * Set price
     *
     * @param string $price
     *
     * @return Catalog
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
     * Set description
     *
     * @param string $description
     *
     * @return Catalog
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
     * Set shortDescription
     *
     * @param string $shortDescription
     *
     * @return Catalog
     */
    public function setShortDescription($shortDescription)
    {
        $this->short_description = $shortDescription;

        return $this;
    }

    /**
     * Get shortDescription
     *
     * @return string
     */
    public function getShortDescription()
    {
        return $this->short_description;
    }

    /**
     * Set image
     *
     * @param string $image
     *
     * @return Catalog
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
     * Set advanced
     *
     * @param array $advanced
     *
     * @return Catalog
     */
    public function setAdvanced($advanced)
    {
        $this->advanced = $advanced;

        return $this;
    }

    /**
     * Get advanced
     *
     * @return array
     */
    public function getAdvanced()
    {
        return $this->advanced;
    }

    /**
     * Set category
     *
     * @param \EnterpriseBundle\Entity\Category $category
     *
     * @return Catalog
     */
    public function setCategory(\EnterpriseBundle\Entity\Category $category = null)
    {
        $this->category = $category;

        return $this;
    }

    /**
     * Get category
     *
     * @return \EnterpriseBundle\Entity\Category
     */
    public function getCategory()
    {
        return $this->category;
    }
}
