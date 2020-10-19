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
        <div id="formLogin"  class="w-100">
            <form action="?page=login" method="post" id="form_login">
                <div class="row">
                    <div class="col-12 col-sm-12 col-md-6">
                        Login 
                    </div>
                    <div class="col-12 col-sm-12 col-md-6">
                        <input type="text" class="form-control width100" name="username" autofocus>
                    </div>
                </div>
                <div class="row">
                    <div class="col-12 col-sm-12 col-md-6">
                        Mot de passe
                    </div>
                    <div class="col-12 col-sm-12 col-md-6">
                        <input type="password" class="form-control width100" name="password">
                    </div>
                </div>
                <input type="hidden" name="from" value="'.$from.'">
                <div class="row justify-content-center">
                    <div class="col-12 col-sm-10 col-md-8 col-lg-6">
                        <input type="hidden" name="token" value="'.$token.'">
                        <button type="submit" class="btn btn-outline-success width100">
                            <i class="fas fa-sign-in-alt"></i> Connexion
                                </button>
                            </div>
                        </div>
                        <div class="row" id="passwordForm" style="margin-top:2rem;margin-bottom:0">
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