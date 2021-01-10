<?php

use app\BO\Meal;

$args = [
    'date' => FILTER_SANITIZE_STRING,
    'declared' => FILTER_VALIDATE_INT,
    'meal_id' => FILTER_VALIDATE_INT,
];
$argsGet = [
    'date' => FILTER_SANITIZE_STRING,
    'meal' => FILTER_SANITIZE_STRING,
];


$GET = filter_input_array(INPUT_GET, $argsGet, false);
$POST = filter_input_array(INPUT_POST, $args, false);

$day = $POST['date'] ?? $GET['date'] ?? date('Y-m-d');
$restaurant = $restaurant_dao->find(['r_id' => $_SESSION['current-rest']]);

$current_meal = $GET['meal'] ?? array_keys($restaurant->getMeals())[0];

$meal = $meal_dao->find([
    'm_restaurant_id' => $restaurant->getId(),
    'm_type_id' => $current_meal,
    'm_date' => $day
]);

if ($meal === false) {
    $meal = new Meal([
        'm_restaurant_id' => $restaurant->getId(),
        'm_type_id' => $current_meal,
        'm_date' => $day
    ]);
    $meal_dao->persist($meal);
}

if(isset($POST['declared'])) {
    $meal->setAbsencesGuests($POST['declared']);
    $meal_dao->persist($meal);
}

$renderer->header('Service')
    ->open_body([
        [
            'tag' => 'div',
            'class' => 'content-center'
        ],
        [
            'tag' => 'form',
            'action' => 'index.php?page=service&meal='.$current_meal.'&date='.$day,
            'method' => 'POST',
        ]
    ], $USER)
    ->previous_page('dayly&date='.$day)
    ->service_form($meal)
    ->close_body()
    ->footer()
    ->render();