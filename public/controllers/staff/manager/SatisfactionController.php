<?php
use app\BO\Meal;

$args = [
    'date' => FILTER_SANITIZE_STRING,
    'meal_id' => FILTER_VALIDATE_INT,
];

for ($i = 1; $i < 11; $i++) {
    $args['sp_field_'.$i] = FILTER_SANITIZE_STRING;
}

$argsGet = [
    'date' => FILTER_SANITIZE_STRING,
    'meal' => FILTER_SANITIZE_STRING,
];


$GET = filter_input_array(INPUT_GET, $argsGet, false);
$POST = filter_input_array(INPUT_POST, $args, false);

$day = $POST['date'] ?? $GET['date'] ?? date('Y-m-d');
$restaurant = $restaurant_dao->find(['r_id' => $_SESSION['current-rest']]);

$current_meal = $GET['meal'];

if(isset($POST['sp_field_1'])
    || isset($POST['sp_field_2'])
    || isset($POST['sp_field_3'])
    || isset($POST['sp_field_4'])
    || isset($POST['sp_field_5'])
    || isset($POST['sp_field_6'])
    || isset($POST['sp_field_7'])
    || isset($POST['sp_field_8'])
    || isset($POST['sp_field_9'])
    || isset($POST['sp_field_10'])) {
    $fields = [];
    $index = 1;
    for ($i = 1; $i < 11; $i++) {
        if($POST['sp_field_'.$i] !== '') {
            $fields['sp_field_'.$index] = $POST['sp_field_'.$i];
            $index++;
        }
    }

    $meal = $meal_dao->find([
        'm_restaurant_id' => $restaurant->getId(),
        'm_type_id' => $current_meal,
        'm_date' => $day
    ]);

    if ($meal === false) {
        $meal = new Meal([
            'm_restaurant_id' => $restaurant->getId(),
            'm_type_id' => $current_meal,
            'm_date' => $day
        ]);
        $meal_dao->persist($meal);
    }
    $poll = $polls_dao->find(['sp_meal_id' => $meal->getId()]);
    if ($poll === false) {
        $p_id = $polls_dao->persist([
            'sp_meal_id' => $meal->getId(),
        ]);
    } else {
        $p_id = $poll['sp_id'];
    }
    $fields['sp_id'] = $p_id;
    $polls_dao->persist($fields);
}

$poll = $polls_dao->by_type_day($current_meal, $day);
if ($poll !== false) {
    $votes = $poll_votes_dao->find(['spv_poll_id'=>$poll['sp_id']]);
} else {
    $votes = [];
}

$stats = [];
foreach ($poll as $key => $value) {
    if(strpos($key, 'sp_field') !== false && $value !== null) {
        $stats[str_replace('sp', 'spv', $key)]['count'] = 0;
        $stats[str_replace('sp', 'spv', $key)]['sum'] = 0;
    }
}

foreach ($stats as $key => $value) {
    foreach ($votes as $vote) {
        if ($vote[$key] !== null) {
            $stats[$key]['count']++;
            (int) $stats[$key]['sum'] += (int) $vote[$key];
        }
    }

}

$renderer->header('Satisfaction des convives')
    ->open_body([
        [
            'tag' => 'div',
            'class' => 'content-center'
        ],
        [
            'tag' => 'form',
            'action' => 'index.php?page=satisfaction&meal='.$current_meal.'&date='.$day,
            'method' => 'POST',
        ]
    ], $USER)
    ->previous_page('service&current-meal='.$current_meal.'&date='.$day)
//    ->satisfaction()
    ->summary($day, $meal_types_dao->find(['mt_id' => $current_meal])['mt_name'])
    ->satisfaction_form($poll, $stats)
    ->close_body()
    ->footer()
    ->render();