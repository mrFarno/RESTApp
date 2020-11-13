<?php


namespace app\DAO;


class TaskContextDAO extends DAO
{
    protected $table = 'task_contexts';
    protected $prefix = 'tc';

    public function persist($datas)
    {
        $request = 'INSERT INTO task_contexts (tc_id, tc_type) VALUES (NULL, \''.$datas['tc_type'].'\');';
        $this->getPDO()->exec($request);

        return $this->getPDO()->lastInsertId();
    }
}