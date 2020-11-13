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
                    <button onclick="init_affectations_modal(\''.$equipment['eq_id'].'\')" type="button" class="fnt_aw-btn" data-toggle="modal" data-target="#cleaning_modal">
                        <i class="far fa-clipboard"></i>
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
                    <button type="button" onclick="init_affectations_modal(\''.$space['s_id'].'\')" class="fnt_aw-btn" data-toggle="modal" data-target="#cleaning_modal">
                        <i class="far fa-clipboard"></i>
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

    public function set_day($day) {
        $this->day = $day;
        $this->from .= '&date='.$day;

        return $this;
    }
}