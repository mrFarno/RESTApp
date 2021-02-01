<?php

$args = [
    'date' => FILTER_SANITIZE_STRING,
    'search' => FILTER_VALIDATE_INT,
    'p_id' => FILTER_VALIDATE_INT,
    'p_stock' => FILTER_VALIDATE_INT,
    'p_temperature' => FILTER_SANITIZE_STRING,
    'p_name' => FILTER_SANITIZE_STRING,
    'p_provider' => FILTER_SANITIZE_STRING,
    'p_aspect' => FILTER_SANITIZE_STRING,
    'p_sent_back' => FILTER_SANITIZE_STRING,
    'affect' => FILTER_VALIDATE_INT,
];
$argsGet = [
    'date' => FILTER_SANITIZE_STRING,
    'current-meal' => FILTER_SANITIZE_STRING,
];


$GET = filter_input_array(INPUT_GET, $argsGet, false);
$POST = filter_input_array(INPUT_POST, $args, false);

$day = $POST['date'] ?? $GET['date'] ?? date('Y-m-d');

foreach ($restaurant->getMeals() as $meal) {
//    $meals[] = $meal_dao->find([
//        'm_restaurant_id' => $restaurant->getId(),
//        'm_type_id' => $meal,
//        'm_date' => $day
//    ]);
    $meal_types[$meal] = $meal_types_dao->find(['mt_id' => $meal])['mt_name'];
}

$current_meal = $GET['current-meal'] ?? array_keys($meal_types)[0];

$restaurant = $restaurant_dao->find(['r_id' => $_SESSION['current-rest']]);
$products = $product_dao->find([
    'p_restaurant_id' => $restaurant->getId(),
    'p_date' => $day
], true);

$cols = [
    'p_name' => [
        'text',
        'Nom',
        'p_name'
    ],
    'p_provider' => [
        'text',
        'Fournisseur',
        'p_provider'
    ],
    'p_stock' => [
        'number',
        'Stock',
        'p_stock'
    ],
    'p_aspect' => [
        'text',
        'Aspect',
        'p_aspect'
    ],
    'p_temperature' => [
        'text',
        'Temperature',
        'p_temperature'
    ],
    'p_sent_back' => [
        'checkbox',
        'Renvoyé',
        'p_sent_back'
    ],
    'p_photo' => [
        'file',
        'Photo',
        'p_photo'
    ]
];

if(isset($POST['search'])) {
    $product = $product_dao->find(['p_id' => $POST['search']]);
    foreach ($product as $col => $value) {
        if ($value === null || $value === '') {
            echo json_encode($cols[$col]);
            die();
        }
    }
    if (!is_file(__DIR__.'/../uploads/products/product-'.$product['p_id'].'.png')) {
        echo json_encode($cols['p_photo']);
        die();
    }
    echo json_encode('Le suivi de cette marchandise est terminé');
    die();
}

if(isset($POST['p_id'])
    && !isset($POST['p_name'])
    && !isset($POST['p_provider'])
    && !isset($POST['p_stock'])
    && !isset($POST['p_aspect'])
    && !isset($POST['p_temperature'])
    && !isset($POST['p_sent_back'])
    && !isset($_FILES['p_photo'])) {
    $product_dao->persist([
        'p_id' => $POST['p_id'],
        'p_sent_back' => '0'
    ]);
    die();
}

if(isset($POST['affect']) && $USER->getRole() === 'manager') {
    $employement = $employement_dao->find([
        'e_restaurant_id' => $restaurant->getId(),
        'e_user_id' => $POST['affect'],
    ]);
    $aff = $pra_dao->find(['pra_employement_id' => $employement['e_id'],]);
    if ($aff === false) {
        $pra_dao->persist([
            'pra_employement_id' => $employement['e_id'],
        ]);
    } else {
        $pra_dao->delete($aff['pra_id']);
    }
    die();
}

foreach ($cols as $col => $value) {
    if(isset($POST[$col])) {
        switch ($col) {
            case 'p_name':
                $id = $product_dao->persist([
                    'p_restaurant_id' => $restaurant->getId(),
                    'p_date' => $day,
                    $col => $POST[$col],
                ]);
                echo json_encode([
                    'p_id' => $id
                ]);
                break;
            case 'p_provider':
            case 'p_stock':
            case 'p_aspect':
            case 'p_temperature':
                $product_dao->persist([
                    'p_id' => $POST['p_id'],
                    $col => $POST[$col]
                ]);
                break;
            case 'p_sent_back':
                $product_dao->persist([
                    'p_id' => $POST['p_id'],
                    $col => isset($POST[$col]) ? 1 : 0
                ]);
                break;
            default:
                break;
        }
        die();
    }
}

if(isset($_FILES['p_photo'])) {
    $file = $_FILES['p_photo'];
    if(!is_file(__DIR__.'/../uploads/products/product-'.$POST['p_id'].'.png')) {
        imagepng(imagecreatefromstring(file_get_contents($file['tmp_name'])), __DIR__.'/../uploads/products/product-'.$POST['p_id'].'.png');
    }
}


$renderer->header('Marchandises')
    ->products_modal($day)
    ->affectations_modal($employement_dao->employees_by_restaurant($restaurant->getId()),
        $pra_dao->affectations_by_restaurant($restaurant->getId()),
        $USER->getRole() === 'staff::manager')
    ->open_body([
        [
            'tag' => 'div',
            'class' => 'content-center'
        ]
    ], $USER)
    ->previous_page('management&date='.$day.'&meal='.$current_meal)
    ->products_list(array_reverse($products), $USER->getRole() === 'staff::manager')
    ->close_body()
    ->footer()
    ->render();
