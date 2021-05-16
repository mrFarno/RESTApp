<?php


namespace renderers\staff;


class AllergyRenderer extends \renderers\BaseRenderer
{
    private static $allergens = [
            1 => 'Gluten',
            2 => 'Crustacés',
            3 => 'Oeufs',
            4 => 'Poisson',
            5 => 'Arachide',
            6 => 'Soja',
            7 => 'Lait',
            8 => 'Fruits à coque',
            9 => 'Céléri',
            10 => 'Moutarde',
            11 => 'Graines de sésame',
            12 => 'Sulfites',
            13 => 'Lupin',
            14 => 'Mollusques'
        ];

    public function __construct() {
        parent::__construct();
        $this->from = 'allergy';
    }

    public function allergies_list($allergies) {
//        dump($allergies);die();
        $this->output .= '<h1 style="text-align: center">P.A.I.</h1><br>
        <div class="" >
            <table class="table table-hover" style="">
                    <th>Nom</th>
                    <th>Prénom</th>
                    <th>Age/classe</th>
                    <th>Allergies</th>
                    <th>Modifier</th>';
        if (count($allergies) !== 0) {
            foreach ($allergies as $allergy) {
                $this->output .= '<tr>
                <td>'.$allergy['al_firstname'].'</td>
                <td>'.$allergy['al_lastname'].'</td>
                <td>'.$allergy['al_age'].'</td>
                <td>';
            for ($i = 1; $i < 15; $i++) {
                if($allergy['al_'.$i] == 1) {
                    $this->output .= self::$allergens[$i].'<br>';
                }
            }
            $this->output .= '</td>
            <td><button type="button" class="fnt_aw-btn" data-toggle="modal" data-target="#allergies_modal"
                onclick="empty_allergies_modal(); init_allergies_modal('.$allergy['al_id'].')">
                    <i class="fas fa-edit"></i>
                </button></td>
            </tr>';
            }
        }
        $this->output .= '</table><div>
                <button type="button" class="btn btn-outline-success width100" data-toggle="modal" data-target="#allergies_modal" onclick="empty_allergies_modal()">
                    +
                </button>
                </div>';
        return $this;
    }

    public function allergies_modal($meal) {
        $this->output .= '<div class="modal fade" aria-labelledby="manualModalLabel" id="allergies_modal" style="margin-bottom: 1rem"  tabindex="-1" role="dialog" aria-hidden="true">
            <div  class="modal-dialog modal-lg" role="document" id="formManual">
                <div class="modal-content" style="margin-top: 33%">
                    <div class="modal-header">
                        <h5 class="modal-title" id="manualModalLabel"><span id="eq_name-modal">P.A.I</span></h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                    <form method="POST" action="?page=allergy&meal='.$meal.'" id="step-form">
                    <input type="hidden" id="al_id" name="al_id" value="">
                    <div class="form-row">
                        <div class="form-group">
                            <label for="al_firstname">Prénom</label>
                            <input type="text" name="al_firstname" class="form-control" id="al_firstname">
                        </div>
                        <div class="form-group">
                            <label for="al_lastname">Nom</label>
                            <input type="text" name="al_lastname" class="form-control" id="al_lastname">
                        </div>
                        <div class="form-group">
                            <label for="al_age">Age/classe</label>
                            <input type="text" name="al_age" class="form-control" id="al_age">
                        </div>
                    </div>
                    <div class="">';
                    for ($i = 1; $i < 15; $i++) {
                        $this->output .= '<span><label for="al_'.$i.'">'.self::$allergens[$i].'</label>
                        <input name="al_'.$i.'" type="checkbox" id="al_'.$i.'"></span>';
                        if ($i % 3 == 0) {
                            $this->output .= '<br>';
                        } else {
                            $this->output .= ' - ';
                        }
                    }
                    $this->output .= '</div>
                    <div>
                        <button type="submit" class="btn btn-outline-success width100">
                            Enregistrer
                        </button>                       
                    </div>
                    </form>
                    </div>
                </div>
            </div>
        </div>';

        return $this;
    }

    public function links($meal_type, $day) {
        $this->output .= '<div style="display: flex;
        justify-content: space-around;     height: 90%;
        align-items: center;">';
        $links = [
            'allergy_guests' => 'Convives dont P.A.I',
            'allergy_adults' => 'Adultes',
        ];
        foreach ($links as $link => $name) {
            $this->output .= '<a href="?page='.$link.'&meal='.$meal_type.'&date='.$day.'">
                <img class="icon-img" id="'.$link.'-link" src="'.$GLOBALS['domain'].'/public/style/resources/icons/'.$link.'.png"> 
                <p style="text-align: center; color: black; font-size: large;" for="'.$link.'-link">'.$name.'</p>
            </a>';
        }
        $this->output .= '</div>';

        return $this;
    }

    private function set_day($day) {
        $this->from .= '&date='.$day;

        return $this;
    }
}