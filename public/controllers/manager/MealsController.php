<?php

$args = [
    'date' => FILTER_SANITIZE_STRING,
    'form' => FILTER_SANITIZE_STRING,
];
$argsGet = [
    'date' => FILTER_SANITIZE_STRING,
];
$GET = filter_input_array(INPUT_GET, $argsGet, false);
$POST = filter_input_array(INPUT_POST, $args, false);

$day = $POST['date'] ?? $GET['date'] ?? null;
$restaurant = $restaurant_dao->find(['r_id' => $_SESSION['current-rest']]);
//$employees = $employement_dao->employees_by_restaurant($restaurant->getId());
$employees = $affectation_dao->find_users($restaurant->getId(), 2, $day, true);

$meal_types = [];
$meals = [];
foreach ($restaurant->getMeals() as $meal) {
//    $meals[] = $meal_dao->find([
//        'm_restaurant_id' => $restaurant->getId(),
//        'm_type_id' => $meal,
//        'm_date' => $day
//    ]);
    $meal_types[$meal] = $meal_types_dao->find(['mt_id' => $meal])['mt_name'];
}

if (isset($POST['form'])) {
    switch ($POST['form']) {
        case 'team':
            $params = $employees;
            break;
        case 'team_equipment' :
            $params = $team_equipment_dao->find(['te_restaurant_id' => $restaurant->getId()])['te_stock'];
            break;
        default: $params = null;
            break;
    }
    $form = $POST['form'].'_form';
    $renderer->$form($params)
                ->render();
    die();
}

$renderer->set_day($day)
    ->header()
    ->open_body([
        [
            'tag' => 'div',
            'class' => 'content-center'
        ],
        [
            'tag' => 'form',
            'action' => 'index.php?page=restaurants',
            'method' => 'POST'
        ]
    ])
    ->dropdown($meal_types)
    ->checks_navigation()
    ->team_form($employees)
//    ->home()
    ->close_body()
    ->footer()
    ->render();