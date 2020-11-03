<?php


namespace renderers;


class ManagementRenderer extends BaseRenderer
{
    public function __construct() {
        parent::__construct();
        $this->from = 'management';
    }

    public function links($day) {
        $this->set_day($day);
        $this->output .= '<div class="col-12 col-sm-12 col-md-12 col-lg-12">
            <div class="row justify-content-between">
                <a href="?page=meals&date='.$day.'">Repas</a>
                <a href="#">PND</a>
                <a href="#">Stocks</a>
                <a href="#">Matériel</a>        
            </div>
        </div>';
        return $this;
    }

    private function set_day($day) {
        $this->from .= '&date='.$day;

        return $this;
    }
}