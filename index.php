<?php

require __DIR__.'/vendor/autoload.php';
// require "app/Init.php";


if (!file_exists(__DIR__.'/config/Config.php')) {
    header('Location: '.$_SERVER['REQUEST_SCHEME'].'://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'].'public/install/');
    die;
}


header("Location: ".$_SERVER['REQUEST_SCHEME'].'://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI']."public/");
exit;