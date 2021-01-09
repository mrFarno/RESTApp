<?php


namespace renderers\manager;


class CleaningRenderer extends BaseRenderer
{
    private $day;

    public function __construct() {
        parent::__construct();
        $this->from = 'cleaning';
    }

    public function list_equipments($equipments, $tasks) {
        $this->output .= '<h1>Nettoyage et désinfection</h1>
            <input type="hidden" name="date" id="date-hidden" value="'.$this->day.'">
            <div class="equipment-list">
            <h2>Matériels</h2>
            <table class="table table-hover" style="">
                    <th>Désignation</th>
                    <th>Par</th>
                    <th>A</th>
                    <th>Commentaire</th>';
        if (count($equipments) !== 0) {
            foreach ($equipments as $equipment) {
                if(isset($tasks[$equipment['eq_id']]) && $tasks[$equipment['eq_id']] !== false) {
                    $checked = $tasks[$equipment['eq_id']]['t_done'] == 1 ? 'checked' : '';
                    $hour = $tasks[$equipment['eq_id']]['t_done_hour'] ?? '';
                } else {
                    $checked = '';
                    $hour = '';
                }
                $this->output .= '<tr>
                <td>'.$equipment['eq_name'].'</td>                 
                <td>
                    <button onclick="init_affectations_modal(\''.$equipment['eq_id'].'\')" type="button" class="fnt_aw-btn" data-toggle="modal" data-target="#cleaning_modal">
                        <i class="far fa-clipboard"></i>
                    </button>
                </td>                
                <td>'.$hour.'</td>                 
                <td>
                    <button type="button" onclick="comment_modal('.$tasks[$equipment['eq_id']]['t_id'].')" class="fnt_aw-btn" data-toggle="modal" data-target="#comment_modal">
                        <i class="fas fa-comment"></i>
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

    public function list_spaces($spaces, $tasks) {
        $this->output .= '<div class="space-list">
            <h2>Locaux</h2>
            <table class="table table-hover" style="">
                    <th>Désignation</th>
                    <th>Par</th>
                    <th>A</th>
                    <th>Commentaire</th>';
        if (count($spaces) !== 0) {
            foreach ($spaces as $space) {
                if(isset($tasks[$space['s_id']]) && $tasks[$space['s_id']] !== false) {
                    $checked = $tasks[$space['s_id']]['t_done'] == 1 ? 'checked' : '';
                    $hour = $tasks[$space['s_id']]['t_done_hour'] ?? '';
                } else {
                    $checked = '';
                    $hour = '';
                }
                $this->output .= '<tr>
                <td>'.$space['s_name'].'</td>  
                <td>
                    <button type="button" onclick="init_affectations_modal(\''.$space['s_id'].'\')" class="fnt_aw-btn" data-toggle="modal" data-target="#cleaning_modal">
                        <i class="far fa-clipboard"></i>
                    </button>
                </td>                               
                <td>'.$hour.'</td>                 
                <td>
                    <button type="button" onclick="comment_modal('.$tasks[$space['s_id']]['t_id'].')" class="fnt_aw-btn" data-toggle="modal" data-target="#comment_modal">
                        <i class="fas fa-comment"></i>
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
                    <div class="modal-body" id="modal-content">  

                    </div>
                </div>
            </div>
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
                        <input type="hidden" id="check-step" value="team">             
                        <input type="hidden" id="meal_id" value="">             
                        <textarea class="form-control" rows="5" id="comment-content"></textarea>     
                        <div class="row justify-content-center">
                        <input type="hidden" id="task_id" name="t_id">
                        <button type="button" onclick="save_task_comment()" class="btn btn-outline-success width100">Enregistrer</button>
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