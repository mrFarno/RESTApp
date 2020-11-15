<?php

namespace renderers\manager;

class TeamRenderer extends BaseRenderer
{
    public function __construct() {
        parent::__construct();
        $this->from = 'team';
    }

    public function team_modal($id) {
//        $disabled = '';
//        $this->output .= '
//        <div class-"rest-form-container" style="width: fit-content;">
//        <h1 style="text-align: center;">Gestion de l\'équipe</h1>
//        <div class="team-ctnr">
//            <h2 style="text-align: center;">Importer depuis la base de donnée</h2>
//            <div class="form-inline w-100">
//            <select class="form-control" name="u_id">';
//        if (count($employees) === 0) {
//            $this->output .= '<option selected disabled>Aucun employé disponible</option>';
//            $disabled = ' disabled ';
//        } else {
//            $this->output .= '<option selected disabled>-- Sélectionner un employé ---</option>';
//            foreach ($employees as $employee) {
//                $this->output .= '<option value="'.$employee->getId().'">'.$employee->getFirstname().' '.$employee->getLastname().' ('.$employee->getEmail().')</option>';
//            }
//        }
//        $this->output .='</select>
//        <button type="submit" onclick="valid_form()" class="btn btn-outline-success width100"'.$disabled.'>
//        Ajouter
//        </button>
//        </div>
//        </div>
//        <br>';

        $this->output .= '<div class="modal fade" aria-labelledby="manualModalLabel" id="team_modal" style="margin-bottom: 1rem"  tabindex="-1" role="dialog" aria-hidden="true">
            <div  class="modal-dialog modal-lg" role="document" id="formManual">
                <div class="modal-content" style="margin-top: 33%">
                    <div class="modal-header">
                        <h5 class="modal-title" id="manualModalLabel">Ajouter un employé</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">  
                    <form method="POST" action="index.php?page=team&restid='.$id.'">  
                        <div class="team-ctnr">
                            <div class="form-group">
                                <input type="text" class="form-control" id="u_firstname" name="u_firstname" required placeholder="Prénom">
                            </div>
                            <div class="form-group">
                                <input type="text" class="form-control" id="u_lastname" name="u_lastname" required placeholder="Nom">
                            </div>
                            <div class="form-group">
                                <input type="email" class="form-control" id="u_email" name="u_email" required placeholder="Adresse email">
                            </div>
                            <div class="row justify-content-center">
                            <button type="submit" class="btn btn-outline-success width100">
                            Ajouter
                            </button>
                            </div>
                        </div>
                    </form>
                    </div>
                    </div>
                </div>
            </div>
        </div>';
        return $this;
    }

    public function employees_list($employees, $restaurants, $id) {
        $this->output .= '<h1 style="text-align: center;">Gestion de l\'équipe</h1>
        <form method="POST" action="index.php?page=team&restid='.$id.'"> 
        <h3 style="text-align: center;">Liste des employés</h3>';
        if (count($employees) === 0) {
            $this->output .= 'Ce restaurant ne compte encore aucun employé';
        } else {
            $this->output .= '
            <div class="">
            <table class="table table-hover">
            <th>Nom</th>
            <th>Prénom</th>
            <th>Adresse mail</th>
            <th>Supprimer</th>';

            foreach ($employees as $employee) {
            $title = 'Cette personne est employée dans le(s) restaurant(s) suivant(s) :
            ';
            foreach ($restaurants[$employee->getId()] as $restaurant) {
                $title .= $restaurant->getName().'
            ';
            }

            $this->output .= '<tr>
                <td>'.$employee->getLastname().'</td>
                <td>'.$employee->getFirstname().'</td>
                <td>'.$employee->getEmail().' <i class="fas fa-info-circle" title="'.$title.'"></i></td>
                <td>
                    <button type="submit" onclick="valid_form(); return delete_confirm();" name="delete" value="'.$employee->getId().'" class="fnt_aw-btn delete-btn">
                    <i class="fas fa-trash-alt"></i>
                    </button>
                </td>
            </tr>';
        }

        $this->output .= '</table>
            </div>
            <button type="button" data-toggle="modal" data-target="#team_modal" class="btn btn-outline-success width100">
        Ajouter
        </button>';
        }
        return $this;
    }

    public function home() {
        $this->output .='<br><div class="row justify-content-center">
        <button type="button" class="btn btn-outline-success width100">
        <a href="?page=home">Terminer</a>
        </button>
        </div>
        </form>';

        return $this;
    }

}