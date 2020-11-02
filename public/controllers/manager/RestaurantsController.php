<?php

use app\BO\Restaurant;

$restaurant_types = $restaurant_types_dao->all();
$meal_types = $meal_types_dao->all();


$argsGet = [
    'edit' => FILTER_VALIDATE_INT,
    'delete' => FILTER_VALIDATE_INT,
];

$args = [
    'r_name' => FILTER_SANITIZE_STRING,
    'r_adress_street' => FILTER_SANITIZE_STRING,
    'r_adress_town' => FILTER_SANITIZE_STRING,
    'r_adress_country' => FILTER_SANITIZE_STRING,
    'r_adress_zip' => FILTER_VALIDATE_INT,
    'r_type_id' => FILTER_VALIDATE_INT,
    'current-rest' => FILTER_VALIDATE_INT,
    'from' => FILTER_SANITIZE_STRING,
];

foreach ($meal_types as $meal_type) {
    $args['mealtype_'.$meal_type['mt_id']] = FILTER_SANITIZE_STRING;
}
$GET = filter_input_array(INPUT_GET, $argsGet, false);
$POST = filter_input_array(INPUT_POST, $args, false);
$form_action = '';
$prefill = new Restaurant([]);
$edit = false;

if (isset($POST['current-rest'])) {
    $_SESSION['current-rest'] = $POST['current-rest'];
    $page = $POST['from'];
    if ($page === 'restaurants') {
        $page .= '&edit='.$_SESSION['current-rest'];
    }
    header('Location: ?page='.$page);
    die();
}

if(isset($GET['edit'])) {
    $GET['edit'] = $_SESSION['current-rest'];
    $edit = true;
    // if (!in_array($GET['edit'], $restaurant_dao->ids_by_manager($USER))) {
    //     error_redirect('401', $from);
    // }
    $action = [
        'title' => 'Mise à jour',
        'btn' => 'Mettre à jour',
        'btn-del' => '<a href="?page=restaurants&delete='.$GET['edit'].'">Supprimer</a>'
    ];
    $form_action = '&edit='.$GET['edit'];
    $prefill = $restaurant_dao->find('r_id', $GET['edit']);
} else if (isset($GET['delete'])) {
    // if (!in_array($GET['edit'], $restaurant_dao->ids_by_manager($USER))) {
    //     error_redirect('401', $from);
    // }
    $restaurant_dao->delete($GET['delete']);
    header('Location: ?page=home');
    die();
} else {
    $action = [
        'title' => 'Créer un restaurant',
        'btn' => 'Créer le restaurant',
        'btn-del' => '<a href="?page=home">Annuler</a>'
    ];
}
if(isset($POST['r_name'])) {
    $meals = [];
    foreach ($meal_types as $meal_type) {
        if (isset($POST['mealtype_'.$meal_type['mt_id']])) {
            $meals[] = $meal_type['mt_id'];
        }
    }
    $restaurant = new Restaurant($POST);
    $restaurant->setManager($USER)
                ->setMeals($meals);
    if (isset($GET['edit'])) {
        $restaurant->setId(intval($GET['edit']));
    } 
    $restaurant_dao->persist($restaurant);
    if ($edit === false) {
        header('Location: ?page=team&restid='.$restaurant->getId());
    }
    header('Location: ?page=home');
}
$renderer->header()
            ->open_body([
                [
                    'tag' => 'div',
                    'class' => 'content-center' 
                ],
                [
                    'tag' => 'form',
                    'action' => 'index.php?page=restaurants'.$form_action,
                    'method' => 'POST'
                ],             
            ])
            ->set_action($action)
            ->restaurant_form($prefill, $restaurant_types, $meal_types)
            ->close_body()
            ->footer()
            ->render();