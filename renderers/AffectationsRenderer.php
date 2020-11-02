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