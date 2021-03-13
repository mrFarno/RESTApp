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
    'date' => FILTER_SANITIZE_STRING,
    'delete' => FILTER_VALIDATE_INT,
    'm_delete' => FILTER_VALIDATE_INT,
];

$restaurant = $restaurant_dao->find(['r_id' => $_SESSION['current-rest']]);

$POST = filter_input_array(INPUT_POST, $args, false);

if (isset($POST['meal'])) {
    $comment_dao->persist([
        'mc_meal_id' => $POST['meal'],
        'mc_step' => $POST['step'],
        'mc_content' => $POST['content'],
        'mc_author' => $USER->getId(),
        'mc_date' => date('Y-m-d'),
        'mc_time' => date('H:i'),
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

if(isset($POST['m_delete'])) {
    $comment = $comment_dao->find([
        'mc_id' => $POST['m_delete']
    ]);
    if ($comment['mc_author'] == $USER->getId()) {
        $comment_dao->delete($POST['m_delete']);
        echo json_encode([
            'success',
            'Commentaire supprimé'
        ]);
    }
    die();
}

if (isset($POST['prefill'])) {
//    $content = $comment_dao->find(['mc_meal_id' => $POST['prefill']]);
//    $content = $content !== false ? $content['mc_check_'.$POST['step'].'_comment'] : '';
//    echo $content;
    $comments = $comment_dao->find([
        'mc_meal_id' => $POST['prefill'],
        'mc_step' => $POST['step'],
        'mc_date' => $POST['date']
    ], true);
    if (count($comments) > 0) {
        foreach ($comments as $index => $comment) {
            $author = $user_dao->find([
                'u_id' => $comment['mc_author']
            ]);
            if ($author->getId() == $USER->getId()) {
                $author_name = 'Vous';
            } else {
                $author_name = $author->getFirstname().' '.$author->getLastname();
            }
            $comments[$index]['mc_author_name'] = $author_name;
        }
    }
    $renderer->meal_comments_list(array_reverse($comments), $USER)
                ->render();
    die();
}