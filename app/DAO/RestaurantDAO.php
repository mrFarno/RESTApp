<?php

namespace app\DAO;

use app\BO\Restaurant;
use app\BO\User;

class RestaurantDAO extends DAO
{
    protected $table = 'restaurants';
    protected $prefix = 'r';

    /**
     * Specific Restaurant find function
     * @param string $filter Column to filter by
     * @param string $value Targetet value
     * @param boolean $force_array TRUE if result can be array
     * 
     * @return mixed array of Restaurant objects if several results, one Restaurant object else
     */
    public function find($params, $force_array = false){
        $request = 'SELECT * FROM restaurants
                    INNER JOIN users ON restaurants.r_manager_id = users.u_id';
        $i = 0;
        $binds = [];
        foreach ($params as $filter => $value) {
            if ($i === 0) {
                $request .= ' WHERE '.$filter.' = :value'.$i;
            } else {
                $request .= ' AND '.$filter.' = :value'.$i;
            }
            $binds[':value'.$i] = $value;
            $i++;
        }
        $stmt = $this->getPDO()->prepare($request);
        $stmt->execute($binds);
        $result = $stmt->fetchAll();
        $data = [];
        foreach ($result as $row) {
            $meals = explode(':', $row['r_meals']);
            $restaurant = new Restaurant($row);
            $manager = new User($row);
            $restaurant->setManager($manager)
                        ->setMeals($meals);
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
                return reset($data);
                break; 
            default : return $data;
        }
    }

    /**
     * @param $restaurant Restaurant to add/update
     * Check if mail already exist. If true update else insert
     * @return true
     */
    public function persist($restaurant) {
        $meals = $restaurant->getMeals();
        $meals_string = '';
        foreach ($meals as $id) {
            $meals_string .= ':'.$id;
        }
        $meals_string = ltrim($meals_string, ':');
        if ($this->find(['r_id' => $restaurant->getId()]) !== false) {
            $update = true;
            $request = 'UPDATE restaurants SET
                            r_name = :name,
                            r_adress_zip = :adress_zip,
                            r_adress_town = :adress_town,
                            r_adress_street = :adress_street,
                            r_adress_country = :adress_country,
                            r_manager_id = :manager_id,
                            r_type_id = :type_id,
                            r_meals = :meals
                        WHERE r_id = :id;';
        } else {
            $update = false;
            $request = 'INSERT INTO restaurants (r_name, r_adress_zip, r_adress_town, r_adress_street, r_adress_country, r_manager_id, r_type_id, r_meals) VALUES (
                            :name,
                            :adress_zip,
                            :adress_town,
                            :adress_street,
                            :adress_country,
                            :manager_id,
                            :type_id,
                            :meals
                        );';
        }
        $stmt = $this->getPDO()->prepare($request);
        $binds = [
            ':name' => $restaurant->getName(),
            ':adress_zip' => $restaurant->getZip(),
            ':adress_town' => $restaurant->getTown(),
            ':adress_street' => $restaurant->getStreet(),
            ':adress_country' => $restaurant->getCountry(),
            ':manager_id' => $restaurant->getManager()->getId(),
            ':type_id' => $restaurant->getType(),
            ':meals' => $meals_string
        ];
        if ($update === true) {
            $binds[':id'] = $restaurant->getId();
        }
        $stmt->execute($binds);
        if ($update === false) {
            $restaurant->setId($this->getPDO()->lastInsertId());
        }
        return true;
    }

    public function ids_by_manager($user) {
        $request = 'SELECT r_id FROM restaurants
        WHERE r_manager_id = :value;';

        $stmt = $this->getPDO()->prepare($request);
        $stmt->execute([
            ':value' => $user->getId()
        ]);

        return $stmt->fetchAll();
    }
}