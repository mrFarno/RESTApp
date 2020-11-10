<?php

$renderer->header('Nettoyage et désinfection')
    ->open_body([
        [
            'tag' => 'div',
            'class' => 'content-center'
        ]
    ], $USER->getRole())
    ->wip()
    ->close_body()
    ->footer()
    ->render();