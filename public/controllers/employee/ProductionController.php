<?php

$renderer->header('Production')
    ->open_body([
        [
            'tag' => 'div',
            'class' => 'content-center'
        ]
    ], $USER->getRole())
    ->previous_page('management')
    ->wip()
    ->close_body()
    ->footer()
    ->render();