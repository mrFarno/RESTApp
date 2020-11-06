<?php

$args = [
    'search' => FILTER_VALIDATE_INT,
];

$POST = filter_input_array(INPUT_POST, $args, false);

if(isset($POST['search'])) {
    $equipment = $equipment_dao->find(['eq_id' => $POST['search']]);
    echo json_encode($equipment);
    die();
}

$renderer->header()
    ->open_body()
    ->equipment_form()
    ->close_body()
    ->footer()
    ->render();