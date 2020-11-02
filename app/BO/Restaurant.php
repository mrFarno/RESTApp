<?php

namespace app\BO;
use app\BO\User;

class Restaurant 
{
    private $r_id;
    private $r_name;
    private $r_adress_zip;
    private $r_adress_town;
    private $r_adress_street;
    private $r_adress_country;
    private $meals = [];
    private $r_type_id;
    private $manager;
    private $equipments;

    public function __construct($data)
    {
        foreach ($data as $key => $value) {
            if (property_exists($this, $key)) {
                $this->$key = $value;
            }
        }        
    }

    /**
     * Set the value of id
     *
     * @return  self
     */ 
    public function setId($id)
    {
        $this->r_id = $id;

        return $this;
    }

    /**
     * Get the value of id
     */ 
    public function getId()
    {
        return $this->r_id;
    }

    /**
     * Get the value of name
     */ 
    public function getName()
    {
        return $this->r_name;
    }

    /**
     * Set the value of name
     *
     * @return  self
     */ 
    public function setName($name)
    {
        $this->r_name = $name;

        return $this;
    }

    /**
     * Get the value of adress_zip
     */ 
    public function getZip()
    {
        return $this->r_adress_zip;
    }

    /**
     * Set the value of adress_zip
     *
     * @return  self
     */ 
    public function setZip($adress_zip)
    {
        $this->r_adress_zip = $adress_zip;

        return $this;
    }

    /**
     * Get the value of adress_town
     */ 
    public function getTown()
    {
        return $this->r_adress_town;
    }

    /**
     * Set the value of adress_town
     *
     * @return  self
     */ 
    public function setTown($adress_town)
    {
        $this->r_adress_town = $adress_town;

        return $this;
    }

    /**
     * Get the value of adress_street
     */ 
    public function getStreet()
    {
        return $this->r_adress_street;
    }

    /**
     * Set the value of adress_street
     *
     * @return  self
     */ 
    public function setStreet($adress_street)
    {
        $this->r_adress_street = $adress_street;

        return $this;
    }

    /**
     * Get the value of adress_country
     */ 
    public function getCountry()
    {
        return $this->r_adress_country;
    }

    /**
     * Set the value of adress_country
     *
     * @return  self
     */ 
    public function setCountry($adress_country)
    {
        $this->r_adress_country = $adress_country;

        return $this;
    }

    /**
     * Get the value of meals
     */ 
    public function getMeals()
    {
        return $this->meals;
    }

    /**
     * Set the value of meals
     *
     * @return  self
     */ 
    public function setMeals(array $meals)
    {
        $this->meals = $meals;

        return $this;
    }

    /**
     * Get the value of type_id
     */ 
    public function getType()
    {
        return $this->r_type_id;
    }

    /**
     * Set the value of type_id
     *
     * @return  self
     */ 
    public function setType($type_id)
    {
        $this->r_type_id = $type_id;

        return $this;
    }

    /**
     * Get the value of manager
     */ 
    public function getManager()
    {
        return $this->manager;
    }

    /**
     * Set the value of manager
     *
     * @return  self
     */ 
    public function setManager(User $manager)
    {
        $this->manager = $manager;

        return $this;
    }

    /**
     * Get the value of equipments
     */ 
    public function getEquipments()
    {
        return $this->equipments;
    }

    /**
     * Set the value of equipments
     *
     * @return  self
     */ 
    public function setEquipments($equipments)
    {
        $this->equipments = $equipments;

        return $this;
    }
}