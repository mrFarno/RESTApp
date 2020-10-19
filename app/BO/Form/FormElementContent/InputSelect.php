<?php

namespace app\BO\Form\FormElementContent;

class InputSelect extends FormElementContent 
{
    private $references;

    public function toHtml()
    {
        $value = $this->getHtml_value();
        if (isset($value) && trim($value) != '') {
            $disabled = ' disabled ';
            $value = ' value="'.$this->getHtml_value().'" ';
        } else {
            $disabled = ' ';
            $value = '';
        }
        $output = '<label for="'.$this->getHtml_id().'">'.$this->getHtml_label().'</label>
                    <select'.$this->classList().$disabled.'name="'.$this->getHtml_name().'">';
        if (!(isset($value) && trim($value) != '')) {
            $output .= '<option value="" selected disabled>--Choisissez une option--</option>';
        }               
        foreach ($this->getReferences() as $element) {
            $element->toHtml();
        }
        $output .= '</select>';
        
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