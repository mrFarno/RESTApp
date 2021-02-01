<?php

$args = [
    'date' => FILTER_SANITIZE_STRING,
];
$argsGet = [
    'date' => FILTER_SANITIZE_STRING,
];


$GET = filter_input_array(INPUT_GET, $argsGet, false);
$POST = filter_input_array(INPUT_POST, $args, false);

$day = $POST['date'] ?? $GET['date'] ?? date('Y-m-d');
$restaurant = $restaurant_dao->find(['r_id' => $_SESSION['current-rest']]);

$year = (new DateTime($day))->format('Y');
$year_wastes = $biowastes_dao->by_year($year);
$year_total = 0;
foreach ($year_wastes as $biowaste) {
    $total = $biowaste['bw_production'] + $biowaste['bw_bread'] + $biowaste['bw_other'] + $biowaste['bw_carton'] + $biowaste['bw_package_other'] + $biowaste['bw_green'] + $biowaste['bw_valuation'];
    $year_total += $total;
}

$biowastes = $biowastes_dao->select('SELECT *
						FROM biowastes
						WHERE bw_restaurant_id = '.$restaurant->getId().'
						ORDER BY bw_date DESC
						LIMIT 20', true);

$renderer->header('Biodéchets')
    ->open_body([
        [
            'tag' => 'div',
            'class' => 'content-center'
        ]
    ], $USER)
    ->previous_page('home')
    ->biowaste_graph($biowastes)
    ->year_total($year_total, $year)
    ->close_body()
    ->footer()
    ->render();
