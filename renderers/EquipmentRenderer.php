<?php


namespace renderers;


class EquipmentRenderer extends BaseRenderer
{
    public function __construct() {
        parent::__construct();
        $this->from = 'equipment';
    }

    public function equipment_form() {
        return $this;
    }
}