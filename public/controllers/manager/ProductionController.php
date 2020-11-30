<?php

$args = [
    'date' => FILTER_SANITIZE_STRING,
    'validform' => FILTER_SANITIZE_STRING,
    'search' => FILTER_VALIDATE_INT,
    't_target_id' => FILTER_VALIDATE_INT,
    't_user_id' => FILTER_VALIDATE_INT,
    'delete' => FILTER_VALIDATE_INT,
    'rs_meal_type' => FILTER_VALIDATE_INT,
    'rs_name' => FILTER_SANITIZE_STRING,
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

if(isset($POST['rs_name']) && $POST['rs_name'] !== '' && isset($POST['rs_meal_type']) && $POST['rs_meal_type'] !== '') {
    $recipe_sheet_dao->persist([
        'rs_name' => $POST['rs_name'],
        'rs_meal_type' => $POST['rs_meal_type'],
        'rs_restaurant_id' => $restaurant->getId(),
        'rs_date' => $day
    ]);
}

if(isset($POST['delete'])) {
    $recipe_sheet_dao->delete($POST['delete']);
}

if(isset($POST['search'])) {
    $sheet = $recipe_sheet_dao->find(['rs_id' => $POST['search']]);
    $renderer->temperature_content($sheet)
                ->render();
    die();
}

$renderer->set_day($day)
    ->header('Production')
    ->production_modal()
    ->temperature_modal()
    ->open_body([
        [
            'tag' => 'div',
            'class' => 'content-center'
        ]
    ],  $USER)
    ->previous_page('management&date='.$day)
    ->production_form($recipe_sheet_dao->find([
        'rs_restaurant_id' => $restaurant->getId(),
        'rs_date' => $day
    ], true), $meal_types)
    ->close_body()
    ->footer()
    ->render();