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
        $can_access = ['login', 'success', 'error', 'reset', 'logout', 'calendar', 'signin'];
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
    $page = 'home';
}

if ($USER !== null && $USER->getRole() === 'manager') {
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
} elseif ($USER !== null && $USER->getRole() === 'employee') {
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
if (!file_exists(__DIR__.'/controllers/'.$controller)) {
    $controller = $USER->getRole().'/'.$controller;
    $page = $USER->getRole().'\\'.$page;
}
$renderer = renderers\Provider::get_renderer($page);
require __DIR__.'/controllers/'.$controller;