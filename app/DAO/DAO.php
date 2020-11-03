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
    protected $table;
    protected $prefix;

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
     * Generic persist function
     * Override when entities in BO
     * @param datas : Object if entity in BO, array else
     */
    public function persist($datas) {
        if (isset($datas[$this->prefix.'_id']) && $datas[$this->prefix.'_id'] !== null) {
            $update = true;
            $request = 'UPDATE '.$this->table.' SET ';
            foreach ($datas as $key => $value) {
                if ($key !== $this->prefix.'_id') {
                    $request .= $key.'= :'.$key.', ';
                }
            }
            $request = substr($request, 0, -2);
            $request .= ' WHERE '.$this->prefix.'_id = :'.$this->prefix.'_id;';
        } else {
            $update = false;
            $request = 'INSERT INTO '.$this->table.' (';
            foreach ($datas as $key => $value) {
                if ($key !== $this->prefix.'_id') {
                    $request .= $key.',';
                }
            }
            $request = substr($request, 0, -1);
            $request .= ') VALUES (';
            foreach ($datas as $key => $value) {
                if ($key !== $this->prefix.'_id') {
                    $request .= ':'.$key.',';
                }
            }
            $request = substr($request, 0, -1);
            $request .= ');';
        }
        $binds = [];
        foreach ($datas as $key => $value) {
            $binds[':'.$key] = $value;
        }
        if ($update !== true) {
            unset($binds[':'.$this->prefix.'_id']);
        }
        $stmt = $this->getPDO()->prepare($request);
        $stmt->execute($binds);

        return true;
    }

    /**
     * General all function for entities not in BO
     * @return array
     */
    public function all() {
        $request = 'SELECT * FROM '.$this->table.';';
        return $this->getPDO()->query($request)->fetchAll();
    }

    /**
     * General find function for entities not in BO
     * @param array $params associative array filter => value
     * @param boolean $force_array TRUE if result can be array. Used in specific find functions for entities in BO, ignored here
     * TODO replace filter/value by associative array to filter by several cols
     * @return array
     */
    public function find($params, $force_array = false){
        $request = 'SELECT * FROM '.$this->table;
        $i = 0;
        $binds = [];
        foreach ($params as $filter => $value) {
            if ($i === 0) {
                $request .= ' WHERE '.$filter.' = :value'.$i;
            } else {
                $request .= ' AND '.$filter.' = :value'.$i;
            }
            $binds[':value'.$i] = $value;
            $i++;
        }
        $stmt = $this->getPDO()->prepare($request);
        $stmt->execute($binds);
        $result = $stmt->fetchAll();
        $data = [];
        foreach ($result as $row) {
            $data[$row[$this->prefix.'_id']] = $row;
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