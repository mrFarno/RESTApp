<?php

$renderer->header('Erreur')
            ->open_body([
                [
                    'tag' => 'div',
                    'class' => 'content-center'
                ]             
                ], false)
            ->set_referer($from)
            ->previous_page('home')
            ->display_error($error_code)
            ->close_body($USER)
            ->footer()
            ->render();