<?php

namespace renderers;
use renderers\lib\Format;
require __DIR__.'/lib/lib.php';

abstract class BaseRenderer
{
    protected $opened_tags;
    protected $output;
    protected const STYLE_DIRECTORY = __DIR__.'/../public/style/';

    public function __construct()
    {
        $this->output = '';
        $this->opened_tags = [];
    }

    //--- HTML ---

    /**
     * Html header
     * @param string $title - Html title
     * @return self
     */
    public function header($title = 'Accueil'){
        $this->output .= '<!DOCTYPE html>
                            <html lang="fr" id="html">
                                <head>
                                <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.6.0/css/all.css" integrity="sha384-aOkxzJ5uQz7WBObEZcHvV5JvRW3TUc2rNPA7pe3AwnsUohiw1Vj2Rgx2KSOkF5+h" crossorigin="anonymous">';
        $this->style('css/lib')
            ->style('css/app');
        $this->output .= '<title>OpenFlow - '.$title.'</title>
            </head>';
        
        return $this;
    }
    /**
     * Html footer
     * @return self
     */
    public function footer(){
        $this->output .= '<footer>';
        $this->style('js/lib')
            ->style('js/app');
        $this->output .= '</footer>';

        return $this;
    }

    
    /**
     * display error
     * @param string $e Error message or true if no error 
     * @return self
     */
    public function error($passed) {
        if ($passed !== true) {
            $this->output .= '<div class="alert alert-danger alert-dismissible" role="alert">
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                <p style="text-align: center"><strong>'.$passed.'</strong></p>
            </div>';
        }
        return $this;
    }

    public function valid($valid){
        if ($valid !== false ) {
        $this->output .= '<div class="alert alert-success" role="alert">
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                <p style="text-align: center"><strong>'.$valid.'</strong></p>
                </div>';
        }
        return $this;
    }

    /**
     * @param string $subdirectory Style subdirectory name; base folder : public/style
     * @return self
     */
    private function style(string $subdirectory){
        $domain = str_replace($_SERVER['DOCUMENT_ROOT'],'',__DIR__);     
        $domain = str_replace('/renderers', '', $domain);
        $domain = $_SERVER['REQUEST_SCHEME'].'://'.$_SERVER['HTTP_HOST'].$domain;
        $styledirectory = self::STYLE_DIRECTORY.$subdirectory;
        $files = array_diff(scandir($styledirectory), array('..', '.'));
        foreach ($files as $stylefile) {
            if ($this->endsWith($stylefile, '.css')) {
                $this->output .= '<link rel="stylesheet" href="'.$domain.'/public/style/'.$subdirectory.'/'.$stylefile.'">';
            } else {
                $this->output .= '<script src="'.$domain.'/public/style/'.$subdirectory.'/'.$stylefile.'"></script>';
            }     
        }
        return $this;
    }
    /**
     * Redirect button to $from
     * @param string $from 
     * @return self
     */
    public function previous_page($from) {
        $this->output .= '<div class="d-flex flex-row w-100">
                                <div class="homeIcon justify-content-start">
                                    <a href="index.php?page='.$from.'">
                                        <i class="fas fa-arrow-left leftArrow" style="margin:0;"></i>
                                    </a>
                                </div>
                            </div>';
        return $this;
    }

    /**
     * Open body with optional params
     * @param array $tags : Optionnal tags - [Tag name] => array([attribute] => [value])
     * @return self
     */
    public function open_body(array $tags = []) {
        $this->output .='<body>';
        foreach ($tags as $tag => $attributes) {
            $this->output .= '<'.$tag;
            foreach ($attributes as $key => $value) {
                $this->output .= ' '.$key.'="'.$value.'"';
            }
            $this->output .= '>';
            $this->opened_tags[] = $tag;
        }

        return $this;
    }

    /**
     * Close body and optionnals tags opened in open_body()
     * @return self
     */
    public function close_body($USER = null) {
        if ($USER !== null) {
            $this->output .= '<a href="?page=logout">Déconnexion</a>';
        }
        $this->opened_tags = array_reverse($this->opened_tags);
        foreach ($this->opened_tags as $tag) {
            $this->output .= '</'.$tag.'>';
        }
        $this->output .= '</body>';
        return $this;
    }

    //--- ---

    /**
     * Format and display HTML contained in $this->output
     */
    public function render() {
        $format = new Format;
        echo $format->HTML($this->output);
    }

    /**
     * TODO : delete and use str_ends_with() when update to PHP 8
     */
    private function endsWith(string $haystack,string $needle ) {
        $length = strlen( $needle );
        if( !$length ) {
            return true;
        }
        return substr( $haystack, -$length ) === $needle;
    }
}