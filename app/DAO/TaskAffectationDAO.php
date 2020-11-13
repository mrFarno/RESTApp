<?php


namespace app\DAO;


use app\BO\User;

class TaskAffectationDAO extends DAO
{
    protected $table = 'task_affectations';
    protected $prefix = 'ta';

    public function persist($datas)
    {
        if(!isset($datas['ta_id'])) {
            $af_dao = new AffectationDAO([
                'db_host' => $this->host,
                'db_user' => $this->user,
                'db_pass' => $this->password,
                'db_name' => $this->db_name,
                'db_type' => $this->type
            ]);
            $id = $af_dao->persist(['af_id' => null]);
            $datas['ta_id'] = $id;
        }
        return parent::persist($datas);
    }

    public function frequents_affectations($target_id, $date) {
        $day = date('N', strtotime($date));
        $request = 'SELECT * FROM tasks
                    INNER JOIN task_affectations ON ta_task_id = t_id
                    INNER JOIN employements ON ta_employement_id = e_id
                    INNER JOIN users ON e_user_id = u_id
                    WHERE t_target_id = :target_id
                    AND ta_frequency LIKE :day';
        $stmt = $this->getPDO()->prepare($request);
        $stmt->execute([
            ':target_id' => $target_id,
            ':day' => "%$day%",
        ]);
        return $stmt->fetchAll();
    }

    public function user_frequents_affectations($user, $target_id, $date) {
        $day = date('N', strtotime($date));
        $request = 'SELECT * FROM tasks
                    INNER JOIN task_affectations ON ta_task_id = t_id
                    INNER JOIN employements ON ta_employement_id = e_id
                    INNER JOIN users ON e_user_id = u_id
                    WHERE t_target_id = :target_id
                    AND u_id = :u_id
                    AND ta_frequency LIKE :day';
        $stmt = $this->getPDO()->prepare($request);
        $stmt->execute([
            ':target_id' => $target_id,
            ':u_id' => $user->getId(),
            ':day' => "%$day%",
        ]);
        return $stmt->fetchAll();
    }

    public function daily_tasks($user, $restaurant, $date, $type) {
        $request = 'SELECT * FROM tasks
                    INNER JOIN task_affectations ON ta_task_id = t_id
                    INNER JOIN employements ON ta_employement_id = e_id
                    INNER JOIN '.$type['table'].' ON '.$type['prefix'].'_id = t_target_id
                    WHERE e_user_id = :u_id   
                    AND e_restaurant_id = :r_id
                    AND t_date = :date;';
        $stmt = $this->getPDO()->prepare($request);
        $stmt->execute([
            ':u_id' => $user->getId(),
            ':r_id' => $restaurant->getId(),
            ':date' => $date,
        ]);
        return $stmt->fetchAll();
    }

    public function find_by_id_date($id, $date) {
        $day = date('N', strtotime($date));
        $request = 'SELECT * FROM task_affectations 
                    INNER JOIN tasks ON t_id = ta_task_id
                    WHERE ta_task_id = :id
                     AND (t_date = :date
                     OR (t_date <= :date AND ta_dateend >= :date)
                     OR (t_date <= :date AND ta_dateend IS NULL)
                     );';
        $stmt = $this->getPDO()->prepare($request);
        $stmt->execute([
            ':id' => $id,
            ':date' => $date,
            ':day' => "%$day%",
        ]);
        return $stmt->fetchAll();

    }
}