<?php
include('config.php');

class db extends PDO
{
    // single instance of self shared among all instances
    private static $instance = null;

    // db connection config vars
    private $user = DB_USER;
    private $pass = DB_PWD;
    private $dbName = DB_NAME;
    private $dbHost = DB_HOST;

    public function __construct()
    {
        try{
            $dsn = "mysql:host={$this->dbHost};dbname={$this->dbName}";
            parent::__construct($dsn, $this->user, $this->pass);
            $this->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        }catch(PDOException $e) {
            echo $e->getMessage();
        }

    }

    function getSingleValue($sql, $parameters)
    {
        $stm = $this->prepare($sql);
        $stm->execute($parameters);
        return $stm->fetchColumn();
    }

    function getValues($sql, $parameters){
        $stm = $this->prepare($sql);
        $stm->execute($parameters);
        return $stm->fetchAll();
    }
}
