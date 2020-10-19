<?php

namespace app\BO\Form\FormElementContent;
use app\BO\Form\FormElement;

abstract class FormElementContent 
{

    private $id;
    private $html_id;
    private $html_name;
    private $html_value;
    private $html_label;
    private $referent;
    private $classlist;

    public function __construct($data)
    {
        foreach ($data as $key => $value) {
            $key = str_replace('fec_', '', $key);
            if (isset($this->$key)) {
                $this->$key = $value;
            }
        }
    }

    // public function toHtml() {
    //     $typeinfos = explode('::', $this->getParent()->getType());
    //     switch ($typeinfos[0]) {
    //         case 'html' : 
    //             $output = '<div>'.$this->name.'</div>';
    //             break;
    //         case 'input' :
    //             $output = $this->inputHtml($typeinfos[1]);
    //             break;
    //         default :
    //             $output = '';
    //             break;
    //     }
    //     echo $output;
    // }

    // public function inputHtml($type) {
    //     $inputname = $this->attr_name;
    //     $output = '<label for="'.$inputname.'">'.$this->name.'</label>';
    //     if (isset($this->attr_value) && trim($this->attr_value) != '') {
    //         $disabled = ' disabled ';
    //         $value = ' value="'.$this->attr_value.'" ';
    //     } else {
    //         $disabled = '';
    //         $value = '';
    //     }
    //     switch ($type) {
    //         case 'select' :
    //             $output .= '<select'.$disabled.'name="'.$inputname.'">';
    //             if (!(isset($this->attr_value) && trim($this->attr_value) != '')) {
    //                 $output .= '<option value="" selected disabled>--Choisissez une option--</option>';
    //             }               
    //             foreach ($this->references as $element) {
    //                 $element->toHtml();
    //             }
    //             $output .= '</select>';
    //             break;
    //         case 'option' :
    //             if (isset($this->attr_value) && $this->attr_value == $this->name) {
    //                 $selected = ' selected ';
    //             } else {
    //                 $selected = '';
    //             }
    //             $output = '<option'.$selected.'value="'.$this->name.'">'.$this->name.'</option>';
    //             break;
    //         case 'radio' :
    //             $output = '';
    //             if (isset($this->attr_value) && $this->attr_value == $this->name) {
    //                 $selected = ' selected ';
    //             } else {
    //                 $selected = '';
    //             }
    //             foreach ($this->references as $element) {
    //                 $elementname = preg_replace('#\s+#', '_', trim(strtolower($element->getName())));
    //                 $output .= '<input type="'.$type.'"name="'.$inputname.'" id="'.$elementname.'" value="'.$elementname.'"'.$selected.$disabled.'>';
    //                 $output .= '<label for="'.$elementname.'">'.$element->getName().'</label>';
    //             }
    //             break;
    //         case 'validation' :                
    //             break;
    //         default :                
    //             $output .= '<input type="'.$type.'" name="'.$inputname.'" required'.$disabled.$value.'>';
    //             break;
    //     }
    //     return $output;
    // }


    protected function attributes(){
        $output = 'id="'.$this->getHtml_id().'" name="'.$this->getHtml_name().'" value="'.$this->getHtml_value().'"';
        echo $output;
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
     * Get the value of referent
     */ 
    public function getReferent()
    {
        return $this->referent;
    }

    /**
     * Set the value of referent
     *
     * @return  self
     */ 
    public function setReferent(FormElementContent $referent)
    {
        $this->referent = $referent;

        return $this;
    }

    public function addClass(string $class) {
        $this->classlist .= ' '.$class;

        return $this;
    }

    public function setClass(string $classlist){
        $this->classlist = $classlist;

        return $this;
    }

    protected function classList() {
        echo 'class="'.$this->classList.'"';
    }


    abstract public function toHtml();
    abstract public function userInput($content);

    /**
     * Get the value of html_id
     */ 
    public function getHtml_id()
    {
        return $this->html_id;
    }

    /**
     * Set the value of html_id
     *
     * @return  self
     */ 
    public function setHtml_id($html_id)
    {
        $this->html_id = $html_id;

        return $this;
    }

    /**
     * Get the value of html_name
     */ 
    public function getHtml_name()
    {
        return $this->html_name;
    }

    /**
     * Set the value of html_name
     *
     * @return  self
     */ 
    public function setHtml_name($html_name)
    {
        $this->html_name = $html_name;

        return $this;
    }

    /**
     * Get the value of html_value
     */ 
    public function getHtml_value()
    {
        return $this->html_value;
    }

    /**
     * Set the value of html_value
     *
     * @return  self
     */ 
    public function setHtml_value($html_value)
    {
        $this->html_value = $html_value;

        return $this;
    }

    /**
     * Get the value of html_label
     */ 
    public function getHtml_label()
    {
        return $this->html_label;
    }

    /**
     * Set the value of html_label
     *
     * @return  self
     */ 
    public function setHtml_label($html_label)
    {
        $this->html_label = $html_label;

        return $this;
    }
}