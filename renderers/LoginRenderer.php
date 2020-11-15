<?php

namespace renderers;

class LoginRenderer extends BaseRenderer
{
    /**
     * @param string $token login token
     * @return self
     */
    public function login_form($token, $from = 'home') {
        $this->output .=' <div class="d-flex justify-content-center">
        <div id="formLogin"  class="w-100 d-flex justify-content-center">
            <form action="?page=login" method="post" id="form_login">
            <h1>Bienvenu sur Good For Restau</h1>
                <div class="mx-sm-3 mb-2">
                    <div class="mx-sm-3 mb-2">
                        <input type="text" placeholder="Adresse mail" class="form-control width100" name="username" autofocus>
                    </div>
                </div>
                <div class="mx-sm-3 mb-2">
                    <div class="mx-sm-3 mb-2">
                        <input type="password" placeholder="Mot de passe" class="form-control width100" name="password">
                    </div>
                </div>
                <input type="hidden" name="from" value="'.$from.'">
                <div class="row justify-content-center">
                    <div class="">
                        <input type="hidden" name="token" value="'.$token.'">
                        <button type="submit" class="btn btn-outline-success width100">Connexion
                                </button>
                            </div>
                        </div>
                        <div class="row" id="passwordForm" style="margin-top:2rem;margin-bottom:0">
                        <div class="col-12">
                            <a href="?page=signin">
                                Créer un compte
                            </a>
                        </div>
                        <div class="col-12">
                            <a data-toggle="collapse" href="#collapsePassword" role="button" aria-expanded="false" aria-controls="collapsePassword">
                                Réinitialiser le mot de passe
                            </a>
                        </div>
                    </div>
                    <div class="w-100" style="margin-top:0;">
                        <div class="collapse col-12" id="collapsePassword">
                            <div class="row">
                                <div class="input-group mb-3">
                                        <input id="reset" type="text" class="form-control" placeholder="Email" name="reset">
                                        <div class="input-group-append">
                                            <button class="btn btn-outline-success" type="button" onclick="reset_password();">
                                                Envoyer
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <br>
                        <br>
                        <br>
                        <br>
                        <span class="cprght">
                            Texte instutitionnel <br>
                            <img src="'.$GLOBALS['domain'].'/public/style/resources/logo.png">
                            @Copyright CNFPT 2020
                        </span>
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