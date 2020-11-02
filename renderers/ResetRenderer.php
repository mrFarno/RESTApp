<?php

namespace renderers;

use app\BO\User;

class ResetRenderer extends BaseRenderer
{

    /**
     * @param User $user User
     * @return self
     */
    public function reset_form($user){
        if ($user !== false) {
            $this->output .= '<form method="post" action="?page=reset&token='.$user->getToken().'">
            <div class="row" style="margin: 2rem 0">
                <div class="col-12 col-sm-6">
                    Nouveau mot de passe :
                </div>
                <div class="col-12 col-sm-6">
                    <input class="form-control" type="password" name="password" required>
                </div>
            </div>
            <div class="row" style="margin: 2rem 0">
                <div class="col-12 col-sm-6">
                    Confirmer le mot de passe
                </div>
                <div class="col-12 col-sm-6">
                    <input class="form-control" type="password" name="confirm" required>
                </div>
            </div>
            <div class="row justify-content-center" style="margin: 2rem 0">
                <div class="col-4">
                    <button type="submit" class="btn btn-outline-success width100">Modifier</button>
                </div>
            </div> 
        </form>';
        }
        return $this;
    }
}