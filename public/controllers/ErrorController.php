<?php

$renderer->header('Erreur')
            ->open_body([
                'div' => [
                    'class' => 'app-container content-center'
                ]             
            ])
            ->previous_page($from)
            ->display_error($error_code)
            ->close_body($USER)
            ->footer()
            ->render();