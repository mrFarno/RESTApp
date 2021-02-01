<?php


namespace app\DAO;


class BiowastesDAO extends DAO
{
    protected $table = 'biowastes';
    protected $prefix = 'bw';


    public function by_year($year) {
        $request = 'SELECT * FROM biowastes
                    WHERE bw_date LIKE :year;';

        $stmt = $this->getPDO()->prepare($request);
        $stmt->execute([
            ':year' => $year.'-%'
        ]);
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }
}