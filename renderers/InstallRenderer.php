<?php

namespace renderers;
use renderers\BaseRenderer;

class InstallRenderer extends BaseRenderer
{
    private $disabled;
    private $attribute;

    public function __construct()
    {
        parent::__construct();
        $this->disabled = '';
        $this->attribute = 'placeholder';
    }

    /**
     * @param array $data : fields default value
     * @param boolean $test_passed if $data are correct
     */
    public function database_form($data, $test_passed) {
        $this->error($test_passed);
        $this->output .= '<div class="row">
                        <div class="col-12">
                            <h2><strong>I- Base de données</strong></h2>
                        </div>
                    </div>
                    <div class="row justify-content-end" style="margin: 1rem">
                        <div class="col-5">
                            Serveur :
                        </div>  
                        <div class="col-6">
                            <input type="text" name="db_host" placeholder="localhost" class="form-control" required '.$this->disabled.' '.$this->attribute.'='.$data['db_host'].'>
                        </div>
                    </div>
                    <div class="row justify-content-end" style="margin: 1rem">
                        <div class="col-5">
                            Login :
                        </div>
                        <div class="col-6">
                            <input type="text" name="db_user" class="form-control" required '.$this->disabled.' '.$this->attribute.'='.$data['db_user'].'>
                        </div>
                    </div>
                    <div class="row justify-content-end" style="margin: 1rem">
                        <div class="col-5">
                            Mot de passe :
                        </div>
                        <div class="col-6">
                            <input type="password" name="db_pass" required class="form-control"  '.$this->disabled.'>
                        </div>
                    </div>
                    <div class="row justify-content-end" style="margin: 1rem">
                        <div class="col-5">
                            Base de données :
                        </div>
                        <div class="col-6">
                            <input type="text" name="db_name" required class="form-control" '.$this->disabled.' '.$this->attribute.'='.$data['db_name'].'>
                        </div>
                    </div>
                    <div class="row justify-content-end" style="margin: 1rem">
                        <div class="col-5">
                            Type :
                        </div>
                        <div class="col-6">
                            <select name="db_type" class="form-control" required '.$this->disabled.'>
                                <option value="mysql"'.self::isselected($data, 'mysql').'>MySQL/MariaDB</option>
                                <option value="oci"'.self::isselected($data, 'oci').'>Oracle</option>
                                <option value="pgsql"'.self::isselected($data, 'pgsql').'>PostgreSQL</option>
                            </select>
                        </div>
                    </div>';
        return $this;
    }

    /**
     * @param array $data : fields default value
     * @param boolean $test_passed if $data are correct
     */
    public function smtp_form($data, $test_passed) {
        $this->error($test_passed);
        $checked = isset($data['smtp_certs']) && $data['smtp_certs'] == 'on' ? ' checked' : '';
        $this->output .= '<div class="row">
                <div class="col-12">
                    <h2 style="display:inline"><strong>III- Paramètres SMTP</strong></h2>
                    <a type="button" data-toggle="modal" data-target="#modalHelpSMTP">
                        <i class="far fa-question-circle" style="font-size:1.2rem;"></i>
                    </a>
                </div>
            </div>
            <div class="row justify-content-end" style="margin: 1rem">
                <div class="col-5">
                    Host :
                </div>
                <div class="col-6">
                    <input type="text" name="smtp_host" class="form-control" required '.$this->disabled.' '.$this->attribute.'='.$data['smtp_host'].'>
                </div>
            </div>
            <div class="row justify-content-end" style="margin: 1rem">
                <div class="col-5">
                    Port :
                </div>
                <div class="col-6">
                    <input type="number" name="smtp_port" class="form-control" required '.$this->disabled.' '.$this->attribute.'='.$data['smtp_port'].'>
                </div>
            </div>
            <div class="row justify-content-end" style="margin: 1rem">
                <div class="col-5">
                    Email :
                </div>
                <div class="col-6">
                    <input type="email" name="smtp_user" class="form-control" required '.$this->disabled.' '.$this->attribute.'='.$data['smtp_user'].'>
                </div>
            </div>
            <div class="row justify-content-end" style="margin: 1rem">
                <div class="col-5">
                    Mot de passe :
                </div>
                <div class="col-6">
                    <input type="password" name="smtp_pass" class="form-control" required '.$this->disabled.'>
                </div>
            </div>            
            <div class="row justify-content-end" style="margin: 1rem">
            <div class="col-5">
                Autoriser les certificats auto-signés :
            </div>
            <div class="col-6">
                <input type="checkbox" name="smtp_certs" class="" '.$this->disabled.$checked.'>
            </div>
        </div>';
        return $this;
    }

