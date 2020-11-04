<?php

$args = [
    'username' => FILTER_SANITIZE_STRING,
    'firstname' => FILTER_SANITIZE_STRING,
    'lastname' => FILTER_SANITIZE_STRING,
    'password' => FILTER_SANITIZE_STRING,
    'confirm' => FILTER_SANITIZE_STRING,
];

$POST = filter_input_array(INPUT_POST, $args, false);

$ERROR = [
    'message' => true
];

// trying to login
if (isset($POST['username']) && isset($POST['password'])) {
    $credentials = [
        'username' => $POST['username'],
        'password' => $POST['password'],
    ];
    try {
        $user = $user_dao->find(['u_email' => $credentials['username']]);
    } catch (\PDOException $e) {
        $ERROR = [
            'message' => 'Veuillez transmettre l\'erreur suivante à un administrateur : '.$e->getMessage()
        ];
        goto rendering;
    }
    if ($user !== false) {
        $ERROR = [
            'message' => 'Cette adresse mail est déjà utilisée'
        ];
        goto rendering;
    } else {
        $check = check_passwords($POST['password'], $POST['confirm']);
        if ($check !== true) {
            $ERROR = [
                'message' => $check
            ];
            goto rendering;
        }
        $user = new \app\BO\User([
            'u_firstname' => $POST['firstname'],
            'u_lastname' => $POST['lastname'],
            'u_email' => $POST['username'],
            'u_password' => password_hash($POST['password'], PASSWORD_DEFAULT),
            'u_role' => 'manager'
        ]);
        $user_dao->persist($user);
    }
    try {
        $auth->login($credentials);
    } catch (\Vespula\Auth\Exception $e) {
        $ERROR = [
            'message' => $e->getMessage(),
        ];
        goto rendering;
    }
    header('Location: index.php?page=restaurants');
    exit();
}

rendering:
$renderer->header('Connexion')
    ->open_body([
        [
            'tag' => 'div',
        ],
    ], false)
    ->error($ERROR['message'])
    ->signin_form()
    ->close_body()
    ->footer()
    ->render();