<?php

namespace renderers;

use app\BO\Contributor;

class AccountsRenderer extends BaseRenderer
{
    /**
     * @param array $contributors List of all Contributors from database
     * @return self
     */
    public function contributors_form(array $contributors) {
        $type = isset($contributors[0]) && $contributors[0]->getRole() === 'Admin' ? 'administrateurs' : 'contributeurs';
        if (count($contributors) > 0) {
            $role = $contributors[0]->getRole();
        } else {
            $role = 'Contributor';
        }
        $this->output .= '<h2 class="inline">Comptes '.$type.'</h2>
                            <button onclick="modal_set_action(\'add\',\''.$role.'\')" type="button" class="success-btn fnt_aw-btn" data-toggle="modal" data-target="#user_modal"><i class="fas fa-plus-circle hvr-pulse"></i></button>
                        <table class="table table-hover">
                            <th>Désignation</th>
                            <th>Identifiant</th>
                            <th>Adresse mail</th>
                            <th>Modifier</th>
                            <th>Supprimer</th>';
        if (count($contributors) > 0) {
            foreach ($contributors as $contributor) {
                $this->output .= '<tr>
                    <td>'.$contributor->getLabel().'</td>
                    <td>'.$contributor->getLogin().'</td>
                    <td>'.$contributor->getMail().'</td>
                    <td>
                            <button onclick="modal_set_action(\'edit\',\''.$contributor->getId().'\')" type="button" class="fnt_aw-btn" data-toggle="modal" data-target="#user_modal">
                            <i class="fas fa-edit"></i></td>
                        </a>
                    <td>
                        <button type="submit" onclick="return delete_confirm()" name="delete" value="'.$contributor->getId().'" class="fnt_aw-btn delete-btn">
                        <i class="fas fa-trash-alt"></i>
                        </button>
                    </td>
                </tr>';
            }
        }
        $this->output .= '</table>';
        return $this;
    }

    public function user_modal() {
        $this->output .= '<div class="modal fade" aria-labelledby="manualModalLabel" id="user_modal" style="margin-bottom: 1rem"  tabindex="-1" role="dialog" aria-hidden="true">
        <div  class="modal-dialog modal-lg" role="document" id="formManual">
            <div class="modal-content" style="margin-top: 33%">
                <div class="modal-header">
                    <h5 class="modal-title" id="manualModalLabel"><i class="fas fa-user"></i><span id="action_label"></span></h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">                
                        <div class="row">
                            <div class="col-6">
                                Désignation
                            </div>
                            <div class="col-6">
                                <input type="text" id="modal_label" class="form-control" name="label">
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-6">
                                Identifiant
                            </div>
                            <div class="col-6">
                                <input type="text" id="modal_login" class="form-control" name="login">
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-6">
                                Email
                            </div>
                            <div class="col-6">
                                <input type="email" id="modal_mail" class="form-control" name="mail" >
                            </div>
                        </div>                    
                        <div class="row justify-content-center">
                            <div class="col-4">
                                <button type="submit" id="modal_role_btn" class="btn btn-outline-success width100">Ok</button>
                            </div>
                        </div>              
                </div>
            </div>
        </div>
    </div>';
    return $this;
    }
}