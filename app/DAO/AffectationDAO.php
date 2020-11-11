<?php


namespace app\DAO;


class AffectationDAO extends DAO
{
    protected $table = 'affectations';
    protected $prefix = 'af';

    public function persist($datas)
    {
        $request = 'INSERT INTO affectations (af_id) VALUES (NULL);';
        $this->getPDO()->exec($request);

        return $this->getPDO()->lastInsertId();
    }
}