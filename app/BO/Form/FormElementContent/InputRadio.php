<?php

namespace app\BO\Form\FormElementContent;

class InputRadio extends FormElementContent
{

    private $references;

    public function toHtml()
    {

    }   

    public function userInput($content){
        
    }

        /**
     * Get the value of references
     */ 
    public function getReferences()
    {
        return $this->references;
    }

    /**
     * @return self
     */
    public function addReference(FormElementContent $element){
        $this->references[] = $element;

        return $this;
    }

    /**
     * Set the value of references
     *
     * @return  self
     */ 
    public function setReferences(array $references)
    {
        foreach ($references as $element) {
            $this->addReference($element);
        }

        return $this;
    }
}