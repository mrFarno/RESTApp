<?php


namespace renderers\manager;


class ProductionRenderer extends BaseRenderer
{
    private $day;

    public function __construct() {
        parent::__construct();
        $this->from = 'production';
    }

    public function production_form($recipes, $meal_types, $current_meal) {
        $this->output .= '<h2 style="text-align: center;">Production</h2><br>
            <input type="hidden" name="date" id="date-hidden" value="'.$this->day.'">
            <input type="hidden" name="current-meal" id="date-hidden" value="'.$current_meal.'">
            <div class="" >
            <table class="table table-hover" style="">
                    <th>Nom</th>
                    <th>Portions réalisées</th>
                    <th>Prochaine étape</th>
                    <th>Affectations</th>
                    <th>Suivi</th>
                    <th></th>';
        if (count($recipes) !== 0) {
            $trads = [
                'rs_end_cooking_tmp' => 'Fin de cuisson',
                'rs_refrigeration_tmp' => 'Mise en cellule',
                'rs_end_refrigeration_tmp' => 'Sortie de cellule',
                'rs_sample' => 'Échantillon',
            ];
            foreach ($recipes as $recipe) {
                foreach ($recipe as $col => $value) {
                    if($value === null || $value === '') {
                        $recipe['state'] = $trads[$col];
                        goto display;
                    }
                }
                display :
                $state = $recipe['state'] ?? 'Suivi terminé';
                $this->output .= '<tr>
                <td>'.$recipe['rs_name'].'</td>          
                <td>'.$recipe['done_parts'].'</td>          
                <td>'.$state.'</td>          
                <td><button type="button" onclick="init_affectations_modal(\''.$recipe['rs_id'].'\')" class="fnt_aw-btn" data-toggle="modal" data-target="#production_modal">
                    <i class="far fa-clipboard"></i>
                </button></td>  
                <td><button type="button" onclick="init_temperature_modal(\''.$recipe['rs_id'].'\')" class="fnt_aw-btn" data-toggle="modal" data-target="#temperature_modal">
                    <i class="fas fa-thermometer-half"></i>
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

    public function temperature_modal() {
        $this->output .= '<div class="modal fade" aria-labelledby="manualModalLabel" id="temperature_modal" style="margin-bottom: 1rem"  tabindex="-1" role="dialog" aria-hidden="true">
            <div  class="modal-dialog modal-lg" role="document" id="formManual">
                <div class="modal-content" style="margin-top: 33%">
                    <div class="modal-header">
                        <h5 class="modal-title" id="manualModalLabel"><span id="eq_name-modal">Suivi des températures</span></h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body" id="temperature-modal-content">  

                    </div>
                </div>
            </div>
        </div>';
        return $this;
    }

    public function temperature_content($recipe_sheet) {
        $this->output .= '<span>Température de fin de cuisson '.$this->responsible('ec', $recipe_sheet).' : </span>'.$recipe_sheet['rs_end_cooking_tmp'].'°C<br>
        <span>Mise en cellule '.$this->responsible('r', $recipe_sheet).' : </span>
        <ul>
            <li>Température : '.$recipe_sheet['rs_refrigeration_tmp'].'°C</li>
            <li>Heure : '.$recipe_sheet['rs_refrigeration_hour'].'</li>            
        </ul>
        <span>Sortie de cellule '.$this->responsible('er', $recipe_sheet).' : </span>
        <ul>
            <li>Température : '.$recipe_sheet['rs_end_refrigeration_tmp'].'°C</li>
            <li>Heure : '.$recipe_sheet['rs_end_refrigeration_hour'].'</li>
        </ul>
        <span>Échantillon : </span>'.$recipe_sheet['rs_sample'];
        if (is_file(__DIR__.'/../../public/uploads/samples/rs-'.$recipe_sheet['rs_id'].'.png')) {
            $this->output .= '<a href="'.$GLOBALS['domain'].'/public/uploads/samples/rs-'.$recipe_sheet['rs_id'].'.png" target="_blank">&nbsp;-></a>';
        }
        return $this;
    }

    private function responsible($col, $recipe_sheet) {
        if($recipe_sheet[$col.'_responsible'] !== '') {
            return '(par '.$recipe_sheet[$col.'_responsible'].')';
        }
        return '';
    }

    public function set_day($day) {
        $this->day = $day;
        $this->from .= '&date='.$day;

        return $this;
    }
}