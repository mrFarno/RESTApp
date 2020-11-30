<?php

$args = [
    'delete' => FILTER_VALIDATE_INT,
    's_name' => FILTER_SANITIZE_STRING,
    's_cleaning_instructions' => FILTER_SANITIZE_STRING,
];

$restaurant = $restaurant_dao->find(['r_id' => $_SESSION['current-rest']]);

$POST = filter_input_array(INPUT_POST, $args, false);

if(isset($POST['delete'])) {
    $space_dao->delete($POST['delete']);
}

if(isset($POST['s_name']) && $POST['s_name'] !== '') {
    $space_dao->persist([
        's_name' => $POST['s_name'],
        's_restaurant_id' => $restaurant->getId(),
        's_cleaning_instructions' => $POST['s_cleaning_instructions']
    ]);
}

$renderer->header('Locaux')
    ->open_body([
        [
            'tag' => 'div',
            'class' => 'content-center'
        ],
        [
            'tag' => 'form',
            'method' => 'POST',
            'action' => '?page=spaces'
        ]
    ],  $USER)
    ->spaces_form($space_dao->find(['s_restaurant_id' => $restaurant->getId()], true))
    ->close_body()
    ->footer()
    ->render();