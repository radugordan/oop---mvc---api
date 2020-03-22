<?php

define("DB_HOST", 'localhost');
define("DB_NAME", 'php_learning');
define("DB_USER", 'root');
define("DB_PASS", '');

class DB {
    protected $dbh;
    protected $sth;
    
    function __construct() {
        try {
            $this->dbh = new PDO('mysql:host='.DB_HOST. ';dbname='.DB_NAME, DB_USER, DB_PASS);

        } catch (PDOExeption $e) {
            print "Error!: " . $e->getMessage(); 
        }
    }
    
     protected function selectAll($sql) {
         $this->sth = $this->dbh->prepare($sql);
         $this->sth->execute();
         return $this->sth->fetchAll(PDO::FETCH_ASSOC);
     }   

    protected function countAll() {
        return $this->sth->rowCount();    
    }

    protected function addItem($sql, $data) {  
        $this->sth = $this->dbh->prepare($sql);
        $this->sth->execute($data);
        return $this->dbh->lastInsertId();
    }
    
    protected function selectItem ($sql, $data=array()) {
        $this->sth = $this->dbh->prepare($sql);
        $this->sth->execute($data);
        return $this->sth->fetch(PDO::FETCH_ASSOC);
    }    
    protected function selectItems ($sql, $data=array()) {
        $this->sth = $this->dbh->prepare($sql);
        $this->sth->execute($data);
        return $this->sth->fetchAll(PDO::FETCH_ASSOC);
    }   
    
    protected function checkItem ($sql, $data=array()) {
        $this->sth = $this->dbh->prepare($sql);
        $this->sth->execute($data);
        return $this->sth->rowCount();
    } 
    
    
    protected function updateItem($sql, $data) {
        $this->sth = $this->dbh->prepare($sql);
        $this->sth->execute($data);
        return $this->sth->rowCount();   
    }
    
    protected function removeItem($sql, $data) {
        $this->sth = $this->dbh->prepare($sql);
        $this->sth->execute($data); 
        return $this->sth->rowCount();
    }
}






?>