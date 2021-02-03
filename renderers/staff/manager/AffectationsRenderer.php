<?php

namespace renderers\staff\manager;

class AffectationsRenderer extends BaseRenderer
{
    private $action = [];
    private $date;

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
            <th>Affecter</th>';

            foreach ($employees as $employee) {
            $this->output .= '<tr>
                <td id="firstname-'.$employee->getId().'">'.$employee->getFirstname().'</td>
                <td id="lastname-'.$employee->getId().'">'.$employee->getLastname().'</td>
                <td>'.$employee->getEmail().'</td>
                <td>
                    <button onclick="modal_init(\''.$employee->getId().'\')" type="button" class="fnt_aw-btn" data-toggle="modal" data-target="#user_modal">
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
                <form method="POST" action="?page=affectations">
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
                    <input type="date" id="af_timestart-'.$id.'" name="af_timestart-'.$id.'" value="'.date('Y-m-d').'">
                </div>
                <div id="end-mt-'.$id.'" class="col" hidden>
                    <span> au </span>
                    <input type="date" name="af_timeend-'.$id.'" id="af_timeend-'.$id.'">
                </div>
                </div>';
        }
        $this->output .= '<div class="row justify-content-center">
            <button type="submit" class="btn btn-outline-success width100">
                Ok
            </button>
            </div>
            </div>
            </form>
            </div>
        </div>
    </div>';
        return $this;
    }

    public function modal_content($task, $employees, $responsibles, $type) {
        $check = $task['t_done'] == 1 ? 'checked' : '';
        $all = $employees + $responsibles;
        $this->output .= '<form method="POST" action="?page='.$type.'" id="step-form">
                            <input type="hidden" name="date" value="'.$task['t_date'].'">
                            <input type="hidden" name="t_target_id" id="t_target_id" value="">
                            <input type="hidden" name="delete" id="delete-hidden" value="">                            
                        <div class="">
                            <label for="t_user_id">Responsable : </label>
                            <select id="t_controller" name="t_controller">
                            <option selected disabled>---Sélectionner un responsable---</option>';
        foreach ($all as $id => $employee) {
            $selected = '';
            if($task['t_controller'] == $id) {
                $selected = 'selected';
            }
            $this->output .= '<option '.$selected.' value="'.$id.'">'.$employee.'</option>';
        }
        $this->output .= '</select>
                            <button type="button" class="btn btn-outline-success width100" onclick="post_form(\'add_responsible\', \'affectations\');update_affectation_modal()">
                                Enregistrer le responsable
                            </button>
                    <hr>';
        $this->output .= '<label for="t_user_id">Intervenants : </label>
                            <select id="t_user_id" name="t_user_id">
                                <option selected disabled>---Sélectionner un employé---</option>';
        foreach ($employees as $id => $employee) {
            $this->output .= '<option value="'.$id.'">'.$employee.'</option>';
        }
        $this->output .= '</select>
                        </div>';
        switch ($type) {
            case 'cleaning':
                $days = ['Lundi', 'Mardi', 'Mercredi', 'Jeudi', 'Vendredi', 'Samedi', 'Dimanche'];
                for ($i = 0; $i < 7; $i++) {
                    $this->output .= '<input type="checkbox" name="day-'.($i+1).'" id="day-'.($i+1).'">
                <label for="day-'.($i+1).'">'.$days[$i].'</label>';
                }
                break;
            case 'production':
                $this->output .= 'Nombre de portions : <input name="ta_number" type="number">';
                break;
            default:
                break;
        }

        $this->output .= '<button type="button" class="btn btn-outline-success width100" onclick="post_form(\'add_user\', \'affectations\');update_affectation_modal()">
                              Enregistrer
                            </button><hr>
                        <!-- Commentaire : 
                        <textarea id="t_comment" name="t_comment" class="form-control">'.$task['t_comment'].'</textarea> -->
                        <div class="">
                            <label for="t_done">A été fait : </label>
                            <input disabled type="checkbox" '.$check.' id="t_done" name="t_done">
                        </div>
                        <hr>
                        <h5 style="text-align: center">Intervenant(s) :</h5>
                        <ul id="responsibles">';
        foreach ($responsibles as $id => $responsible) {
            $this->output .= '<li>'.$responsible.'                            
                            <button type="button" class="fnt_aw-btn" onclick="del_user_aff(\''.$id.'\'); post_form(\'del_user_aff\', \'affectations\');update_affectation_modal()">
                                <i class="fas fa-trash-alt"></i>
                            </button>
                                </li>';
        }
        $this->output .= '</ul>
                        <!-- <div class="justify-content-center">
                            <button type="button" class="btn btn-outline-success width100" onclick="post_form(\'update_task\', \'affectations\');">
                            Enregistrer
                            </button>
                        </div> -->
        </form>';
        return $this;
    }

    public function set_day($day) {
        $this->date = $day;

        return $this;
    }
    
}