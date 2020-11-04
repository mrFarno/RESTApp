<?php


namespace renderers;


class MealsRenderer extends BaseRenderer
{
    public function __construct() {
        parent::__construct();
        $this->from = 'meals';
    }

    public function dropdown($meals) {
        $this->output .= '<div style="padding: 10px;">
        <select class="form-control" name="m_type_id">';
        foreach ($meals as $id => $name) {
            $this->output .= '<option value="'.$id.'">'.$name.'</option>';
        }
        $this->output .= '</select>
        </div>';
        return $this;
    }

    public function checks_navigation() {
        $this->output .= '<nav class="navbar bg-light" id="nav-checks">
            <ul class="navbar-nav">
              <li class="nav-item">
                <button type="button" id="team-btn" class="nav-link fnt_aw-btn nav-btn btn-active" onclick="load_form(\'team\')">Equipe</button>
              </li>
              <li class="nav-item">
                <button type="button" id="team_equipment-btn" class="nav-link fnt_aw-btn nav-btn" onclick="load_form(\'team_equipment\')">EPI</button>
              </li>
              <li class="nav-item">
                <button type="button" id="equipment-btn" class="nav-link fnt_aw-btn nav-btn" onclick="load_form(\'equipment\')">Matériel</button>
              </li>
              <li class="nav-item">
                <button type="button" id="cutlery-btn" class="nav-link fnt_aw-btn nav-btn" onclick="load_form(\'cutlery\')">Petit matériel</button>
              </li>
              <li class="nav-item">
                <button type="button" id="products-btn" class="nav-link fnt_aw-btn nav-btn" onclick="load_form(\'products\')">Marchandise</button>
              </li>
              <li class="nav-item">
                <button type="button" id="guests-btn" class="nav-link fnt_aw-btn nav-btn" onclick="load_form(\'guests\')">Convives</button>
              </li>              
            </ul>
        </nav>
        <div id="form-container">';
        $this->opened_tags[] = 'div';
        return $this;
    }

    public function team_form($employees) {
        $this->output .= '<br><br><h2 style="text-align: center;">Emargement</h2><br>';
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
                    <input type="checkbox" checked name="'.$employee->getId().'-present">
                </td>
            </tr>';
            }

            $this->output .= '</table>
            </div>';
        }
        $this->next_btn('team_equipment');
        $this->home();
        return $this;
    }

    public function team_equipment_form($equipments) {
        $this->output .= '<br><br><h2 style="text-align: center;">Equipement des employés</h2><br>';
        if (count($equipments) === 0) {
            $this->output .= 'Pas d\'epi renseigné';
        } else {
            $this->output .= '<div class="">
            <table class="table table-hover">
            <th>Equipement</th>
            <th>Manque</th>
            <th>En réserve</th>';
            foreach ($equipments as $equipment) {
                $class = $equipment['te_kit_part'] == 1 ? ' class="kit-part-target" ' : '';
                $input = $equipment['te_kit_part'] == 1 ? '<input type="hidden" id="kit-nmbr" value="'.$equipment['te_stock'].'">' : '';
                $max = $equipment['te_kit_part'] == 1 ? $equipment['te_stock'] : '';
                $this->output .= '<tr>
                <td>'.$equipment['te_name'].'</td>
                <td><input class="missing-input" onchange="update_stock()" oninput="update_stock()" id="missing-'.$equipment['te_id'].'" type="number" value="0" min="0" max="'.$max.'"></td>
                <td'.$class.'>'.$equipment['te_stock'].'</td>    
                '.$input.'                           
            </tr>';
            }
        }
        $this->next_btn('equipment');
        $this->home();
        return $this;
    }

    public function equipment_form() {
        $this->output .= '<br><br><h2 style="text-align: center;">Matériel</h2><br>WIP';
        $this->next_btn('cutlery');
        $this->home();
        return $this;
    }

    public function cutlery_form() {
        $this->output .= '<br><br><h2 style="text-align: center;">Petit matériel</h2><br>WIP';
        $this->next_btn('products');
        $this->home();
        return $this;
    }

    public function products_form() {
        $this->output .= '<br><br><h2 style="text-align: center;">Marchandise</h2><br>WIP';
        $this->next_btn('guests');
        $this->home();
        return $this;
    }

    public function guests_form() {
        $this->output .= '<br><br><h2 style="text-align: center;">Convives</h2><br>WIP';
        $this->home();
        return $this;
    }

    public function set_day($day) {
        $this->from .= '&date='.$day;

        return $this;
    }

    public function home() {
        $this->output .='<button style="    position: absolute;
            bottom: 3vh;
            right: 5vw !important;" type="button" title="Commentaire" class="fnt_aw-btn comment-btn fa-2x" data-toggle="modal" data-target="#comment_modal"><i class="far fa-comment-alt"></i></button>
                <div class="row justify-content-center">
                <button type="button" class="btn btn-outline-success width100 home-btn">
                <a href="?page=home">Terminer</a>
                </button>
                </div>';

        return $this;
    }

    public function comment_modal() {
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
                        <textarea class="form-control" rows="5"></textarea>       
                    </div>
                    <div class="row justify-content-center">
                    <button type="button" class="btn btn-outline-success width100">Enregistrer</button>
                    </div>
                </div>
            </div>
        </div>';
        return $this;
    }

    private function next_btn($form) {
        $this->output .='<div class="row justify-content-center next-btn">
        <button type="button" onclick="load_form(\''.$form.'\')" class="btn btn-outline-success width100">
            Suivant
        </button>
        </div>';

        return $this;
    }
}