<?php


namespace renderers\employee;


class ProductionRenderer extends BaseRenderer
{
    private $day;

    public function __construct() {
        parent::__construct();
        $this->from = 'production';
    }

    public function tasks_list($tasks) {
        $this->output .= '<h1>Production</h1><br>
            <div class="">
            <table class="table table-hover" style="">
                    <th>Nom</th>
                    <th>Repas</th>
                    <th>Suivi température</th>
                    <th>Terminé</th>';
        if (count($tasks) !== 0) {
            foreach ($tasks as $task) {
                $checked = $task['t_done'] == 1 ? 'checked' : '';
                $this->output .= '<tr>
                <td>'.$task['rs_name'].'</td>                 
                <td>'.$task['meal'].'</td>                 
                <td>
                <button type="button" onclick="employee_tmp_modal(\''.$task['rs_id'].'\')" class="fnt_aw-btn" data-toggle="modal" data-target="#temperature_modal">
                    <i class="fas fa-thermometer-half"></i>
                </button>
                </td>                 
                <td>
                    <input type="checkbox" '.$checked.' onclick="update_task_status(\''.$task['t_id'].'\')">
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
                    <form method="POST" action="?page=production" onsubmit="recipe_form(event)" id="step-form">
                    <div class="justify-content-center">
                        <label for="recipe-current-input" id="label"></label>
                        <input id="recipe-current-input">  
                    </div>  
                    <input type="hidden" name="rs_id" id="rs_id">                                     
                    <input type="hidden" name="date" id="current-date" value="'.$this->day.'">                                     
                    </form>   
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