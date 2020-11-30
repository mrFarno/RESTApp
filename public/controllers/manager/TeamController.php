<?php

use app\BO\Restaurant;
use app\BO\User;

$argsGet = [
    'restid' => FILTER_VALIDATE_INT,
];

$args = [
    'u_firstname' => FILTER_SANITIZE_STRING,
    'u_lastname' => FILTER_SANITIZE_STRING,
    'u_email' => FILTER_SANITIZE_STRING,
    'u_id' => FILTER_VALIDATE_INT,
    'delete' => FILTER_VALIDATE_INT,
];

$GET = filter_input_array(INPUT_GET, $argsGet, false);
$POST = filter_input_array(INPUT_POST, $args, false);

// if (isset($GET['restid'])) {
//     $restaurant = $restaurant_dao->find('r_id', $GET['restid']);
// } else {
//     error_redirect('400', 'home');
// }

$restaurant = $restaurant_dao->find(['r_id' => $_SESSION['current-rest']]);

//if (isset($POST['u_id'])) {
//    $employement_dao->persist([
//        'e_restaurant_id' => $restaurant->getId(),
//        'e_user_id' => $POST['u_id']
//    ]);
//    $user = $user_dao->find(['u_id' => $POST['u_id']]);
//}

if ((isset($POST['u_firstname']) && $POST['u_firstname'] !== '')
    && (isset($POST['u_lastname']) && $POST['u_lastname'] !== '')
    && (isset($POST['u_email']) && $POST['u_email'] !== '')) {

    $user = $user_dao->find(['u_email' => $POST['u_email']]);
    if ($user === false) {
        $datas = [
            'u_firstname' => $POST['u_firstname'],
            'u_lastname' => $POST['u_lastname'],
            'u_email' => $POST['u_email'],
            'u_password' => generate_token(),
            'u_token' => generate_token(),
            'u_role' => 'employee'
        ];
        $user = new User($datas);
        $user_dao->persist($user);
        notify_new_user($user, $smtp_connector);
    }
    $employement_dao->persist([
        'e_restaurant_id' => $restaurant->getId(),
        'e_user_id' => $user->getId()
    ]);
}

if (isset($POST['delete'])) {
    $employement_dao->delete_by_user_restaurant($POST['delete'], $restaurant->getId());
}

//$employees = $user_dao->find(['u_role' => 'employee'], true);
$r_employees = $employement_dao->employees_by_restaurant($restaurant->getId());
$restaurants = [];
foreach ($r_employees as $employee) {
    $restaurants[$employee->getId()] = $restaurant_dao->restaurants_by_employee($employee);
}

rendering :
$renderer->header('Gestion de l\'équipe')
            ->team_modal($restaurant->getId())
            ->open_body([
                [
                    'tag' => 'div',
                    'class' => 'content-center' 
                ],
            ],  $USER)
            ->employees_list($r_employees, $restaurants, $restaurant->getId())
            ->home()
            ->close_body()
            ->footer()
            ->render();