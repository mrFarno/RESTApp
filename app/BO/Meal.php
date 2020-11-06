<?php

namespace app\BO;
use Comment;

class Meal 
{
    private $m_id;
    private $m_restaurant_id;
    private $m_check_team;
    private $m_check_team_equipment;
    private $m_check_equipment;
    private $m_check_cutlery;
    private $m_check_products;
    private $m_expected_guests;
    private $m_absences_guests;
    private $m_real_guests;
    private $m_type_id;
    private $m_date;

    /**
     * @param mixed $m_id
     */
    public function setId($m_id)
    {
        $this->m_id = $m_id;

        return $this;
    }

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
     * @return mixed
     */
    public function getRestaurantId()
    {
        return $this->m_restaurant_id;
    }

    /**
     * @param mixed $m_restaurant_id
     */
    public function setRestaurantId($m_restaurant_id)
    {
        $this->m_restaurant_id = $m_restaurant_id;

        return $this;
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

    /**
     * @return mixed
     */
    public function getExpectedGuests()
    {
        return $this->m_expected_guests;
    }

    /**
     * @param mixed $m_expected_guests
     */
    public function setExpectedGuests($m_expected_guests)
    {
        $this->m_expected_guests = $m_expected_guests;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getAbsencesGuests()
    {
        return $this->m_absences_guests;
    }

    /**
     * @param mixed $m_absences_guests
     */
    public function setAbsencesGuests($m_absences_guests)
    {
        $this->m_absences_guests = $m_absences_guests;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getRealGuests()
    {
        return $this->m_real_guests;
    }

    /**
     * @param mixed $m_real_guests
     */
    public function setRealGuests($m_real_guests)
    {
        $this->m_real_guests = $m_real_guests;

        return $this;
    }
}