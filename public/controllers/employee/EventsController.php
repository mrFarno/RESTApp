<?php

$args = [
    'date' => FILTER_SANITIZE_STRING,
    'search' => FILTER_VALIDATE_INT,
    'target' => FILTER_VALIDATE_INT,
    'ev_comment' => FILTER_SANITIZE_STRING,
    'current-meal' => FILTER_SANITIZE_STRING,
];
$argsGet = [
    'date' => FILTER_SANITIZE_STRING,
    'current-meal' => FILTER_SANITIZE_STRING,
];


$GET = filter_input_array(INPUT_GET, $argsGet, false);
$POST = filter_input_array(INPUT_POST, $args, false);

$day = $POST['date'] ?? $GET['date'] ?? date('Y-m-d');

$restaurant = $restaurant_dao->find(['r_id' => $_SESSION['current-rest']]);

$current_meal = $GET['current-meal'] ?? $POST['current-meal'] ?? array_keys($restaurant->getMeals())[0];

$ev_tasks = $task_affectation_dao->daily_tasks($USER, $restaurant, $day, [
    'table' => 'events',
    'prefix' => 'ev'
]);

if(isset($POST['target'])) {
    $events_dao->persist([
        'ev_id' => $POST['target'],
        'ev_comment' => $POST['ev_comment']
    ]);
    die();
}

$renderer->set_day($day)
    ->header('Animation/Évenementiel')
    ->open_body([
        [
            'tag' => 'div',
            'class' => 'content-center'
        ]
    ], $USER)
    ->previous_page('management&date='.$day.'&meal='.$current_meal)
    ->tasks_list($ev_tasks)
    ->close_body()
    ->footer()
    ->render();