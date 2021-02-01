<?php

$args = [
    'date' => FILTER_SANITIZE_STRING,
    'status' => FILTER_VALIDATE_INT,
    'done' => FILTER_VALIDATE_INT,
    't_id' => FILTER_VALIDATE_INT,
    't_id_comment' => FILTER_VALIDATE_INT,
    'done_hour' => FILTER_SANITIZE_STRING,
    'comment' => FILTER_SANITIZE_STRING,
];
$argsGet = [
    'date' => FILTER_SANITIZE_STRING,
    'current-meal' => FILTER_SANITIZE_STRING,
];


$GET = filter_input_array(INPUT_GET, $argsGet, false);
$POST = filter_input_array(INPUT_POST, $args, false);

$day = $POST['date'] ?? $GET['date'] ?? date('Y-m-d');

$restaurant = $restaurant_dao->find(['r_id' => $_SESSION['current-rest']]);

$current_meal = $GET['current-meal'] ?? array_keys($restaurant->getMeals())[0];

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
    $emp = $employement_dao->find([
        'e_user_id' => $USER->getId(),
        'e_restaurant_id' => $restaurant->getId()
    ]);
    $aff = $task_affectation_dao->find([
        'ta_task_id' => $POST['status'],
        'ta_employement_id' => $emp['e_id'],
    ]);
    $aff['ta_done'] = $aff['ta_done'] == 1 ? 0 : 1;
    $aff['ta_done_hour'] = date('H:i');
    $task_affectation_dao->persist($aff);
//    if ($task['t_controller'] === null || $task['t_controller'] === '') {
        $task['t_done_hour'] = date('H:i');
        $task_dao->persist($task);
//    }
    die();
}

if(isset($POST['done'])) {
    $task = $task_dao->find(['t_id' => $POST['done']]);
    $task['t_done'] = $task['t_done'] == 1 ? 0 : 1;
    $task['t_done_hour'] = date('H:i');
    $task_dao->persist($task);
    die();
}

if(isset($POST['done_hour'])) {
    $task = $task_dao->find(['t_id' => $POST['t_id']]);
    $emp = $employement_dao->find([
        'e_user_id' => $USER->getId(),
        'e_restaurant_id' => $restaurant->getId()
    ]);
    $aff = $task_affectation_dao->find([
        'ta_task_id' => $POST['t_id'],
        'ta_employement_id' => $emp['e_id'],
    ]);
    $aff['ta_done'] = 1;
    $aff['ta_done_hour'] = $POST['done_hour'];
    $task_affectation_dao->persist($aff);
//    if ($task['t_controller'] === null || $task['t_controller'] === '') {
        $task['t_done_hour'] = $POST['done_hour'];
        $task['t_done'] = 1;
        $task_dao->persist($task);
//    }
    die();
}

if(isset($POST['t_id_comment'])) {
    $task = $task_dao->find([
        't_id' => $POST['t_id_comment']
    ]);
    if ($task !== false) {
        echo $task['t_comment'];
    }
    die();
}

if(isset($POST['comment'])) {
    $task_dao->persist([
        't_id' => $POST['t_id'],
        't_comment' => $POST['comment']
    ]);
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

$controls = $task_dao->find([
    't_date' => $day,
    't_controller' => $USER->getId()
], true);

foreach ($controls as $index => $control) {
    $name = $space_dao->find(['s_id' => $control['t_target_id']]);
    if($name === false) {
        $name = $equipment_dao->find(['eq_id' => $control['t_target_id']])['eq_name'];
    } else {
        $name = $name['s_name'];
    }
    $controls[$index]['target'] = $name;
}

$renderer->set_day($day)
    ->header('Nettoyage et désinfection')
    ->controls_modal($controls)
    ->comments_modal($day)
    ->open_body([
        [
            'tag' => 'div',
            'class' => 'content-center'
        ]
    ], $USER)
    ->previous_page('management&date='.$day.'&meal='.$current_meal)
    ->equipement_tasks_list($eq_tasks)
    ->spaces_tasks_list($spaces_tasks)
    ->close_body()
    ->footer()
    ->render();