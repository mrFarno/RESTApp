<?php


namespace app\DAO;
use app\DAO\CleaningContextDAO;


class EquipmentDAO extends DAO
{
    protected $table = 'equipments';
    protected $prefix = 'eq';

    public function persist($datas)
    {
        if(!isset($datas['eq_id'])) {
            $cc_dao = new CleaningContextDAO([
                'db_host' => $this->host,
                'db_user' => $this->user,
                'db_pass' => $this->password,
                'db_name' => $this->db_name,
                'db_type' => $this->type
            ]);
            $id = $cc_dao->persist(['cc_id' => null]);
            $datas['eq_id'] = $id;
        }
        return parent::persist($datas);
    }

    public function delete($id)
    {
        $cc_dao = new CleaningContextDAO([
            'db_host' => $this->host,
            'db_user' => $this->user,
            'db_pass' => $this->password,
            'db_name' => $this->db_name,
            'db_type' => $this->type
        ]);
        $cc_dao->delete($id);
        parent::delete($id);
    }
}