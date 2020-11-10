<?php


namespace renderers;


use app\BO\Meal;

class MealsRenderer extends BaseRenderer
{

    private $current_meal;
    private $day;

    public function __construct() {
        parent::__construct();
        $this->from = 'meals';
    }

    public function dropdown($meals, $day) {
        $this->output .= '<form id="meal-form" action="?page=meals" method="POST"><div style="padding: 10px;">                
        <span class="meal-date">'.date("d/m/Y", strtotime($day)).'</span><select onchange="update_current_meal()" class="form-control" name="m_type_id">';
        foreach ($meals as $id => $name) {
            $selected = $this->current_meal === $id ? ' selected ' : '';
            $this->output .= '<option value="'.$id.'"'.$selected.'>'.$name.'</option>';
        }
        $this->output .= '</select>
        </div>
        <input type="hidden" name="current-meal" id="current-meal" value="'.$this->current_meal.'">
        <input type="hidden" name="date" value="'.$this->day.'">
        </form><br><br>';
        return $this;
    }

    public function checks_navigation() {
        $this->output .= '<nav class="navbar bg-light" id="nav-checks" style="bottom: 15vh">
            <ul class="navbar-nav">
              <li class="nav-item">
                <button type="button" id="team-btn" class="nav-link fnt_aw-btn nav-btn btn-active" onclick="post_current(); load_form(\'team\', \'meals\')">Equipe</button>
              </li>
              <li class="nav-item">
                <button type="button" id="team_equipment-btn" class="nav-link fnt_aw-btn nav-btn" onclick="post_current(); load_form(\'team_equipment\', \'meals\')">EPI</button>
              </li>
              <li class="nav-item">
                <button type="button" id="equipment-btn" class="nav-link fnt_aw-btn nav-btn" onclick="post_current(); load_form(\'equipment\', \'meals\')">Matériel</button>
              </li>
              <li class="nav-item">
                <button type="button" id="cutlery-btn" class="nav-link fnt_aw-btn nav-btn" onclick="post_current(); load_form(\'cutlery\', \'meals\')">Petit matériel</button>
              </li>
              <li class="nav-item">
                <button type="button" id="products-btn" class="nav-link fnt_aw-btn nav-btn" onclick="post_current(); load_form(\'products\', \'meals\')">Marchandise</button>
              </li>
              <li class="nav-item">
                <button type="button" id="guests-btn" class="nav-link fnt_aw-btn nav-btn" onclick="post_current(); load_form(\'guests\', \'meals\')">Convives</button>
              </li>    
              <li class="nav-item">
                <button type="button" id="comment-btn" class="nav-link fnt_aw-btn nav-btn" onclick="post_current(); load_form(\'comment\', \'meals\')">Commentaires</button>
              </li>            
            </ul>
        </nav>
        <form method="POST" action="?page=meals" id="step-form">
        <input type="hidden" name="current-meal" id="current-meal" value="'.$this->current_meal.'">
        <input type="hidden" name="date" id="current-date" value="'.$this->day.'">
        <input type="hidden" id="nav-step" value="\'team\'">
        <div id="form-container">';
        $this->opened_tags[] = 'form';
        $this->opened_tags[] = 'div';
        return $this;
    }

    public function team_form($employees) {
        $this->output .= '<h2 style="text-align: center;">Emargement</h2><br>';
        if (count($employees) === 0) {
            $this->output .= 'Pas d\'employés affectés à ce repas';
        } else {
            $this->output .= '
            <div class="">
            <table class="table table-hover">
            <th>Prénom</th>
            <th>Nom</th>
            <th>Adresse mail</th>
            <th>Présent</th>';

            foreach ($employees as $employee) {
                $this->output .= '<tr>
                <td id="firstname-'.$employee->getId().'">'.$employee->getFirstname().'</td>
                <td id="lastname-'.$employee->getId().'">'.$employee->getLastname().'</td>
                <td>'.$employee->getEmail().'</td>
                <td>
                    <input type="checkbox" checked name="'.$employee->getId().'-present" id="'.$employee->getId().'-present" onclick="show_absence_button('.$employee->getId().')">
                    <button onclick="update_user_id()" type="button" data-toggle="modal" data-target="#absences_modal" hidden id="absence-'.$employee->getId().'">Remplacer</button>
                </td>
            </tr>';
            }

            $this->output .= '</table>
            </div>';
        }
        $this->next_btn('team', 'team_equipment');
        $this->home('team');
        return $this;
    }

