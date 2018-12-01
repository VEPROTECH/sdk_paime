<?php
/**
 * Created by PhpStorm.
 * User: Verbeck DEGBESSE
 * Date: 01/09/2018
 * Time: 00:51
 */

class DB
{
    private $host="localhost";
    private $database="ampayeur";
    private $username="root";
    private $password="";
    private $db;

    public function __construct($host=null,$username=null,$password=null,$database=null){
        if($host !=null){
            $this->host=$host;
            $this->username=$username;
            $this->password=$password;
            $this->database=$database;
        }
        try{
            $this->db=new PDO('mysql:host='.$this->host.';dbname='.$this->database,$this->username,
                $this->password,array(
                    PDO::MYSQL_ATTR_INIT_COMMAND=>'SET NAMES UTF8',
                    PDO::ATTR_ERRMODE=>PDO::ERRMODE_WARNING
                ));
        } catch (PDOException $ex) {
            http_response_code(404);
            die("<h1>Impossible de se connecter à la base de données</h1>");
        }
    }

    public function query($sql,$data=array())
    {
        $result=array();
        $req=$this->db->prepare($sql);
        $req->execute($data);
        if($req)
        {
            while ($row=$req->fetchAll(PDO::FETCH_OBJ))
            {
                $result=$row;
            }
        }
        return $result;
    }


    public function verify($table,$field, $code)
    {
        $req=$this->db->prepare("select * from ".$table." where ".$field."= ?");
        $req->execute(array($code));
        if($req->rowCount() > 0){
            return true;
        }else{
            return false;
        }
    }

    public function update($sql,$data=array())
    {
        $result=array();
        $req=$this->db->prepare($sql);
        $req->execute($data);
        if ($req)
        {
            $result["msg"]="Enregistrement effectué avec succès !";
        }else{
            http_response_code(500);
            $result["msg"]="Une erreur s'est produite lors du traitement !";
        }
        return $result;
    }

    public function insert($sql,$table,$field,$code,$data=array())
    {
        $result=array();
        if(!empty($data)){
            if($this->verify($table, $field, $code) == false){
                $req=$this->db->prepare($sql);
                $req->execute($data);
                if($req)
                {
                    $result["msg"]="Enregistrement effectué avec succès !";

                }else{
                    http_response_code(500);
                    $result["msg"]="Une erreur s'est produite lors de l'enregistrement !";
                }
            }else{
                http_response_code(201);
                $result["msg"]="Cet email existe déjà !";
            }
        }else{
            http_response_code(400);
            $result["msg"]="Champ(s) vide(s) !";
        }
        return $result;

    }


    public function register($sql,$table,$field,$code,$data=array())
    {
        $result=array();
        if(!empty($data)){
            if($this->verify($table, $field, $code) == false){
                $req=$this->db->prepare($sql);
                $req->execute($data);
                if($req)
                {
                    $result["msg"]="Enregistrement effectué avec succès !";

                }else{

                    $result["msg"]="Une erreur s'est produite lors de l'enregistrement !";
                }
            }else{
                $result["msg"]="Cet email existe déjà !";
            }
        }else{
            $result["msg"]="Champ(s) vide(s) !";
        }
        return $result["msg"];

    }


    public function connect($sql,$data=array())
    {
        $result=array();
        if(!empty($data))
        {
            $req=$this->db->prepare($sql);
            $req->execute($data);
            if($req->rowCount() >0)
            {
                http_response_code(200);
                $result["error"]=false;
                $result["msg"]="success";
            }else{
                http_response_code(201);
                $result["error"]=true;
                $result["msg"]="Combinaison login et/ou mot de passe incorrect !";
            }
        }else{
            http_response_code(400);
            $result["error"]=true;
            $result["msg"]="Champ(s) vide(s) !";
        }
        return $result;
    }


}