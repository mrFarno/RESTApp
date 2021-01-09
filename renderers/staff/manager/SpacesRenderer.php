<?php


namespace renderers\staff\manager;


class SpacesRenderer extends BaseRenderer
{
    public function __construct() {
        parent::__construct();
        $this->from = 'equipment';
    }

    public function spaces_form($spaces) {
        $this->output .= '<h1 style="text-align: center">Locaux</h1>
        <table class="table table-hover" style="">
            <th>Nom</th>
            <th>Instructions de nettoyage</th>
            <th></th>';
        if (count($spaces) !== 0) {
            foreach ($spaces as $space) {
                $this->output .= '<tr>
                <td>'.$space['s_name'].'</td>
                <td>'.$space['s_cleaning_instructions'].'</td>
                <td><button onclick="return confirm(\'Etes vous sur de vouloir supprimer ce local ?\')" type="submit" value="'.$space['s_id'].'" name="delete" class="fnt_aw-btn delete-btn">
                        <i class="fas fa-trash-alt"></i>
                    </button></td>
            </tr>';
            }
        }
        $this->output .= '<tr>
            <td><input type="text" name="s_name"></td>
            <td><textarea name="s_cleaning_instructions"></textarea></td>
            <td><button class="btn btn-outline-success width100">+</button></td>
        </tr>';

        return $this;
    }
}