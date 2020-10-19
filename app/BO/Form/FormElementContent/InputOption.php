<?php

namespace app\BO\Form\FormElementContent;

class InputOption extends FormElementContent 
{
    public function toHtml(){
        $value = $this->getHtml_value();
        if (isset($value) && $value == $this->getHtml_name()) {
            $selected = ' selected ';
        } else {
            $selected = ' ';
        }
        echo '<option'.$this->classList().$selected.'value="'.$this->getHtml_name().'">'.$this->getHtml_name().'</option>';
    }

    public function userInput($content){
        
    }
}