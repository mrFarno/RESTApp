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

$renderer->set_day($day)
    ->header('Nettoyage et désinfection')
    ->open_body([
        [
            'tag' => 'div',
            'class' => 'content-center'
        ]
    ])
    ->previous_page('management&date='.$day)
    ->list_equipments($equipment_dao->find(['eq_restaurant_id' => $restaurant->getId()], true))
    ->list_spaces($space_dao->find(['s_restaurant_id' => $restaurant->getId()], true))
    ->close_body()
    ->footer()
    ->render();