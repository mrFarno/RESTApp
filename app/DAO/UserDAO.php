<?php

namespace app\DAO;

use app\BO\User;
use Vespula\Auth\Adapter\AdapterInterface;

class UserDAO extends DAO implements AdapterInterface
{

    protected $table = 'users';
    protected $prefix = 'u';

    /**
     * Specific User find function
     * @param string $filter Column to filter by
     * @param string $value Targetet value
     * @param boolean $force_array TRUE if result can be array
     * 
     * @return mixed array of FormElementContent objects if several results, one FormElementContent object else
     */
    public function find($filter, $value, $force_array = false){
        $request = 'SELECT * FROM users
                    WHERE '.$filter.' = :value;';
        $stmt = $this->getPDO()->prepare($request);
        $stmt->execute([
            ':value' => $value
        ]);
        $result = $stmt->fetchAll();
        $data = [];
        foreach ($result as $row) {
            $data[$row['u_id']] = new User($row);
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
     * @param $user User to add/update
     * Check if mail already exist. If true update else insert
     * @return true
     */
    public function persist($user) {
        if ($this->find('u_email', $user->getEmail()) !== false) {
            $update = true;
            $request = 'UPDATE users SET
                            u_firstname = :firstname,
                            u_lastname = :lastname,
                            u_password = :password,
                            u_email = :email,
                            u_token = :token,
                            u_role = :role
                        WHERE u_id = :id;';
        } else {
            $update = false;
            $request = 'INSERT INTO users (u_firstname, u_lastname, u_password, u_email, u_token, u_role) VALUES (
                            :firstname,
                            :lastname,
                            :password,
                            :email,
                            :token,
                            :role
                        );';
        }
        $stmt = $this->getPDO()->prepare($request);
        $binds = [
            ':firstname' => $user->getFirstname(),
            ':lastname' => $user->getLastname(),
            ':password' => $user->getPassword(),
            ':email' => $user->getEmail(),
            ':token' => $user->getToken(),
            ':role' => $user->getRole()
        ];
        if ($update === true) {
            $binds[':id'] = $user->getId();
        }
        $stmt->execute($binds);
        if ($update === false) {
            $user->setId($this->getPDO()->lastInsertId());
        }
        return true;
    }

    // --- AddapterInterface methods implementation ---

    public function authenticate(array $credentials)
    {
        $request = 'SELECT * FROM users
                    WHERE u_email = :username;';

        $stmt = $this->getPDO()->prepare($request);
        $stmt->execute([
            ':username' => $credentials['username']
        ]);
        $result = $stmt->fetch();
        return password_verify($credentials['password'], $result['u_password']);

    }

    public function lookupUserData($username)
    {
        return $this->find('u_email', $username);
    }

    public function getError()
    {
        return 'Error';
    }
}