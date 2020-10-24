<?php

namespace app\BO;
use User;

class Equipment 
{
    private $eq_id;
    private $eq_name;
    private $eq_fail_contact;
    private $eq_fail_instructions;

    public function __construct($data)
    {
        foreach ($data as $key => $value) {
            if (property_exists($this, $key)) {
                $this->$key = $value;
            }
        }        
    }

    /**
     * Get the value of eq_id
     */ 
    public function getId()
    {
        return $this->eq_id;
    }

    /**
     * Get the value of eq_name
     */ 
    public function getName()
    {
        return $this->eq_name;
    }

    /**
     * Set the value of eq_name
     *
     * @return  self
     */ 
    public function setName($nname)
    {
        $this->eq_name = $eq_name;

        return $this;
    }

    /**
     * Get the value of eq_fail_contact
     */ 
    public function getFail_contact()
    {
        return $this->eq_fail_contact;
    }

    /**
     * Set the value of eq_fail_contact
     *
     * @return  self
     */ 
    public function setFail_contact($fail_contact)
    {
        $this->eq_fail_contact = $eq_fail_contact;

        return $this;
    }

    /**
     * Get the value of eq_fail_instructions
     */ 
    public function getFail_instructions()
    {
        return $this->eq_fail_instructions;
    }

    /**
     * Set the value of eq_fail_instructions
     *
     * @return  self
     */ 
    public function setFail_instructions($fail_instructions)
    {
        $this->eq_fail_instructions = $eq_fail_instructions;

        return $this;
    }
}