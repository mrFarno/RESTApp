<?php


namespace app\DAO;


class EventsDAO extends DAO
{
    protected $table = 'events';
    protected $prefix = 'ev';

    public function persist($datas)
    {
        if(!isset($datas['ev_id'])) {
            $tc_dao = new TaskContextDAO([
                'db_host' => $this->host,
                'db_user' => $this->user,
                'db_pass' => $this->password,
                'db_name' => $this->db_name,
                'db_type' => $this->type
            ]);
            $id = $tc_dao->persist(['tc_type' => 'events']);
            $datas['ev_id'] = $id;
        }
        return parent::persist($datas);
    }

    public function delete($id)
    {
        $tc_dao = new TaskContextDAO([
            'db_host' => $this->host,
            'db_user' => $this->user,
            'db_pass' => $this->password,
            'db_name' => $this->db_name,
            'db_type' => $this->type
        ]);
        $tc_dao->delete($id);
        parent::delete($id);
    }
}