<?php


namespace renderers\staff\employee;


class EventsRenderer extends BaseRenderer
{
    private $day;

    public function __construct() {
        parent::__construct();
        $this->from = 'events';
    }

    public function tasks_list($tasks) {
        $this->output .= '<h1>Animation/Évenementiel</h1><br>
            <div class="">
            <table class="table table-hover" style="">
                    <th>Nom</th>
                    <th>Terminé</th>
                    <th>Commentaire</th>
                    <th></th>';
        if (count($tasks) !== 0) {
            foreach ($tasks as $task) {
                $checked = $task['t_done'] == 1 ? 'checked' : '';
                $this->output .= '<tr>
                <td>'.$task['ev_name'].'</td>                                                
                <td>
                    <input type="checkbox" '.$checked.' onclick="update_task_status(\''.$task['t_id'].'\')">
                </td>   
                <td><textarea id="ev_comment-'.$task['ev_id'].'" class="form-control">'.$task['ev_comment'].'</textarea></td>                                                       
                <td>
                    <button onclick="save_event_comment('.$task['ev_id'].')" type="button" class="btn btn-outline-success width100">
                    Enregistrer
                    </button>
                </td>                                                       
            </tr>';
            }
        } else {
            $this->output .= 'Pas de tâche';
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