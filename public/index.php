<?php

require __DIR__.'/../app/Init.php';
require __DIR__.'/controllers/lib.php';
$args = [
    "page" => FILTER_SANITIZE_STRING
];
$GET = filter_input_array(INPUT_GET, $args, false);

if (!isset($from)) {
    $from = 'home';
}
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
        $can_access = ['home', 'login', 'success', 'error', 'reset', 'logout', 'calendar'];
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
// Load renderer and controller
$renderer = renderers\Provider::get_renderer($page);
$controller = ucfirst($page).'Controller.php';   
if (!file_exists(__DIR__.'/controllers/'.$controller)) {
    $controller = 'manager/'.$controller;
}
require __DIR__.'/controllers/'.$controller;