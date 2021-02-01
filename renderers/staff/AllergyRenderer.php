<?php


namespace renderers\staff;


class AllergyRenderer extends \renderers\BaseRenderer
{
    public function __construct() {
        parent::__construct();
        $this->from = 'allergy';
    }

    public function links($meal_type, $day) {
        $this->output .= '<div style="display: flex;
        justify-content: space-around;     height: 90%;
        align-items: center;">';
        $links = [
            'allergy_guests' => 'Convives dont P.A.I',
            'allergy_adults' => 'Adultes',
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