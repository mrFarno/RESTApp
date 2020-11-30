<?php

$args = [
    'from' => FILTER_SANITIZE_STRING,
];

$POST = filter_input_array(INPUT_POST, $args, false);

if (isset($_FILES['user-pic'])) {
    upload($_FILES['user-pic'], 'users/user-'.$USER->getId());
    header('Location: ?page='.$POST['from']);
    die();
}