<?php


namespace app\DAO;


use app\BO\Meal;

class MealDAO extends DAO
{
    protected $table = 'meals';
    protected $prefix = 'm';

    /**
     * @param array $params
     * @param false $force_array
     * @return array|void
     */
    public function find($params, $force_array = false)
    {
        $result = parent::find($params, true);
        $data = [];
        foreach ($result as $row) {
            $data[$row['m_id']] = new Meal($row);
        }
        switch (count($data)) {
            case 0 :
                if ($force_array === true) {
                    return [];
                }
                return false;
                break;
            case 1 :
                if ($force_array === true) {
                    return $data;
                }
                return reset($data);
                break;
            default : return $data;
        }
    }
}