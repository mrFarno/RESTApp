<?php

namespace app\BO\Form\FormElementContent;
use app\BO\Form\FormElement;

class InputText extends FormElementContent 
{

    public function toHtml()
    {
        echo '<label for="'.$this->getHtml_name().'">'.$this->getHtml_label().'</label>
                <input type="text" '.$this->attributes().' '.$this->classList().'>';
    }

    public function userInput($content){
        
    }


}