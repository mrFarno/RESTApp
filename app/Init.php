<?php

require __DIR__.'/Autoloader.php';
require __DIR__.'/../vendor/autoload.php';

app\Autoloader::register();

use app\Config;
use app\DAO\AffectationDAO;
use app\DAO\AbsenceDAO;
use app\DAO\SpaceDAO;
use app\DAO\TaskAffectationDAO;
use app\DAO\TaskDAO;
use app\DAO\UserDAO;
use app\DAO\RestaurantDAO;
use app\DAO\MealTypeDAO;
use app\DAO\MealDAO;
use app\DAO\RestaurantTypeDAO;
use app\DAO\EmployementDAO;
use app\DAO\TeamEquipmentDAO;
use app\DAO\CommentDAO;
use app\DAO\EquipmentDAO;
use app\DAO\SmallEquipmentDAO;
use app\DAO\ProductDAO;
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

$container = new Container();
$container->add('session', new Session());

$container->add('adapter', function () use ($db_connector) {
    return new UserDAO($db_connector);
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

$user_dao = new UserDAO($db_connector);
$restaurant_dao = new RestaurantDAO($db_connector);
$meal_types_dao = new MealTypeDAO($db_connector);
$restaurant_types_dao = new RestaurantTypeDAO($db_connector);
$employement_dao = new EmployementDAO($db_connector);
$affectation_dao = new AffectationDAO($db_connector);
$meal_dao = new MealDAO($db_connector);
$team_equipment_dao = new TeamEquipmentDAO($db_connector);
$absence_dao = new AbsenceDAO($db_connector);
$comment_dao = new CommentDAO($db_connector);
$equipment_dao = new EquipmentDAO($db_connector);
$small_equipment_dao  = new SmallEquipmentDAO($db_connector);
$product_dao = new ProductDAO($db_connector);
$space_dao = new SpaceDAO($db_connector);
$task_dao = new TaskDAO($db_connector);
$task_affectation_dao = new TaskAffectationDAO($db_connector);