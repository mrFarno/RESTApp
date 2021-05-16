<?php


namespace renderers\staff;


use renderers\BaseRenderer;

class DaylyRenderer extends BaseRenderer
{
    public function __construct() {
        parent::__construct();
        $this->from = 'calendar';
    }

    public function meal_choice($meal_types, $day) {
        $imgs = [
            '1' => 'breakfast',
            '2' => 'lunch',
            '3' => 'snack',
            '4' => 'dinner'
        ];
        $this->output .= '<div class="meal-choice">Veuillez choisir le repas que vous souhaitez gérer</div>
        <div style="display: flex;
        justify-content: space-around;     height: 90%;
        align-items: center;">';
        foreach ($meal_types as $id => $name) {
            $this->output .= '<a href="?page=management&meal='.$id.'&date='.$day.'">
                <img class="icon-img" id="'.$id.'-meal" src="'.$GLOBALS['domain'].'/public/style/resources/icons/'.$imgs[$id].'.png"> 
                <p style="text-align: center; color: black; font-size: large;" for="'.$id.'-meal">'.$name.'</p>
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