<?php


namespace renderers\manager;


class EquipmentRenderer extends BaseRenderer
{
    public function __construct() {
        parent::__construct();
        $this->from = 'equipment';
    }

    public function inventory_navigation() {
        $this->output .= '<h1>Inventaire</h1>
            <nav class="navbar navbar-expand-lg navbar-light bg-light nav-inventory" style="background-color: white">
            <ul class="navbar-nav">
              <li class="nav-item" style="background-color: white;">
                <button type="button" id="team_equipment-btn" class="nav-link fnt_aw-btn btn-active inventory-btn" onclick="load_form(\'team_equipment\', \'equipment\')">Equipement des employés</button>
              </li>
              <li class="nav-item" style="background-color: white;">
                <button type="button" id="equipment-btn" class="nav-link fnt_aw-btn inventory-btn" onclick="load_form(\'equipment\', \'equipment\')">Matériel</button>
              </li>
              <li class="nav-item" style="background-color: white;">
                <button type="button" id="cutlery-btn" class="nav-link fnt_aw-btn inventory-btn" onclick="load_form(\'cutlery\', \'equipment\')">Petit matériel</button>
              </li>         
            </ul>
        </nav>
        <form method="POST" action="?page=equipment" id="step-form">
        <div id="form-container">';
        $this->opened_tags[] = 'form';
        $this->opened_tags[] = 'div';

        return $this;
    }

    public function map_modal($r_id) {
        $this->output .= '<div class="modal fade" aria-labelledby="manualModalLabel" id="map_modal" style="margin-bottom: 1rem"  tabindex="-1" role="dialog" aria-hidden="true">
            <div  class="modal-dialog modal-lg" role="document" id="formManual">
                <div class="modal-content" style="margin-top: 33%">
                    <div class="modal-header">
                        <h5 class="modal-title" id="manualModalLabel">Plan du restaurant</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">  
                        <img class="map-pic" src="'.$GLOBALS['domain'].'/public/uploads/restaurants/maps/rest-'.$r_id.'.png">
                    </div>
                </div>
            </div>
        </div>';

        return $this;
    }

    public function team_equipment_form($equipments) {
        $this->output .= '<div class="" >
            <table class="table table-hover" style="">
                    <th>Nom</th>
                    <th>Stock</th>
                    <th>Compris dans kit de rechange</th>
                    <th></th>';
        if (count($equipments) !== 0) {
            foreach ($equipments as $equipment) {
                $checked = $equipment['te_kit_part'] == 1 ? 'Oui' : 'Non';
                $this->output .= '<tr>
                <td>'.$equipment['te_name'].'</td>
                <td><input id="'.$equipment['te_id'].'-stock" type="number" value="'.$equipment['te_stock'].'">
                    <button type="button" class="btn btn-outline-success width100" onclick="update_eq_stock(\''.$equipment['te_id'].'\', \'team_equipment\'); load_form(\'team_equipment\', \'equipment\')">
                        <i class="fas fa-edit"></i>
                    </button>                    
                </td>                     
                <td>'.$checked.'</td>    
                <td>
                    <button type="button" onclick="delete_eq(\''.$equipment['te_id'].'\', \'team_equipment\'); load_form(\'team_equipment\', \'equipment\')" name="delete" class="fnt_aw-btn delete-btn">
                        <i class="fas fa-trash-alt"></i>
                    </button>
                </td>                                                       
            </tr>';
            }
        }
        $this->output .= '<tr>
            <td><input type="text" name="te_name" required></td>
            <td><input type="number" name="te_stock" min="0" required></td>                                                         
            <td><input type="checkbox" name="te_kit_part"></td>  
            <td>
                <button onclick="post_form(\'team_equipment\', \'equipment\'); load_form(\'team_equipment\', \'equipment\')" type="button" class="btn btn-outline-success width100">
                    +
                </button>
            </td>
        </tr>';
        return $this;
    }

