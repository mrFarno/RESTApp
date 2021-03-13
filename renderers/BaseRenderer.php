<?php

namespace renderers;
use App\Session;
use League\Container\Container;
use renderers\lib\Format;
require __DIR__.'/lib/lib.php';

abstract class BaseRenderer
{
    protected $opened_tags;
    protected $output;
    protected const STYLE_DIRECTORY = __DIR__.'/../public/style/';
    protected $from;

    public function __construct()
    {
        $this->output = '';
        $this->opened_tags = [];
    }

    //--- HTML ---

    protected function navbar($USER) {
        $this->output .= '<nav class="navbar navbar-expand-lg navbar-light" style="background-color: #e5d9cc;">';
        if (isset($_SESSION['restaurants']) && count($_SESSION['restaurants']) !== 0) {
            if (is_file(__DIR__.'/../public/uploads/restaurants/photos/rest-'.$_SESSION['current-rest'].'.png')) {
//            $this->output .= '<img class="user-pic" src="'.$GLOBALS['domain'].'/public/uploads/users/user-'.$USER->getId().'.png">';
                $src = $GLOBALS['domain'].'/public/uploads/restaurants/photos/rest-'.$_SESSION['current-rest'].'.png';
                $src = '<img class="user-pic" src="'.$src.'" title="Accueil">';
            } else {
                $src = '<i title="Accueil" alt="Accueil" class="fas fa-home fa-2x"></i>';
            }
            $this->output .= '<a class="navbar-brand" href="?page=home">'.$src.'</a>';
            $this->output .= '<form action="?page=calendar" method="POST" class="form-inline my-2 my-lg-0" id="current-rest-form">
            <select onchange="update_current_rest()" name="current-rest" class="form-control mr-sm-2">';
            foreach ($_SESSION['restaurants'] as $id => $name) {
                $selected = $_SESSION['current-rest'] == $id ? ' selected' : '';
                $this->output .= '<option value="' . $id . '"' . $selected . '>' . $name . '</option>';
            }
            $this->output .= '</select>
            <input type="hidden" name="from" value="' . $this->from . '">
            </form>';
            $this->output .= '<button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
          <span class="navbar-toggler-icon"></span>
        </button>
      
        <div class="collapse navbar-collapse" id="navbarSupportedContent">
          <ul class="navbar-nav mr-auto">';
            switch ($USER->getRole()) {
                case 'staff::manager':
                    $this->output .= '<li class="nav-item dropdown ' . $this->active('restaurants') . $this->active('equipment') . $this->active('spaces') . '">
                    <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                      <i title="Mon restaurant" class="fas fa-cog"></i>
                    </a>                    
                    <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                      <a class="dropdown-item" href="?page=restaurants&edit">Informations générales</a>
                      <a class="dropdown-item" href="?page=equipment">Inventaire</a>
                      <a class="dropdown-item" href="?page=spaces">Locaux</a>                      
                    </div>
                  </li>
        
                    <li class="nav-item dropdown ' . $this->active('team') . $this->active('affectations') . '">
                    <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                      <i title="Mon équipe" class="fas fa-users-cog"></i>
                    </a>
                    <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                      <a class="dropdown-item" href="?page=team">Gestion de l\'équipe</a>
                      <a class="dropdown-item" href="?page=affectations">Affectations</a>
                    </div>
                  </li>
                  <li><a class="nav-link" href="?page=restaurants"><i title="Nouveau restaurant" alt="Nouveau restaurant" class="fas fa-plus-circle"></i></a></li>
                  </ul>';
                    break;
                case 'staff::employee':
                    $this->output .= '<li class="nav-item  '. $this->active('team') .'">
                    <a class="nav-link" href="?page=team">Déclarer une absence</a>
                  </li>
                </ul>';
                    break;
                default: $this->output.= '</li></ul>';
                    break;
            }
        } else {
            $this->output .= '<a class="navbar-brand" href="?page=home"><i title="Accueil" alt="Accueil" class="fas fa-home fa-2x"></i></a>';
            if ($USER->getRole() === 'staff::manager') {
                $this->output .= 'Créer un restaurant :
                <a style="color: black" class="nav-link" href="?page=restaurants"><i title="Nouveau restaurant" alt="Nouveau restaurant" class="fas fa-plus-circle"></i></a>';
            }
        }
//        $this->output .= '<div class="rest-choice">';
//        $this->output .= '</div>';
        $this->output .= 'Bienvenue '.$USER->getFirstname().' '.$USER->getLastname().'&nbsp';
        if (is_file(__DIR__.'/../public/uploads/users/user-'.$USER->getId().'.png')) {
//            $this->output .= '<img class="user-pic" src="'.$GLOBALS['domain'].'/public/uploads/users/user-'.$USER->getId().'.png">';
            $src = $GLOBALS['domain'].'/public/uploads/users/user-'.$USER->getId().'.png';
        } else {
            $src = $GLOBALS['domain'].'/public/style/resources/avatar.png';
        }
        $this->output .= '   
           <form action="?page=profile" method="POST" enctype="multipart/form-data" id="pic-form">
                <label class="user-pic" for="user-pic">
                    <img class="user-pic" src="'.$src.'" title="Choisir une photo de profil">
                </label>
                <input type="file" id="user-pic" name="user-pic" style="display: none" onchange="submit_pic_form()">
                <input type="hidden" name="from" value="' . $this->from . '">
            </form>             
            <a style="color: black" class="nav-link" href="?page=logout"><i title="Déconnexion" alt="Déconnexion" class="fas fa-sign-out-alt fa-2x"></i></a>
            </div>
        </nav>';

        return $this;
    }

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
        $this->output .= '<title>Good4Restau - '.$title.'</title>
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

