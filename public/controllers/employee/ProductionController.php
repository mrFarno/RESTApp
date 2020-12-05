<?php

$args = [
    'date' => FILTER_SANITIZE_STRING,
    'rs_sample' => FILTER_SANITIZE_STRING,
    'search' => FILTER_VALIDATE_INT,
    'rs_end_cooking_tmp' => FILTER_VALIDATE_INT,
    'rs_refrigeration_tmp' => FILTER_VALIDATE_INT,
    'rs_end_refrigeration_tmp' => FILTER_VALIDATE_INT,
    'rs_id' => FILTER_VALIDATE_INT,
];
$argsGet = [
    'date' => FILTER_SANITIZE_STRING,
];


$GET = filter_input_array(INPUT_GET, $argsGet, false);
$POST = filter_input_array(INPUT_POST, $args, false);

$day = $POST['date'] ?? $GET['date'] ?? date('Y-m-d');

$restaurant = $restaurant_dao->find(['r_id' => $_SESSION['current-rest']]);

$current_meal = $GET['current-meal'] ?? $POST['current-meal'];

$rs_tasks = $task_affectation_dao->daily_tasks($USER, $restaurant, $day, [
    'table' => 'recipe_sheets',
    'prefix' => 'rs'
]);

foreach ($rs_tasks as $index => $rs_task) {
    $rs_tasks[$index]['meal'] = $meal_types_dao->find(['mt_id' => $rs_task['rs_meal_type']])['mt_name'];
}

$cols = [
    'rs_end_cooking_tmp' => [
        'number',
        'Température de fin de cuisson',
        'rs_end_cooking_tmp'
    ],
    'rs_refrigeration_tmp' => [
        'number',
        'Température de mise en cellule',
        'rs_refrigeration_tmp'
    ],
    'rs_end_refrigeration_tmp' => [
        'number',
        'Température de sortie de cellule',
        'rs_end_refrigeration_tmp'
    ],
    'rs_sample' => [
        'text',
        'Échantillon',
        'rs_sample'
    ],
];

if(isset($POST['search'])) {
    $product = $recipe_sheet_dao->find(['rs_id' => $POST['search']]);
    foreach ($product as $col => $value) {
        if ($value === null || $value === '') {
            echo json_encode($cols[$col]);
            die();
        }
    }
    echo json_encode('Le suivi de cette fiche technique est terminé');
    die();
}

foreach ($cols as $col => $value) {
    if(isset($POST[$col])) {
        switch ($col) {
            case 'rs_end_cooking_tmp':
            case 'rs_sample':
                $recipe_sheet_dao->persist([
                    'rs_id' => $POST['rs_id'],
                    $col => $POST[$col]
                ]);
                break;
            case 'rs_refrigeration_tmp':
            case 'rs_end_refrigeration_tmp':
                $hour_col = str_replace('tmp', 'hour', $col);
                $recipe_sheet_dao->persist([
                    'rs_id' => $POST['rs_id'],
                    $col => $POST[$col],
                    $hour_col => date('H:i')
                ]);
                break;
            default:
                break;
        }
        die();
    }
}

$renderer->set_day($day)
    ->header('Production')
    ->temperature_modal()
    ->open_body([
        [
            'tag' => 'div',
            'class' => 'content-center'
        ]
    ], $USER)
    ->previous_page('management&date='.$day.'&meal='.$current_meal)
    ->tasks_list($rs_tasks)
    ->close_body()
    ->footer()
    ->render();