    public function team_equipment_form($equipments) {
        $this->output .= '<h2 style="text-align: center;">Equipement des employés</h2><br>';
        if (count($equipments) === 0) {
            $this->output .= 'Pas d\'epi renseigné';
        } else {
            $this->output .= '<div class="">
            <table class="table table-hover">
            <th>Equipement</th>
            <th>Manque</th>
            <th>En réserve</th>';
            foreach ($equipments as $equipment) {
                $class = $equipment['te_kit_part'] == 1 ? 'kit-part-target' : '';
                $input = $equipment['te_kit_part'] == 1 ? '<input type="hidden" id="kit-nmbr" value="'.$equipment['te_stock'].'">' : '<input type="hidden" id="'.$equipment['te_id'].'-stock" value="'.$equipment['te_stock'].'">';
                $this->output .= '<tr>
                <td>'.$equipment['te_name'].'</td>
                <td><input class="missing-input '.$class.'" onchange="update_stock('.$equipment['te_kit_part'].')" oninput="update_stock('.$equipment['te_kit_part'].')" id="missing-'.$equipment['te_id'].'" name="missing-'.$equipment['te_id'].'" type="number" value="0" min="0" max="'.$equipment['te_stock'].'"></td>
                <td class="'.$class.'" id="stock-'.$equipment['te_id'].'">'.$equipment['te_stock'].'</td>    
                '.$input.'                           
            </tr>';
            }
        }
        $this->next_btn('team_equipment', 'equipment');
        $this->home('team_equipment');
        return $this;
    }

    public function equipment_form($equipments) {
        $this->output .= '<h2 style="text-align: center;">Matériel</h2><br>';
        if (count($equipments) === 0) {
            $this->output .= 'Pas d\'équipement renseigné';
        } else {
            $this->output .= '<div class="">
            <table class="table table-hover">
            <th>Equipement</th>
            <th>Bon état</th>';
            foreach ($equipments as $equipment) {
                $checked = $equipment['eq_failed'] == 0 ? 'checked' : '';
                $hidden = $equipment['eq_failed'] == 0 ? 'hidden' : '';
                $this->output .= '<tr>
                    <td>
                        '.$equipment['eq_name'].'
                    </td>
                    <td>
                        <input type="checkbox" name="eq_'.$equipment['eq_id'].'_ok" '.$checked.' onclick="show_infos_button('.$equipment['eq_id'].')">
                        <button onclick="get_equipment_infos('.$equipment['eq_id'].')" type="button" data-toggle="modal" data-target="#equipment_modal" '.$hidden.' id="failure-'.$equipment['eq_id'].'">Infos</button>
                    </td>
                </tr>';
            }
        }
        $this->next_btn('equipment', 'cutlery');
        $this->home('equipment');
        return $this;
    }

    public function cutlery_form($equipments) {
        $this->output .= '<h2 style="text-align: center;">Petit matériel</h2><br>';
        if (count($equipments) === 0) {
            $this->output .= 'Pas d\'epi renseigné';
        } else {
            $this->output .= '<div class="">
            <table class="table table-hover">
            <th>Equipement</th>
            <th>Type</th>
            <th>Manque</th>
            <th>En réserve</th>';
            foreach ($equipments as $equipment) {
                $this->output .= '<tr>
                <td>'.$equipment['se_name'].'</td>
                <td>'.$equipment['se_type'].'</td>
                <td><input class="missing-input" onchange="update_stock(0)" oninput="update_stock(0)" id="missing-'.$equipment['se_id'].'" name="missing-'.$equipment['se_id'].'" type="number" value="0" min="0" max="'.$equipment['se_stock'].'"></td>
                <td id="stock-'.$equipment['se_id'].'">'.$equipment['se_stock'].'</td>    
                <input type="hidden" id="'.$equipment['se_id'].'-stock" value="'.$equipment['se_stock'].'">                        
            </tr>';
            }
        }
        $this->next_btn('cutlery','products');
        $this->home('cutlery');
        return $this;
    }

