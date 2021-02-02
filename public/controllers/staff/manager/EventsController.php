<?php

$args = [
    'date' => FILTER_SANITIZE_STRING,
    'validform' => FILTER_SANITIZE_STRING,
    'search' => FILTER_VALIDATE_INT,
    't_target_id' => FILTER_VALIDATE_INT,
    't_user_id' => FILTER_VALIDATE_INT,
    'delete' => FILTER_VALIDATE_INT,
    'ev_name' => FILTER_SANITIZE_STRING,
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

$meal_types = [];
foreach ($restaurant->getMeals() as $meal) {
//    $meals[] = $meal_dao->find([
//        'm_restaurant_id' => $restaurant->getId(),
//        'm_type_id' => $meal,
//        'm_date' => $day
//    ]);
    $meal_types[$meal] = $meal_types_dao->find(['mt_id' => $meal])['mt_name'];
}

$current_meal = $GET['current-meal'] ?? $POST['current-meal'] ?? array_keys($meal_types)[0];

if(isset($POST['ev_name']) && trim($POST['ev_name']) !== '') {
    $events_dao->persist([
        'ev_name' => $POST['ev_name'],
        'ev_restaurant_id' => $restaurant->getId(),
        'ev_date' => $day
    ]);
}

if(isset($POST['delete'])) {
    $events_dao->delete($POST['delete']);
}

if(isset($POST['search'])) {
    $event = $recipe_sheet_dao->find(['ev_id' => $POST['search']]);
    die();
}

$renderer->set_day($day)
    ->header('Animation/Évenementiel')
    ->events_modal()
    ->comments_modal($day)
    ->open_body([
        [
            'tag' => 'div',
            'class' => 'content-center'
        ],
        [
            'tag' => 'form',
            'action' => 'index.php?page=events',
            'method' => 'POST'
        ]
    ],  $USER)
    ->previous_page('management&date='.$day.'&meal='.$current_meal)
    ->summary($day)
    ->events_form($events_dao->find([
        'ev_restaurant_id' => $restaurant->getId(),
        'ev_date' => $day
    ], true), $current_meal)
    ->close_body()
    ->footer()
    ->render();