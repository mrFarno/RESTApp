<?php

namespace renderers;
use renderers\BaseRenderer;

class Provider {

    /**
     * @param string $page Page to load
     * @return BaseRenderer Renderer corresponding to $page
     */
    static function get_renderer($page) {
        $class = ucfirst($page).'Renderer';  
        if (!file_exists(__DIR__.'/'.$class.'.php')) {
            $class = 'ErrorRenderer';
        } 
        $renderer = 'renderers\\'.$class;
        return new $renderer();
    }
}