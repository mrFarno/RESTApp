<?php


namespace renderers\employee;


class CleaningRenderer extends BaseRenderer
{
    private $day;

    public function __construct() {
        parent::__construct();
        $this->from = 'cleaning';
    }

    public function equipement_tasks_list($tasks) {
        $this->output .= '<h1>Nettoyage et Désinfection</h1>';
        if (true) {
            $this->output .= '<button type="button" data-toggle="modal" data-target="#controls_modal">
                    Contrôles
                </button>';
        }
        $this->output .= '<div class="equipment-list">
            <h2>Matériel</h2>
            <table class="table table-hover" style="">
                    <th>Nom</th>
                    <th>Instructions</th>
                    <th>Terminé</th>
                    <th>Heure</th>
                    <th>Commentaire</th>';
        if (count($tasks) !== 0) {
            foreach ($tasks as $task) {
                $checked = $task['ta_done'] == 1 ? 'checked' : '';
                $disabled = $task['ta_done'] == 1 ? ' disabled' : '';
                $this->output .= '<tr>
                <td>'.$task['eq_name'].'</td>                 
                <td>'.$task['eq_cleaning_instructions'].'</td>                 
                <td>
                    <input type="checkbox" '.$checked.$disabled.' onclick="update_task_status(\''.$task['t_id'].'\')" id="check-t-'.$task['t_id'].'">
                </td> 
                <td>
                    <input type="time" onchange="update_done_hour(\''.$task['t_id'].'\')">
                </td>  
                <td>
                    <button type="button" onclick="comment_modal('.$task['t_id'].')" class="fnt_aw-btn" data-toggle="modal" data-target="#comment_modal">
                        <i class="fas fa-comment"></i>
                    </button>
                </td>                                                       
            </tr>';
            }
        } else {
            $this->output .= 'Pas de tâches';
        }
        $this->output .= '</table>
        </div>';
        return $this;
    }

    public function spaces_tasks_list($tasks) {
        $this->output .= '<div class="space-list">
            <h2>Locaux</h2>
            <table class="table table-hover" style="">
                    <th>Nom</th>
                    <th>Instructions</th>
                    <th>Terminé</th>
                    <th>Heure</th>
                    <th>Commentaire</th>';
        if (count($tasks) !== 0) {
            foreach ($tasks as $task) {
                $checked = $task['ta_done'] == 1 ? 'checked' : '';
                $disabled = $task['ta_done'] == 1 ? ' disabled' : '';
                $this->output .= '<tr>
                <td>'.$task['s_name'].'</td>                 
                <td>'.$task['s_cleaning_instructions'].'</td>                 
                <td>
                    <input type="checkbox" '.$checked.$disabled.' onclick="update_task_status(\''.$task['t_id'].'\')" id="check-t-'.$task['t_id'].'">
                </td>  
                <td>
                    <input type="time" onchange="update_done_hour(\''.$task['t_id'].'\')">
                </td>   
                <td>
                    <button type="button" onclick="comment_modal('.$task['t_id'].')" class="fnt_aw-btn" data-toggle="modal" data-target="#comment_modal">
                        <i class="fas fa-comment"></i>
                    </button>
                </td>                                                          
            </tr>';
            }
        } else {
            $this->output .= 'Pas de tâches';
        }
        $this->output .= '</table>
        </div>';
        return $this;
    }

    public function controls_modal($controls)
    {
        $this->output .= '<div class="modal fade" aria-labelledby="manualModalLabel" id="controls_modal" style="margin-bottom: 1rem"  tabindex="-1" role="dialog" aria-hidden="true">
            <div  class="modal-dialog modal-lg" role="document" id="formManual">
                <div class="modal-content" style="margin-top: 33%">
                    <div class="modal-header">
                        <h5 class="modal-title" id="manualModalLabel"><span id="eq_name-modal">Contrôles</span></h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">';
        if(count($controls) === 0) {
            $this->output .= 'Vous n\'avez pas de contrôles à effectuer';
        } else {
            $this->output .= '<table class="table table-hover" style="">
                    <th>Nom</th>
                    <th>Terminé</th>';
            foreach ($controls as $control) {
                $checked = $control['t_done'] == 1 ? 'checked' : '';
                $this->output .= '<tr>
                    <td>'.$control['target'].'</td>
                    <td>
                    <input type="checkbox" '.$checked.' onclick="set_task_done(\''.$control['t_id'].'\')">
                </td> 
                </tr>';
            }
            $this->output .= '</table>';
        }
        $this->output .= '</div>
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