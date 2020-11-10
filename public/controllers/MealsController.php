<?php

use app\BO\Meal;

$args = [
    'date' => FILTER_SANITIZE_STRING,
    'form' => FILTER_SANITIZE_STRING,
    'validform' => FILTER_SANITIZE_STRING,
    'current-meal' => FILTER_VALIDATE_INT,
];
$argsGet = [
    'date' => FILTER_SANITIZE_STRING,
];


$GET = filter_input_array(INPUT_GET, $argsGet, false);
$POST = filter_input_array(INPUT_POST, $args, false);

$day = $POST['date'] ?? $GET['date'] ?? date('Y-m-d');

$restaurant = $restaurant_dao->find(['r_id' => $_SESSION['current-rest']]);
//$employees = $employement_dao->employees_by_restaurant($restaurant->getId());


$meal_types = [];
$meals = [];
foreach ($restaurant->getMeals() as $meal) {
//    $meals[] = $meal_dao->find([
//        'm_restaurant_id' => $restaurant->getId(),
//        'm_type_id' => $meal,
//        'm_date' => $day
//    ]);
    $meal_types[$meal] = $meal_types_dao->find(['mt_id' => $meal])['mt_name'];
}

if (isset($POST['current-meal'])) {
    $renderer->set_meal($POST['current-meal']);
    $type_id = $POST['current-meal'];
} elseif(isset($GET['current-meal'])) {
    $renderer->set_meal($GET['current-meal']);
    $type_id = $GET['current-meal'];
} else {
    $renderer->set_meal(array_keys($meal_types)[0]);
    $type_id = array_keys($meal_types)[0];
}

// TODO $employees : employés non absents et remplaçants absencedao
$employees = $affectation_dao->find_users($restaurant->getId(), $type_id, $day, true);
$r_employees = $employement_dao->employees_by_restaurant($restaurant->getId());

$meal = $meal_dao->find([
    'm_restaurant_id' => $restaurant->getId(),
    'm_type_id' => $type_id,
    'm_date' => $day
]);

if ($meal === false) {
    $meal = new Meal([
        'm_restaurant_id' => $restaurant->getId(),
        'm_type_id' => $type_id,
        'm_date' => $day
    ]);
    $meal_dao->persist($meal);
}

