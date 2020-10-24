<?php

namespace app\BO;
use app\BO\User;

class Task 
{
    private $t_id;
    private $t_description;
    private $t_date;
    private $t_context_id;
    private $user;


    public function __construct($data)
    {
        foreach ($data as $key => $value) {
            if (property_exists($this, $key)) {
                $this->$key = $value;
            }
        }        
    }


    /**
     * Get the value of t_id
     */ 
    public function getId()
    {
        return $this->t_id;
    }

    /**
     * Get the value of t_description
     */ 
    public function getDescription()
    {
        return $this->t_description;
    }

    /**
     * Set the value of t_description
     *
     * @return  self
     */ 
    public function setDescription($description)
    {
        $this->t_description = $description;

        return $this;
    }

    /**
     * Get the value of t_date
     */ 
    public function getDate()
    {
        return $this->t_date;
    }

    /**
     * Set the value of t_date
     *
     * @return  self
     */ 
    public function setDate($ate)
    {
        $this->t_date = $date;

        return $this;
    }

    /**
     * Get the value of t_context_id
     */ 
    public function getContext_id()
    {
        return $this->t_context_id;
    }

    /**
     * Set the value of t_context_id
     *
     * @return  self
     */ 
    public function setContext_id($context_id)
    {
        $this->t_context_id = $context_id;

        return $this;
    }

    /**
     * Get the value of user
     */ 
    public function getUser()
    {
        return $this->user;
    }

    /**
     * Set the value of user
     *
     * @return  self
     */ 
    public function setUser(User $user)
    {
        $this->user = $user;

        return $this;
    }
}