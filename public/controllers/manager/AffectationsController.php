<?php

$restaurant = $restaurant_dao->find('r_id', $_SESSION['current-rest']);
$employees = $employement_dao->employees_by_restaurant($restaurant->getId());
$meals = [];
foreach ($restaurant->getMeals() as $meal) {
    $meals[$meal] = $meal_types_dao->find('mt_id', $meal)[0]['mt_name'];
}

$renderer->header()
            ->open_body([
                [
                    'tag' => 'div',
                    'class' => 'content-center' 
                ],
                [
                    'tag' => 'form',
                    'action' => 'index.php?page=affectations',
                    'method' => 'POST'
                ],             
            ])
            ->employees_table($employees)
            ->user_modal($meals)
            ->close_body()
            ->footer()
            ->render();