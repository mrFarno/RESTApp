<?php

$args = [
    'date' => FILTER_SANITIZE_STRING,
    'bw_production' => FILTER_VALIDATE_INT,
    'bw_bread' => FILTER_VALIDATE_INT,
    'bw_other' => FILTER_VALIDATE_INT,
    'bw_carton' => FILTER_VALIDATE_INT,
    'bw_package_other' => FILTER_VALIDATE_INT,
    'bw_green' => FILTER_VALIDATE_INT,
    'bw_valuation' => FILTER_VALIDATE_INT,
    'bw_comment' => FILTER_SANITIZE_STRING,
];
$argsGet = [
    'date' => FILTER_SANITIZE_STRING,
];

$GET = filter_input_array(INPUT_GET, $argsGet, false);
$POST = filter_input_array(INPUT_POST, $args, false);

$restaurant = $restaurant_dao->find(['r_id' => $_SESSION['current-rest']]);
$day = $POST['date'] ?? $GET['date'] ?? date('Y-m-d');
$notify = false;

if (isset($POST['bw_production'])
    || isset($POST['bw_bread'])
    || isset($POST['bw_other'])
    || isset($POST['bw_carton'])
    || isset($POST['bw_package_other'])
    || isset($POST['bw_green'])
    || isset($POST['bw_valuation'])
    || isset($POST['bw_comment'])) {
        $biowaste = $biowastes_dao->find([
            'bw_date' => $day,
            'bw_restaurant_id' => $restaurant->getId()]);
        if($biowaste !== false) {
            $id = $biowaste['bw_id'];
        } else {
            $id = $biowastes_dao->persist([
                'bw_date' => $day,
                'bw_restaurant_id' => $restaurant->getId(),
            ]);
        }
        $biowastes_dao->persist([
            'bw_id' => $id,
            'bw_production' => $POST['bw_production'] ?? null,
            'bw_bread' => $POST['bw_bread'] ?? null,
            'bw_other' => $POST['bw_other'] ?? null,
            'bw_carton' => $POST['bw_carton'] ?? null,
            'bw_package_other' => $POST['bw_package_other'] ?? null,
            'bw_green' => $POST['bw_green'] ?? null,
            'bw_valuation' => -abs($POST['bw_valuation']) ?? null,
            'bw_comment' => $POST['bw_comment'] ?? null,
        ]);
        $notify = 'Données sauvegardées';
}
$biowaste = $biowastes_dao->find([
    'bw_date' => $day,
    'bw_restaurant_id' => $restaurant->getId()]);
if ($biowaste === false) {
    $biowaste = [
        'bw_id' => '',
        'bw_production' => null,
        'bw_bread' => null,
        'bw_other' => null,
        'bw_carton' => null,
        'bw_package_other' => null,
        'bw_green' => null,
        'bw_valuation' => null,
        'bw_comment' => '',
    ];
}
$renderer->header('Biodéchets')
    ->open_body([
        [
            'tag' => 'div',
            'class' => 'content-center'
        ]
    ], $USER)
    ->previous_page('home')
    ->summary($day)
    ->biowaste_form($biowaste, $day)
    ->close_body()
    ->footer()
    ->notify($notify)
    ->render();
