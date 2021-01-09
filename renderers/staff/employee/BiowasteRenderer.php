<?php


namespace renderers\staff\employee;


class BiowasteRenderer extends BaseRenderer
{
    public function __construct() {
        parent::__construct();
        $this->from = 'biowaste';
    }

    public function biowaste_form($biowaste, $day) {
//        $this->output .= '<img src="'.$GLOBALS['domain'].'/public/style/resources/biowaste.png"> ';
        $total = $biowaste['bw_production'] + $biowaste['bw_bread'] + $biowaste['bw_other'] + $biowaste['bw_carton'] + $biowaste['bw_package_other'] + $biowaste['bw_green'] + $biowaste['bw_valuation'];
        $this->output .= '<h1 style="text-align: center;">Déchets de la journée</h1>
        <form method="POST" action="?page=biowaste">
        <input type="hidden" name="date" value="'.$day.'">
        <div class="left" style="width: fit-content;">
        <table>
            <tr>
                <td><h5>Bio-déchets de production</h5></td>
            </tr>
            <tr>
                <td><label for="bw_prod">(Hors emballages)</label></td>
                <td><input type="number" class="form-control" id="bw_prod" name="bw_production" value="'.$biowaste['bw_production'].'"></td>
            </tr>
            <tr>
                <td><h5>Non consommés :</h5></td>
            </tr>
            <tr>
                <td><label for="bw_bread">Pain</label></td>
                <td><input type="number" class="form-control" id="bw_bread" name="bw_bread" value="'.$biowaste['bw_bread'].'"></td>
            </tr>     
            <tr>           
                <td><label for="bw_other">Autre</label></td>
                <td><input type="number" class="form-control" id="bw_other" name="bw_other" value="'.$biowaste['bw_other'].'"></td>
            </tr>
        </table>                                         
        </div>
        <div class="right" style="width: fit-content;">
        <table>
            <tr>
                <td><h5>Emballages</h5></td>
            </tr>
            <tr>
                <td><label for="bw_carts">Cartons</label></td>
                <td><input type="number" class="form-control" id="bw_carts" name="bw_carton" value="'.$biowaste['bw_carton'].'"></td>
            </tr>
            <tr>
                <td><label for="bw_o">Autres</label></td>
                <td><input type="number" class="form-control" id="bw_o" name="bw_package_other" value="'.$biowaste['bw_package_other'].'"></td>
            </tr>
            <tr>
                <td><h5>Espaces verts :</h5></td>
            </tr>
            <tr>
                <td><label for="bw_green">(Tontes, etc)</label></td>
                <td><input type="number" class="form-control" id="bw_green" name="bw_green" value="'.$biowaste['bw_green'].'"></td>
            </tr>    
            <tr>
                <td><h5>Valorisation</h5></td>
            </tr>
            <tr>
                <td><label for="bw_val">(Valeur négative)</label></td>
                <td><input type="number" max="0" class="form-control" id="bw_val" name="bw_valuation" value="'.$biowaste['bw_valuation'].'"></td>
            </tr> 
        </table>                                         
        </div>
        <div style="position: absolute; bottom: 10vh; left: 40vw">
            Total : <input type="number" disabled class="form-control" value="'.$total.'"><br>
            <label for="bw_comment">Commentaire :</label>
            <textarea class="form-control" id="bw_comment" name="bw_comment">'.$biowaste['bw_comment'].'</textarea><br>
            <button type="submit" class="btn btn-outline-success width100">
            Enregistrer
            </button>
        </div>
        </form>';

        return $this;
    }

    private function set_day($day) {
        $this->from .= '&date='.$day;

        return $this;
    }
}