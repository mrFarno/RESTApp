<?php

namespace app\BO\Form;
use app\BO\Contributor;

class Form
{
    private $id;
    private $state;
    private $current;
    private $contributor;
    private $elements;
    private $attempts;

    public function __construct($data)
    {
        foreach ($data as $key => $value) {
            $key = str_replace('f_', '', $key);
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
     * Get the value of state
     */ 
    public function getState()
    {
        return $this->state;
    }

    /**
     * Set the value of state
     *
     * @return  self
     */ 
    public function setState($state)
    {
        $this->state = $state;

        return $this;
    }

    /**
     * Get the value of current
     */ 
    public function getCurrent()
    {
        return $this->current;
    }

    /**
     * Set the value of current
     *
     * @return  self
     */ 
    public function setCurrent($current)
    {
        $this->current = $current;

        return $this;
    }

    /**
     * Get the value of contributor
     * 
     * @return app\BO\Contributor
     */ 
    public function getContributor()
    {
        return $this->contributor;
    }

    /**
     * Set the value of contributor
     *
     * @return  self
     */ 
    public function setContributor(Contributor $contributor)
    {
        $this->contributor = $contributor;

        return $this;
    }

    /**
     * Get the value of elements
     */ 
    public function getElements()
    {
        return $this->elements;
    }

    public function addElement(FormElement $element){
        $this->elements[] = $element;

        return $this;
    }

    /**
     * Set the value of elements
     *
     * @return  self
     */ 
    public function setElements(array $elements)
    {
        foreach ($elements as $element) {
            $this->addElement($element);
        }

        return $this;
    }

        /**
     * Get the value of elements
     */ 
    public function getAttempts()
    {
        return $this->attempts;
    }

    public function addAttempt(FormAttempt $attempt){
        $this->attempts[] = $attempt;

        return $this;
    }

    /**
     * Set the value of elements
     *
     * @return  self
     */ 
    public function setAttempts(array $attempts)
    {
        foreach ($attempts as $attempt) {
            $this->addAttempt($attempt);
        }

        return $this;
    }
}