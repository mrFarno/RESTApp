<?php

$args = [
    'date' => FILTER_SANITIZE_STRING,
    'search' => FILTER_VALIDATE_INT,
    'p_id' => FILTER_VALIDATE_INT,
    'p_stock' => FILTER_VALIDATE_INT,
    'p_temperature' => FILTER_VALIDATE_INT,
    'p_name' => FILTER_SANITIZE_STRING,
    'p_provider' => FILTER_SANITIZE_STRING,
    'p_aspect' => FILTER_SANITIZE_STRING,
    'p_sent_back' => FILTER_SANITIZE_STRING,
];
$argsGet = [
    'date' => FILTER_SANITIZE_STRING,
];


$GET = filter_input_array(INPUT_GET, $argsGet, false);
$POST = filter_input_array(INPUT_POST, $args, false);

$day = $POST['date'] ?? $GET['date'] ?? date('Y-m-d');

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
        'number',
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
    ->open_body([
        [
            'tag' => 'div',
            'class' => 'content-center'
        ]
    ], $USER->getRole())
    ->previous_page('management&date='.$day)
    ->products_modal($day)
    ->products_list($products)
    ->close_body()
    ->footer()
    ->render();
