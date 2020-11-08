<?php

$args = [
    'u_id' => FILTER_VALIDATE_INT,
    'r_id' => FILTER_VALIDATE_INT,
    'findaf_uid' => FILTER_VALIDATE_INT
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

$POST = filter_input_array(INPUT_POST, $args, false);

if (isset($POST['findaf_uid'])) {
    $employement = $employement_dao->find([
        'e_user_id' => $POST['findaf_uid'],
        'e_restaurant_id' => $_SESSION['current-rest']
    ]);
    echo json_encode($affectation_dao->find(['af_employement_id' => $employement['e_id']], true));
    die();
}

if (isset($POST['u_id'])) {
    $employement = $employement_dao->find([
        'e_user_id' => $POST['u_id'],
        'e_restaurant_id' => $_SESSION['current-rest']
    ]);
    foreach ($restaurant->getMeals() as $meal) {
        $af_id = $affectation_dao->find([
            'af_employement_id' => $employement['e_id'],
            'af_meal_type' => $meal
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
            $affectation_dao->persist([
                'af_id' => $af_id,
                'af_employement_id' => $employement['e_id'],
                'af_meal_type' => $meal,
                'af_timestart' => $start,
                'af_timeend' => $end,
            ]);
        } else {
            if ($af_id !== null) {
                $affectation_dao->delete($af_id);
            }
        }
    }
}

$renderer->header('Gestion des affectations')
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