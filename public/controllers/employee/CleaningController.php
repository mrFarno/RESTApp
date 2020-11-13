<?php

$args = [
    'date' => FILTER_SANITIZE_STRING,
    'status' => FILTER_VALIDATE_INT
];
$argsGet = [
    'date' => FILTER_SANITIZE_STRING,
];


$GET = filter_input_array(INPUT_GET, $argsGet, false);
$POST = filter_input_array(INPUT_POST, $args, false);

$day = $POST['date'] ?? $GET['date'] ?? date('Y-m-d');

$restaurant = $restaurant_dao->find(['r_id' => $_SESSION['current-rest']]);

$eqs = $equipment_dao->find([
    'eq_restaurant_id' => $restaurant->getId()
], true);

$spaces = $space_dao->find([
    's_restaurant_id' => $restaurant->getId()
], true);

foreach ($eqs as $eq) {
    $task = $task_dao->find([
        't_target_id' => $eq['eq_id'],
        't_date' => $day
    ]);
    $type = $task_context_dao->find([
        'tc_id' => $eq['eq_id'],
    ])['tc_type'];
    if($task !== false) {
        $id = $task['t_id'];
//        $t_affectations = $task_affectation_dao->find([
//            'ta_task_id' => $id,
//        ], true);
    } else {
        $id = $task_dao->persist([
            't_target_id' => $eq['eq_id'],
            't_date' => $day
        ]);
        $task = $task_dao->find(['t_id' => $id]);
    }
    $regulars = $task_affectation_dao->user_frequents_affectations($USER, $eq['eq_id'], $day);
    $count = 0;
    foreach ($regulars as $regular) {
        if ($regular['t_date'] !== $day) {
            $count++;
        }
    }
    if (count($regulars) !== 0 && $count === count($regulars)) {
        $task_affectation_dao->persist([
            'ta_task_id' => $id ,
            'ta_employement_id' => reset($regulars)['e_id'],
            'ta_frequency' => reset($regulars)['ta_frequency'],
            'ta_dateend' => reset($regulars)['ta_dateend'],
            'ta_number' => reset($regulars)['ta_number'],
        ]);
    }
}

foreach ($spaces as $space) {
    $task = $task_dao->find([
        't_target_id' => $space['s_id'],
        't_date' => $day
    ]);
    $type = $task_context_dao->find([
        'tc_id' => $eq['eq_id'],
    ])['tc_type'];
    if($task !== false) {
        $id = $task['t_id'];
//        $t_affectations = $task_affectation_dao->find([
//            'ta_task_id' => $id,
//        ], true);
    } else {
        $id = $task_dao->persist([
            't_target_id' => $space['s_id'],
            't_date' => $day
        ]);
        $task = $task_dao->find(['t_id' => $id]);
    }
    $regulars = $task_affectation_dao->user_frequents_affectations($USER, $space['s_id'], $day);
    $count = 0;
    foreach ($regulars as $regular) {
        if ($regular['t_date'] !== $day) {
            $count++;
        }
    }
    if (count($regulars) !== 0 && $count === count($regulars)) {
        $task_affectation_dao->persist([
            'ta_task_id' => $id ,
            'ta_employement_id' => reset($regulars)['e_id'],
            'ta_frequency' => reset($regulars)['ta_frequency'],
            'ta_dateend' => reset($regulars)['ta_dateend'],
            'ta_number' => reset($regulars)['ta_number'],
        ]);
    }
}

if(isset($POST['status'])) {
    $task = $task_dao->find(['t_id' => $POST['status']]);
    $task['t_done'] = $task['t_done'] == 1 ? 0 : 1;
    $task_dao->persist($task);
    die();
}

$spaces_tasks = $task_affectation_dao->daily_tasks($USER, $restaurant, $day, [
    'table' => 'spaces',
    'prefix' => 's'
]);
$eq_tasks = $task_affectation_dao->daily_tasks($USER, $restaurant, $day, [
    'table' => 'equipments',
    'prefix' => 'eq'
]);

$renderer->header('Nettoyage et désinfection')
    ->open_body([
        [
            'tag' => 'div',
            'class' => 'content-center'
        ]
    ], $USER->getRole())
    ->previous_page('management&date='.$day)
    ->equipement_tasks_list($eq_tasks)
    ->spaces_tasks_list($spaces_tasks)
    ->close_body()
    ->footer()
    ->render();