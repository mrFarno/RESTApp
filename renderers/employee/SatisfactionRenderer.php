<?php


namespace renderers\employee;


class SatisfactionRenderer extends BaseRenderer
{
    public function __construct() {
        parent::__construct();
        $this->from = 'satisfaction';
    }

    public function satisfaction() {
        $this->output .= '<img src="'.$GLOBALS['domain'].'/public/style/resources/satisfaction.png"> ';


        return $this;
    }

    private function set_day($day) {
        $this->from .= '&date='.$day;

        return $this;
    }
}