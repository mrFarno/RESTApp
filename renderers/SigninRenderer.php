<?php


namespace renderers;


class SigninRenderer extends BaseRenderer
{
    public function __construct() {
        parent::__construct();
        $this->from = 'signin';
    }

    public function signin_form() {
        $this->output .= ' <div class="d-flex justify-content-center">
        <div id="formLogin"  class="w-100 d-flex justify-content-center">
            <form action="?page=signin" method="post" id="form_login">
            <h1>Créer un compte RESTApp</h1>
                <div class="mx-sm-3 mb-2">
                    <div class="mx-sm-3 mb-2">
                        <input type="text" placeholder="Adresse mail" class="form-control width100" name="username" autofocus>
                    </div>
                </div>
                <div class="mx-sm-3 mb-2">
                    <div class="mx-sm-3 mb-2">
                        <input type="text" placeholder="Prénom" class="form-control width100" name="firstname">
                    </div>
                </div>
                <div class="mx-sm-3 mb-2">
                    <div class="mx-sm-3 mb-2">
                        <input type="text" placeholder="Nom" class="form-control width100" name="lastname">
                    </div>
                </div>
                <div class="mx-sm-3 mb-2">
                    <div class="mx-sm-3 mb-2">
                        <input type="password" placeholder="Mot de passe" class="form-control width100" name="password">
                    </div>
                </div>
                <div class="mx-sm-3 mb-2">
                    <div class="mx-sm-3 mb-2">
                        <input type="password" placeholder="Confirmer le mot de passe" class="form-control width100" name="confirm">
                    </div>
                </div>
                <div class="row justify-content-center">
                    <div class="">                    
                        <button type="submit" class="btn btn-outline-success width100">Créer compte
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
}