<?php

$args = [
    'ab_user_id' => FILTER_VALIDATE_INT,
    'ab_mealtype_id' => FILTER_VALIDATE_INT,
    'ab_date' => FILTER_SANITIZE_STRING,
    'ab_substitute_id' => FILTER_VALIDATE_INT,
    'u_firstname' => FILTER_SANITIZE_STRING,
    'u_lastname' => FILTER_SANITIZE_STRING,
    'u_email' => FILTER_SANITIZE_STRING,
    'ab_comment' => FILTER_SANITIZE_STRING,
];
$restaurant = $restaurant_dao->find(['r_id' => $_SESSION['current-rest']]);

$POST = filter_input_array(INPUT_POST, $args, false);

if (isset($POST['ab_user_id'])) {
    if (isset($POST['ab_substitute_id'])) {
        $substitute = $user_dao->find(['u_id' => $POST['ab_substitute_id']]);
    } elseif ((isset($POST['u_firstname']) && $POST['u_firstname'] !== '')
        && (isset($POST['u_lastname']) && $POST['u_lastname'] !== '')
        && (isset($POST['u_email']) && $POST['u_email'] !== '')) {
        $datas = [
            'u_firstname' => $POST['u_firstname'],
            'u_lastname' => $POST['u_lastname'],
            'u_email' => $POST['u_email'],
            'u_password' => generate_token(),
            'u_token' => null,
            'u_role' => 'employee'
        ];
        $substitute = new User($datas);
        $user_dao->persist($substitute);
        $employement_dao->persist([
            'e_restaurant_id' => $restaurant->getId(),
            'e_user_id' => $substitute->getId()
        ]);
    }
    $employement = $employement_dao->find([
        'e_restaurant_id' => $restaurant->getId(),
        'e_user_id' => $substitute->getId()
    ]);
    $date = $POST['ab_date'];
    $affectationid = $affectation_dao->select('SELECT * FROM affectations
        INNER JOIN employements ON af_employement_id = e_id
        WHERE e_restaurant_id = '.$restaurant->getId().'
        AND af_meal_type = '.$POST['ab_mealtype_id'].'
        AND e_user_id = '.$POST['ab_user_id'].'
        AND (af_timestart < "'.$date.' 12:00:00"'.' AND (af_timeend IS NULL OR af_timeend > "'.$date.' 12:00:00"'.'));')['af_id'];
    $rp_id = $replacement_dao->find([
        'rp_affectation_id' => $affectationid,
        'rp_substitute_id' => $substitute->getId()
    ]);
    if ($rp_id !== false) {
        $rp_id = $rp_id['rp_id'];
    } else {
        $rp_id = null;
    }
    $replacement_dao->persist([
        'rp_id' => $rp_id,
        'rp_affectation_id' => $affectationid,
        'rp_substitute_id' => $substitute->getId(),
        'rp_comment' => $POST['ab_comment']
    ]);

    header('Location: ?page=meals&date='.$date.'&current-meal='.$POST['ab_mealtype_id']);
}