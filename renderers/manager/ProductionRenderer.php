<?php


namespace renderers\manager;


class ProductionRenderer extends BaseRenderer
{
    private $day;

    public function __construct() {
        parent::__construct();
        $this->from = 'production';
    }

    public function production_form($recipes, $meal_types) {
        $this->output .= '<h2 style="text-align: center;">Production</h2><br>
            <input type="hidden" name="date" id="date-hidden" value="'.$this->day.'">
            <div class="" >
            <table class="table table-hover" style="">
                    <th>Nom</th>
                    <th>Repas</th>
                    <th>Suivi</th>
                    <th></th>';
        if (count($recipes) !== 0) {
            foreach ($recipes as $recipe) {
                $this->output .= '<tr>
                <td>'.$recipe['rs_name'].'</td>
                <td>'.$meal_types[$recipe['rs_meal_type']].'</td>             
                <td><button type="button" onclick="init_affectations_modal(\''.$recipe['rs_id'].'\')" class="fnt_aw-btn" data-toggle="modal" data-target="#production_modal">
                    <i class="far fa-clipboard"></i>
                </button></td>  
                <td>           
                    <button type="submit" onclick="return confirm(\'Etes vous sur de vouloir supprimer cette fiche technique ?\')" name="delete" value="'.$recipe['rs_id'].'" class="fnt_aw-btn delete-btn">
                        <i class="fas fa-trash-alt"></i>
                    </button>
                </td>                                                          
            </tr>';
            }
        }
        $this->output .= '<tr>
            <td><input type="text" name="rs_name"></td>
            <td>
                <select name="rs_meal_type">
                    <option selected disabled>---Sélectionner un repas---</option>';
        foreach ($meal_types as $id => $meal_type) {
            $this->output .= '<option value="'.$id.'">'.$meal_type.'</option>';
        }
        $this->output .= '</select>
            </td>                                                 
            <td></td>  
            <td></td>  
            <td>
                <button type="submit" class="btn btn-outline-success width100">
                    +
                </button>
            </td>
        </tr>';
        return $this;
    }

    public function production_modal() {
        $this->output .= '<div class="modal fade" aria-labelledby="manualModalLabel" id="production_modal" style="margin-bottom: 1rem"  tabindex="-1" role="dialog" aria-hidden="true">
            <div  class="modal-dialog modal-lg" role="document" id="formManual">
                <div class="modal-content" style="margin-top: 33%">
                    <div class="modal-header">
                        <h5 class="modal-title" id="manualModalLabel"><span id="eq_name-modal">Affectations</span></h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body" id="modal-content">  

                    </div>
                </div>
            </div>
        </div>';
        return $this;
    }

    public function set_day($day) {
        $this->day = $day;
        $this->from .= '&date='.$day;

        return $this;
    }
}