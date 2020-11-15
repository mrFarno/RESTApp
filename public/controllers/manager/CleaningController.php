<?php

$args = [
    'date' => FILTER_SANITIZE_STRING,
    'validform' => FILTER_SANITIZE_STRING,
    'search' => FILTER_VALIDATE_INT,
    't_target_id' => FILTER_VALIDATE_INT,
    't_user_id' => FILTER_VALIDATE_INT,
    't_done' => FILTER_SANITIZE_STRING,
    't_comment' => FILTER_SANITIZE_STRING,
    'delete' => FILTER_VALIDATE_INT
];
$argsGet = [
    'date' => FILTER_SANITIZE_STRING,
];


$GET = filter_input_array(INPUT_GET, $argsGet, false);
$POST = filter_input_array(INPUT_POST, $args, false);

$day = $POST['date'] ?? $GET['date'] ?? date('Y-m-d');
$restaurant = $restaurant_dao->find(['r_id' => $_SESSION['current-rest']]);

//if(isset($POST['search'])) {
//    $task = $task_dao->find([
//        't_target_id' => $POST['search'],
//    ]);
//    $responsibles = [];
//    if($task !== false) {
//        $id = $task['t_id'];
////        $t_affectations = $task_affectation_dao->find([
////            'ta_task_id' => $id,
////        ], true);
//    } else {
//        $id = $task_dao->persist([
//            't_target_id' => $POST['search'],
//        ]);
//        $done = 0;
//        $task = $task_dao->find(['t_id' => $id]);
//    }
//    $t_affectations = $task_affectation_dao->find_by_id_date($id, $day);
//    foreach ($t_affectations as $t_affectation) {
//        $employement = $employement_dao->find(['e_id' => $t_affectation['ta_employement_id']]);
//        $user = $user_dao->find(['u_id' => $employement['e_user_id']]);
//        $responsibles[$user->getId()] = $user->getFirstname().' '.$user->getLastname();
//    }
//    $done = $task['t_done'];
//    $employees = $employement_dao->employees_by_restaurant($restaurant->getId(), true);
//    $users = [];
//    foreach ($employees as $employee) {
//        $users[$employee->getId()] = $employee->getFirstname().' '.$employee->getLastname();
//    }
//
//
//    echo json_encode([
//        'employees' => array_diff($users, $responsibles),
//        'responsibles' => $responsibles,
//        'done' => $done,
//        'comment' => $task['t_comment']
//    ]);
//    die();
//}
//
//if(isset($POST['validform'])) {
//    $task = $task_dao->find([
//        't_target_id' => $POST['t_target_id'],
//    ]);
//    switch ($POST['validform']) {
//        case 'add_user':
//            $employement = $employement_dao->find([
//                'e_user_id' => $POST['t_user_id'],
//                'e_restaurant_id' => $restaurant->getId(),
//            ]);
//            $task_affectation_dao->persist([
//                'ta_task_id' => $task['t_id'],
//                'ta_employement_id' => $employement['e_id'],
//                'ta_date' => $day
//            ]);
//            break;
//        case 'del_user_aff':
//            $employement = $employement_dao->find([
//                'e_user_id' => $POST['delete'],
//                'e_restaurant_id' => $restaurant->getId(),
//            ]);
//            $t_af = $task_affectation_dao->find([
//                'ta_task_id' => $task['t_id'],
//                'ta_employement_id' => $employement['e_id'],
//                'ta_date' => $day
//            ]);
//            $task_affectation_dao->delete($t_af['ta_id']);
//            break;
//        case 'update_task':
//            $task_dao->persist([
//                't_id' => $task['t_id'],
//                't_done' => isset($POST['t_done']) ? 1 : 0,
//                't_comment' => trim($POST['t_comment'])
//            ]);
//            break;
//        default:
//            break;
//    }
//
//    die();
//}

$renderer->set_day($day)
    ->header('Nettoyage et désinfection')
    ->cleaning_modal()
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