<?php
include 'database.php';

function verify($table,$field, $code)
{
    global $bdd;
    $req=$bdd->prepare("select * from ".$table." where ".$field."= ?");
    $req->execute(array($code));
    if($req->rowCount() > 0){
        return true;
    }else{
        return false;
    }
}

function get_user_ip() {
    // IP si internet partagé
    if (isset($_SERVER['HTTP_CLIENT_IP'])) {
        return $_SERVER['HTTP_CLIENT_IP'];
    }
    // IP derrière un proxy
    elseif (isset($_SERVER['HTTP_X_FORWARDED_FOR'])) {
        return $_SERVER['HTTP_X_FORWARDED_FOR'];
    }
    // Sinon : IP normale
    else {
        return (isset($_SERVER['REMOTE_ADDR']) ? $_SERVER['REMOTE_ADDR'] : '');
    }


    //return  "197.234.221.157";
}


//achat en ligne
function getAchatTransation($no)
{
    global $bdd;
    $result=array();
    $sql=$bdd->prepare("select * from infos_tmp where ID_trans=?");
    $sql->execute(array($no));
    if($sql->rowCount() >0 )
    {

        $row=$sql->fetch(PDO::FETCH_OBJ);
        $result=$row;

    }

    return $result;
}
function getAchatFacture($no)
{
    global $bdd;
    $result=array();
    $sql=$bdd->prepare("select * from facturation_tmp where ref=?");
    $sql->execute(array($no));
    if($sql->rowCount() >0 )
    {

        $row=$sql->fetch(PDO::FETCH_OBJ);
        $result=$row;

    }

    return $result;
}
function formatMoney($value, $decimals = 2)
{
    if (trim($value) != null) {
        return number_format($value, null, '.', '  ');
    }
    return null;
}

function formatMoney2($value, $decimals = 2)
{
    if (trim($value) != null) {
        return number_format($value, null, '.', '');
    }
    return null;
}
function getAllProduit($ref)
{
    global $bdd;
    $result=array();
    $req=$bdd->prepare("select * from produit_tmp where ref_vente=?");
    $req->execute(array($ref));
    if($req)
    {
        while ($row=$req->fetch(PDO::FETCH_OBJ))
        {
            $result[]=$row;
        }

    }

    return $result;
}


function getMarchand($key,$type)
{
    global $bdd;
    $result=array();
    $sql=null;
    if($type=="test")
    {
        $sql=$bdd->prepare("select * from application where test_key=?");
    }elseif ($type=="live")
    {
        $sql=$bdd->prepare("select * from application where pro_key=?");
    }

    $sql->execute(array($key));
    if($sql->rowCount() >0 )
    {

        $row=$sql->fetch(PDO::FETCH_OBJ);
        $result=$row;

    }

    return $result;
}


