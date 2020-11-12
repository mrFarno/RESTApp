<?php

$args = [
    'search' => FILTER_VALIDATE_INT,
    'form' => FILTER_SANITIZE_STRING,
    'validform' => FILTER_SANITIZE_STRING,
    'update' => FILTER_VALIDATE_INT,
    'type' => FILTER_SANITIZE_STRING,
    'stock' => FILTER_VALIDATE_INT,
    'failed' => FILTER_VALIDATE_INT,
    'delete' => FILTER_VALIDATE_INT,
];

$restaurant = $restaurant_dao->find(['r_id' => $_SESSION['current-rest']]);

$POST = filter_input_array(INPUT_POST, $args, false);

if(isset($POST['search'])) {
    $equipment = $equipment_dao->find(['eq_id' => $POST['search']]);
    echo json_encode($equipment);
    die();
}

if(isset($POST['validform'])) {
    switch ($POST['validform']) {
        case 'team_equipment':
            $args['te_name'] = FILTER_SANITIZE_STRING;
            $args['te_stock'] = FILTER_VALIDATE_INT;
            $args['te_kit_part'] = FILTER_SANITIZE_STRING;
            $POST = filter_input_array(INPUT_POST, $args, false);
            $team_equipment_dao->persist([
                'te_name' => $POST['te_name'],
                'te_stock' => $POST['te_stock'],
                'te_restaurant_id' => $restaurant->getId(),
                'te_kit_part' => isset($POST['te_kit_part']) ? '1' : '0'
            ]);
            if (isset($POST['te_kit_part'])) {
                $max = $team_equipment_dao->select('SELECT MAX(te_stock) as max FROM team_equipments WHERE te_kit_part = 1 AND te_restaurant_id = '.$restaurant->getId())['max'];
                if (intval($POST['te_stock']) > intval($max)) {
                    $max = $POST['te_stock'];
                }
                $team_equipment_dao->update('UPDATE team_equipments SET te_stock = '.$max.' WHERE te_kit_part = 1 AND te_restaurant_id = '.$restaurant->getId());
            }
            break;
        case 'equipment':
            $args['eq_name'] = FILTER_SANITIZE_STRING;
            $args['eq_mark'] = FILTER_SANITIZE_STRING;
            $args['eq_fail_contact'] = FILTER_SANITIZE_STRING;
            $args['eq_fail_instructions'] = FILTER_SANITIZE_STRING;
            $args['eq_cleaning_instructions'] = FILTER_SANITIZE_STRING;
            $POST = filter_input_array(INPUT_POST, $args, false);

            $equipment_dao->persist([
                'eq_name' => $POST['eq_name'],
                'eq_mark' => $POST['eq_mark'],
                'eq_fail_contact' => $POST['eq_fail_contact'],
                'eq_fail_instructions' => $POST['eq_fail_instructions'],
                'eq_cleaning_instructions' => $POST['eq_cleaning_instructions'],
                'eq_restaurant_id' => $restaurant->getId()
            ]);
            break;
        case 'cutlery':
            $args['se_name'] = FILTER_SANITIZE_STRING;
            $args['se_type'] = FILTER_SANITIZE_STRING;
            $args['se_stock'] = FILTER_VALIDATE_INT;
            $POST = filter_input_array(INPUT_POST, $args, false);

            $small_equipment_dao->persist([
                'se_name' => $POST['se_name'],
                'se_type' => $POST['se_type'],
                'se_stock' => $POST['se_stock'],
                'se_restaurant_id' => $restaurant->getId()
            ]);
            break;
        default:
            break;
    }
}

if(isset($POST['form'])) {
    switch ($POST['form']) {
        case 'team_equipment':
            $params = $team_equipment_dao->find(['te_restaurant_id' => $restaurant->getId()], true);
            break;
        case 'equipment':
            $params = $equipment_dao->find(['eq_restaurant_id' => $restaurant->getId()], true);
            break;
        case 'cutlery':
            $params = $small_equipment_dao->find(['se_restaurant_id' => $restaurant->getId()], true);
            break;
        default:
            break;
    }
    $form = $POST['form'].'_form';
    $renderer->$form($params)
                ->render();
    die();
}

if (isset($POST['update'])) {
    $stock = $POST['stock'];
    if ($POST['type'] == 'team_equipment') {
        $dao = $team_equipment_dao;
        $prefix = 'te_';
        $te = $dao->find(['te_id' => $POST['update']]);
        if ($te['te_kit_part'] == 1) {
            $team_equipment_dao->update('UPDATE team_equipments SET te_stock = '.$stock.' WHERE te_kit_part = 1 AND te_restaurant_id = '.$restaurant->getId());
//            $stock = $max;
        }
    } else {
        $dao = $small_equipment_dao;
        $prefix = 'se_';
    }
    $dao->persist([
        $prefix.'id' => $POST['update'],
        $prefix.'stock' => $stock
    ]);
    die();
}

if (isset($POST['failed'])) {
    $eq = $equipment_dao->find(['eq_id' => $POST['failed']]);
//    if ($eq['eq_failed'] == 0) {
//        $eq['eq_failed'] == 1;
//    } else {
//        $eq['eq_failed'] == 0;
//    }
    $eq['eq_failed'] = $eq['eq_failed'] == 0 ? 1 : 0;
    $equipment_dao->persist($eq);
    die();
}

if(isset($POST['delete'])) {
    $dao = $POST['type'].'_dao';
    $$dao->delete($POST['delete']);
    die();
}

$renderer->header('Inventaire')
    ->open_body([
        [
            'tag' => 'div',
            'class' => 'content-center'
        ]
    ])
    ->inventory_navigation()
    ->team_equipment_form($team_equipment_dao->find(['te_restaurant_id' => $restaurant->getId()], true))
    ->close_body()
    ->footer()
    ->render();