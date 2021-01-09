<?php

$args = [
    'day' => FILTER_VALIDATE_INT,
    'month' => FILTER_VALIDATE_INT,
    'year' => FILTER_VALIDATE_INT,
    'display' => FILTER_SANITIZE_STRING,
    'current-rest' => FILTER_VALIDATE_INT,
    'from' => FILTER_SANITIZE_STRING,
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

if (isset($POST['current-rest'])) {
    $_SESSION['current-rest'] = $POST['current-rest'];
    $page = $POST['from'];
    if ($page === 'restaurants') {
        $page .= '&edit='.$_SESSION['current-rest'];
    }
    header('Location: ?page='.$page);
    die();
}
$renderer->header()
            ->open_body([
                [
                    'tag' => 'form',
                    'action' => 'index.php?page=dayly',
                    'method' => 'POST'
                ],
                [
                    'tag' => 'div',
                    'class' => 'calendar-container'
                ],
            ], $USER)
            ->set_referer('home')
            ->options()
            ->monthly()
            ->close_body()
            ->footer()
            ->render();