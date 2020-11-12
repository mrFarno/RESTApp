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
        'Nom'
    ],
    'p_provider' => [
        'text',
        'Fournisseur'
    ],
    'p_stock' => [
        'number',
        'Stock'
    ],
    'p_aspect' => [
        'text',
        'Aspect'
    ],
    'p_temperature' => [
        'number',
        'Temperature'
    ],
    'p_sent_back' => [
        'checkbox',
        'Renvoyé'
    ],
    'p_photo' => [
        'file',
        'Photo'
    ]
];

if(isset($POST['search'])) {
    $product = $product_dao->find(['p_id' => $POST['search']]);
    foreach ($product as $col => $value) {
        if ($value = null || $value === '') {
            echo json_encode($cols[$col]);
            die();
        }
    }
    if (!is_file(__DIR__.'/../uploads/products/product-'.$product['p_id'])) {
        echo json_encode($cols['p_photo']);
        die();
    }
    echo 'Le suivi de cette marchandise est terminé';
    die();
}

foreach ($cols as $col) {
    if(isset($POST[$col])) {
        switch ($col) {
            case 'p_name':
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
            case 'p_photo':
                break;
        }
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
    ->products_modal($restaurant, $day)
    ->products_list($products)
    ->close_body()
    ->footer()
    ->render();
