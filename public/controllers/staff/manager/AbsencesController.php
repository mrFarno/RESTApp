<?php

use app\BO\User;

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
    $substitute = null;
    $ab_employement = $employement_dao->find([
        'e_restaurant_id' => $restaurant->getId(),
        'e_user_id' => $POST['ab_user_id']
    ]);
    $ab = $absence_dao->find([
        'ab_employement_id' => $ab_employement['e_id'],
        'ab_date' => $POST['ab_date']
    ]);
    $ab = $ab !== false ? $ab['ab_id'] : null;
    $ab = $absence_dao->persist([
        'ab_id' => $ab,
        'ab_employement_id' => $ab_employement['e_id'],
        'ab_date' => $POST['ab_date']
    ]);
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
            'u_token' => generate_token(),
            'u_role' => 'employee'
        ];
        $substitute = new User($datas);
        $user_dao->persist($substitute);
        $employement_dao->persist([
            'e_restaurant_id' => $restaurant->getId(),
            'e_user_id' => $substitute->getId()
        ]);
        notify_new_user($substitute, $smtp_connector);
    }
    $employement = $employement_dao->find([
        'e_restaurant_id' => $restaurant->getId(),
        'e_user_id' => $substitute->getId()
    ]);
    $date = $POST['ab_date'];
    $affectationid = $meal_affectation_dao->select('SELECT * FROM meal_affectations
        INNER JOIN employements ON maf_employement_id = e_id
        WHERE e_restaurant_id = '.$restaurant->getId().'
        AND maf_meal_type = '.$POST['ab_mealtype_id'].'
        AND e_user_id = '.$POST['ab_user_id'].'
        AND (maf_timestart < "'.$date.' 12:00:00"'.' AND (maf_timeend IS NULL OR maf_timeend > "'.$date.' 12:00:00"'.'));')['maf_id'];
    if ($substitute !== null) {
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
    }

    header('Location: ?page=meals&date='.$date.'&current-meal='.$POST['ab_mealtype_id']);
}