function virementAchat($montant,$no,$mylogin,$type,$key)
{
    global  $bdd;
    $message=array();

    $req=$bdd->prepare("select * from users where code=?");
    $req->execute(array($mylogin));
    if($req->rowCount() == 1)
    {
        if(permission_service($mylogin)->virement == "true")
        {
            if($type=="test")
            {
                if(verify("infos_tmp","ID_trans",$no) == true)
                {
                    if(verify("transaction","no_trans",$no) == false)
                    {
                        if(getMontantUser($mylogin)->montant_test >0 && getMontantUser($mylogin)->montant_test >= $montant)
                        {
                            $op='VIR'; //virement
                            $typ="ACHA"; //achat

                            if(count(getMarchand($key,$type)) == 1) //s'il trouve
                            {
                                $des=getMarchand($key,$type)->code_user_app;
                                $sq=$bdd->prepare("insert into transaction(no_trans, operation, typ_virement, date, montant, code_user_sender, code_user_receiver) values (?,?,?,NOW(),?,?,?)");
                                $sq->execute(array($no,$op,$typ,$montant,$mylogin,$des));
                                if($sq)
                                {
//                                    inserNotif($des,"Vous avez reçu sur votre compte, un montant de ".$montant." FCFA venant de ".
//                                        infos_user($mylogin)->nom." ".infos_user($mylogin)->prenom." d'un client pour la vente en ligne");

                                    //augmenter chez lui
                                    $tot1=0;
                                    if($type=="test")
                                    {
                                        $tot1=getMontantUser($des)->montant_test + $montant;
                                    }elseif ($type=="live")
                                    {
                                        $tot1=getMontantUser($des)->montant_commerce + $montant;
                                    }
                                    updateMontant($des,$tot1,$type);

                                    //Diminuer chez moi
                                    $tot2=0;
                                    if($type=="test")
                                    {
                                        $tot2=getMontantUser($mylogin)->montant_test - $montant;
                                    }elseif ($type=="live")
                                    {
                                        $tot2=getMontantUser($mylogin)->total - $montant;
                                    }

                                    updateMontant($mylogin,$tot2,$type);

                                    //enregistrement possible
                                    $data=getAchatTransation($no);
                                    $facture=getAchatFacture($data->ref_id);

                                    //2-facturaction
                                    $fac=$bdd->prepare("insert into facturation(ref, date_vente, client_id, marchand) values (?,?,?,?)");
                                    $fac->execute(array($data->ref_id,$facture->date_vente,$mylogin,$facture->marchand));

                                    //1- les produits
                                    foreach (getAllProduit($data->ref_id) as $a)
                                    {
                                        $ins=$bdd->prepare("insert into produit(ref_vente, libelle, prix, quantity) values (?,?,?,?)");
                                        $ins->execute(array($data->ref_id,$a->libelle,$a->prix,$a->quantity));
                                    }


                                    $message["msg"]="Paiement effectué avec succès pour votre achat en ligne. Merci pour votre confiance !";

                                }
                            }


                        }else{
                            $message["error"]="Désolé, Votre solde test est insuffisant. Recharger, puis réessayer. Merci !";
                        }
                    }else{
                        $message["error"]="Vous avez déjà payer !";
                    }
                }else{
                    $message["error"]="Désolé, le numéro de transaction n'existe pas !";
                }


            }elseif ($type=="live")
            {
                if(verify("infos_tmp","ID_trans",$no) == true)
                {
                    if(verify("transaction","no_trans",$no) == false)
                    {
                        if(getMontantUser($mylogin)->total >0 && getMontantUser($mylogin)->total >= $montant)
                        {
                            $op='VIR'; //virement
                            $typ="ACHA"; //achat
                            if(count(getMarchand($key,$type)) == 1) //s'il trouve
                            {
                                $des=getMarchand($key,$type)->code_user_app;
                                $sq=$bdd->prepare("insert into transaction(no_trans, operation, typ_virement, date, montant, code_user_sender, code_user_receiver) values (?,?,?,NOW(),?,?,?)");
                                $sq->execute(array($no,$op,$typ,$montant,$mylogin,$des));
                                if($sq)
                                {
                                    inserNotif($des,"Vous avez reçu sur votre compte, un montant de ".$montant." FCFA d'un client ( ".
                                        infos_user($mylogin)->nom." ".infos_user($mylogin)->prenom." ) pour la vente en ligne");

                                    //augmenter chez lui
                                    $tot1=0;
                                    if($type=="test")
                                    {
                                        $tot1=getMontantUser($des)->montant_test + $montant;
                                    }elseif ($type=="live")
                                    {
                                        $tot1=getMontantUser($des)->montant_commerce + $montant;
                                    }
                                    updateMontantCommerce($des,$tot1,$type);

                                    //Diminuer chez moi
                                    $tot2=0;
                                    if($type=="test")
                                    {
                                        $tot2=getMontantUser($mylogin)->montant_test - $montant;
                                    }elseif ($type=="live")
                                    {
                                        $tot2=getMontantUser($mylogin)->total - $montant;
                                    }
                                    updateMontant($mylogin,$tot2,$type);

                                    //enregistrement possible
                                    $data=getAchatTransation($no);
                                    $facture=getAchatFacture($data->ref_id);

                                    //2-facturaction
                                    $fac=$bdd->prepare("insert into facturation(ref, date_vente, client_id, marchand) values (?,?,?,?)");
                                    $fac->execute(array($data->ref_id,$facture->date_vente,$mylogin,$facture->marchand));

                                    //1- les produits
                                    foreach (getAllProduit($data->ref_id) as $a)
                                    {
                                        $ins=$bdd->prepare("insert into produit(ref_vente, libelle, prix, quantity) values (?,?,?,?)");
                                        $ins->execute(array($data->ref_id,$a->libelle,$a->prix,$a->quantity));
                                    }


                                    $message["msg"]="Paiement effectué avec succès pour votre achat en ligne. Merci pour votre confiance !";

                                }
                            }

                        }else{
                            $message["error"]="Désolé, Votre solde est insuffisant. Recharger, puis réessayer. Merci !";
                        }
                    }else{
                        $message["error"]="Vous avez déjà payer !";
                    }
                }else{
                    $message["error"]="Désolé, le numéro de transaction n'existe pas !";
                }
            }

        }else{
            $message["error"]="Désolé, Vous n'avez l'autorisation de faire un virement. Activé cette fonctionnalité dans vos paramètres";
        }
    }else{
        $message["error"]="Désolé, Vous n'avez pas de compte active !";
    }

    return $message;


}























