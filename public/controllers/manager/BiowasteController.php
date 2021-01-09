<?php

$args = [
    'date' => FILTER_SANITIZE_STRING,
];
$argsGet = [
    'date' => FILTER_SANITIZE_STRING,
];


$GET = filter_input_array(INPUT_GET, $argsGet, false);
$POST = filter_input_array(INPUT_POST, $args, false);

$day = $POST['date'] ?? $GET['date'] ?? date('Y-m-d');
$restaurant = $restaurant_dao->find(['r_id' => $_SESSION['current-rest']]);

$biowastes = $biowastes_dao->select('SELECT *
						FROM biowastes
						WHERE bw_restaurant_id = '.$restaurant->getId().'
						ORDER BY bw_date DESC
						LIMIT 20', true);

$renderer->header('Biodéchets')
    ->open_body([
        [
            'tag' => 'div',
            'class' => 'content-center'
        ]
    ], $USER)
    ->previous_page('home')
    ->biowaste_graph($biowastes)
    ->close_body()
    ->footer()
    ->render();
