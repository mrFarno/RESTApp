<?php

namespace app\BO\Form;
use app\BO\Form\FormElementContent\FormElementContent;

class FormAttemptElement 
{
    private $form_element_content;
    private $content;

    public function display(){
        $this->form_element_content->userInput($this->content);
    }

}