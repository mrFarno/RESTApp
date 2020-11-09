<?php


namespace renderers;


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
                    <button type="button" class="fnt_aw-btn">
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
                    <button type="button" class="fnt_aw-btn">
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

    public function set_day($day) {
        $this->day = $day;
        $this->from .= '&date='.$day;

        return $this;
    }
}