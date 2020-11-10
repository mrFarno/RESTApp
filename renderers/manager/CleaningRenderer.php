<?php


namespace renderers\manager;


class CleaningRenderer extends BaseRenderer
{
    private $day;

    public function __construct() {
        parent::__construct();
        $this->from = 'cleaning';
    }

    public function list_equipments($equipments) {
        $this->output .= '<h1>Nettoyage et Désinfection</h1>
            <div class="equipment-list">
            <h2>Matériel</h2>
            <table class="table table-hover" style="">
                    <th>Nom</th>
                    <th>Nettoyer</th>';
        if (count($equipments) !== 0) {
            foreach ($equipments as $equipment) {
                $this->output .= '<tr>
                <td>'.$equipment['eq_name'].'</td>                 
                <td>
                    <button onclick="init_cleaning_modal(\''.$equipment['eq_id'].'\')" type="button" class="fnt_aw-btn" data-toggle="modal" data-target="#cleaning_modal">
                        <i class="fas fa-trash-alt"></i>
                    </button>
                </td>                                                          
            </tr>';
            }
        } else {
            $this->output .= 'Pas de matériel renseigné';
        }
        $this->output .= '</table>
        </div>';
        return $this;
    }

    public function list_spaces($spaces) {
        $this->output .= '<div class="space-list">
            <h2>Locaux</h2>
            <table class="table table-hover" style="">
                    <th>Nom</th>
                    <th>Nettoyer</th>';
        if (count($spaces) !== 0) {
            foreach ($spaces as $space) {
                $this->output .= '<tr>
                <td>'.$space['s_name'].'</td>                 
                <td>
                    <button type="button" onclick="init_cleaning_modal(\''.$space['s_id'].'\')" class="fnt_aw-btn" data-toggle="modal" data-target="#cleaning_modal">
                        <i class="fas fa-trash-alt"></i>
                    </button>
                </td>                                                          
            </tr>';
            }
        } else {
            $this->output .= 'Pas de locaux renseigné';
        }
        $this->output .= '</table>
        </div>';
        return $this;
    }

    public function cleaning_modal() {
        $this->output .= '<div class="modal fade" aria-labelledby="manualModalLabel" id="cleaning_modal" style="margin-bottom: 1rem"  tabindex="-1" role="dialog" aria-hidden="true">
            <div  class="modal-dialog modal-lg" role="document" id="formManual">
                <div class="modal-content" style="margin-top: 33%">
                    <div class="modal-header">
                        <h5 class="modal-title" id="manualModalLabel"><span id="eq_name-modal">Affectations</span></h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">  
                        <input type="hidden" name="t_target_id" id="t_target_id">
                        <div class="">
                            <label for="t_user_id">Responsable : </label>
                            <select id="t_user_id" name="t_user_id"></select>
                            <button type="button" class="btn btn-outline-success width100" onclick="post_form(\'add_user\', \'cleaning\');">
                                    +
                            </button>
                        </div>
                        <div class="">
                            <label for="t_done">A été nettoyé : </label>
                            <input type="checkbox" id="t_done" name="t_done">
                        </div>
                        Commentaire : 
                        <textarea id="t_comment" name="t_comment" class="form-control"></textarea>
                        <hr>
                        <h5 style="text-align: center">Responsable(s) :</h5>
                        <ul id="responsibles">                        
                        </ul>
                        <div class="justify-content-center">
                            <button type="button" class="btn btn-outline-success width100" onclick="post_form(\'update_task\', \'cleaning\');">
                            Enregistrer
                            </button>
                        </div>
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