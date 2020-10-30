<?php

namespace app\DAO;
use app\BO\User;

class EmployementDAO extends DAO
{    
    protected $table = 'employements';
    protected $prefix = 'e';

    public function employees_by_restaurant($r_id) {
        $request = 'SELECT * FROM users
                    INNER JOIN employements ON e_user_id = u_id
                    WHERE e_restaurant_id = :r_id;';
        $stmt = $this->getPDO()->prepare($request);
        $stmt->execute([
            ':r_id' => $r_id
        ]);
        $result = $stmt->fetchAll();
        $datas = [];

        foreach ($result as $row) {
            $datas[$row['u_id']] = new User($row);
        }
        return $datas;
    }

    public function delete_by_user_restaurant($u_id, $r_id) {
        $request = 'DELETE FROM employements WHERE e_user_id = :u_id AND e_restaurant_id = :r_id;';
        $stmt = $this->getPDO()->prepare($request);
        $stmt->execute([
            ':u_id' => $u_id,
            ':r_id' => $r_id
        ]);
        return true;
    }
}