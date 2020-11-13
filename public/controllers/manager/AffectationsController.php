<?php

$args = [

];

$args = [
    'u_id' => FILTER_VALIDATE_INT,
    'r_id' => FILTER_VALIDATE_INT,
    'findaf_uid' => FILTER_VALIDATE_INT,
    'date' => FILTER_SANITIZE_STRING,
    'validform' => FILTER_SANITIZE_STRING,
    'search' => FILTER_VALIDATE_INT,
    't_target_id' => FILTER_VALIDATE_INT,
    't_user_id' => FILTER_VALIDATE_INT,
    't_done' => FILTER_SANITIZE_STRING,
    't_comment' => FILTER_SANITIZE_STRING,
    'delete' => FILTER_VALIDATE_INT,
    'ta_number' => FILTER_VALIDATE_INT,
    'day-1' => FILTER_VALIDATE_INT,
    'day-2' => FILTER_VALIDATE_INT,
    'day-3' => FILTER_VALIDATE_INT,
    'day-4' => FILTER_VALIDATE_INT,
    'day-5' => FILTER_VALIDATE_INT,
    'day-6' => FILTER_VALIDATE_INT,
    'day-7' => FILTER_VALIDATE_INT,
];
$argsGet = [
    'date' => FILTER_SANITIZE_STRING,
];

$restaurant = $restaurant_dao->find(['r_id' => $_SESSION['current-rest']]);
$employees = $employement_dao->employees_by_restaurant($restaurant->getId());
$meals = [];

foreach ($restaurant->getMeals() as $meal) {
    $meals[$meal] = $meal_types_dao->find(['mt_id' => $meal])['mt_name'];
    $args['mt-'.$meal] = FILTER_SANITIZE_STRING;
    $args['af_timestart-'.$meal] = FILTER_SANITIZE_STRING;
    $args['af_timeend-'.$meal] = FILTER_SANITIZE_STRING;
}
$GET = filter_input_array(INPUT_GET, $argsGet, false);
$POST = filter_input_array(INPUT_POST, $args, false);

$day = $POST['date'] ?? $GET['date'] ?? date('Y-m-d');

if (isset($POST['findaf_uid'])) {
    $employement = $employement_dao->find([
        'e_user_id' => $POST['findaf_uid'],
        'e_restaurant_id' => $_SESSION['current-rest']
    ]);
    echo json_encode($meal_affectation_dao->find(['maf_employement_id' => $employement['e_id']], true));
    die();
}

if (isset($POST['u_id'])) {
    $employement = $employement_dao->find([
        'e_user_id' => $POST['u_id'],
        'e_restaurant_id' => $_SESSION['current-rest']
    ]);
    foreach ($restaurant->getMeals() as $meal) {
        $af_id = $meal_affectation_dao->find([
            'maf_employement_id' => $employement['e_id'],
            'maf_meal_type' => $meal
        ]);
        if ($af_id !== false) {
            $af_id = $af_id['af_id'];
        } else {
            $af_id = null;
        }
        if (isset($POST['mt-'.$meal])) {
            $start = date($POST['af_timestart-'.$meal]);
            if ($POST['af_timeend-'.$meal] !== '') {
                $end = date($POST['af_timeend-'.$meal].' 23:59:59');
            } else {
                $end = null;
            }
            $meal_affectation_dao->persist([
                'maf_id' => $af_id,
                'maf_employement_id' => $employement['e_id'],
                'maf_meal_type' => $meal,
                'maf_timestart' => $start,
                'maf_timeend' => $end,
            ]);
        } else {
            if ($af_id !== null) {
                $meal_affectation_dao->delete($af_id);
            }
        }
    }
}

if(isset($POST['search'])) {
    $task = $task_dao->find([
        't_target_id' => $POST['search'],
        't_date' => $day
    ]);
    $type = $task_context_dao->find([
        'tc_id' => $POST['search'],
    ])['tc_type'];
    $responsibles = [];
    if($task !== false) {
        $id = $task['t_id'];
//        $t_affectations = $task_affectation_dao->find([
//            'ta_task_id' => $id,
//        ], true);
    } else {
        $id = $task_dao->persist([
            't_target_id' => $POST['search'],
            't_date' => $day
        ]);
        $task = $task_dao->find(['t_id' => $id]);
    }
    $regulars = $task_affectation_dao->frequents_affectations($POST['search'], $day);
    foreach ($regulars as $regular) {
        $e_id = $employement_dao->find([
            'e_user_id' => $regular['u_id'],
            'e_restaurant_id' => $restaurant->getId(),
        ])['e_id'];
        $task_affectation_dao->persist([
            'ta_task_id' => $id,
            'ta_employement_id' => $e_id,
            'ta_date' => $day,
            'ta_frequency' => $regular['ta_frequency'],
            'ta_dateend' => $regular['ta_dateend'],
            'ta_number' => $regular['ta_number'],
        ]);
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
    $employees = array_diff($users, $responsibles);
    foreach ($responsibles as $id => $responsible) {
        $absent = $user_dao->is_absent($id, $restaurant, $day) === true ? ' - <span class="absent">Absent</span>' : '';
        $responsibles[$id] .= $absent;
    }
    $renderer->modal_content($task, $employees, $responsibles, $type)
                ->render();

    die();
}

if(isset($POST['validform'])) {
    $task = $task_dao->find([
        't_target_id' => $POST['t_target_id'],
        't_date' => $day
    ]);
    switch ($POST['validform']) {
        case 'add_user':
            $frequency = '';
            for ($i = 1; $i < 8; $i++) {
                if (isset($POST['day-'.$i])) {
                    $frequency .= ':'.$i;
                }
            }
            $frequency = ltrim($frequency, ':');
            $employement = $employement_dao->find([
                'e_user_id' => $POST['t_user_id'],
                'e_restaurant_id' => $restaurant->getId(),
            ]);
            $task_affectation_dao->persist([
                'ta_task_id' => $task['t_id'],
                'ta_employement_id' => $employement['e_id'],
                'ta_number' => $POST['ta_number'] ?? null,
                'ta_frequency' => $frequency !== '' ? $frequency : null,
                'ta_date' => $day
            ]);
            break;
        case 'del_user_aff':
            $employement = $employement_dao->find([
                'e_user_id' => $POST['delete'],
                'e_restaurant_id' => $restaurant->getId(),
            ]);
            $t_af = $task_affectation_dao->find([
                'ta_task_id' => $task['t_id'],
                'ta_employement_id' => $employement['e_id'],
                'ta_date' => $day
            ]);
            $task_affectation_dao->delete($t_af['ta_id']);
            break;
        case 'update_task':
            $task_dao->persist([
                't_id' => $task['t_id'],
                't_done' => isset($POST['t_done']) ? 1 : 0,
                't_comment' => trim($POST['t_comment'])
            ]);
            break;
        default:
            break;
    }

    die();
}

$renderer->set_day($day)
            ->header('Gestion des affectations')
            ->open_body([
                [
                    'tag' => 'div',
                    'class' => 'content-center' 
                ],
                [
                    'tag' => 'form',
                    'action' => 'index.php?page=affectations',
                    'method' => 'POST'
                ],             
            ])
            ->employees_table($employees)
            ->user_modal($meals)
            ->close_body()
            ->footer()
            ->render();