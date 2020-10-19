<?php

namespace app\BO\Form;

use app\BO\Form\FormElementContent\FormElementContent;

class FormElement
{
    private $id;
    private $title;
    private $type;
    private $order;
    private $element_contents;

    public function __construct($data)
    {
        foreach ($data as $key => $value) {
            $key = str_replace('fe_', '', $key);
            if (isset($this->$key)) {
                $this->$key = $value;
            }
        }
    }



    // public function toHtml() {
    //     $typeinfos = explode('::', $this->type);
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
    //     $inputname = preg_replace('#\s+#', '_', trim(strtolower($this->name)));
    //     $output = '<label for="'.$inputname.'">'.$this->name.'</label>';
    //     if (isset($this->value) && trim($this->value) != '') {
    //         $disabled = ' disabled ';
    //         $value = ' value="'.$this->value.'" ';
    //     } else {
    //         $disabled = '';
    //         $value = '';
    //     }
    //     switch ($type) {
    //         case 'select' :
    //             $output .= '<select'.$disabled.'name="'.$inputname.'">';
    //             if (!(isset($this->value) && trim($this->value) != '')) {
    //                 $output .= '<option value="" selected disabled>--Choisissez une option--</option>';
    //             }               
    //             foreach ($this->references as $element) {
    //                 $element->toHtml();
    //             }
    //             $output .= '</select>';
    //             break;
    //         case 'option' :
    //             if (isset($this->value) && $this->value == $this->name) {
    //                 $selected = ' selected ';
    //             } else {
    //                 $selected = '';
    //             }
    //             $output = '<option'.$selected.'value="'.$this->name.'">'.$this->name.'</option>';
    //             break;
    //         case 'radio' :
    //             $output = '';
    //             if (isset($this->value) && $this->value == $this->name) {
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
     * Get the value of title
     */ 
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * Set the value of title
     *
     * @return  self
     */ 
    public function setTitle($title)
    {
        $this->title = $title;

        return $this;
    }

    /**
     * Get the value of order
     */ 
    public function getOrder()
    {
        return $this->order;
    }

    /**
     * Set the value of order
     *
     * @return  self
     */ 
    public function setOrder($order)
    {
        $this->order = $order;

        return $this;
    }

    /**
     * Get the value of type
     */ 
    public function getType()
    {
        return $this->type;
    }

    /**
     * Set the value of type
     *
     * @return  self
     */ 
    public function setType($type)
    {
        $this->type = $type;

        return $this;
    }

    
    /**
     * Get the value of elements
     */ 
    public function getElements()
    {
        return $this->element_contents;
    }

    public function addElement(FormElementContent $element){
        $this->element_contents[] = $element;

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
}