    public function equipment_form($equipments) {
        if (is_file(__DIR__.'/../../public/uploads/restaurants/maps/rest-'.$_SESSION['current-rest'].'.png')) {
            $this->output .= '<button type="button" class="fnt_aw-btn" data-toggle="modal" data-target="#map_modal">
                        <i class="fas fa-map"></i>
                    </button>';
        }
        $this->output .= '<div class="" >
            <table class="table table-hover" style="">
                    <th>Nom</th>
                    <th>Repère</th>
                    <th>Contact en cas de panne</th>
                    <th>Instructions en cas de panne</th>
                    <th>Instructions de nettoyage</th>
                    <th>En panne</th>
                    <th></th>';
        if (count($equipments) !== 0) {
            foreach ($equipments as $equipment) {
                $checked = $equipment['eq_failed'] == 1 ? ' checked ' : '';
                $link = is_file(__DIR__.'/../../public/uploads/equipments/failed-'.$equipment['eq_id'].'.png') ? '<a id="link-failed-'.$equipment['eq_id'].'" target="_blank" href="'.$GLOBALS['domain'].'/public/uploads/equipments/failed-'.$equipment['eq_id'].'.png'.'">-></a>' : '';
                $this->output .= '<tr>
                <td>'.$equipment['eq_name'].'</td>
                <td>'.$equipment['eq_mark'].'</td>
                <td>'.$equipment['eq_fail_contact'].'</td>                     
                <td>'.$equipment['eq_fail_instructions'].'</td>                     
                <td>'.$equipment['eq_cleaning_instructions'].'</td>                     
                <td>
                    <input value="'.$equipment['eq_id'].'" type="checkbox"'.$checked.' onclick="update_failed_eq(\''.$equipment['eq_id'].'\')">
                    '.$link.'
                </td> 
                <td>
                    <button type="button" onclick="delete_eq(\''.$equipment['eq_id'].'\', \'equipment\'); load_form(\'equipment\', \'equipment\') " name="delete" class="fnt_aw-btn delete-btn">
                        <i class="fas fa-trash-alt"></i>
                    </button>
                </td>                                                          
            </tr>';
            }
        }
        $this->output .= '<tr>
            <td><input type="text" name="eq_name" required placeholder="Ex : Four, frigo"></td>
            <td><input type="text" name="eq_mark" required></td>
            <td><input type="text" name="eq_fail_contact" required></td>
            <td><textarea name="eq_fail_instructions"></textarea></td>                                                     
            <td><textarea name="eq_cleaning_instructions"></textarea></td>                                                     
            <td></td>  
            <td>
                <button onclick="post_form(\'equipment\', \'equipment\'); load_form(\'equipment\', \'equipment\')" type="button" class="btn btn-outline-success width100">
                    +
                </button>
            </td>
        </tr>';
        return $this;
    }

    public function cutlery_form($equipments) {
        $this->output .= '<div class="" >
            <table class="table table-hover" style="">
                    <th>Nom</th>
                    <th>Type</th>
                    <th>En stock</th>
                    <th></th>';
        if (count($equipments) !== 0) {
            foreach ($equipments as $equipment) {
                $this->output .= '<tr>
                <td>'.$equipment['se_name'].'</td>
                <td>'.$equipment['se_type'].'</td>                     
                <td><input type="number" id="'.$equipment['se_id'].'-stock" value="'.$equipment['se_stock'].'">
                <button type="button" class="btn btn-outline-success width100" onclick="update_eq_stock(\''.$equipment['se_id'].'\', \'small_equipment\'); load_form(\'cutlery\', \'equipment\')">
                    <i class="fas fa-edit"></i>
                </button>
                </td>    
                <td>
                    <button type="button" onclick="delete_eq(\''.$equipment['se_id'].'\', \'small_equipment\'); load_form(\'cutlery\', \'equipment\')" name="delete" class="fnt_aw-btn delete-btn">
                        <i class="fas fa-trash-alt"></i>
                    </button>
                </td>                                                                       
            </tr>';
            }
        }
        $this->output .= '<tr>
            <td><input type="text" name="se_name" required></td>
            <td><input type="text" name="se_type" required></td>
            <td><input type="number" min="0" name="se_stock" required></td>                                                     
            <td></td>  
            <td>
                <button onclick="post_form(\'cutlery\', \'equipment\'); load_form(\'cutlery\', \'equipment\')" type="button" class="btn btn-outline-success width100">
                    +
                </button>
            </td>
        </tr>';
        return $this;
    }
}