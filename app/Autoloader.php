<?php
namespace app;

class Autoloader
{

    static function register()
    {
        spl_autoload_register(array(
            __CLASS__,
            'autoload'
        ));
    }

    static function autoload($class_name)
    {
        $app_path = str_replace('app', '', __DIR__);
        require $app_path.str_replace('\\', DIRECTORY_SEPARATOR, $class_name).'.php';
    }
}