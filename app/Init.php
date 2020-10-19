<?php

require __DIR__.'/Autoloader.php';
require __DIR__.'/../vendor/autoload.php';

app\Autoloader::register();

use app\Config;
use app\DAO\ContributorDAO;
use app\Session;
use Vespula\Auth\Auth;
use League\Container\Container;

$domain = str_replace($_SERVER['DOCUMENT_ROOT'],'',__DIR__);     
$domain = str_replace('/app', '', $domain)   ;
$GLOBALS['domain'] = $_SERVER['REQUEST_SCHEME'].'://'.$_SERVER['HTTP_HOST'].$domain;

//config.php instance
$config= Config::getInstance();

//DB connector
$db_connector=[
    'db_name'=>$config->get('db_name'),
    'db_host'=>$config->get('db_host'),
    'db_pass'=>$config->get('db_pass'),
    'db_user'=>$config->get('db_user'),
    'db_type'=>$config->get('db_type'),
];

//SMTP connector
$smtp_connector = [
    'smtp_host' => $config->get('smtp_host'),
    'smtp_port' => $config->get('smtp_port'),
    'smtp_user' => $config->get('smtp_user'),
    'smtp_pass' => $config->get('smtp_pass'),
    'smtp_certs' => $config->get('smtp_certs'),
];

//LDAP connector
$ldap_connector = [
    'ldap_uri' =>$config->get('ldap_uri'),
    'ldap_base_dn' =>$config->get('ldap_base_dn'),
    'ldap_bind_dn' =>$config->get('ldap_bind_dn'),
    'ldap_bind_pass' =>$config->get('ldap_bind_pass'),
    'ldap_filter' =>$config->get('ldap_filter'),
    'ldap_port' =>$config->get('ldap_port'),
];


$container = new Container();
$container->add('session', new Session());

$container->add('adapter', function () use ($db_connector) {
    return new ContributorDAO($db_connector);
});

try {
    $SESSION = $container->get('session');
    $adapter = $container->get('adapter');
    $auth = new Auth($adapter, $SESSION);
} catch (Exception $e) {
    $ERROR = [
        'message' => 'Erreur connexion'
    ];
}

$contributor_dao = new ContributorDAO($db_connector);