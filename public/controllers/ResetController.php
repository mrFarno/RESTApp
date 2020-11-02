<?php
// require __DIR__.'/lib.php';

$argsGet = [
    'token' => FILTER_SANITIZE_STRING,
];

$args = [
    'password' => FILTER_SANITIZE_STRING,
    'confirm' => FILTER_SANITIZE_STRING,
];

$GET = filter_input_array(INPUT_GET, $argsGet, false);
$POST = filter_input_array(INPUT_POST, $args, false);

$ERROR['message'] = true;

if (isset($GET['token'])) {
    $user = $user_dao->find('u_token', $GET['token']);
    if ($user === false) {
        $ERROR['message'] = 'Token invalide';
    }
} else {
    error_redirect('400', 'home');
}

if (isset($POST['password'])) {
    $ERROR['message'] = check_passwords($POST['password'], $POST['confirm']);
    if ($ERROR['message'] === true) {
        $password = password_hash($POST['password'], PASSWORD_DEFAULT);
        $user->setPassword($password)
                ->setToken(null);
        $user_dao->persist($user);
        $credentials = [
            'username' => $user->getLogin(),
            'password' => $POST['password']
        ];
        $auth->login($credentials);
        header('Location: ?page=home');
    }
}

$from = 'reset';

// Rendering
$renderer->header('Réinitialiser mot de passe')
            ->open_body([
                [
                    'tag' => 'div',
                ]
            ])
            ->previous_page($from)
            ->error($ERROR['message'])
            ->reset_form($user)
            ->footer()
            ->close_body()
            ->render();