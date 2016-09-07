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

    public function insert($data,$table){


        $stmt = $this->PDO->prepare("INSERT INTO `$table` (`username`, `password`,`nickname`, `email`) 
        VALUES (:username, :password, :nickname, :email)");
        $stmt->bindParam(':username', $username);
        $stmt->bindParam(':password', $password);
        $stmt->bindParam(':nickname', $nickname);
        $stmt->bindParam(':email', $email);
    }




}