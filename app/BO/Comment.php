<?php

namespace app\BO;

class Comment 
{
    private $mc_id;
    private $mc_check_team_comment;
    private $mc_check_team_equipment_comment;
    private $mc_check_equipment_comment;
    private $mc_check_cutlery_comment;
    private $mc_check_products_comment;


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
        return $this->mc_id;
    }

    /**
     * Get the value of check_team_comment
     */ 
    public function getCheck_team_comment()
    {
        return $this->mc_check_team_comment;
    }

    /**
     * Set the value of check_team_comment
     *
     * @return  self
     */ 
    public function setCheck_team_comment($check_team_comment)
    {
        $this->mc_check_team_comment = $check_team_comment;

        return $this;
    }

    /**
     * Get the value of check_team_equipment_comment
     */ 
    public function getCheck_team_equipment_comment()
    {
        return $this->mc_check_team_equipment_comment;
    }

    /**
     * Set the value of check_team_equipment_comment
     *
     * @return  self
     */ 
    public function setCheck_team_equipment_comment($check_team_equipment_comment)
    {
        $this->mc_check_team_equipment_comment = $check_team_equipment_comment;

        return $this;
    }

    /**
     * Get the value of check_equipment_comment
     */ 
    public function getCheck_equipment_comment()
    {
        return $this->mc_check_equipment_comment;
    }

    /**
     * Set the value of check_equipment_comment
     *
     * @return  self
     */ 
    public function setCheck_equipment_comment($check_equipment_comment)
    {
        $this->mc_check_equipment_comment = $check_equipment_comment;

        return $this;
    }

    /**
     * Get the value of check_cutlery_comment
     */ 
    public function getCheck_cutlery_comment()
    {
        return $this->mc_check_cutlery_comment;
    }

    /**
     * Set the value of check_cutlery_comment
     *
     * @return  self
     */ 
    public function setCheck_cutlery_comment($check_cutlery_comment)
    {
        $this->mc_check_cutlery_comment = $check_cutlery_comment;

        return $this;
    }

    /**
     * Get the value of check_products_comment
     */ 
    public function getCheck_products_comment()
    {
        return $this->mc_check_products_comment;
    }

    /**
     * Set the value of check_products_comment
     *
     * @return  self
     */ 
    public function setCheck_products_comment($check_products_comment)
    {
        $this->mc_check_products_comment = $check_products_comment;

        return $this;
    }
}