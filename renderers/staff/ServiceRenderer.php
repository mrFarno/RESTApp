<?php


namespace renderers\staff;


use renderers\BaseRenderer;

class ServiceRenderer extends BaseRenderer
{
    public function __construct() {
        parent::__construct();
        $this->from = 'service';
    }

    public function links($meal_type, $day) {
        $this->output .= '<div style="display: flex;
        justify-content: space-around;     height: 90%;
        align-items: center;">';
        $links = [
            'food' => 'Plats servis',
            'allergy' => 'P.A.I',
            'satisfaction' => 'Satisfaction des convives',
        ];
        foreach ($links as $link => $name) {
            $this->output .= '<a href="?page='.$link.'&meal='.$meal_type.'&date='.$day.'">
                <img class="icon-img" id="'.$link.'-link" src="'.$GLOBALS['domain'].'/public/style/resources/icons/'.$link.'.png"> 
                <p style="text-align: center; color: black; font-size: large;" for="'.$link.'-link">'.$name.'</p>
            </a>';
        }
        $this->output .= '</div>';

        return $this;
    }

    private function set_day($day) {
        $this->from .= '&date='.$day;

        return $this;
    }
}