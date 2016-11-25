<?php

class Db
{
    protected $PDO;

    public function __construct()
    {
        $driver_options = [
            PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES   => false,
        ];
        $this->PDO = new PDO(DB_DSN, DB_USER, DB_PASS, $driver_options);
    }

    public function execute($query){
        $stmt = $this->PDO->prepare($query);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    /**
     * @param $table string - Table name
     * @param $data - array - Search array
     * @param null $limit
     * @param string $columns
     * @return array
     */
    public function fetch($table,$data = null, $columns = "*",$limit = null)
    {
        $conditions = array();
        if($data != null) {
            foreach ($data as $column => $value) {
                if (is_string($value)) {
                    $conditions[] = "$column LIKE '$value'";
                } else if (is_float($value) || is_int($value)) {
                    $conditions[] = "$column = $value";
                } else if (is_array($value)) {
                    $conditions[] = "$column IN ( " . implode(",", $value) . " )";
                }
            }
            $conditions = implode(" AND ", $conditions);
            $query ="SELECT $columns FROM `$table` WHERE $conditions";
        }else{
            $query =" SELECT $columns FROM `$table`";
        }

        if($limit !== null)
            $query .= " LIMIT $limit";
        $stmt = $this->PDO->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function fetchOne($table,$data = null, $columns = "*",$limit = null){
        $res = $this->fetch($table,$data,$columns,$limit);
        return reset($res);
    }

    public function delete($table,$data){
        foreach ($data as $column => $value) {
            if (is_string($value)) {
                $conditions[] = "$column LIKE '$value'";
            } else if (is_float($value) || is_int($value)) {
                $conditions[] = "$column = $value";
            } else if (is_array($value)) {
                $conditions[] = "$column IN ( " . implode(",", $value) . " )";
            }
        }
        $conditions = implode(" AND ", $conditions);
        $query ="DELETE FROM `$table` WHERE $conditions";
        $stmt = $this->PDO->prepare($query);
        return $stmt->execute();
    }

    /**
     * @param $table string - Table Name
     * @param $data array - e.g. ('columnName1' => value1, 'columnName2' => value2)
     * @return int last inserted id
     */
    public function insert($table,$data){
        $columns = "`" . implode("`,`", array_keys($data)) . "`";
        $valueNames = " :" . implode(", :", array_keys($data));
        $query =  "INSERT INTO `$table` ($columns) VALUES ($valueNames)";

        $stmt = $this->PDO->prepare($query);
        foreach ($data as $column => $value){
            $stmt->bindValue(':'.$column, $value);
        }
        $stmt->execute();
        return $this->PDO->lastInsertId();
    }

    public function update($table,$data,$id){
        $sets= [];
        foreach ($data as $key => $val)
            $sets[] = "$key = :$key ";

        $sets = implode(",",$sets);
        $query =  "UPDATE `$table` SET $sets WHERE id=$id ";

        $stmt = $this->PDO->prepare($query);
        foreach ($data as $column => $value){
            $stmt->bindValue(':'.$column, $value);
        }
        $stmt->execute();
    }

    public function updateWhere(){

    }

    public function getLastInsertId(){
        return $this->PDO->lastInsertId();
    }

    public function findUser($loginData)
    {
        if( $user = $this->fetchOne("user",array("username" => $loginData["username"]))){
            $passwordIsCorrect = password_verify($loginData["password"], $user["password"]);
            if($passwordIsCorrect){
                return $user;
            }
        }
        return false;
    }



}