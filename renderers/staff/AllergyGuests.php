<?php


namespace renderers\staff;


class AllergyGuests extends \renderers\BaseRenderer
{

    public function __construct() {
        parent::__construct();
        $this->from = 'allergy_guests';
    }

    public function allergies_list() {
        $this->output .= 'zrtzrtzrtzrt';

        return $this;
    }


    public function set_day($day) {
        $this->day = $day;
        $this->from .= '&date='.$day;

        return $this;
    }

    public function set_meal($meal) {
        $this->current_meal = $meal;

        return $this;
    }
}