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
//        $this->output .= '<h1>Convives prévus aujourd\'hui :</h1>
//        <input type="number" min="0" class="form-control" name="declared" value="'.$meal->getExpectedGuests().'">
//        <input type="hidden" name="meal_id" value="'.$meal->getId().'">
//        <button class="btn-success btn" type="submit">Enregistrer</button>';
        $this->output .= '<h2 style="text-align: center;">Convives</h2><br>
        <table class="table table-hover">
                            <th>Convives prévus</th>
                            <th>Convives déclarés</th>
                            <th>Convives servis</th>
                            <tr>    
                                <td><input type="number" disabled min="0" name="expected" value="'.$meal->getExpectedGuests().'"></td>
                                <td><input type="number" disabled min="0" name="absences" value="'.$meal->getAbsencesGuests().'"></td>
                                <td><input type="number" min="0" name="real" value="'.$meal->getRealGuests().'"></td>
                            </tr>
        </table>
        <div class="justify-content-center">
            <button class="btn-success btn" type="submit">Enregistrer</button>
        </div>';
        return $this;
    }

    public function set_day($day) {
        $this->day = $day;
        $this->from .= '&date='.$day;

        return $this;
    }
}