if (isset($POST['validform'])) {
    switch ($POST['validform']) {
        case 'team':
            foreach ($employees as $employee) {
                $args[$employee->getId().'-present'] = FILTER_SANITIZE_STRING;
            }
            $POST = filter_input_array(INPUT_POST, $args, false);
            foreach ($employees as $employee) {
                if (!isset($POST[$employee->getId().'-present'])) {
                    $affectationid = $affectation_dao->select('SELECT * FROM affectations
                    INNER JOIN employements ON af_employement_id = e_id
                    WHERE e_restaurant_id = '.$restaurant->getId().'                    
                    AND af_meal_type = '.$type_id.'
                    AND e_user_id = '.$employee->getId().'
                    AND (af_timestart < "'.$day.' 12:00:00"'.' AND (af_timeend IS NULL OR af_timeend > "'.$day.' 12:00:00"'.'));')['af_id'];
                    $ab_id = $absence_dao->find([
                        'ab_affectation_id' => $affectationid,
                    ]);
                    if ($ab_id === false) {
                        $absence_dao->persist([
                            'ab_affectation_id' => $affectationid,
                        ]);
                    }
                }
            }
            break;
        case 'team_equipment' :
            $equipments = $team_equipment_dao->find(['te_restaurant_id' => $restaurant->getId()], true);
            foreach ($equipments as $equipment) {
                $args['missing-'.$equipment['te_id']] = FILTER_VALIDATE_INT;
            }
            $POST = filter_input_array(INPUT_POST, $args, false);
            $used_kits = 0;
            foreach ($equipments as $equipment) {
                if ($equipment['te_kit_part'] == 1) {
                    if ($POST['missing-'.$equipment['te_id']] >= $used_kits) {
                        $used_kits = $POST['missing-'.$equipment['te_id']];
                    }
                } else {
                    $team_equipment_dao->persist([
                        'te_id' => $equipment['te_id'],
                        'te_stock' => intval($equipment['te_stock']) - $POST['missing-'.$equipment['te_id']],
                    ]);
                }
            }
            if ($used_kits !== 0) {
                $team_equipment_dao->update('UPDATE team_equipments SET te_stock = te_stock - '.$used_kits.' WHERE te_kit_part = 1;');
            }
            break;
        case 'equipment' :
            $equipments = $equipment_dao->find(['eq_restaurant_id' => $restaurant->getId()], true);
            foreach ($equipments as $equipment) {
                $args['eq_'.$equipment['eq_id'].'_ok'] = FILTER_SANITIZE_STRING;
            }
            $POST = filter_input_array(INPUT_POST, $args, false);
            foreach ($equipments as $equipment) {
                $equipment_dao->persist([
                    'eq_id' => $equipment['eq_id'],
                    'eq_failed' => isset($POST['eq_'.$equipment['eq_id'].'_ok']) ? '0' : '1'
                ]);
            }
            break;
        case 'cutlery' :
            $equipments = $small_equipment_dao->find(['se_restaurant_id' => $restaurant->getId()], true);
            foreach ($equipments as $equipment) {
                $args['missing-'.$equipment['se_id']] = FILTER_VALIDATE_INT;
            }
            $POST = filter_input_array(INPUT_POST, $args, false);
            foreach ($equipments as $equipment) {
                $small_equipment_dao->persist([
                    'se_id' => $equipment['se_id'],
                    'se_stock' => intval($equipment['se_stock']) - $POST['missing-'.$equipment['se_id']],
                ]);
            }
            break;
        case 'products' :
            $args['p_name'] = FILTER_SANITIZE_STRING;
            $args['p_provider'] = FILTER_SANITIZE_STRING;
            $args['p_aspect'] = FILTER_SANITIZE_STRING;
            $args['p_temperature'] = FILTER_VALIDATE_INT;
            $args['p_sent_back'] = FILTER_SANITIZE_STRING;
            $POST = filter_input_array(INPUT_POST, $args, false);

            if ($POST['p_name'] !== '') {
                $product_dao->persist([
                    'p_meal_id' => $meal->getId(),
                    'p_name' => $POST['p_name'],
                    'p_provider' => $POST['p_provider'],
                    'p_aspect' => $POST['p_aspect'],
                    'p_temperature' => $POST['p_temperature'],
                    'p_sent_back' => isset($POST['p_sent_back']) ? 1 : 0,
                ]);
            }

            break;
        case 'guests' :
            $args['expected'] = FILTER_VALIDATE_INT;
            $args['absences'] = FILTER_VALIDATE_INT;
            $args['real'] = FILTER_VALIDATE_INT;
            $POST = filter_input_array(INPUT_POST, $args, false);
            $meal->setExpectedGuests($POST['expected'])
                    ->setAbsencesGuests($POST['absences'])
                    ->setRealGuests($POST['real']);
            break;
        default:
            break;
    }
    $meal_dao->persist($meal);
    die();
}

if (isset($POST['form'])) {
    switch ($POST['form']) {
        case 'team':
            $params = $employees;
            break;
        case 'team_equipment' :
            $params = $team_equipment_dao->find(['te_restaurant_id' => $restaurant->getId()], true);
            break;
        case 'equipment' :
            $params = $equipment_dao->find(['eq_restaurant_id' => $restaurant->getId()], true);
            break;
        case 'cutlery' :
            $params = $small_equipment_dao->find(['se_restaurant_id' => $restaurant->getId()], true);
            break;
        case 'products' :
            $params = $product_dao->find(['p_meal_id' => $meal->getId()], true);
            break;
        case 'guests' :
            $params = $meal;
            break;
        case 'comment' :
            $params = $comment_dao->find(['mc_meal_id' => $meal->getId()]);
            break;
        default: $params = null;
            break;
    }
    $form = $POST['form'].'_form';
    $renderer->$form($params)
                ->render();
    die();
}

$renderer->set_day($day)
    ->header('Repas')
    ->comment_modal($meal->getId())
    ->absences_modal($r_employees, $day)
    ->equipment_modal()
    ->open_body([
        [
            'tag' => 'div',
            'class' => 'content-center'
        ]
    ], $USER->getRole())
    ->previous_page('management&date='.$day)
    ->dropdown($meal_types, $day)
    ->checks_navigation()
    ->team_form($employees)
    ->close_body()
    ->footer()
    ->render();