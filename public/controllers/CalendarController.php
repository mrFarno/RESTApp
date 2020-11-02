<?php

$args = [
    'day' => FILTER_VALIDATE_INT,
    'month' => FILTER_VALIDATE_INT,
    'year' => FILTER_VALIDATE_INT,
    'display' => FILTER_SANITIZE_STRING,
];

$POST = filter_input_array(INPUT_POST, $args, false);

//Ajax call to change calendar display
if (isset($POST['display']) && in_array($POST['display'], ['monthly', 'weekly'])) {
    $display = $POST['display'];
    $renderer->setDate($POST['day'] ?? null, 
                        $POST['month'] ?? null, 
                        $POST['year'] ?? null) 
                ->options($display)
                ->$display() //call to monthly() or weekly()
                ->render();
    die();
}

$renderer->header()
            ->open_body([
                [
                    'tag' => 'div',
                    'class' => 'calendar-container'
                ],
            ])
            ->set_referer('home')
            ->options()
            ->monthly()
            ->close_body()
            ->footer()
            ->render();