    /**
     * @param array $data : fields default value
     * @param boolean $test_passed if $data are correct
     */
    public function ldap_form($data, $test_passed) {
        $this->error($test_passed);
        $this->output .= '<div class="row">
            <div class="col-12">
                <h2><strong>II- Connexion LDAP</strong></h2>
            </div>
        </div>
        <div class="row justify-content-end" style="margin: 1rem">
            <div class="col-5">
                Url du serveur :
            </div>  
            <div class="col-6">
                <input type="text" name="ldap_uri" class="form-control" required '.$this->disabled.' '.$this->attribute.'='.$data['ldap_uri'].'>
            </div>
        </div>
        <div class="row justify-content-end" style="margin: 1rem">
            <div class="col-5">
                DN de base :
            </div>
            <div class="col-6">
                <input type="text" name="ldap_base_dn" class="form-control" required '.$this->disabled.' '.$this->attribute.'='.$data['ldap_base_dn'].'>
            </div>
        </div>
        <div class="row justify-content-end" style="margin: 1rem">
            <div class="col-5">
                DN de connexion :
            </div>
            <div class="col-6">
                <input type="text" name="ldap_bind_dn" class="form-control" required '.$this->disabled.' '.$this->attribute.'='.$data['ldap_bind_dn'].'>
            </div>
        </div>
        <div class="row justify-content-end" style="margin: 1rem">
            <div class="col-5">
                Mot de passe :
            </div>
            <div class="col-6">
                <input type="password" name="ldap_bind_pass" required class="form-control"  '.$this->disabled.'>
            </div>
        </div>
        <div class="row justify-content-end" style="margin: 1rem">
            <div class="col-5">
                Filtre de recherche :
            </div>
            <div class="col-6">
                <input type="text" name="ldap_filter" required class="form-control"  '.$this->disabled.' '.$this->attribute.'='.$data['ldap_filter'].'>
            </div>
        </div>
        <div class="row justify-content-end" style="margin: 1rem">
            <div class="col-5">
                Port :
            </div>
            <div class="col-6">
                <input type="number" name="ldap_port" class="form-control" required '.$this->disabled.' '.$this->attribute.'='.$data['ldap_port'].'>
            </div>
        </div>';

        return $this;
    }

    /**
     * @param array $data : fields default value
     * @param boolean $test_passed if $data are correct
     */
    public function admin_form($data, $test_passed) {
        $this->error($test_passed);
        $this->output .= '<div class="row">
                <div class="col-12">
                    <h2><strong>VI- Compte administrateur</strong></h2>
                </div>
            </div>
            <div class="row justify-content-end" style="margin: 1rem">
                <div class="col-5">
                    Login :
                </div>
                <div class="col-6">
                    <input type="text" name="admin_username" class="form-control" required '.$this->disabled.' '.$this->attribute.'='.$data['admin_username'].'>
                </div>
            </div>
            <div class="row justify-content-end" style="margin: 1rem">
                <div class="col-5">
                    Email :
                </div>
                <div class="col-6">
                    <input type="email" name="admin_email" class="form-control" required '.$this->disabled.'  '.$this->attribute.'='.$data['admin_email'].'>
                </div>
            </div>
            <div class="row justify-content-end" style="margin: 1rem">
                <div class="col-5">
                    Mot de passe :
                </div>
                <div class="col-6">
                    <input type="password" name="admin_pass" class="form-control" required '.$this->disabled.' placeholder="Minimum 8 characters">
                </div>
            </div>
            <div class="row justify-content-end" style="margin: 1rem">
                <div class="col-5">
                    Confirmer mot de passe :
                </div>
                <div class="col-6">
                    <input type="password" name="admin_confirm" class="form-control" required '.$this->disabled.' placeholder="Minimum 8 characters">
                </div>
            </div>
        <button type="submit" class="btn btn-outline-success width100" '.$this->disabled.' style="margin:2rem" >Ok</button>';
        return $this;
    }

    /**
     * @param boolean $can_write if server is writable
     */
    public function refresh($can_write) {
        if ($can_write === false) {
            $this->output .= '<div class="container" style="display :block; align-items: center">';
            $this->error('Vous n\'avez pas les droits d\'écrire sur ce serveur, veuillez contacter un administrateur.');
            $this->output .= '<a type="button" href="" >
                <i class="fas fa-redo" style="margin-left:47%" title="Reload page"></i>
            </a>
            </div>'; 
        } 
        return $this;
    }

    static function isselected($data, $dbtype) {
        return $data['db_type'] == $dbtype ? ' selected' : '';
    }

    public function set_attribute($attribute) {
        $this->attribute = $attribute;
    }

    public function disable() {
        $this->disabled = 'disabled';
    }
    
}