function app_verif($code,$app){
    global $bdd;
    $result=null;
    $req=$bdd->prepare("select * from compte_marhand where user_created=? and app_connect=?");
    $req->execute(array($code,$app));
    if($req->rowCount() == 1){
        $result=true;
    }else{
        $result=false;
    }

    return $result;
}


function permission_service($user){
    global $bdd;
    $result=null;
    $sql=$bdd->prepare("select * from m_autorisation where user_c=?");
    $sql->execute(array($user));
    if($sql->rowCount() == 1)
    {
        $row=$sql->fetch(PDO::FETCH_OBJ);
        $result=$row;
    }

    return $result;

}

function find_user_groupe(){
    global $bdd;
    $result=null;
    $sql=$bdd->prepare("select * from user_groupe where user_cod=?");
    $sql->execute(array(getUserLogin()));
    if($sql->rowCount() > 0)
    {
        $row=$sql->fetch(PDO::FETCH_OBJ);
        $result=$row;
    }

    return $result;
}

function getcompte_marchand()
{
    global $bdd;
    $result=null;
    $sql=$bdd->prepare("select * from compte_marhand where user_created=?");
    $sql->execute(array(getUserLogin()));
    if($sql->rowCount() >0 )
    {

        $row=$sql->fetch(PDO::FETCH_OBJ);
        $result=$row;

    }

    return $result;
}

function getTransa($no)
{
    global $bdd;
    $result=array();
    $sql=$bdd->prepare("select * from transaction where no_trans=?");
    $sql->execute(array($no));
    if($sql->rowCount() >0 )
    {

        $row=$sql->fetch(PDO::FETCH_OBJ);
        $result=$row;

    }

    return $result;
}

function verify_user_membre($mail)
{
    global $bdd;
    $req=$bdd->prepare("select * from user_autorisation where user_sender=? and email_invite=?");
    $req->execute(array(getUserLogin(),$mail));
    if($req->rowCount() > 0){
        return true;
    }else{
        return false;
    }
}

function getGroupe($id)
{
    global $bdd;
    $result=array();
    $req=$bdd->prepare("select * from groupe where  id=?");
    $req->execute(array($id));
    if($req)
    {
        $row=$req->fetch(PDO::FETCH_OBJ);
        $result=$row;
    }

    return $result;
}

function getAllGroupe()
{
    global $bdd;
    $result=array();
    $req=$bdd->query("select * from groupe");
    if($req)
    {
        while ($row=$req->fetch(PDO::FETCH_OBJ))
        {
            $result[]=$row;
        }

    }

    return $result;
}

function infos_user($code)
{
    global $bdd;
    $result=array();
    $req=$bdd->prepare("select * from users where  code=?");
    $req->execute(array($code));
    if($req)
    {
        $row=$req->fetch(PDO::FETCH_OBJ);
        $result=$row;
    }

    return $result;
}

