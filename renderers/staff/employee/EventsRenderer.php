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
                    <th>Commentaire</th>';
        if (count($tasks) !== 0) {
            foreach ($tasks as $task) {
                $checked = $task['ev_done'] == 1 ? 'checked' : '';
                $this->output .= '<tr>
                <td>'.$task['ev_name'].'</td>                                                
                <td>
                    <input type="checkbox" '.$checked.' onclick="update_event_status(\''.$task['ev_id'].'\')">
                </td>   
                <td>
                    <button type="button" onclick="init_comments_modal(\''.$task['t_target_id'].'\', \''.$this->day.'\')" class="fnt_aw-btn" data-toggle="modal" data-target="#comment_modal">
                        <i class="fas fa-comment"></i>
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