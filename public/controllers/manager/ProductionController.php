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
    $task = $task_dao->find([
        't_target_id' => $POST['search'],
    ]);
    $responsibles = [];
    if($task !== false) {
        $id = $task['t_id'];
//        $t_affectations = $task_affectation_dao->find([
//            'ta_task_id' => $id,
//        ], true);
    } else {
        $id = $task_dao->persist([
            't_target_id' => $POST['search'],
        ]);
        $done = 0;
        $task = $task_dao->find(['t_id' => $id]);
    }
    $t_affectations = $task_affectation_dao->find_by_id_date($id, $day);
    foreach ($t_affectations as $t_affectation) {
        $employement = $employement_dao->find(['e_id' => $t_affectation['ta_employement_id']]);
        $user = $user_dao->find(['u_id' => $employement['e_user_id']]);
        $responsibles[$user->getId()] = $user->getFirstname().' '.$user->getLastname();
    }
    $done = $task['t_done'];
    $employees = $employement_dao->employees_by_restaurant($restaurant->getId(), true);
    $users = [];
    foreach ($employees as $employee) {
        $users[$employee->getId()] = $employee->getFirstname().' '.$employee->getLastname();
    }


    echo json_encode([
        'employees' => array_diff($users, $responsibles),
        'responsibles' => $responsibles,
        'done' => $done,
        'comment' => $task['t_comment']
    ]);
    die();
}

$renderer->set_day($day)
    ->header('Production')
    ->open_body([
        [
            'tag' => 'div',
            'class' => 'content-center'
        ],
        [
            'tag' => 'form',
            'method' => 'POST',
            'action' => '?page=production',
            'id' => 'step-form'
        ]
    ])
    ->production_modal()
    ->previous_page('management&date='.$day)
    ->production_form($recipe_sheet_dao->find([
        'rs_restaurant_id' => $restaurant->getId(),
        'rs_date' => $day
    ], true), $meal_types)
    ->close_body()
    ->footer()
    ->render();