<?php

namespace renderers;

class AffectationsRenderer extends BaseRenderer
{
    private $action = [];

    public function __construct() {
        parent::__construct();
        $this->from = 'affectations';
    }

    public function employees_table($employees) {
        $this->output .= '<br><h1 style="text-align: center;">Gestion des affectations</h1><br>';
        if (count($employees) === 0) {
            $this->output .= 'Ce restaurant ne compte encore aucun employé';
        } else {
            $this->output .= '
            <div class="">
            <table class="table table-hover">
            <th>Prénom</th>
            <th>Nom</th>
            <th>Adresse mail</th>
            <th>Supprimer</th>';

            foreach ($employees as $employee) {
            $this->output .= '<tr>
                <td id="firstname-'.$employee->getId().'">'.$employee->getFirstname().'</td>
                <td id="lastname-'.$employee->getId().'">'.$employee->getLastname().'</td>
                <td>'.$employee->getEmail().'</td>
                <td>
                    <button onclick="modal_init('.$employee->getId().')" type="button" class="fnt_aw-btn" data-toggle="modal" data-target="#user_modal">
                    <i class="fas fa-user-edit"></i>
                    </button>
                </td>
            </tr>';
        }

        $this->output .= '</table>
            </div>';
        }
        return $this;
    }

    public function user_modal($meals) {
        $this->output .= '<div class="modal fade" aria-labelledby="manualModalLabel" id="user_modal" style="margin-bottom: 1rem"  tabindex="-1" role="dialog" aria-hidden="true">
        <div  class="modal-dialog modal-lg" role="document" id="formManual">
            <div class="modal-content" style="margin-top: 33%">
                <div class="modal-header">
                    <h5 class="modal-title" id="manualModalLabel"><i class="fas fa-user"></i>Affectations de <span id="u_firstname"></span> <span id="u_lastname"></span></h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <input type="hidden" name="u_id" id="u_id" value="">
                <input type="hidden" name="r_id" value="'.$_SESSION['current-rest'].'">
                <div class="modal-body">
                Affecter à :<br><br>';
        foreach ($meals as $id => $name) {
            $this->output .= '<div class="form-row">
                <div class="col">
                    <label for=mt-'.$id.'">'.$name.'</label>
                    <input onclick="display_dates('.$id.')" type="checkbox" name="mt-'.$id.'" id="mt-'.$id.'">
                </div>
                <div id="start-mt-'.$id.'" class="col" hidden>
                    <span>Du </span>
                    <input type="date" name="af_timestart-'.$id.'" value="'.date('Y-m-d').'">
                </div>
                <div id="end-mt-'.$id.'" class="col" hidden>
                    <span> au </span>
                    <input type="date" name="af_timeend-'.$id.'">
                </div>
                </div>';
        }
        $this->output .= '<div class="row justify-content-center">
            <button type="submit" class="btn btn-outline-success width100">
                Ok
            </button>
            </div>
            </div>
            </div>
        </div>
    </div>';
        return $this;
    }
    
}