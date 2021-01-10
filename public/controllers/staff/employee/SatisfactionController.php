<?php

$args = [
    'date' => FILTER_SANITIZE_STRING,
    'meal_id' => FILTER_VALIDATE_INT,
    'spv_poll_id' => FILTER_VALIDATE_INT,
];

for ($i = 1; $i < 11; $i++) {
    $args['spv_field_'.$i] = FILTER_SANITIZE_STRING;
}

$argsGet = [
    'date' => FILTER_SANITIZE_STRING,
    'meal' => FILTER_SANITIZE_STRING,
];


$GET = filter_input_array(INPUT_GET, $argsGet, false);
$POST = filter_input_array(INPUT_POST, $args, false);

$day = $POST['date'] ?? $GET['date'] ?? date('Y-m-d');
$restaurant = $restaurant_dao->find(['r_id' => $_SESSION['current-rest']]);

$current_meal = $GET['meal'];

if(isset($POST['spv_poll_id'])) {
    $poll_votes_dao->persist($POST);
}

$poll = $polls_dao->by_type_day($current_meal, $day);

$renderer->header('Satisfaction des convives')
    ->open_body([
        [
            'tag' => 'div',
            'class' => 'content-center'
        ],
        [
            'tag' => 'form',
            'action' => 'index.php?page=satisfaction&meal='.$current_meal.'&date='.$day,
            'method' => 'POST',
        ]
    ], $USER)
    ->previous_page('calendar')
    ->satisfaction_form($poll)
    ->close_body()
    ->footer()
    ->render();