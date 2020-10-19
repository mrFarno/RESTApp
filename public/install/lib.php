<?php

use app\BO\Contributor;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
// require __DIR__.'/../../vendor/phpmailer/phpmailer/src/PHPMailer.php';

function test_db($data){
    try{
        new PDO($data['db_type'].':host='.$data['db_host'],$data['db_user'],$data['db_pass']);
        return true;
    }catch(PDOException $e){
        return $e->getMessage();
    }
}

function test_smtp($data){
    $mail = new PHPMailer(true);
    try {
        $mail->isSMTP();                                           
        $mail->Host       = $data['smtp_host']; 
        $mail->SMTPAuth   = true;   
        //$mail->SMTPDebug = 4 ;   
        $mail->Username   = $data['smtp_user'];
        $mail->Password   = $data['smtp_pass'];    
        $mail->SMTPSecure = 'ssl'; 
        if (isset($data['smtp_certs']) && $data['smtp_certs'] == 'on') {
            $mail->SMTPOptions = array(
                'ssl' => array(
                    'verify_peer' => false,
                    'verify_peer_name' => false,
                    'allow_self_signed' => true
                )
            );
        }      
        $mail->Port       = $data['smtp_port'];    
        $mail->setFrom($data['smtp_user'], 'Mailer');
        $mail->addAddress($data['smtp_user']);    
        $mail->isHTML(true);
        $mail->Subject = 'Test SMTP Openflow';
        $mail->Body    = 'SMTP ok';
        $mail->send();
        return true;
    } catch (Exception $e) {
        return "Error authenticate SMTP";
    }
}

function test_admin($data) {
    if (!filter_var($data['admin_email'], FILTER_VALIDATE_EMAIL)){
        return 'L\'email n\'est pas valide';
    }        
    //password size verify
    $length = strlen($data['admin_pass']);
    if ($length <= 7) {
        return 'Le mot de passe doit contenir un minimum de 8 caractères';
    } 
    //password input comparison
    $compare = strcmp($data['admin_confirm'], $data['admin_pass']);
    if ($compare != 0) {
        return 'Les deux mots de passe doivent être identiques';
    }
    return true;
}

function test_ldap($data){
    $ldaprdn  = $data['ldap_bind_dn'];     // ldap rdn or dn
    $ldappass = $data['ldap_bind_pass'];  // associated password

    // connect to ldap server
    $ldapconn = ldap_connect($data['ldap_uri']);

    // Set some ldap options for talking to
    ldap_set_option($ldapconn, LDAP_OPT_PROTOCOL_VERSION, 3);
    ldap_set_option($ldapconn, LDAP_OPT_REFERRALS, 0);

    if ($ldapconn) {
        // binding to ldap server
        $ldapbind = @ldap_bind($ldapconn, $ldaprdn, $ldappass);
        if($ldapbind == true){
            return true;
        }else{
            return "Error : Check LDAP DN / password";
        }
    }else{
        return "Error : Check LDAP uri";
    }
}

function create_database($data) {
    try {
        $pdo = new PDO($data['db_type'].":host=".$data['db_host'], $data['db_user'], $data['db_pass']);
        $fileSql = __DIR__.'/install.sql';
        $sqlContent = file_get_contents($fileSql);

        $dbName = $data['db_name'];
        $file = fopen($fileSql, 'r+');
        fwrite($file,
        "DROP DATABASE IF EXISTS $dbName;
        CREATE DATABASE $dbName
        CHARACTER SET utf8mb4
        COLLATE utf8mb4_unicode_ci;
        USE $dbName;
        ".$sqlContent);
        fclose($file);

        function executeQueryFile($fileSql, $bdd) {
            $query = file_get_contents($fileSql);
            $bdd->query($query);
        }
        executeQueryFile($fileSql, $pdo);

        $file = fopen($fileSql, 'w');
        fwrite($file, $sqlContent);
        fclose($file);

        return true;
    } catch (Exception $e) {
        return $e->getMessage();
    }
}

function insert_admin($admin_data, $contributor_dao) {
    $datas = [
        'login' => $admin_data['admin_username'],
        'password' => $admin_data['admin_pass'],
        'label' => $admin_data['admin_username'],
        'mail' => $admin_data['admin_email'],
        'role' => 'Admin'
    ];
    $admin = new Contributor($datas);

    $contributor_dao->persist($admin);
}

function create_config_file($datas) {
    $content = "<?php\r\n
    return [\r\n";
    foreach ($datas as $key => $value) {
        $content .= "\t\t\"$key\" =>     \"$value\",\r\n";
    }
    $content .= "];";
    $file = fopen(__DIR__."/../../config/Config.php", "w");
    fwrite($file, $content);
    //Close configuration:
    fclose($file);
}