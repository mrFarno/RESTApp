<?php


namespace app\DAO;


class CleaningContextDAO extends DAO
{
    protected $table = 'cleaning_contexts';
    protected $prefix = 'cc';

    public function persist($datas)
    {
        $request = 'INSERT INTO cleaning_contexts (cc_id) VALUES (NULL);';
        $this->getPDO()->exec($request);

        return $this->getPDO()->lastInsertId();
    }
}