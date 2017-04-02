<?php
class Database{
    private $host      = '127.0.0.1';
    private $user      = 'sa';
    private $pass      = '';
    private $dbname    = '';
 
    private $con;
    private $link;
    private $error;
    private $stmt;
 
    public function __construct(){
        $this->con = mssql_connect($this->host, $this->user, $this->pass);
        if($this->con){
            $this->link = mssql_select_db($this->dbname, $this->con);
        }else{
            die('Unnable to connect to SQL Server!');
        }
    }

    public function query($query){
        $this->stmt = $this->dbh->prepare($query);
    }

    public function result(){
        $this->execute();
        return $this->stmt->fetch(PDO::FETCH_ASSOC);
    }    

    public function results(){
        $this->execute();
        return $this->stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getLastInsertId(){
        return $this->dbh->lastInsertId();
    }





}