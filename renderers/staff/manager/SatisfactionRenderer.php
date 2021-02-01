<?php


namespace renderers\staff\manager;


class SatisfactionRenderer extends BaseRenderer
{
    public function __construct() {
        parent::__construct();
        $this->from = 'satisfaction';
    }

    public function satisfaction() {
        $this->output .= '<img src="'.$GLOBALS['domain'].'/public/style/resources/satisfaction2.png"> ';


        return $this;
    }

    public function satisfaction_form($poll, $stats) {
        $this->output .= '<h1 style="text-align: center;">Enquête de satisfaction</h1>
        <div>';
        for ($i = 1; $i < 11; $i++) {
            $value = $poll !== false ? $poll['sp_field_'.$i] : '';
            if (isset($stats['spv_field_'.$i]) && $stats['spv_field_'.$i]['count'] !== 0) {
                $percent = $stats['spv_field_'.$i]['sum'] / $stats['spv_field_'.$i]['count'];
                $satisfaction = 'Satisfaction : '.round($percent).'% ('.$stats['spv_field_'.$i]['count'].' votes)';
            } else {
                $satisfaction = '';
            }
            $this->output .= '<div class="form-inline" style="padding-bottom: 10px">
            <input name="sp_field_'.$i.'" type="text" class="form-control" style="width: 30vw;" placeholder="Critère n°'.$i.'" value="'.$value.'"> 
            &nbsp;'.$satisfaction.'                      
            </div>';
        }
        $this->output .= '  <div class="row justify-content-center">
                        <button class="btn btn-outline-success width100">Enregistrer</button>
                        </div>
        </div>';

        return $this;
    }

    private function set_day($day) {
        $this->from .= '&date='.$day;

        return $this;
    }
}