<?php

namespace renderers;

class TeamRenderer extends BaseRenderer
{

    public function team_form($employees) {
        $disabled = '';
        $this->output .= '
        <div class-"rest-form-container" style="width: fit-content;">
        <h1 style="text-align: center;">Gestion de l\'équipe</h1><br>
        <div class="team-ctnr">
            <h2 style="text-align: center;">Importer depuis la base de donnée</h2>
            <div class="form-inline w-100">
            <select class="form-control" name="u_id">';
        if (count($employees) === 0) {
            $this->output .= '<option selected disabled>Aucun employé disponible</option>';
            $disabled = ' disabled ';
        } else {
            $this->output .= '<option selected disabled>-- Sélectionner un employé ---</option>';
            foreach ($employees as $employee) {
                $this->output .= '<option value="'.$employee->getId().'">'.$employee->getFirstname().' '.$employee->getLastname().' ('.$employee->getEmail().')</option>';
            }
        }
        $this->output .='</select>
        <button type="submit" onclick="valid_form()" class="btn btn-outline-success width100"'.$disabled.'>
        Ajouter
        </button>
        </div>
        </div>
        <br>
        <div class="team-ctnr">
            <h2 style="text-align: center;">Ajouter un employé</h2>
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
        </div>
        <br>';
        return $this;
    }

    public function employees_list($employees) {
        $this->output .= '<h3 style="text-align: center;">Liste des employés</h3>';
        if (count($employees) === 0) {
            $this->output .= 'Ce restaurant ne compte encore aucun employé';
        } else {
            $this->output .= '
            <div class="table-box">
            <table class="table table-hover">
            <th>Prénom</th>
            <th>Nom</th>
            <th>Adresse mail</th>
            <th>Supprimer</th>';

            foreach ($employees as $employee) {
            $this->output .= '<tr>
                <td>'.$employee->getFirstname().'</td>
                <td>'.$employee->getLastname().'</td>
                <td>'.$employee->getEmail().'</td>
                <td>
                    <button type="submit" onclick="valid_form(); return delete_confirm();" name="delete" value="'.$employee->getId().'" class="fnt_aw-btn delete-btn">
                    <i class="fas fa-trash-alt"></i>
                    </button>
                </td>
            </tr>';
            }

        $this->output .= '</table>
            </div>';
        }
        return $this;
    }

}