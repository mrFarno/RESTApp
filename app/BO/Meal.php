<?php

namespace app\BO;
use Comment;

class Meal 
{
    private $m_id;
    private $m_check_team;
    private $m_check_team_equipment;
    private $m_check_equipment;
    private $m_check_cutlery;
    private $m_check_products;
    private $m_guests;
    private $m_type_id;
    private $m_date;

    /**
     * @return mixed
     */
    public function getDate()
    {
        return $this->m_date;
    }

    /**
     * @param mixed $m_date
     */
    public function setDate($m_date)
    {
        $this->m_date = $m_date;
    }
    private $comment;

    public function __construct($data)
    {
        foreach ($data as $key => $value) {
            if (property_exists($this, $key)) {
                $this->$key = $value;
            }
        }        
    }


    /**
     * Get the value of id
     */ 
    public function getId()
    {
        return $this->m_id;
    }

    /**
     * Get the value of check_team
     */ 
    public function getCheck_team()
    {
        return $this->m_check_team;
    }

    /**
     * Set the value of check_team
     *
     * @return  self
     */ 
    public function setCheck_team($check_team)
    {
        $this->m_check_team = $check_team;

        return $this;
    }

    /**
     * Get the value of check_team_equipment
     */ 
    public function getCheck_team_equipment()
    {
        return $this->m_check_team_equipment;
    }

    /**
     * Set the value of check_team_equipment
     *
     * @return  self
     */ 
    public function setCheck_team_equipment($check_team_equipment)
    {
        $this->m_check_team_equipment = $check_team_equipment;

        return $this;
    }

    /**
     * Get the value of check_equipment
     */ 
    public function getCheck_equipment()
    {
        return $this->m_check_equipment;
    }

    /**
     * Set the value of check_equipment
     *
     * @return  self
     */ 
    public function setCheck_equipment($check_equipment)
    {
        $this->m_check_equipment = $check_equipment;

        return $this;
    }

    /**
     * Get the value of check_cutlery
     */ 
    public function getCheck_cutlery()
    {
        return $this->m_check_cutlery;
    }

    /**
     * Set the value of check_cutlery
     *
     * @return  self
     */ 
    public function setCheck_cutlery($check_cutlery)
    {
        $this->m_check_cutlery = $check_cutlery;

        return $this;
    }

    /**
     * Get the value of check_products
     */ 
    public function getCheck_products()
    {
        return $this->m_check_products;
    }

    /**
     * Set the value of check_products
     *
     * @return  self
     */ 
    public function setCheck_products($check_products)
    {
        $this->m_check_products = $check_products;

        return $this;
    }

    /**
     * Get the value of guests
     */ 
    public function getGuests()
    {
        return $this->m_guests;
    }

    /**
     * Set the value of guests
     *
     * @return  self
     */ 
    public function setGuests($guests)
    {
        $this->m_guests = $guests;

        return $this;
    }

    /**
     * Get the value of type_id
     */ 
    public function getType()
    {
        return $this->m_type_id;
    }

    /**
     * Set the value of type_id
     *
     * @return  self
     */ 
    public function setType($type_id)
    {
        $this->m_type_id = $type_id;

        return $this;
    }

    /**
     * Get the value of comment
     */ 
    public function getComment()
    {
        return $this->comment;
    }

    /**
     * Set the value of comment
     *
     * @return  self
     */ 
    public function setComment(Comment $comment)
    {
        $this->comment = $comment;

        return $this;
    }
}