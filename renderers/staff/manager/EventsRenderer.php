<?php


namespace renderers\staff\manager;


class EventsRenderer extends BaseRenderer
{
    private $day;

    public function __construct() {
        parent::__construct();
        $this->from = 'events';
    }

    public function events_form($events, $current_meal) {
        $this->output .= '<h2 style="text-align: center;">Animation/Évenementiel</h2><br>
            <input type="hidden" name="date" id="date-hidden" value="'.$this->day.'">
            <input type="hidden" name="current-meal" id="date-hidden" value="'.$current_meal.'">
            <div class="" >
            <table class="table table-hover" style="">
                    <th>Nom</th>
                    <th>Affectations</th>
                    <th>Fait</th>
                    <th>Commentaire</th>
                    <th></th>';
        if (count($events) !== 0) {
            foreach ($events as $event) {
                $checked = $event['ev_done'] == 1 ? ' checked' : '';
                $this->output .= '<tr>
                <td>'.$event['ev_name'].'</td>          
                <td><button type="button" onclick="init_affectations_modal(\''.$event['ev_id'].'\')" class="fnt_aw-btn" data-toggle="modal" data-target="#events_modal">
                    <i class="far fa-clipboard"></i>
                </button></td>  
                <td>
                    <input type="checkbox" disabled '.$checked.'>
                </td>
                <td>'.$event['ev_comment'].'</td>
                <td>           
                    <button type="submit" onclick="return confirm(\'Etes vous sur de vouloir supprimer cet évenement ?\')" name="delete" value="'.$event['ev_id'].'" class="fnt_aw-btn delete-btn">
                        <i class="fas fa-trash-alt"></i>
                    </button>
                </td>                                                          
            </tr>';
            }
        }
        $this->output .= '<tr>
            <td><input type="text" name="ev_name"></td>                                                
            <td></td>  
            <td></td>  
            <td></td>  
            <td>
                <button type="submit" class="btn btn-outline-success width100">
                    +
                </button>
            </td>
        </tr>';
        return $this;
    }

    public function events_modal() {
        $this->output .= '<div class="modal fade" aria-labelledby="manualModalLabel" id="events_modal" style="margin-bottom: 1rem"  tabindex="-1" role="dialog" aria-hidden="true">
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

    public function set_day($day) {
        $this->day = $day;
        $this->from .= '&date='.$day;

        return $this;
    }
}