    public function products_form($products) {
        $this->output .= '<h2 style="text-align: center;">Marchandise</h2><br>';
        $this->output .= '<div class="" style="    max-width: 50vw !important;
overflow: scroll !important;">
        <table class="table table-hover" style="">
                <th>Nom/réference</th>
                <th>Fournisseur</th>
                <th>Aspect</th>
                <th>Température</th>
                <th>Renvoyé</th>
                <th>Photo</th>';
        foreach ($products as $product) {
            $sent = $product['p_sent_back'] == 1 ? 'Oui' : 'Non';
            $this->output .= '<tr>
            <td>'.$product['p_name'].'</td>
            <td>'.$product['p_provider'].'</td>                     
            <td>'.$product['p_aspect'].'</td>                     
            <td>'.$product['p_temperature'].'</td>                     
            <td>'.$sent.'</td>                                        
        </tr>';
        }
        $this->output .= '<tr>
            <td><input type="text" name="p_name" required></td>
            <td><input type="text" name="p_provider"></td>                     
            <td><input type="text" name="p_aspect"></td>                     
            <td><input type="number" name="p_temperature"></td>                     
            <td><input type="checkbox" name="p_sent_back"></td>  
            <td></td>
            <td>
            <button onclick="post_form(\'products\', \'meals\'); load_form(\'products\', \'meals\')" type="button" class="btn btn-outline-success width100">
                +
            </button></td>
        </tr>';

        $this->next_btn('products','guests');
        $this->home('products');
        return $this;
    }

    public function guests_form(Meal $meal) {
        $this->output .= '<h2 style="text-align: center;">Convives</h2><br>
        <table class="table table-hover">
                            <th>Convives prévus</th>
                            <th>Convives absents</th>
                            <th>Convives servis</th>
                            <tr>    
                                <td><input type="number" min="0" name="expected" value="'.$meal->getExpectedGuests().'"></td>
                                <td><input type="number" min="0" name="absences" value="'.$meal->getAbsencesGuests().'"></td>
                                <td><input type="number" min="0" name="real" value="'.$meal->getRealGuests().'"></td>
                            </tr>';
        $this->next_btn('guests','comment');
        $this->home('guests');
        return $this;
    }

    public function comment_form($comment) {
        $this->output .= '<h2 style="text-align: center;">Commentaires</h2>';
        $this->output .= '<div class="comment-container">
        <div class="comment-title">---Emargement---</div><br>
        <div class="comment-content">'.$comment['mc_check_team_comment'].'</div>
        <div class="comment-title">---EPI---</div><br>
        <div class="comment-content">'.$comment['mc_check_team_equipment_comment'].'</div>
        <div class="comment-title">---Matériel---</div><br>
        <div class="comment-content">'.$comment['mc_check_equipment_comment'].'</div>
        <div class="comment-title">---Petit matériel---</div><br>
        <div class="comment-content">'.$comment['mc_check_cutlery_comment'].'</div>   
        <div class="comment-title">---Marchandise---</div><br>
        <div class="comment-content">'.$comment['mc_check_products_comment'].'</div>  
        <div class="comment-title">---Convives---</div><br>
        <div class="comment-content">'.$comment['mc_check_guests_comment'].'</div>                   
        </div>';
        $this->home('comment', false);
        return $this;
    }

    public function home($valid, $comment_button = true) {
        if ($comment_button === true) {
            $this->output .= '<button style="    position: absolute;
            bottom: 3vh;
            right: 5vw !important;" type="button" title="Commentaire" class="fnt_aw-btn comment-btn fa-2x" data-toggle="modal" data-target="#comment_modal" onclick="init_comment_modal()"><i class="far fa-comment-alt"></i></button>';
        }
        $this->output .='<div class="row justify-content-center">
                <button onclick="post_form(\''.$valid.'\', \'meals\')" type="button" class="btn btn-outline-success width100 home-btn">
                <a href="?page=home">Terminer</a>
                </button>
                </div>';

        return $this;
    }

