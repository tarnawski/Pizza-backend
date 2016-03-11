<?php

namespace PizzaBundle\Entity;

use OAuthBundle\Entity\User;

class Application
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
     * @var string
     */
    private $homepage;

    /**
     * @var boolean
     */
    private $demo;

    /**
     * @var \DateTime
     */
    private $createDate;

    /**
     * @var \Doctrine\Common\Collections\ArrayCollection
     */
    private $users;

    /**
     * @var \Doctrine\Common\Collections\ArrayCollection
     */
    private $promoCodes;

    /**
     * @var \Doctrine\Common\Collections\ArrayCollection
     */
    private $customers;

    /**
     * @var \Doctrine\Common\Collections\ArrayCollection
     */
    private $products;

    public function __construct()
    {
        $this->users = new \Doctrine\Common\Collections\ArrayCollection();
        $this->promoCodes = new \Doctrine\Common\Collections\ArrayCollection();
        $this->customers = new \Doctrine\Common\Collections\ArrayCollection();
        $this->products = new \Doctrine\Common\Collections\ArrayCollection();
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
     * @return string
     */
    public function getHomepage()
    {
        return $this->homepage;
    }

    /**
     * @param string $homepage
     */
    public function setHomepage($homepage)
    {
        $this->homepage = $homepage;
    }

    /**
     * @return \DateTime
     */
    public function getCreateDate()
    {
        return $this->createDate;
    }

    /**
     * @param \DateTime $createDate
     */
    public function setCreateDate($createDate)
    {
        $this->createDate = $createDate;
    }

    /**
     * @return boolean
     */
    public function isDemo()
    {
        return $this->demo;
    }

    /**
     * @param boolean $demo
     */
    public function setDemo($demo)
    {
        $this->demo = $demo;
    }

    /**
     * @return \Doctrine\Common\Collections\ArrayCollection
     */
    public function getUsers()
    {
        return $this->users;
    }

    /**
     * @param User $user
     * @return $this
     */
    public function addUser(User $user)
    {
        if (!$this->users->contains($user)) {
            $user->setApplication($this);
            $this->users[] = $user;
        }
        return $this;
    }

    /**
     * @param User $user
     */
    public function removeUser(User $user)
    {
        $this->users->remove($user);
    }

    /**
     * @return \Doctrine\Common\Collections\ArrayCollection
     */
    public function getPromoCodes()
    {
        return $this->promoCodes;
    }

    /**
     * @param PromoCode $promoCode
     * @return Application
     */
    public function addPromoCode($promoCode)
    {
        if (!$this->promoCodes->contains($promoCode)) {
            $promoCode->setApplication($this);
            $this->promoCodes[] = $promoCode;
        }
        return $this;
    }

    /**
     * @param PromoCode $promoCode
     */
    public function removePromoCode(PromoCode $promoCode)
    {
        $this->promoCodes->remove($promoCode);
    }

    /**
     * @return \Doctrine\Common\Collections\ArrayCollection
     */
    public function getCustomers()
    {
        return $this->customers;
    }

    /**
     * @param Customer $customer
     * @return $this
     */
    public function addCustomer(Customer $customer)
    {
        if (!$this->customers->contains($customer)) {
            $customer->setApplication($this);
            $this->customers[] = $customer;
        }
        return $this;
    }

    /**
     * @param Customer $customer
     */
    public function removeCustomer(Customer $customer)
    {
        $this->getCustomers()->remove($customer);
    }

    /**
     * @return \Doctrine\Common\Collections\ArrayCollection
     */
    public function getProducts()
    {
        return $this->products;
    }

    /**
     * @param Product $product
     * @return $this
     */
    public function addProduct(Product $product)
    {
        if (!$this->products->contains($product)) {
            $product->setApplication($this);
            $this->products[] = $product;
        }
        return $this;
    }

    /**
     * @param Product $product
     */
    public function removeProduct(Product $product)
    {
        $this->products->remove($product);
    }
}
