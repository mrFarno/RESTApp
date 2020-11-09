<?php


namespace app\DAO;


class SpaceDAO extends DAO
{
    protected $table = 'spaces';
    protected $prefix = 's';

    public function persist($datas)
    {
        $cc_dao = new CleaningContextDAO([
            'db_host' => $this->host,
            'db_user' => $this->user,
            'db_pass' => $this->password,
            'db_name' => $this->db_name,
            'db_type' => $this->type
        ]);
        $id = $cc_dao->persist(['cc_id' => null]);
        $datas['s_id'] = $id;
        return parent::persist($datas);
    }
}