    public function comment_modal($meal_id) {
        $this->output .= '<div class="modal fade" aria-labelledby="manualModalLabel" id="comment_modal" style="margin-bottom: 1rem"  tabindex="-1" role="dialog" aria-hidden="true">
            <div  class="modal-dialog modal-lg" role="document" id="formManual">
                <div class="modal-content" style="margin-top: 33%">
                    <div class="modal-header">
                        <h5 class="modal-title" id="manualModalLabel">Commentaire</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">  
                        <input type="hidden" id="check-step" value="team">             
                        <input type="hidden" id="meal_id" value="'.$meal_id.'">             
                        <textarea class="form-control" rows="5" id="comment-content"></textarea>     
                        <div class="row justify-content-center">
                        <button type="button" onclick="save_comment()" class="btn btn-outline-success width100">Enregistrer</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>';
        return $this;
    }

    public function absences_modal($employees, $day) {
        $this->output .= '<div class="modal fade" aria-labelledby="ModalLabel" id="absences_modal" style="margin-bottom: 1rem"  tabindex="-1" role="dialog" aria-hidden="true">
            <div  class="modal-dialog modal-lg" role="document" id="formManual">
                <div class="modal-content" style="margin-top: 33%">
                    <div class="modal-header">
                        <h5 class="modal-title" id="ModalLabel">Remplacements</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>            
                    <div class="modal-body">
                    <form action="?page=absences" method="POST" id="absences-form">     
                    <input type="hidden" name="ab_user_id" id="ab_user_id">   
                    <input type="hidden" name="ab_mealtype_id" id="ab_mealtype_id" value="'.$this->current_meal.'">  
                    <input type="hidden" name="ab_date" id="ab_date" value="'.$day.'"> 
                    <textarea class="form-control" rows="5" name="ab_comment" placeholder="Commentaire..."></textarea>     
                        <select name="ab_substitute_id">';
        if (count($employees) === 0) {
            $this->output .= '<option selected disabled>Aucun employé disponible</option>';
            $disabled = ' disabled ';
        } else {
            $disabled = '';
            $this->output .= '<option selected disabled>--- Remplacer par un employé du restaurant ---</option>';
            foreach ($employees as $employee) {
                $this->output .= '<option value="'.$employee->getId().'">'.$employee->getFirstname().' '.$employee->getLastname().'</option>';
            }
        }
        $this->output .= '</select>  
                <button type="button" onclick="valid_form(); submit_absences_form()" class="btn btn-outline-success width100"'.$disabled.'>
                    Remplacer
                </button>
                <div class="team-ctnr">
                    <h3 style="text-align: center;">Ajouter un externe</h3>
                    <div class="form-group">
                        <input type="text" class="form-control" id="u_firstname" name="u_firstname" required placeholder="Prénom">
                    </div>
                    <div class="form-group">
                        <input type="text" class="form-control" id="u_lastname" name="u_lastname" required placeholder="Nom">
                    </div>
                    <div class="form-group">
                        <input type="email" class="form-control" id="u_email" name="u_email" required placeholder="Adresse email">
                    </div>
                    <div class="row justify-content-center">
                    <button type="button" onclick="submit_absences_form()" class="btn btn-outline-success width100">
                    Remplacer
                    </button>   
                    </div>
                </div>
                </div>
                </form>
            </div>
            </div>
        </div>';
        return $this;
    }

    public function equipment_modal() {
        $this->output .= '<div class="modal fade" aria-labelledby="manualModalLabel" id="equipment_modal" style="margin-bottom: 1rem"  tabindex="-1" role="dialog" aria-hidden="true">
            <div  class="modal-dialog modal-lg" role="document" id="formManual">
                <div class="modal-content" style="margin-top: 33%">
                    <div class="modal-header">
                        <h5 class="modal-title" id="manualModalLabel"><span id="eq_name-modal">Informations</span></h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">  
                        <div>Contact : <span id="eq_contact"></span></div>
                        <div>Instructions : <span id="eq_instructions"></span></div>
                    </div>
                </div>
            </div>
        </div>';
        return $this;
    }

    private function next_btn($valid, $load) {
        $this->output .='<div class="row justify-content-center next-btn">
        <button type="button" onclick="post_form(\''.$valid.'\', \'meals\'); load_form(\''.$load.'\', \'meals\')" class="btn btn-outline-success width100">
            Suivant
        </button>
        </div>';

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