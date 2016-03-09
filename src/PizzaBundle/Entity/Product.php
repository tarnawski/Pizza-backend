<?php

namespace PizzaBundle\Entity;

class Product
{
    /**
     * @var integer
     */
    private $id;

    /**
     * @var string
     */
    private $name;

    /**
     * @var string
     */
    private $description;

    /**
     * @var boolean
     */
    private $available;

    /**
     * @var \PizzaBundle\Entity\Application
     */
    private $application;

    /**
     * @var \Doctrine\Common\Collections\ArrayCollection
     */
    private $prices;

    /**
     * @var \Doctrine\Common\Collections\ArrayCollection
     */
    private $items;

    /**
     * @var Type
     */
    private $type;

    public function __construct()
    {
        $this->prices = new \Doctrine\Common\Collections\ArrayCollection();
        $this->items = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param string $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * @param string $description
     */
    public function setDescription($description)
    {
        $this->description = $description;
    }

    /**
     * @return boolean
     */
    public function isAvailable()
    {
        return $this->available;
    }

    /**
     * @param boolean $available
     */
    public function setAvailable($available)
    {
        $this->available = $available;
    }

    /**
     * @return Application
     */
    public function getApplication()
    {
        return $this->application;
    }

    /**
     * @param Application $application
     * @return $this
     */
    public function setApplication(Application $application)
    {
        $this->application = $application;

        return $this;
    }

    /**
     * @return \Doctrine\Common\Collections\ArrayCollection
     */
    public function getPrices()
    {
        return $this->prices;
    }

    /**
     * @param Price $price
     * @return $this
     */
    public function addPrice(Price $price)
    {
        if (!$this->prices->contains($price)) {
            $price->setProduct($this);
            $this->prices[] = $price;
        }
        return $this;
    }

    /**
     * @param Price $price
     */
    public function removePrice(Price $price)
    {
        $this->prices->remove($price);
    }

    /**
     * @return \Doctrine\Common\Collections\ArrayCollection
     */
    public function getItems()
    {
        return $this->items;
    }

    /**
     * @param Item $item
     * @return $this
     */
    public function addItem(Item $item)
    {
        if (!$this->items->contains($item)) {
            $item->setProduct($this);
            $this->items[] = $item;
        }
        return $this;
    }

    /**
     * @param Item $item
     */
    public function removeItem(Item $item)
    {
        $this->items->remove($item);
    }

    /**
     * @return Type
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @param Type $type
     * @return $this
     */
    public function setType(Type $type)
    {
        $this->type = $type;

        return $this;
    }
}