<?php

namespace app\DAO\Form;
use app\DAO\DAO;

class FormElementContentDAO extends DAO
{

    /**
     * @param string $filter Column to filter by
     * @param string $value Targetet value
     * 
     * @return mixed array of FormElementContent objects if several results, one FormElementContent object else
     */
    public function find($filter, $value){
        $request = 'SELECT fec_id, fec_html_id, fec_html_name, fec_html_value, fec_parent_id, fec_referent_id, fe_type FROM FormElementContent 
                    INNER JOIN FormELement
                    WHERE fec_parent_id = fe_id
                    AND '.$filter.' = :value;';

        $stmt = $this->getPDO()->prepare($request);
        $stmt->execute([
            ':value' => $value
        ]);
        $result = $stmt->fetchAll();
        $data = [];
        foreach ($result as $row) {
            $type = explode('::', $row['fe_type']);
            $type = ucfirst($type[0]).ucfirst($type[1]);
    
            $data[] = new $type($row);
        }
        switch (count($data)) {
            case 0 : return false;
                    break;
            case 1 : return $data[0];
                    break; 
            default : return $data;
        }
    }

}