<?php

use app\DAO\UserDAO;
use League\Container\Container;
use Vespula\Auth\Adapter\Ldap;
use Vespula\Auth\Auth;
use app\Session;
use PHPMailer\PHPMailer\PHPMailer;

// require __DIR__.'/lib.php';

// $contributor_dao = new ContributorDAO($db_connector);

$args = [
    'username' => FILTER_SANITIZE_STRING,
    'password' => FILTER_SANITIZE_STRING,
    'token' => FILTER_SANITIZE_STRING,
    'reset' => FILTER_VALIDATE_EMAIL,
    'from' => FILTER_SANITIZE_STRING,
];

$POST = filter_input_array(INPUT_POST, $args, false);

$ERROR = [
    'message' => true
];

// trying to login
if (isset($POST['username']) && isset($POST['password'])) {
    $from = $POST['from'];
    if (!$SESSION->verifyToken('login_admin', $POST['token'])) {
        $ERROR = [
            'message' => "Invalid Token"
        ];
    }
    $credentials = [
        'username' => $POST['username'],
        'password' => $POST['password'],
    ];
    try {
        $user_dao->find('u_email', $credentials['username']);
    } catch (\PDOException $e) {
        $ERROR = [
            'message' => 'Veuillez transmettre l\'erreur suivante à un administrateur : '.$e->getMessage()
        ];
        goto rendering;
    }
    try {
        $auth->login($credentials);
    } catch (\Vespula\Auth\Exception $e) {
        $ERROR = [
            'message' => $e->getMessage(),
        ];
        goto rendering;
    }
}

if($auth->isValid()){
    $from = $POST['from'] ?? $from;
    if (strpos($from,'login')) {
        $form = 'home';
    }
    header('Location: index.php?page='.$from);
    exit();   
}else{
    if (isset($POST['username'])) {
        $ERROR['message'] = 'Veuillez vérifier vos valeurs de connexion';
    }
    $token = $SESSION->generateToken('login_admin');
}

//reset password
if (isset($POST['reset']) && trim($POST['reset']) !== '') {  
    $user = $user_dao->find('u_email', $POST['reset']);
    if ($user === false) {
        echo json_encode([
            'error',
            'Aucun compte contributeur n\'est associé à cette adresse mail. Si vous avez oublié votre mot de passe LDAP veuillez contacter le service informatique'
        ]);
        die();
    }
    $token = generate_token();
    $user->setToken($token);
    $user_dao->persist($user);
    try{
        $content = '<p>Ce mail a été envoyé automatiquement par OpenFlow pour vous permettre de réinitialiser votre mot de passe.
                    Merci de ne pas y répondre</p>
                    <a href = "'.$GLOBALS['domain'].'/public/index.php?page=reset&token='.$user->getToken().'">Cliquez sur ce lien pour réinitialiser votre mot de passe OpenFlow</a>';
        send_mail($user, 'Réinitialisaton de votre mot de passe OpenFlow', $content, $smtp_connector);
    }catch(Exception $e){
        echo json_encode([
            'error',
            'Une erreur est survenue lors de l\'envoi du mail : '.$e->getMessage()
        ]);
        die();
    }
    echo json_encode([
            'success',
            'Un email a été envoyé à '.$user->getEmail()
        ]);
        die();
}

rendering :
$renderer->header('Connexion')
            ->open_body([
                'div' => [
                    'class' => 'app-container'
                ],
            ])
            ->previous_page($from)
            ->error($ERROR['message'])
            ->login_form($token, $from)
            ->close_body()
            ->footer()
            ->render();