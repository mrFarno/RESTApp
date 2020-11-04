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

$renderer->header()
    ->open_body()
    ->links($day)
    ->close_body()
    ->footer()
    ->render();