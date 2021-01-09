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
    't_controller' => FILTER_SANITIZE_STRING,
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
        $search_start = $POST['af_timestart-'.$meal] == '' ? null : $POST['af_timestart-'.$meal];
        $search_end = $POST['af_timeend-'.$meal] == '' ? null : $POST['af_timeend-'.$meal];
        $af_id = $meal_affectation_dao->find([
            'maf_employement_id' => $employement['e_id'],
            'maf_meal_type' => $meal,
        ]);
        if ($af_id !== false) {
            $af_id = $af_id['maf_id'];
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
    $type = $task_context_dao->find([
        'tc_id' => $POST['search'],
    ])['tc_type'];
    if($task['t_controller'] !== null) {
        $controller = $user_dao->find(['u_id' => $task['t_controller']]);
    } else {
        $controller = false;
    }
    $task['controller'] = $controller;
    $regulars = $task_affectation_dao->frequents_affectations($POST['search'], $day);
    $count = 0;
    foreach ($regulars as $regular) {
        $e_id = $employement_dao->find([
            'e_user_id' => $regular['u_id'],
            'e_restaurant_id' => $restaurant->getId(),
        ])['e_id'];
        if ($regular['t_date'] !== $day) {
            $count++;
        }
    }
    if (count($regulars) !== 0 && $count === count($regulars)) {
        $task_affectation_dao->persist([
            'ta_task_id' => $id,
            'ta_employement_id' => reset($regulars)['e_id'],
            'ta_frequency' => reset($regulars)['ta_frequency'],
            'ta_dateend' => reset($regulars)['ta_dateend'],
            'ta_number' => reset($regulars)['ta_number'],
        ]);
    }
    $t_affectations = $task_affectation_dao->find_by_id_date($id, $day);
    foreach ($t_affectations as $t_affectation) {
        $employement = $employement_dao->find(['e_id' => $t_affectation['ta_employement_id']]);
        $user = $user_dao->find(['u_id' => $employement['e_user_id']]);
        $responsibles[$user->getId()] = $user->getFirstname().' '.$user->getLastname();
        if($type == 'production') {
            $responsibles[$user->getId()] .= '('.$t_affectation['ta_number'].' portions)';
        }
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
        case 'add_responsible':
            $task_dao->persist([
                't_id' => $task['t_id'],
                't_controller' => $POST['t_controller']
            ]);
            break;
        default:
            break;
    }

    die();
}

$renderer->set_day($day)
            ->header('Gestion des affectations')
            ->user_modal($meals)
            ->open_body([
                [
                    'tag' => 'div',
                    'class' => 'content-center' 
                ],
            ],  $USER)
            ->employees_table($employees)
            ->close_body()
            ->footer()
            ->render();