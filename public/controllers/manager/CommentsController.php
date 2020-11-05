<?php

$args = [
    'meal' => FILTER_VALIDATE_INT,
    'prefill' => FILTER_VALIDATE_INT,
    'step' => FILTER_SANITIZE_STRING,
    'content' => FILTER_SANITIZE_STRING
];

$restaurant = $restaurant_dao->find(['r_id' => $_SESSION['current-rest']]);

$POST = filter_input_array(INPUT_POST, $args, false);

if (isset($POST['meal'])) {
    $comment_id = $comment_dao->find(['mc_meal_id' => $POST['meal']]);
    if ($comment_id !== false) {
        $comment_id = $comment_id['mc_id'];
    } else {
        $comment_id = null;
    }
    $comment_dao->persist([
        'mc_id' => $comment_id,
        'mc_meal_id' => $POST['meal'],
        'mc_check_'.$POST['step'].'_comment' => $POST['content']
    ]);
    echo json_encode([
        'success',
        'Commentaire enregistré'
    ]);
    die();
}

if (isset($POST['prefill'])) {
    $content = $comment_dao->find(['mc_meal_id' => $POST['prefill']]);
    $content = $content !== false ? $content['mc_check_'.$POST['step'].'_comment'] : '';
    echo $content;
    die();
}