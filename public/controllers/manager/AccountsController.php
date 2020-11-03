<?php

use app\BO\User;

$args = [
    'label' => FILTER_SANITIZE_STRING,
    'mail' => FILTER_SANITIZE_EMAIL,    
    'login' => FILTER_SANITIZE_STRING,    
    'add' => FILTER_SANITIZE_STRING,
    'delete' => FILTER_SANITIZE_STRING,
    'from' => FILTER_SANITIZE_STRING,
    'find' => FILTER_VALIDATE_INT,
    'edit' => FILTER_VALIDATE_INT,
];

$POST = filter_input_array(INPUT_POST, $args, false);

$ERROR = [
    'message' => true
];
$VALID = [
    'message' => false
];

if (isset($POST['add'])) {  
    $datas = [
        'label' => $POST['label'],
        'login' => $POST['login'],
        'mail' => $POST['mail'],
        'role' => $POST['add'],
        'token' => generate_token(),
        'password' => generate_token()
    ];
    $user = new User($datas);
    try {
        if (!$user_dao->find(['u_email' => $user->getEmail()])) {
            $user_dao->persist($user);            
            $content = '<p>Ce mail a été envoyé automatiquement par RESTApp pour vous permettre de choisir votre mot de passe.
                    Merci de ne pas y répondre</p>
                    <a href = "'.$GLOBALS['domain'].'/public/index.php?page=reset&token='.$user->getToken().'">Cliquez sur ce lien pour réinitialiser votre mot de passe OpenFlow</a>';
            send_mail($user, 'Bienvenu sur RESTApp', $content, $smtp_connector);
            $VALID['message'] = 'L\'utilisateur a bien été ajouté';
        } else {
            $ERROR['message'] = 'Cette adresse mail est déja utilisée';
        }
    } catch (Exception $e) {
        $ERROR['message'] = 'Veuillez transmettre l\'erreur suivante à un administrateur : '.$e->getMessage();
    }
}

if (isset($POST['delete'])) {
    $admins = $user_dao->find('u_role', 'Admin', true);
    $to_delete = $user_dao->find(['u_id' => $POST['delete']]);
    if ($to_delete->getRole() === 'Admin' && count($admins) === 1) {
        $ERROR['message'] = 'Impossible de supprimer le seul administrateur du site';
    } else {
        $user_dao->delete($POST['delete']);
        $VALID['message'] = 'L\'utilisateur a bien été supprimé';
    }
}

// Ajax call to get user infos
if (isset($POST['find'])) {
    $user_info = $user_dao->find('u_id', $POST['find']);
    if ($user_info === false) {
        echo json_encode('Erreur');
        die();
    }
    echo json_encode([
        'label' => $user_info->getFirstname().' '.$user_info->getLastname(),
        'mail' => $user_info->getEmail(),
        'role' => $user_info->getRole()
    ]);
    die();
}

if (isset($POST['edit'])) {
    $user_edit = $contributor_dao->find('c_id', $POST['edit']);
    if ($user_edit === false) {
        $ERROR['message'] = 'Utilisateur introuvable';
    }
    $user_edit->setLabel($POST['label'])
                ->setLogin($POST['login'])
                ->setMail($POST['mail']);
    $contributor_dao->persist($user_edit);
    $VALID['message'] = 'L\'utilisateur a bien été mis à jour';
}

$admins = $user_dao->find('u_role', 'manager', true);
$contributors = $user_dao->find('u_role', 'employee', true);

$renderer->header('Administration : Gestion des comptes')
            ->open_body([
                [
                    'tag' => 'div',
                    'class' => 'app-container'
                ],
                [
                    'tag' => 'form',
                    'action' => 'index.php?page=accounts',
                    'method' => 'POST'
                ],
            ])
            ->previous_page($from)
            ->error($ERROR['message'])
            ->valid($VALID['message'])
            ->contributors_form($admins)
            ->contributors_form($contributors)     
            ->user_modal()  
            ->close_body()
            ->footer()
            ->render();