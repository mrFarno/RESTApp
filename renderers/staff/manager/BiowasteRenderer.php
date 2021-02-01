<?php


namespace renderers\staff\manager;


class BiowasteRenderer extends BaseRenderer
{
    public function __construct() {
        parent::__construct();
        $this->from = 'biowaste';
    }

    public function biowaste_graph($biowastes) {
        $this->output .= '<h1>Synthèse</h1>
            <h4 style="font-style: italic">20 derniers jours, tous repas confondus</h4>
            <table style="border-bottom:1px solid;border-left:1px solid;margin:auto;">
            <tr>';
        foreach ($biowastes as $biowaste){
            $total = $biowaste['bw_production'] + $biowaste['bw_bread'] + $biowaste['bw_other'] + $biowaste['bw_carton'] + $biowaste['bw_package_other'] + $biowaste['bw_green'] + $biowaste['bw_valuation'];
            $this->output .= '<td valign="bottom"><table><tr><td align="bottom">
                <img src="'.$GLOBALS['domain'].'/public/style/resources/cyan.png" width="8" height="'.$total.'" style="border-left:2px solid white;">
                </td></tr>
                <tr><td><span class="gris" style="font-size:0.7em;">'.date("d/m", strtotime($biowaste['bw_date'])).'</span></td></tr></table></td>';
        }
        $this->output .= '<td rowspan="2"><table style="margin-left:10px;border:1px solid #CCC;padding:5px;padding-top:0px;"><tr><td valign="center">
				<img src="'.$GLOBALS['domain'].'/public/style/resources/cyan.png" width="8" height="8"> <span class="gris" style="font-size:0.7em;">Déchets</span></td></tr></table>
		</td>
		</tr></table>';

        return $this;
    }

    public function year_total($total, $year) {
        $this->output .= '<br>Total en '.$year.' : '.$total.'kg';
        return $this;
    }

    private function set_day($day) {
        $this->from .= '&date='.$day;

        return $this;
    }
}