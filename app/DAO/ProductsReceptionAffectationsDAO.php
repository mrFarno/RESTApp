<?php


namespace app\DAO;


class ProductsReceptionAffectationsDAO extends DAO
{
    protected $table = 'products_reception_affectations';
    protected $prefix = 'pra';

    public function persist($datas)
    {
        if(!isset($datas['pra_id'])) {
            $af_dao = new AffectationDAO([
                'db_host' => $this->host,
                'db_user' => $this->user,
                'db_pass' => $this->password,
                'db_name' => $this->db_name,
                'db_type' => $this->type
            ]);
            $id = $af_dao->persist(['af_id' => null]);
            $datas['pra_id'] = $id;
        }
        return parent::persist($datas);
    }

    public function affectations_by_restaurant($r_id) {
        $request = 'SELECT * FROM products_reception_affectations
                    INNER JOIN employements ON pra_employement_id = e_id
                    WHERE e_restaurant_id = '.$r_id.';';
        $result =  $this->getPDO()->query($request)->fetchAll();
        $datas = [];
        foreach ($result as $row) {
            $datas[$row['e_user_id']] = $row;
        }

        return $datas;
    }
}