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

    /**
     * @param $datas
     * @return bool
     */
    public function persist($meal)
    {
        if ($this->find(['m_id' => $meal->getId()]) !== false) {
            $update = true;
            $request = 'UPDATE meals SET
                            m_restaurant_id = :r_id,
                            m_type_id = :type_id,
                            m_check_team = :check_team,
                            m_check_team_equipment = :check_team_equipment,
                            m_check_equipment = :check_equipment,
                            m_check_cutlery = :check_cutlery,
                            m_check_products = :check_products,
                            m_expected_guests = :expected_guests,
                            m_absences_guests = :absences_guests,
                            m_real_guests = :real_guests,
                            m_date = :date
                        WHERE m_id = :id;';
        } else {
            $update = false;
            $request = 'INSERT INTO meals (m_restaurant_id, 
                                            m_type_id, 
                                            m_check_team, 
                                            m_check_team_equipment, 
                                            m_check_equipment, 
                                            m_check_cutlery, 
                                            m_check_products, 
                                            m_expected_guests, 
                                            m_absences_guests, 
                                            m_real_guests, 
                                            m_date) VALUES (
                            :r_id,
                            :type_id,
                            :check_team,
                            :check_team_equipment,
                            :check_equipment,
                            :check_cutlery,
                            :check_products,
                            :expected_guests
                            :absences_guests
                            :real_guests
                            :date
                        );';
        }
        $stmt = $this->getPDO()->prepare($request);
        $binds = [
            ':r_id' => $meal->getRestaurantId(),
            ':type_id' => $meal->getType(),
            ':check_team' => $meal->getCheck_team(),
            ':check_team_equipment' => $meal->getCheck_team_equipment(),
            ':check_equipment' => $meal->getCheck_equipment(),
            ':check_cutlery' => $meal->getCheck_cutlery(),
            ':check_products' => $meal->getCheck_products(),
            ':expected_guests' => $meal->getExpectedGuests(),
            ':absences_guests' => $meal->getAbsencesGuests(),
            ':real_guests' => $meal->getRealGuests(),
            ':date' => $meal->getDate()
        ];
        if ($update === true) {
            $binds[':id'] = $meal->getId();
        }
        $stmt->execute($binds);
        if ($update === false) {
            $meal->setId($this->getPDO()->lastInsertId());
        }
        return true;
    }
}