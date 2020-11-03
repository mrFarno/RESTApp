<?php


namespace app\DAO;


use app\BO\User;

class AffectationDAO extends DAO
{
    protected $table = 'affectations';
    protected $prefix = 'af';

    public function find_users($r_id, $mt_id, $date, $force_array = false) {
        $request = 'SELECT * FROM affectations
                    INNER JOIN employements ON af_employement_id = e_id
                    INNER JOIN users ON e_user_id = u_id
                    WHERE e_restaurant_id = :r_id
                    AND af_meal_type = :mt_id';
        $stmt = $this->getPDO()->prepare($request);
        $stmt->execute([
            ':r_id' => $r_id,
            ':mt_id' => $mt_id
        ]);
        $result = $stmt->fetchAll();
        $data = [];
        foreach ($result as $row) {
            $current = strtotime($date.' 12:00:00');
            if ($current > strtotime($row['af_timestart']) && ($row['af_timeend'] === null || $current < strtotime($row['af_timeend']))) {
                $data[$row['u_id']] = new User($row);
            }
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
}