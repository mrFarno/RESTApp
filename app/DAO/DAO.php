<?php

namespace app\DAO;

abstract class DAO extends \PDO
{
    private $host;
    private $user;
    private $password;
    private $db_name;
    private $type;
    private $pdo;
    private $table;
    private $prefix;

    public function __construct(array $connector){
        $this->db_name= $connector['db_name'] ;
        $this->user= $connector['db_user'];
        $this->password= $connector['db_pass'];
        $this->host= $connector['db_host'];
        $this->type= $connector['db_type'];
    }

    protected function getPDO(){
        if(!isset($this->pdo)){
            error_reporting(E_ALL);
            ini_set('display_errors', 1);
            $this->pdo = new \PDO($this->type.':host=' . $this->host .
                ';dbname=' .$this->db_name,
                $this->user,
                $this->password,
                [
                    \PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8',
                    \PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION,
                    \PDO::ATTR_DEFAULT_FETCH_MODE => \PDO::FETCH_ASSOC
                ]);
        }
        return $this->pdo;
    }

    /**
     * General find function for entities not in BO
     * @param string $filter Column to filter by
     * @param string $value Targetet value
     * @param boolean $force_array TRUE if result can be array. Used in specific find functions for entities in BO, ignored here
     * 
     * @return array
     */
    public function find($filter, $value, $force_array = false){
        $request = 'SELECT * FROM '.$this->table.'
                    WHERE '.$filter.' = :value;';

        $stmt = $this->getPDO()->prepare($request);
        $stmt->execute([
            ':value' => $value
        ]);
        return $stmt->fetchAll();
    }

    /**
     * Delete
     * @param int $id Id to delete
     * @return true
     */
    public function delete($id) {
        $request = 'DELETE FROM '.$this->table.' WHERE '.$this->prefix.'_id = :id';
        $stmt = $this->getPDO()->prepare($request);
        $stmt->execute([
            ':id' => $id
        ]);
        return true;
    }

}