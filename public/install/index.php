<?php

use app\DAO\ContributorDAO;

require __DIR__.'/../../vendor/autoload.php';
require __DIR__.'/../../app/Autoloader.php';
require __DIR__.'/lib.php';

app\Autoloader::register();

$domain = str_replace($_SERVER['DOCUMENT_ROOT'],'',__DIR__);     
$domain = str_replace('/public/install', '', $domain)   ;
$tmpUrl = $_SERVER['REQUEST_SCHEME'].'://'.$_SERVER['HTTP_HOST'].$domain;

if (file_exists(__DIR__.'/../../config/Config.php')) {
    header('Location: '.$tmpUrl);
    die();
}
$args = [
    'db_host' => FILTER_SANITIZE_STRING,
    'db_user' => FILTER_SANITIZE_STRING,
    'db_pass' => FILTER_SANITIZE_STRING,
    'db_name' => FILTER_SANITIZE_STRING,
    'db_type' => FILTER_SANITIZE_STRING,
    'smtp_host' => FILTER_SANITIZE_STRING,
    'smtp_port' => FILTER_VALIDATE_INT,
    'smtp_user' => FILTER_SANITIZE_STRING,  
    'smtp_pass' => FILTER_SANITIZE_STRING,
    'smtp_certs' => FILTER_SANITIZE_STRING,
    'admin_username' => FILTER_SANITIZE_STRING,
    'admin_email' => FILTER_VALIDATE_EMAIL,
    'admin_pass' => FILTER_SANITIZE_STRING,
    'admin_confirm' => FILTER_SANITIZE_STRING,
    'ldap_uri' =>FILTER_SANITIZE_STRING,
    'ldap_base_dn' =>FILTER_SANITIZE_STRING,
    'ldap_bind_dn' =>FILTER_SANITIZE_STRING,
    'ldap_bind_pass' =>FILTER_SANITIZE_STRING,
    'ldap_filter' =>FILTER_SANITIZE_STRING,
    'ldap_port' =>FILTER_VALIDATE_INT,
];
$attribute = 'placeholder';
$POST = filter_input_array(INPUT_POST, $args, false);

if (isset($POST['db_host'])) {
    $attribute = 'value';
    $has_error = false;
    foreach($POST as $key => $value) {
        $index = explode('_', $key)[0];
        $var = $index.'_data';
        if (!isset($$var)) {
            $$var = [];
        }
        $$var[$key] = $value;
    }
    $test_db = test_db($db_data);
    $test_smtp = test_smtp($smtp_data);
    $test_admin = test_admin($admin_data);
    $test_ldap = test_ldap($ldap_data);

    if ($test_db === true
        && $test_smtp === true
        && $test_ldap === true
        && $test_admin === true) {
            create_database($db_data);
            insert_admin($admin_data, new ContributorDAO($db_data));
            create_config_file($POST);
            require __DIR__.'/../../app/Init.php';
            try{
                $credentials['username'] = $admin_data['admin_username'];
                $credentials['password'] = $admin_data['admin_pass'];
                $result = $auth->login($credentials);
                $admin = $contributor_dao->find('username', $credentials['username']);
                if ($admin !== false) {
                    $SESSION->setValue('id', $admin->getId());
                    $SESSION->setValue('role', $admin->getRole());
                }
                header('Location: '.$tmpUrl);   
            } catch (Exception $e){
                $ERROR = [
                    'message' => 'Veuillez transmettre l\'erreur suivante à un administrateur : '.$e->getMessage()
                ];
            }         
        }
}

$can_write = (!is_writable (__DIR__."/../../config")) ? false : true;

if (!isset($db_data)) {
    $db_data = [
        'db_host' => 'localhost',
        'db_user' => 'root',
        'db_pass' => '',
        'db_name' => 'openflow',
        'db_type' => 'mysql',
    ];
    $test_db = true;
}

if (!isset($smtp_data)) {
    $smtp_data = [
        'smtp_host' => 'smtps.example.com',
        'smtp_port' => 465,
        'smtp_user' => 'example@domain.com',
        'smtp_pass' => '',
        'smtp_certs' => 'off',
    ];
    $test_smtp = true;
}

if (!isset($admin_data)) {
    $admin_data = [
        'admin_username' => 'admin',
        'admin_email' => 'admin@domain.com',
        'admin_pass' => '',
        'admin_confirm' => '',
    ];
    $test_admin = true;
}

if (!isset($ldap_data)) {
    $ldap_data = [
        'ldap_uri' =>'ldap.example.fr',
        'ldap_base_dn' =>'ou=people,dc=example,dc=fr',
        'ldap_bind_dn' =>'uid=login,ou=people,dc=example,dc=fr',
        'ldap_bind_pass' =>'',
        'ldap_filter' =>'uid',
        'ldap_port' =>389,
    ];
    $test_ldap = true;
}

//Rendering
$renderer = renderers\Provider::get_renderer('install');
$renderer->set_attribute($attribute);
if ($can_write === false) {
    $renderer->disable();
}
$renderer->header('Installation')
            ->open_body([
                'form' => [
                    'page' => 'install',
                    'method' => 'POST'
                ],
                'div' => [
                    'class' => 'app-container'
                ],
            ])
            ->refresh($can_write)
            ->database_form($db_data, $test_db)
            ->ldap_form($ldap_data, $test_ldap)
            ->smtp_form($smtp_data, $test_smtp)
            ->admin_form($admin_data, $test_admin)
            ->close_body()
            ->footer()
            ->render();