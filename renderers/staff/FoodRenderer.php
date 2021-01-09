<?php


namespace renderers\staff;


class FoodRenderer extends \renderers\BaseRenderer
{
    private $day;

    public function __construct() {
        parent::__construct();
        $this->from = 'food';
    }

    public function service_form($meal) {
        $this->output .= '<h1>Convives prévus aujourd\'hui :</h1>
        <input type="number" min="0" class="form-control" name="declared" value="'.$meal->getExpectedGuests().'">
        <input type="hidden" name="meal_id" value="'.$meal->getId().'">
        <button class="btn-success btn" type="submit">Enregistrer</button>';
        return $this;
    }

    public function set_day($day) {
        $this->day = $day;
        $this->from .= '&date='.$day;

        return $this;
    }
}