function infos_user_with_email($email)
{
    global $bdd;
    $result=array();
    $req=$bdd->prepare("select * from users where adresse=?");
    $req->execute(array($email));
    if($req)
    {
        $row=$req->fetch(PDO::FETCH_OBJ);
        $result=$row;
    }

    return $result;
}

function get_nbre_facture()
{
    global $bdd;
    $result=array();
    $req=$bdd->query("select * from compte_marhand");

    if($req)
    {
        $result=$req->rowCount();
    }

    return '0000'.$result;
}

function get_nbre_virement()
{
    global  $bdd;
    $login=getUserLogin();
    $result=0;
    $sql=$bdd->prepare("select * from transaction where  operation='VIR' and code_user_sender=? ");
    $sql->execute(array($login));
    if($sql)
    {
        $result=$sql->rowCount();

    }

    if (in_array($result,array(0,1,2,3,4,5,6,7,8,9)))
    {
        $result="0".$result;
    }
    return $result;
}



function get_nbre_achat()
{
    global $bdd;
    $result=0;
    $req=$bdd->prepare("select * from facturation where client_id=?");
    $req->execute(array(getUserLogin()));

    if($req)
    {
        $result=$req->rowCount();
    }

    if (in_array($result,array(0,1,2,3,4,5,6,7,8,9)))
    {
        $result="0".$result;
    }
    return $result;
}


function get_nbre_app()
{
    global $bdd;
    $result=0;
    $req=$bdd->prepare("select * from application where code_user_app=?");
    $req->execute(array(getUserLogin()));
    if($req)
    {
        $result=$req->rowCount();
    }

    if (in_array($result,array(0,1,2,3,4,5,6,7,8,9)))
    {
        $result="0".$result;
    }
    return $result;

}


function get_data_in_table($code)
{
    global $bdd;
    $result=array();
//    $req=$bdd->prepare("select * from".$table."where".$field."=?");
    $req=$bdd->prepare("select * from compte_marhand where user_created=?");
    $req->execute(array($code));
    if($req)
    {
        $row=$req->fetch(PDO::FETCH_OBJ);
        $result=$row;
    }

    return $result;
}

function infos_user_op_data($code)
{
    global $bdd;
    $result=array();
    $req=$bdd->prepare("select * from users where  op_code=?");
    $req->execute(array($code));
    if($req)
    {
        $row=$req->fetch(PDO::FETCH_OBJ);
        $result=$row;
    }

    return $result;
}

function inserNotif($code,$msg)
{
    global $bdd;
    $statut='NEW';
    $pl=$bdd->prepare(" insert into notification(code_user, message, date, statut) values(?,?,NOW(),?) ");
    $pl->execute(array($code,$msg,$statut));
}



function get_nbre_notif()
{
    global $bdd;
    $statut='NEW';
    $result=0;
    $pl=$bdd->prepare(" select * from notification where  code_user=? and statut=?");
    $pl->execute(array(getUserLogin(),$statut));
    if($pl)
    {
        $result=$pl->rowCount();
    }
    return $result;

}

function get_all_notif()
{
    global  $bdd;
    $login=getUserLogin();
    $statut='NEW';
    $result=array();
    $sql=$bdd->prepare("select * from notification where  code_user=? and statut=? order by date desc ");
    $sql->execute(array($login,$statut));
    if($sql)
    {
        while ($row=$sql->fetch(PDO::FETCH_OBJ))
        {
            $result[]=$row;
        }
    }
    return $result;

}

function get_all_notif_vu()
{
    global  $bdd;
    $login=getUserLogin();
    $statut='VU';
    $result=array();
    $sql=$bdd->prepare("select * from notification where  code_user=? and statut=? order by date desc ");
    $sql->execute(array($login,$statut));
    if($sql)
    {
        while ($row=$sql->fetch(PDO::FETCH_OBJ))
        {
            $result[]=$row;
        }
    }
    return $result;

}


function generateToken($nbre)
{
    $token=openssl_random_pseudo_bytes($nbre);
    $token=bin2hex($token);
    return $token;
}

