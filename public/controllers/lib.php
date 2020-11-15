<?php

use PHPMailer\PHPMailer\PHPMailer;

function notify_new_user($user, $smtp_connector) {
    $mail = new PHPMailer(true);
    try {
        $mail->isSMTP();
        $mail->CharSet = 'UTF-8';
        $mail->Host       = $smtp_connector['smtp_host'];
        $mail->SMTPAuth   = true;
        $mail->Username   = $smtp_connector['smtp_user'];
        $mail->Password   = $smtp_connector['smtp_pass'];
        $mail->SMTPSecure = 'ssl';
        if ($smtp_connector['smtp_certs'] == 'on') {
            $mail->SMTPOptions = array(
                'ssl' => array(
                    'verify_peer' => false,
                    'verify_peer_name' => false,
                    'allow_self_signed' => true
                )
            );
        }
        //$mail->SMTPDebug  = 4;
        $mail->Port       = $smtp_connector['smtp_port'];
        $mail->setFrom($smtp_connector['smtp_user'], 'Administration Good For Restau');
        $mail->addAddress($user->getEmail());
        $mail->isHTML(true);
        $mail->Subject = 'Invitation sur Good For Restau';
        $mail->Body = 'Bonjour,
        Vous avez été invité(e) à rejoindre l\'application Good For Restau. Veuille cliquer sur ce lien pour choisir un mot de passe : '.$GLOBALS['domain'].'/public/index.php?page=reset&token='.$user->getToken();
        $mail->send();
        return true;
    } catch (Exception $e) {
        throw new Exception($e->getMessage());
    }
}

function send_mail($user, $subject, $body, $smtp_connector) {
    // Instantiation and passing `true` enables exceptions
	$mail = new PHPMailer(true);
	try {
		$mail->isSMTP();   
		$mail->CharSet = 'UTF-8';
	    $mail->Host       = $smtp_connector['smtp_host'];
	    $mail->SMTPAuth   = true;
	    $mail->Username   = $smtp_connector['smtp_user']; 
	    $mail->Password   = $smtp_connector['smtp_pass']; 
		$mail->SMTPSecure = 'ssl';  
		if ($smtp_connector['smtp_certs'] == 'on') {
			$mail->SMTPOptions = array(
				'ssl' => array(
					'verify_peer' => false,
					'verify_peer_name' => false,
					'allow_self_signed' => true
				)
			);
		}
		//$mail->SMTPDebug  = 4;
	    $mail->Port       = $smtp_connector['smtp_port'];                                   
	    $mail->setFrom($smtp_connector['smtp_user'], 'Administration Good For Restau');
	    $mail->addAddress($user->getEmail());    
	    $mail->isHTML(true);                                 
	    $mail->Subject = $subject;
		$mail->Body = $body;
		$mail->send();
		return true;
	} catch (Exception $e) {
	    throw new Exception($e->getMessage());
	}
}

function generate_token()
{
    return sha1(uniqid(microtime(), true));
}

function check_passwords($password, $confirm) {
    $length = strlen($password);
    if ($length <= 7) {
        return 'Le mot de passe doit contenir un minimum de 8 caractères';
    } 
    //password input comparison
    $compare = strcmp($confirm, $password);
    if ($compare != 0) {
        return 'Les deux mots de passe doivent être identiques';
    }
    return true;
}

function error_redirect($error_code, $from, $user = null) {
	$USER = $user;
	$from = $from;
	$error_code = $error_code;
	$renderer = renderers\Provider::get_renderer('error');
	require __DIR__.'/ErrorController.php';
	die();
}

function page_exist($page) {
	$files = array_diff(scandir(__DIR__), array('..', '.'));
	$files = array_merge($files, array_diff(scandir(__DIR__.'/manager'), array('..', '.')));
	$files = array_merge($files, array_diff(scandir(__DIR__.'/employee'), array('..', '.')));
	return in_array(ucfirst($page).'Controller.php', $files);
}

function can_access($page, $USER) {
	if ($USER === null) {
		return true;
	}
	$can_access = [];
	$files = array_diff(scandir(__DIR__), array('..', '.', 'manager'));
	$files = array_merge($files, array_diff(scandir(__DIR__.'/'.$USER->getRole()), array('..', '.')));

	foreach ($files as $file) {
		if ($file !== 'lib.php') {
			$file = strtolower(preg_replace('/(?<!^)[A-Z]/', '_$0', $file));
			$can_access[] = strtolower(str_replace('_controller.php', '', $file));
		}
	}
	return in_array($page, $can_access);
}