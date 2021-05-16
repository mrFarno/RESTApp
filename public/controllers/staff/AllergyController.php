<?php
$args = [
    'date' => FILTER_SANITIZE_STRING,
    'al_id' => FILTER_VALIDATE_INT,
    'al_firstname' => FILTER_SANITIZE_STRING,
    'al_lastname' => FILTER_SANITIZE_STRING,
    'al_age' => FILTER_SANITIZE_STRING,
    'allergen' => FILTER_VALIDATE_INT,
    'search' => FILTER_VALIDATE_INT,
];
$argsGet = [
    'date' => FILTER_SANITIZE_STRING,
    'meal' => FILTER_SANITIZE_STRING,
];

for ($i = 1; $i < 15; $i++) {
    $args['al_'.$i] = FILTER_VALIDATE_INT;
}


$GET = filter_input_array(INPUT_GET, $argsGet, false);
$POST = filter_input_array(INPUT_POST, $args, false);

$day = $POST['date'] ?? $GET['date'] ?? date('Y-m-d');
$restaurant = $restaurant_dao->find(['r_id' => $_SESSION['current-rest']]);

if (isset($POST['al_firstname'])) {
    $datas = [
        'al_firstname' => $POST['al_firstname'],
        'al_lastname' => $POST['al_lastname'],
        'al_age' => $POST['al_age'],
    ];
    if ($POST['al_id'] !== false) {
        $datas['al_id'] = $POST['al_id'];
    }
    for ($i = 1; $i < 15; $i++) {
        $checked = isset($POST['al_'.$i]) ? 1 : 0;
        $datas['al_'.$i] = $checked;
    }
    $allergies_dao->persist($datas);
}

//if (isset($POST['al_id'])) {
//    $allergy = $allergies_dao->find($POST['al_id']);
//    $allergy['al_'.$POST['allergen']] = $allergy['al_'.$POST['allergen']] == 1 ? 0 : 1;
//    $allergies_dao->persist($allergy);
//    die();
//}

if(isset($POST['search'])) {
    $allergy = $allergies_dao->find([
        'al_id' => $POST['search']
    ]);
    $checked = [];
    for ($i = 1; $i < 15; $i++) {
        if ($allergy['al_'.$i] == 1) {
            $checked[$i] = $i;
        }
    }
    $datas = [
        'id' => $allergy['al_id'],
        'firstname' => $allergy['al_firstname'],
        'lastname' => $allergy['al_lastname'],
        'age' => $allergy['al_age'],
        'checked' => $checked
    ];
    echo json_encode($datas);
    die();
}

$current_meal = $GET['meal'];
$renderer->header('P.A.I')
    ->allergies_modal($current_meal)
    ->open_body([
        ], $USER)
    ->previous_page('service&date='.$day.'&current-meal='.$current_meal)
//    ->links($current_meal, $day)
    ->allergies_list($allergies_dao->all(), $current_meal)
    ->close_body()
    ->footer()
    ->render();