function getMontantUser($code)
{
    global $bdd;
    $result=array();
    $req=$bdd->prepare("select * from solde where  code_user_solde=?");
    $req->execute(array($code));
    if($req)
    {
        $row=$req->fetch(PDO::FETCH_OBJ);
        $result=$row;
    }

    return $result;
}

function getMontantUserRecharge($code)
{
    global $bdd;
    $result=array();
    $req=$bdd->prepare("select * from recharge_autorisation where  utilisateur=?");
    $req->execute(array($code));
    if($req)
    {
        $row=$req->fetch(PDO::FETCH_OBJ);
        $result=$row;
    }

    return $result;
}

function updateMontantRecharge($code,$value)
{
    global $bdd;
    $sql = $bdd->prepare("update recharge_autorisation set solde=? where utilisateur=?");
    $sql->execute(array($value,$code));
}


function updateMontant($code,$value,$type)
{
    global $bdd;
    $sql=null;
    if($type=="test")
    {
        $sql = $bdd->prepare("update solde set montant_test=? where code_user_solde=?");
    }

    if($type=="live")
    {
        $sql = $bdd->prepare("update solde set total=? where code_user_solde=?");
    }

    $sql->execute(array($value,$code));
}


function updateMontantCommerce($code,$value,$type="live")
{
    global $bdd;
    $sql=null;
    $sql = $bdd->prepare("update solde set montant_commerce=? where code_user_solde=?");
    $sql->execute(array($value,$code));
}


function getCode_Op($code)
{
    global $bdd;
    $result=array();
    $req=$bdd->prepare("select * from parametre where  user_code=?");
    $req->execute(array($code));
    if($req)
    {
        $row=$req->fetch(PDO::FETCH_OBJ);
        $result=$row;
    }

    return $result;
}

function limite_caractere($chaine)
{
    $len=15;
    if(strlen($chaine) >= $len)
    {
        $chaine=substr($chaine,0,$len)." ....";
    }
    echo $chaine;
}


function paramVerify()
{
    global $bdd;
    $result=null;
    $req=$bdd->prepare("select * from m_autorisation where user_c = ?");
    $req->execute(array(getUserLogin()));
    if($req->rowCount() > 0){
        $row=$req->fetch(PDO::FETCH_OBJ);
        if($row->virement == "true")
        {
            $result = true; //il a l'autorisation
        }else{
            $result = false; //il n'a pas l'autorisation
        }
    }else{
        $result = false; //il n'a pas l'autorisation
    }

    return $result;

}

function getUserLogin()
{
    return $_COOKIE["account_code"];
}


    function getUrl(){
        return "http://localhost/ampayeur_dashboard_final/";
    }


function date_conversion($value)
{
    $data=date_create($value);
    $mois=date_format($data,"M");
    $fr_mois="";
    if($mois == "Jan" || $mois == "Jan.")
    {
        $fr_mois="Jan.";
    }else{
        if($mois=="Feb" || $mois=="Feb."){
            $fr_mois="Fév.";
        }else if($mois == "Mar" || $mois == "Mar."){
            $fr_mois="Mar.";
        }else if($mois == "Apr" || $mois == "Apr."){
            $fr_mois="Avr.";
        }else if($mois == "May" || $mois == "May."){
            $fr_mois = "Mai.";
        }else if($mois == "Jun" || $mois == "Jun."){
            $fr_mois = "Jui.";
        }else if($mois == "Aug" || $mois == "Aug."){
            $fr_mois = "Aou.";
        }else if($mois == "Sep" || $mois == "Sep.")
        {
            $fr_mois="Sep.";
        }else if($mois == "Oct" || $mois == "Oct."){
            $fr_mois = "Oct.";
        }else if($mois == "Nov" || $mois == "Nov."){
            $fr_mois = "Nov.";
        }else if($mois == "Dec" || $mois == "Dec."){
            $fr_mois = "Dec.";
        }
    }

    $jour=date_format($data,"d");
    $annee=date_format($data,"Y");
    $heure=date_format($data,"H:i");

    return $jour." ".$fr_mois." ".$annee."  ".$heure;
}

