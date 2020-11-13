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
        $this->output .= '<h1>Nettoyage et Désinfection</h1>
            <div class="equipment-list">
            <h2>Matériel</h2>
            <table class="table table-hover" style="">
                    <th>Nom</th>
                    <th>Instructions</th>
                    <th>Terminé</th>';
        if (count($tasks) !== 0) {
            foreach ($tasks as $task) {
                $checked = $task['t_done'] == 1 ? 'checked' : '';
                $this->output .= '<tr>
                <td>'.$task['eq_name'].'</td>                 
                <td>'.$task['eq_cleaning_instructions'].'</td>                 
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

    public function spaces_tasks_list($tasks) {
        $this->output .= '<div class="space-list">
            <h2>Locaux</h2>
            <table class="table table-hover" style="">
                    <th>Nom</th>
                    <th>Instructions</th>
                    <th>Terminé</th>';
        if (count($tasks) !== 0) {
            foreach ($tasks as $task) {
                $checked = $task['t_done'] == 1 ? 'checked' : '';
                $this->output .= '<tr>
                <td>'.$task['s_name'].'</td>                 
                <td>'.$task['s_cleaning_instructions'].'</td>                 
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



    public function set_day($day) {
        $this->day = $day;
        $this->from .= '&date='.$day;

        return $this;
    }
}