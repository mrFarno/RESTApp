<?php

namespace renderers;

class HomeRenderer extends BaseRenderer
{
   public function coucou($user) {
        dump($user);
        return $this;
   }
}