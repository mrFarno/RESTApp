<?php

$args = [
    'date' => FILTER_SANITIZE_STRING,
];
$argsGet = [
    'date' => FILTER_SANITIZE_STRING,
    'current-meal' => FILTER_SANITIZE_STRING,
];


$GET = filter_input_array(INPUT_GET, $argsGet, false);
$POST = filter_input_array(INPUT_POST, $args, false);

$day = $POST['date'] ?? $GET['date'] ?? date('Y-m-d');
$restaurant = $restaurant_dao->find(['r_id' => $_SESSION['current-rest']]);

$current_meal = $GET['current-meal'];

$renderer->header('Service')
    ->open_body([], $USER)
    ->previous_page('management&date='.$day.'&meal='.$current_meal)
    ->summary($day)
    ->links($current_meal, $day)
    ->close_body()
    ->footer()
    ->render();