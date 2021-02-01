<?php
$args = [
    'date' => FILTER_SANITIZE_STRING,
];
$argsGet = [
    'date' => FILTER_SANITIZE_STRING,
    'meal' => FILTER_SANITIZE_STRING,
];


$GET = filter_input_array(INPUT_GET, $argsGet, false);
$POST = filter_input_array(INPUT_POST, $args, false);

$day = $POST['date'] ?? $GET['date'] ?? date('Y-m-d');
$restaurant = $restaurant_dao->find(['r_id' => $_SESSION['current-rest']]);

$current_meal = $GET['meal'];
$renderer->header('Convives dont P.A.I')
    ->open_body([
        [
            'tag' => 'div',
            'class' => 'content-center'
        ]
    ], $USER)
    ->previous_page('calendar')
    ->wip()
    ->close_body()
    ->footer()
    ->render();
