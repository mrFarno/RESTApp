<?php
require __DIR__.'/../app/Init.php';
require __DIR__.'/controllers/lib.php';
$args = [
    "page" => FILTER_SANITIZE_STRING
];
$GET = filter_input_array(INPUT_GET, $args, false);

// if (!isset($from)) {
//     $from = 'home';
// }
$USER = null;
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
//rooting
if(isset($GET['page'])) {
    $page = $GET['page'];
    if (strpos($page, '_')) {
        $parts = explode('_', $page);
        $page = '';
        foreach ($parts as $part) {
            $page .= ucfirst($part);
        }
    }
    if (!page_exist($page)) {
        error_redirect('404', $from);
    }
    // If is not connected
    if ($auth->isAnon() || $SESSION->getUserdata() === null) {
        // Page that can be visited without authentication
        $can_access = ['login', 'success', 'error', 'reset', 'logout', 'signin'];
        // Redirect to login
        if (!in_array($page, $can_access)) {
            $from = $page;
            $page = 'login';
        }
    } else {
        $USER = $SESSION->getUserData();
        // Check rights       
        if (!can_access($GET['page'], $USER)) {
            error_redirect('401', $from, $USER);
        }
    }
} else {
    $page = $auth->isAnon() || $SESSION->getUserdata() === null ? 'login' : 'home';
}

if ($USER !== null && $USER->getRole() === 'staff::manager') {
    $restaurants = $restaurant_dao->find(['r_manager_id' => $USER->getId()], true);
    $_SESSION['restaurants'] = [];
    if ($restaurants !== []) {
        foreach ($restaurants as $restaurant) {
            $_SESSION['restaurants'][$restaurant->getId()] = $restaurant->getName();
        }
        if (!isset($_SESSION['current-rest'])) {
            $_SESSION['current-rest'] = reset($restaurants)->getId();
        }
    }
} elseif ($USER !== null && ($USER->getRole() === 'staff::employee' || $USER->getRole() === 'ext')) {
    $restaurants = $restaurant_dao->restaurants_by_employee($USER);
    if ($restaurants !== []) {
        foreach ($restaurants as $restaurant) {
            $_SESSION['restaurants'][$restaurant->getId()] = $restaurant->getName();
        }
        if (!isset($_SESSION['current-rest'])) {
            $_SESSION['current-rest'] = reset($restaurants)->getId();
        }
    }
}

$page = $page === 'home' ? 'calendar' : $page;
// Load renderer and controller
$controller = ucfirst($page).'Controller.php';
$page = ucfirst($page);
$to_render = $page;
$to_load = $controller;

if($USER !== null) {
    $to_load = str_replace('::', DIRECTORY_SEPARATOR, $USER->getRole()).'/'.$controller;
    $to_render = str_replace('::', '\\', $USER->getRole()).'\\'.$page;
    if (!file_exists(__DIR__.'/controllers/'.$to_load)) {
        if(strpos($USER->getRole(), 'staff') !== false) {
            $to_load = 'staff/'.$controller;
            $to_render = 'staff\\'.$page;
        }
    }
}
if (!file_exists(__DIR__.'/controllers/'.$to_load)) {
    $to_load = $controller;
    $to_render = $page;
}
//if($USER !== null && strpos($USER->getRole(), 'ext') !== false) {
//    $controller = str_replace('::', DIRECTORY_SEPARATOR, $USER->getRole()).'/'.$controller;
//    $page = str_replace('::', '\\', $USER->getRole()).'\\'.$page;
//} elseif (!file_exists(__DIR__.'/controllers/'.$controller) && $USER !== null) {
//    $controller = $USER->getRole().'/'.$controller;
//    $page = $USER->getRole().'\\'.$page;
//}

$renderer = renderers\Provider::get_renderer($to_render);
require __DIR__.'/controllers/'.$to_load;