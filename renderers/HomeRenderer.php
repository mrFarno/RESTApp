<?php

namespace renderers;

class HomeRenderer extends BaseRenderer
{

   public function __construct() {
      parent::__construct();
      $this->from = 'home';
   }

   public function coucou($user) {
        dump($user);
        return $this;
   }
}