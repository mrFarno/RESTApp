<?php


namespace app\DAO;


class SatisfactionPollDAO extends DAO
{
    protected $table = 'satisfaction_polls';
    protected $prefix = 'sp';

    public function by_type_day($mt, $day) {
        $request = 'SELECT * FROM satisfaction_polls
                                    INNER JOIN meals ON sp_meal_id = m_id
                                    WHERE m_type_id = :mt AND m_date = :day;';

        $stmt = $this->getPDO()->prepare($request);
        $stmt->execute([
            ':mt' => $mt,
            ':day' => $day
        ]);
        return $stmt->fetch();
    }
}