    public function summary($date, $meal = false) {
        $date = new \DateTime($date);
        $date = $date->format('d/m/Y');
        if ($meal === false) {
            $meal = '';
        } else {
            $meal = ', '.$meal;
        }
        $this->output .= '<div class="summary">
            Le '.$date.$meal.'
        </div>';
        return $this;
    }

    public function notify($notify) {
        if($notify !== false) {
            $this->output .= '<script>show_toast(\'success\', \''.$notify.'\')</script>';
        }
        return $this;
    }

    public function comments_modal($date)
    {
        $this->output .= '<div class="modal fade" aria-labelledby="manualModalLabel" id="comments_modal" style="margin-bottom: 1rem"  tabindex="-1" role="dialog" aria-hidden="true">
            <div  class="modal-dialog modal-lg" role="document" id="formManual">
                <div class="modal-content" style="margin-top: 33%">
                    <div class="modal-header">
                        <h5 class="modal-title" id="manualModalLabel"><span id="eq_name-modal">Commentaires</span></h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div id="comments-list"></div>
                        <input type="hidden" id="today" value="'.$date.'">
                        <textarea class="form-control" rows="5" id="comment-content" placeholder="Votre commentaire"></textarea>     
                        <div class="row justify-content-center">
                            <button type="button" onclick="submit_comment(\''.$date.'\')" class="btn btn-outline-success width100">
                                Enregistrer
                            </button>
                        </div>                   
                    </div>
                </div>
            </div>
        </div>';
        return $this;
    }

    public function comments_list($comments, $task, $USER) {
        $this->output .= '<input type="hidden" id="c_target" name="c_target" value="'.$task['t_id'].'">
                            <input type="hidden" id="t_target" name="t_target" value="'.$task['t_target_id'].'">';
        if (count($comments) > 0) {
            $this->output .= '<table class="table table-hover">';
            foreach ($comments as $comment) {
                $date = new \DateTime($comment['c_date']);
                $date = $date->format('d/m Y');
                $time = new \DateTime($comment['c_time']);
                $time = $time->format('G:i');
                $delete = '';
                if ($comment['c_author'] == $USER->getId()) {
                    $delete = '<button type="button" onclick="delete_comment('.$comment['c_id'].')" name="delete" value="' . $comment['c_id'] . '" class="fnt_aw-btn delete-btn">
                            <i class="fas fa-trash-alt"></i>
                        </button>';
                }
                $this->output .= '<tr>
                    <td style="font-style: italic">'.$comment['c_author_name'].':</td>
                    <td>'.$comment['c_content'].'</td>
                    <td style="font-style: italic">'.$date.' à '.$time.'</td>
                    <td>
                        '.$delete.'
                    </td>
                </tr>';
            }
            $this->output .= '</table>';
        } else {
            $this->output .= 'Pas de commentaires';
        }

        return $this;
    }

    public function meal_comments_list($comments, $USER)
    {
        if (count($comments) > 0) {
            $this->output .= '<table class="table table-hover">';
            foreach ($comments as $comment) {
                $date = new \DateTime($comment['mc_date']);
                $date = $date->format('d/m Y');
                $time = new \DateTime($comment['mc_time']);
                $time = $time->format('G:i');
                $delete = '';
                if ($comment['mc_author'] == $USER->getId()) {
                    $delete = '<button type="button" onclick="delete_m_comment('.$comment['mc_id'].')" name="delete" value="' . $comment['mc_id'] . '" class="fnt_aw-btn delete-btn">
                            <i class="fas fa-trash-alt"></i>
                        </button>';
                }
                $this->output .= '<tr>
                    <td style="font-style: italic">'.$comment['mc_author_name'].':</td>
                    <td>'.$comment['mc_content'].'</td>
                    <td style="font-style: italic">'.$date.' à '.$time.'</td>
                    <td>
                        '.$delete.'
                    </td>
                </tr>';
            }
            $this->output .= '</table>';
        } else {
            $this->output .= 'Pas de commentaires';
        }

        return $this;
    }

    /**
     * Open body with optional params
     * @param array $tags : Optionnal tags - array([tag] => [value], [attribute] => [value], ...)
     * @param bool $USER : User role, false if no navbar, default manager
     * @return self
     */
    public function open_body(array $tags = [], $USER) {
        $this->output .='<body>';
        if ($USER !== false) {
            $this->navbar($USER);
            $this->output .= '<div class="app-container">';
            $this->opened_tags[] = 'div';
        } else {
            $this->output .= '<div class="login-container">';
            $this->opened_tags[] = 'div';
        }
        foreach ($tags as $attributes) {
            $this->output .= '<'.$attributes['tag'];
            foreach ($attributes as $key => $value) {
                if ($key !== 'tag') {
                    $this->output .= ' '.$key.'="'.$value.'"';
                }
            }
            $this->output .= '>';
            $this->opened_tags[] = $attributes['tag'];
        }

        return $this;
    }

    public function wip() {
        $this->output .= '<h1 style="text-align: center; margin-top: 20%">Cette page est en construction</h1>';
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

    public function set_referer($from) {
        $this->from = $from;

        return $this;
    }

    public function get_referer() {
        return $this->from;
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

    protected function active($page) {
        return $this->from === $page ? 'active' : '';
    }
}