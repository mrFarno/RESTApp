<?php


namespace app\DAO;


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

    public function find_by_id_date($id, $date) {
        $request = 'SELECT * FROM task_affectations WHERE ta_task_id = :id
                     AND ta_date = :date
                     OR (ta_date <= :date AND ta_dateend >= :date)
                     OR (ta_date <= :date AND ta_dateend IS NULL);';
        $stmt = $this->getPDO()->prepare($request);
        $stmt->execute([
            ':id' => $id,
            ':date' => $date,
        ]);
        return $stmt->fetchAll();

    }
}