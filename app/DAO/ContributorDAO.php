<?php

namespace app\DAO;

use app\BO\Contributor;
use Vespula\Auth\Adapter\AdapterInterface;

class ContributorDAO extends DAO implements AdapterInterface
{

    /**
     * @param string $filter Column to filter by
     * @param string $value Targetet value
     * @param boolean $force_array TRUE if result can be array
     * 
     * @return mixed array of FormElementContent objects if several results, one FormElementContent object else
     */
    public function find($filter, $value, $force_array = false){
        $request = 'SELECT * FROM Contributor
                    WHERE '.$filter.' = :value;';

        $stmt = $this->getPDO()->prepare($request);
        $stmt->execute([
            ':value' => $value
        ]);
        $result = $stmt->fetchAll();
        $data = [];
        foreach ($result as $row) {
            $data[] = new Contributor($row);
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
                return $data[0];
                break; 
            default : return $data;
        }
    }

    /**
     * @param Contributor $contributor User to add/update
     * Check if mail already exist. If true update else insert
     * @return true
     */
    public function persist(Contributor $contributor) {
        if ($this->find('c_mail', $contributor->getMail()) !== false) {
            $update = true;
            $request = 'UPDATE Contributor SET
                            c_login = :c_login,
                            c_password = :c_password,
                            c_label = :c_label,
                            c_mail = :c_mail,
                            c_token = :c_token,
                            c_role = :c_role
                        WHERE c_id = :c_id;';
        } else {
            $update = false;
            $request = 'INSERT INTO Contributor (c_login, c_password, c_label, c_mail, c_token, c_role) VALUES (
                            :c_login,
                            :c_password,
                            :c_label,
                            :c_mail,
                            :c_token,
                            :c_role
                        );';
        }
        $stmt = $this->getPDO()->prepare($request);
        $binds = [
            ':c_login' => $contributor->getLogin(),
            ':c_password' => $contributor->getPassword(),
            ':c_label' => $contributor->getLabel(),
            ':c_mail' => $contributor->getMail(),
            ':c_token' => $contributor->getToken(),
            ':c_role' => $contributor->getRole()
        ];
        if ($update === true) {
            $binds[':c_id'] = $contributor->getId();
        }
        $stmt->execute($binds);
        return true;
    }

    /**
     * Delete user
     * @param int $id Id to delete
     * @return true
     */
    public function delete($id) {
        $request = 'DELETE FROM Contributor WHERE c_id = :c_id';
        $stmt = $this->getPDO()->prepare($request);
        $stmt->execute([
            ':c_id' => $id
        ]);
        return true;
    }

    // --- AddapterInterface methods implementation ---

    public function authenticate(array $credentials)
    {
        $request = 'SELECT * FROM Contributor
                    WHERE c_login = :username;';

        $stmt = $this->getPDO()->prepare($request);
        $stmt->execute([
            ':username' => $credentials['username']
        ]);
        $result = $stmt->fetch();
        return password_verify($credentials['password'], $result['c_password']);

    }

    public function lookupUserData($username)
    {
        return $this->find('c_login', $username);
    }

    public function getError()
    {
        return 'Error';
    }
}