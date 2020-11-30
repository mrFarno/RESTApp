<?php

namespace renderers\manager;

class RestaurantsRenderer extends BaseRenderer
{
    private $action = [];

    public function __construct() {
        parent::__construct();
        $this->from = 'restaurants';
    }

    public function restaurant_form($prefill, $restaurant_types, $meal_types) {
        $this->output .= '
        <div class-"rest-form-container" style="width: fit-content;">
        <h1 style="text-align: center;">'.$this->action['title'].'</h1>
                <div class="form-group">
                    <label for="r_name">Nom</label>
                    <input type="text" class="form-control" id="r_name" name="r_name" autofocus required value="'.$prefill->getName().'"> 
                </div>
                <div class="form-group">
                    <label for="r_adress_street">N° et nom de rue</label>
                    <input type="text" class="form-control" id="r_adress_street" name="r_adress_street" required value="'.$prefill->getStreet().'">
                </div>
                <div class="form-row">
                    <div class="form-group col-md-6">
                    <label for="r_adress_town">Ville</label>
                    <input type="text" class="form-control" id="r_adress_town" name="r_adress_town" required value="'.$prefill->getTown().'">
                    </div>
                    <div class="form-group col-md-4">
                    <label for="r_adress_country">Pays</label>
                    <input type="text" class="form-control" id="r_adress_country" name="r_adress_country" required value="'.$prefill->getCountry().'">
                    </div>
                    <div class="form-group col-md-2">
                    <label for="r_adress_zip">Code postal</label>
                    <input type="number" class="form-control" id="r_adress_zip" name="r_adress_zip" required value="'.$prefill->getZip().'">
                    </div>
                </div>
                <div class="form-group">
                <label for="r_type_id">Type de restaurant</label>
            <select class="form-control" id="r_type_id" name="r_type_id" required>
            <option selected disabled>--- Choisissez une option ---</option>';
            foreach ($restaurant_types as $restaurant_type) {
                $this->output .= '
                <option value="'.$restaurant_type['rt_id'].'" '.is_selected($restaurant_type['rt_id'], $prefill->getType()).'>'.$restaurant_type['rt_label'].'</option>';
            }
            $this->output .= '</select>
            </div> 
            <label>Repas</label>';
            foreach ($meal_types as $meal_type) {
                $checked = in_array($meal_type['mt_id'], $prefill->getMeals()) ? ' checked ' : '';
                $this->output .= '
                <div class="form-check">
                <input class="form-check-input" type="checkbox" id="mealtype_'.$meal_type['mt_id'].'" name="mealtype_'.$meal_type['mt_id'].'" '.$checked.'>
                <label class="form-check-label" for="mealtype_'.$meal_type['mt_id'].'">
                '.$meal_type['mt_name'].'
                </label>
                </div>';
            }
        $this->output .= '
        <div class="form-group">
            <label for="rest-pic">Photo du restaurant</label>
            <input type="file" name="rest-pic" id="rest-pic">
        </div>
        <div class="form-group">
            <label for="rest-map">Plan du restaurant</label>
            <input type="file" name="rest-map" id="rest-map">
        </div>
        <div class="row justify-content-center">
        <div class="">
            <button type="submit" class="btn btn-outline-success width100">
                    '.$this->action['btn'].'
                    </button>
                    <button type="button" class="btn btn-outline-danger width100">
                    '.$this->action['btn-del'].'
                    </button>
                </div>
            </div>
        </div>';

        return $this;
    }

    public function set_action($action) {
        $this->action = $action;

        return $this;
    }
}