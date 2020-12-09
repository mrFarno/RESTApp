<?php


namespace renderers\employee;


class TeamRenderer extends \renderers\BaseRenderer
{
    public function __construct() {
        parent::__construct();
        $this->from = 'team';
    }

    public function absence_form() {
        $this->output .= '<h1 style="text-align: center;">Déclarer une absence</h1>
            <div class="form-row" style="margin-top: 5vh;">
            <div class="form-group">
                <label for="datestart">Du&nbsp;</label>
                <input type="date" id="datestart" name="ab_date" value="'.date('Y-m-d').'">                
            </div>
            <div class="form-group">
                <label for="dateend">&nbsp;au&nbsp;</label>
                <input type="date" id="dateend" name="ab_dateend">                
            </div>
        </div>
        <div class="form-group">
            <label for="ab_reason">Motif : </label>
            <textarea class="form-control" id="ab_reason" name="ab_reason"></textarea>
        </div>
        <label for="absence_pic">Certificat médical :&nbsp;</label>
        <input type="file" id="absence_pic" name="absence_pic">
        <div class="row justify-content-center" style="margin-top: 5%;">
            <button type="submit" class="btn btn-outline-success width100">
                Enregistrer
            </button>
        </div>';

        return $this;
    }

    public function absences_list($absences) {
        //TODO implement this function
        return $this;
    }
}