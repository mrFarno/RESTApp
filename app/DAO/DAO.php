<?php

namespace app\DAO;
use PDO;

abstract class DAO extends PDO
{
    private $host;
    private $user;
    private $password;
    private $db_name;
    private $type;
    private $pdo;
    
    public function __construct(array $connector){
        $this->db_name= $connector['db_name'] ;
        $this->user= $connector['db_user'];
        $this->password= $connector['db_pass'];
        $this->host= $connector['db_host'];
        $this->type= $connector['db_type'];
    }

    public function getPDO(){
        if(!isset($this->pdo)){
            $this->pdo = new PDO($this->type.':host=' . $this->host .
                ';dbname=' .$this->db_name,
                $this->user,
                $this->password,
                [
                    PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8',
                    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
                ]);
        }
        return $this->pdo;
    }

}