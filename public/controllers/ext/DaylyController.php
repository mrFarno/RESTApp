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

$meal_types = [];
foreach ($restaurant->getMeals() as $meal) {
//    $meals[] = $meal_dao->find([
//        'm_restaurant_id' => $restaurant->getId(),
//        'm_type_id' => $meal,
//        'm_date' => $day
//    ]);
    $meal_types[$meal] = $meal_types_dao->find(['mt_id' => $meal])['mt_name'];
}

$renderer->header('Navigation')
    ->open_body([], $USER)
    ->previous_page('calendar')
    ->summary($day)
    ->meal_choice($meal_types, $day)
    ->close_body()
    ->footer()
    ->render();