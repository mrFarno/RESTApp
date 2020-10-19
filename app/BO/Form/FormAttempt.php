<?php

namespace app\BO\Form;

class FormAttempt
{
    private $id;
    private $respondant;

    public function __construct($data)
    {
        foreach ($data as $key => $value) {
            $key = str_replace('fa_', '', $key);
            if (isset($this->$key)) {
                $this->$key = $value;
            }
        }        
    }

    /**
     * Get the value of id
     */ 
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set the value of id
     *
     * @return  self
     */ 
    public function setId($id)
    {
        $this->id = $id;

        return $this;
    }

    /**
     * Get the value of respondant
     */ 
    public function getRespondant()
    {
        return $this->respondant;
    }

    /**
     * Set the value of respondant
     *
     * @return  self
     */ 
    public function setRespondant($respondant)
    {
        $this->respondant = $respondant;

        return $this;
    }
}