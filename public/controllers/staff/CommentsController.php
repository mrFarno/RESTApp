<?php

$args = [
    'meal' => FILTER_VALIDATE_INT,
    'prefill' => FILTER_VALIDATE_INT,
    'step' => FILTER_SANITIZE_STRING,
    'content' => FILTER_SANITIZE_STRING,
    'c_content' => FILTER_SANITIZE_STRING,
    'c_target' => FILTER_SANITIZE_STRING,
    't_target' => FILTER_VALIDATE_INT,
    't_date' => FILTER_SANITIZE_STRING,
    'delete' => FILTER_VALIDATE_INT,
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

if (isset($POST['c_target'])) {
    $comments_dao->persist([
        'c_author' => $USER->getId(),
        'c_target' => $POST['c_target'],
        'c_content' => $POST['c_content'],
        'c_date' => date('Y-m-d'),
        'c_time' => date('H:i'),
    ]);
    echo json_encode([
        'success',
        'Commentaire enregistré'
    ]);
    die();
}

if (isset($POST['t_target'])) {
    $task = $task_dao->find([
        't_target_id' => $POST['t_target'],
        't_date' => $POST['t_date']
    ]);
    if ($task === false) {
        $task_id = $task_dao->persist([
            't_target_id' => $POST['t_target'],
            't_date' => $POST['t_date']
        ]);
        $task = $task_dao->find([
            't_id' => $task_id
        ]);
    }
    $comments = $comments_dao->find([
        'c_target' => $task['t_id']
    ], true);
    if (count($comments) > 0) {
        foreach ($comments as $index => $comment) {
            $author = $user_dao->find([
                'u_id' => $comment['c_author']
            ]);
            if ($author->getId() == $USER->getId()) {
                $author_name = 'Vous';
            } else {
                $author_name = $author->getFirstname().' '.$author->getLastname();
            }
            $comments[$index]['c_author_name'] = $author_name;
        }
    }
    $renderer->comments_list(array_reverse($comments), $task, $USER)
                ->render();
    die();
}

if(isset($POST['delete'])) {
    $comment = $comments_dao->find([
        'c_id' => $POST['delete']
    ]);
    if ($comment['c_author'] == $USER->getId()) {
        $comments_dao->delete($POST['delete']);
        echo json_encode([
            'success',
            'Commentaire supprimé'
        ]);
    }
    die();
}

if (isset($POST['prefill'])) {
    $content = $comment_dao->find(['mc_meal_id' => $POST['prefill']]);
    $content = $content !== false ? $content['mc_check_'.$POST['step'].'_comment'] : '';
    echo $content;
    die();
}