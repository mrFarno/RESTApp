<?php


namespace renderers\staff\employee;


class SatisfactionRenderer extends BaseRenderer
{
    public function __construct() {
        parent::__construct();
        $this->from = 'satisfaction';
    }

    public function satisfaction() {
        $this->output .= '<img src="'.$GLOBALS['domain'].'/public/style/resources/satisfaction.png"> ';


        return $this;
    }

    public function satisfaction_form($poll) {
        $this->output .= '<h1 style="text-align: center;">Enquête de satisfaction</h1>
        <table>
        <tr>';
        if ($poll !== false) {
            for ($i = 1; $i < 11; $i++) {
                $new_line = $i % 2 !== 0;
                if ($poll['sp_field_'.$i] !== null) {
                    if ($new_line) {
                        $this->output .= '</tr><tr>';
                    }
                    $this->output .= '<td style="padding-right: 5vw; padding-left: 5vw; padding-bottom: 3vh">
                    <h4>'.$poll['sp_field_'.$i].'</h4>                    
                    <input type="hidden" name="spv_poll_id" value="'.$poll['sp_id'].'">
                    <label>
                          <input type="radio" name="spv_field_'.$i.'" value="0" class="sat-radio sat-radio-0">
                          <img src="'.$GLOBALS['domain'].'/public/style/resources/icons/satisfaction/0.png'.'" class="sat-icon">
                    </label>                       
                    <label>
                          <input type="radio" name="spv_field_'.$i.'" value="33" class="sat-radio sat-radio-33">
                          <img src="'.$GLOBALS['domain'].'/public/style/resources/icons/satisfaction/33.png'.'" class="sat-icon">
                    </label>                          
                    <label>
                          <input type="radio" name="spv_field_'.$i.'" value="67" class="sat-radio sat-radio-67">
                          <img src="'.$GLOBALS['domain'].'/public/style/resources/icons/satisfaction/67.png'.'" class="sat-icon">
                    </label>                    
                    <label>
                          <input type="radio" name="spv_field_'.$i.'" value="100" class="sat-radio sat-radio-100">
                          <img src="'.$GLOBALS['domain'].'/public/style/resources/icons/satisfaction/100.png'.'" class="sat-icon">
                    </label>
                    </td>';
                }
            }
        }
        $this->output .= '</table>
        <div class="content-center">
            <button class="btn btn-success">
                Voter
            </button>
        </div>';

        return $this;
    }

    private function set_day($day) {
        $this->from .= '&date='.$day;

        return $this;
    }
}