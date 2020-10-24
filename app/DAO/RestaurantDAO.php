<?php

namespace app\DAO;

use app\BO\Restaurant;
use app\BO\User;

class RestaurantDAO extends DAO
{
    private $table = 'restaurants';
    private $prefix = 'r';

    /**
     * Specific Restaurant find function
     * @param string $filter Column to filter by
     * @param string $value Targetet value
     * @param boolean $force_array TRUE if result can be array
     * 
     * @return mixed array of Restaurant objects if several results, one Restaurant object else
     */
    public function find($filter, $value, $force_array = false){
        $request = 'SELECT * FROM restaurants
                    WHERE '.$filter.' = :value
                    INNER JOIN users ON restaurants.r_manager_id = users.u_id;';

        $stmt = $this->getPDO()->prepare($request);
        $stmt->execute([
            ':value' => $value
        ]);
        $result = $stmt->fetchAll();
        $data = [];
        foreach ($result as $row) {
            $restaurant = new Restaurant($row);
            $manager = new User($row);
            $restaurant->setManager($manager);
            $data[] = $restaurant;
        }
        switch (count($data)) {
            case 0 : 
                if ($force_array === true) {
                    return [];
                }
                return false;
                break;
            case 1 : 
                if ($force_array === true) {
                    return $data;
                }
                return $data[0];
                break; 
            default : return $data;
        }
    }
}