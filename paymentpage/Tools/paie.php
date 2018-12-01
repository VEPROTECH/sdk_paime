<?php
include 'database.php';
include 'fonction.php';
if(isset($_POST["ID"]) && isset($_POST["montant"])
    && isset($_POST["client"]) && isset($_POST["type"]) && isset($_POST["key"]))
{
    extract($_POST);
   echo  json_encode(virementAchat($montant,$ID,$client,$type,$key));
}