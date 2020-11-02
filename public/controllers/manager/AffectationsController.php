<?php

$restaurant = $restaurant_dao->find('r_id', $_SESSION['current-rest']);
$employees = $employement_dao->employees_by_restaurant($restaurant->getId());

$renderer->header()
            ->open_body([
                [
                    'tag' => 'div',
                    'class' => 'content-center' 
                ],
                [
                    'tag' => 'form',
                    'action' => 'index.php?page=team&restid='.$restaurant->getId(),
                    'method' => 'POST'
                ],             
            ])
            ->employees_table($employees)
            ->close_body()
            ->footer()
            ->render();