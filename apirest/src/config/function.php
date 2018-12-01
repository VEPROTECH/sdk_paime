<?php
/**
 * Created by PhpStorm.
 * User: PEROGROUPE
 * Date: 21/09/2018
 * Time: 14:54
 */
function generateToken($nbre){
    $token=openssl_random_pseudo_bytes($nbre);
    $token=bin2hex($token);
    return $token;
}

function getApp($code,$env){
    $bdd=new DB();
    $bdd=$bdd->connect();
    $result=array();
    $req=null;
    if($env == 'live'){
        $req=$bdd->prepare("select * from application where pro_key=?");
        $req->execute(array($code));
        if($req)
        {
            $row=$req->fetch(PDO::FETCH_OBJ);
            $result=$row;
        }

        return $result;
    }elseif ($env == 'test'){
        $req=$bdd->prepare("select * from application where  test_key=?");
        $req->execute(array($code));
        if($req)
        {
            $row=$req->fetch(PDO::FETCH_OBJ);
            $result=$row;
        }

        return $result;
    }else{
        return false;
    }


}

function info_marchand($code){
    $bdd=new DB();
    $bdd=$bdd->connect();
    $result=array();
    $req=$bdd->prepare("select * from compte_marhand where  app_connect=?");
    $req->execute(array($code));
    if($req)
    {
        $row=$req->fetch(PDO::FETCH_OBJ);
        $result=$row;
    }

    return $result;
}



function formatToNumber($value, $decimals = 2)
{
    if (trim($value) != null) {
        return number_format($value, $decimals, '.', '  ');
    }
    return null;
}

function devise($value){
    $all_devise=array('FCFA','EUR','XOF');
    if(in_array($value,$all_devise)){
        return true;
    }else{
        return false;
    }
}

function formatMoney($value, $decimals = 2)
{
    if (trim($value) != null) {
        return number_format($value, null, '.', '  ');
    }
    return null;
}