<?php

namespace renderers;
use renderers\BaseRenderer;

class ErrorRenderer extends BaseRenderer 
{
    /**
     * @param string $error_code The HTML error code
     * @return self
     */
    public function display_error($error_code = '404') {
        $errors = [
            '400' => 'Erreur dans la requête',
            '401' => 'Vous n\'avez rien à faire ici',
            '403' => 'Vous n\'avez rien à faire ici',
            '404' => 'Cette page n\'existe pas',
        ];
        $domain = str_replace($_SERVER['DOCUMENT_ROOT'],'',__DIR__);     
        $domain = str_replace('/renderers', '', $domain);
        $domain = $_SERVER['REQUEST_SCHEME'].'://'.$_SERVER['HTTP_HOST'].$domain;
        $this->output .= '<strong>Erreur '.$error_code.'</strong>
                            <p>'.$errors[$error_code].'</p>
            <img src="'.$domain.'/public/style/resources/'.$this->get_error_file().'">';
        return $this;
    }

    private function get_error_file() {
        $extensions = ['jpg', 'JPG', 'jpeg', 'JPEG', 'png', 'PNG', 'gif', 'GIF'];

        $files = array_diff(scandir(self::STYLE_DIRECTORY.'resources'), array('..', '.'));
        foreach ($files as $file) {
            if (strpos($file, 'error') !== false) {
                $tmp = explode('.', $file);
                $extension = end($tmp);
                if (in_array($extension, $extensions)) {
                    return 'error.'.$extension;
                }
            }
        }
        return false;
    }
}