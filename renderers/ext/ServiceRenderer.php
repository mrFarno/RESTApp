<?php

namespace renderers\ext;

class ServiceRenderer extends \renderers\BaseRenderer
{
    private $day;

    public function __construct() {
        parent::__construct();
        $this->from = 'service';
    }

    public function service_form($meal) {
        $this->output .= '<h1>Convives annoncés aujourd\'hui :</h1>
        <input type="number" min="0" class="form-control" name="declared" value="'.$meal->getAbsencesGuests().'">
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