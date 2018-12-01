<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of DB
 *
 * @author Verbeck DEGBESSE
 */
class DB {
    private $host="localhost"; //79.143.188.44
    private $database="ampayeur";
    private $username="root";
    private $password="";
    private $db;

    public function connect(){
        try{
            $this->db=new PDO('mysql:host='.$this->host.';dbname='.$this->database,$this->username,
                $this->password,array(
                        PDO::MYSQL_ATTR_INIT_COMMAND=>'SET NAMES UTF8',
                        PDO::ATTR_ERRMODE=>PDO::ERRMODE_WARNING
                        ));


            return $this->db;


        } catch (PDOException $ex) {
            http_response_code(404);
            die("<h1>Impossible de se connecter à la base de données</h1>");
        }    
}


}
