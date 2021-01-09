<?php


namespace app\DAO;


class RecipeSheetDAO extends DAO
{
    protected $table = 'recipe_sheets';
    protected $prefix = 'rs';

    public function persist($datas)
    {
        if(!isset($datas['rs_id'])) {
            $tc_dao = new TaskContextDAO([
                'db_host' => $this->host,
                'db_user' => $this->user,
                'db_pass' => $this->password,
                'db_name' => $this->db_name,
                'db_type' => $this->type
            ]);
            $id = $tc_dao->persist(['tc_type' => 'production']);
            $datas['rs_id'] = $id;
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

    public function done_parts($rs_id) {
        $request = 'SELECT * FROM tasks
                    INNER JOIN task_affectations ON ta_task_id = t_id
                    WHERE t_target_id = '.$rs_id.';';

        $result = $this->getPDO()->query($request)->fetchAll();
        $total = 0;
        $done = 0;
        foreach ($result as $row) {
            $total = $total + $row['ta_number'];
            if ($row['ta_done'] == 1) {
                $done = $done + $row['ta_number'];
            }
        }
        return $done.'/'.$total;
    }
}