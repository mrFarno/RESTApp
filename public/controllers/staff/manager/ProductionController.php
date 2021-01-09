<?php

$args = [
    'date' => FILTER_SANITIZE_STRING,
    'validform' => FILTER_SANITIZE_STRING,
    'search' => FILTER_VALIDATE_INT,
    't_target_id' => FILTER_VALIDATE_INT,
    't_user_id' => FILTER_VALIDATE_INT,
    'delete' => FILTER_VALIDATE_INT,
    'rs_name' => FILTER_SANITIZE_STRING,
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

if(isset($POST['rs_name']) && trim($POST['rs_name']) !== '') {
    $recipe_sheet_dao->persist([
        'rs_name' => $POST['rs_name'],
        'rs_meal_type' => $current_meal,
        'rs_restaurant_id' => $restaurant->getId(),
        'rs_date' => $day
    ]);
}

if(isset($POST['delete'])) {
    $recipe_sheet_dao->delete($POST['delete']);
}

if(isset($POST['search'])) {
    $sheet = $recipe_sheet_dao->find(['rs_id' => $POST['search']]);
    $sheet['ec_responsible'] = $user_dao->identify($sheet['rs_end_cooking_responsible']);
    $sheet['r_responsible'] = $user_dao->identify($sheet['rs_refrigeration_responsible']);
    $sheet['er_responsible'] = $user_dao->identify($sheet['rs_end_refrigeration_responsible']);
    $renderer->temperature_content($sheet)
                ->render();
    die();
}

$sheets = $recipe_sheet_dao->find([
    'rs_restaurant_id' => $restaurant->getId(),
    'rs_meal_type' => $current_meal,
    'rs_date' => $day
], true);

foreach ($sheets as $index => $sheet) {
    $sheets[$index]['done_parts'] = $recipe_sheet_dao->done_parts($sheet['rs_id']);
}

$renderer->set_day($day)
    ->header('Production')
    ->production_modal()
    ->temperature_modal()
    ->open_body([
        [
            'tag' => 'div',
            'class' => 'content-center'
        ],
        [
            'tag' => 'form',
            'action' => 'index.php?page=production',
            'method' => 'POST'
        ]
    ],  $USER)
    ->previous_page('management&date='.$day.'&meal='.$current_meal)
    ->production_form($sheets, $meal_types, $current_meal)
    ->close_body()
    ->footer()
    ->render();