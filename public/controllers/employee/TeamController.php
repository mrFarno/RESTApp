<?php
$args = [
    'date' => FILTER_SANITIZE_STRING,
    'ab_date' => FILTER_SANITIZE_STRING,
    'ab_dateend' => FILTER_SANITIZE_STRING,
    'ab_reason' => FILTER_SANITIZE_STRING,
];
$argsGet = [
    'date' => FILTER_SANITIZE_STRING,
];


$GET = filter_input_array(INPUT_GET, $argsGet, false);
$POST = filter_input_array(INPUT_POST, $args, false);

$restaurant = $restaurant_dao->find(['r_id' => $_SESSION['current-rest']]);

if (isset($POST['ab_date'])) {
    $e_id = $employement_dao->find([
        'e_user_id' => $USER->getId(),
        'e_restaurant_id' => $restaurant->getId()
    ]);
    $dateend = $POST['ab_dateend'] == '' ? null : $POST['ab_dateend'];
    $absence_dao->persist([
        'ab_employement_id' => $e_id['e_id'],
        'ab_date' => $POST['ab_date'],
        'ab_dateend' => $dateend,
        'ab_reason' => $POST['ab_reason'] ?? null
    ]);
    if (isset($_FILES['absence_pic'])) {
        upload($_FILES['absence_pic'], 'absences/user-'.$USER->getId());
    }
    header('Location: ?page=home');
    die();
}

$renderer->header('Déclarer une absence')
    ->open_body([
        [
            'tag' => 'div',
            'class' => 'content-center'
        ],
        [
            'tag' => 'form',
            'action' => 'index.php?page=team',
            'method' => 'POST',
            'enctype' => 'multipart/form-data'
        ],
    ], $USER)
    ->previous_page('home')
    ->absence_form()
    ->close_body()
    ->footer()
    ->render();
