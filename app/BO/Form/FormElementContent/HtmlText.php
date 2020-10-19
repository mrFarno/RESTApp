<?php

namespace app\BO\Form\FormElementContent;

class HtmlText extends FormElementContent 
{
    public function toHtml(){
        
    }

    public function userInput($content){
        return false;
    }
}