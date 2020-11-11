<?php


namespace app\DAO;


use app\BO\User;
use app\DAO\UserDAO;

class MealAffectationDAO extends DAO
{
    protected $table = 'meal_affectations';
    protected $prefix = 'maf';


    public function persist($datas)
    {
        if(!isset($datas['maf_id'])) {
            $af_dao = new AffectationDAO([
                'db_host' => $this->host,
                'db_user' => $this->user,
                'db_pass' => $this->password,
                'db_name' => $this->db_name,
                'db_type' => $this->type
            ]);
            $id = $af_dao->persist(['af_id' => null]);
            $datas['maf_id'] = $id;
        }
        return parent::persist($datas);
    }

    public function find_users($r_id, $mt_id, $date, $force_array = false) {
        $u_dao = new UserDAO([
            'db_host' => $this->host,
            'db_user' => $this->user,
            'db_pass' => $this->password,
            'db_name' => $this->db_name,
            'db_type' => $this->type
        ]);
        $request = 'SELECT * FROM meal_affectations
                    INNER JOIN employements ON maf_employement_id = e_id
                    INNER JOIN users ON e_user_id = u_id
                    LEFT JOIN absences ON ab_employement_id = e_id
                    LEFT JOIN replacements ON rp_affectation_id = maf_id
                    WHERE e_restaurant_id = :r_id
                    AND maf_meal_type = :mt_id
                    AND (maf_timestart < :date AND (maf_timeend IS NULL OR maf_timeend > :date))                
                    UNION
                    SELECT * FROM meal_affectations
                    INNER JOIN employements ON maf_employement_id = e_id
                    INNER JOIN users ON e_user_id = u_id
                    LEFT JOIN absences ON ab_employement_id = e_id
                    RIGHT JOIN replacements ON rp_affectation_id = maf_id
                    WHERE e_restaurant_id = :r_id
                    AND maf_meal_type = :mt_id
                    AND (maf_timestart < :date AND (maf_timeend IS NULL OR maf_timeend > :date));';
        $stmt = $this->getPDO()->prepare($request);
        $stmt->execute([
            ':r_id' => $r_id,
            ':mt_id' => $mt_id,
            ':date' => $date.' 12:00:00',
        ]);
        $result = $stmt->fetchAll();
        $data = [];
        foreach ($result as $row) {
            $data[$row['u_id']] = new User($row);
            if ($row['ab_id'] !== null
                && ($row['ab_date'] == $date
                || ($row['ab_date'] >= $date
                        && $row['ab_dateend'] !== null))) {
                $data[$row['u_id']]->setAbsent(true);
            }
            if ($row['rp_substitute_id'] !== null) {
                $data[$row['rp_substitute_id']] = $u_dao->find([
                    'u_id' => $row['rp_substitute_id']
                ]);
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