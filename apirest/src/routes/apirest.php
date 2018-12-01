<?php
use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;
//error_reporting(1);
$app = new \Slim\App;


$app->post("/api/v1/payment",function (Request $request, Response $response){

    $panier=$request->getParsedBody()["basket"]; //tableau de panier
    $app_token=$request->getParsedBody()["app_key"];
    $serveur_key=$request->getParsedBody()["serveur_key"];
    $amount=$request->getParsedBody()["amount"];
    $url = $request->getParsedBody()["url"];
    $config=$request->getParsedBody()["config"];
    $transaction = $request->getParsedBody()["transaction"];
    $intention=$request->getParsedBody()["intent"]; // l'intention sale pour achat

    //amount (sous-total et les taxes)
    $subtotal = $amount["subtotal"]; //total HT
    $tax = $amount["tax"]; // taxe
    $frais=$amount["frais"]; //les frais possibles
    $currency=$amount["currency"]; // type de devise

    //les configurations
    $type_paiement=$config["mode"]; //test ou live

    //les urls de redirection en cas de succes ou d'échec
    $success_url=$url["urlReturn"];
    $faild_url=$url["urlSuccess"];

    //les informations de la transaction
    $description=$transaction["description"];

    $db=new DB();
    $db=$db->connect();
    $result=array();
    $errors=array();
    $tot=0;
    if(count($panier) >0){
        foreach ($panier as $pan)
        {
            $tot += $pan["price"] * $pan["quantity"]; //montant total du panier
        }
    }

    if($subtotal != $tot){
        $errors["message"][]="The total of the basket (without tax) and the subtotal are not equal";
    }

    if(devise($currency) == false){
        $errors["message"][] = "We do not currently accept the currency you used.";
    }


    //calcule du montant à payer
    $total = 0;
    if($frais != ""){
        if($tax != ""){
            $total = formatToNumber((1+$tax)*($tot+$frais)); // total TTC avec frais
        }else{
            $total = formatToNumber($tot+$frais); // total TTC avec frais
        }
    }else{
        if($tax != ""){
            $total = formatToNumber((1+$tax)*$tot); // total TTC sans frais
        }else{
            $total = formatToNumber($tot); // total TTC sans frais
        }
    }

    $type=array("test","live");
    if(!in_array(strtolower($type_paiement),$type)){
        $errors["message"][] = "The mode of use ".$type_paiement." is not valid";
    }


     $id_transaction=strtoupper("TR_".generateToken(8));
     $ref=strtoupper("REF".generateToken(4));


    //les intents
    if($intention != 'sale'){
        $errors["message"][]="An error occurred at the intent level. Possible values: 'sale' ";
    }
    $cod=null;
    $acces=array();


    if(count($errors) == 0 ){
        if(count($panier) >0){
            if(getApp($app_token,$type_paiement) != false){
                $cod=getApp($app_token,$type_paiement)->code_app; //code application
            }else{
                $acces[]["message"]="Invalide application key !";
            }
            if(count($acces) == 0){
                $marchand=info_marchand($cod)->code_marchand; //code marchand
                if(getApp($app_token,$type_paiement)->app_token == $serveur_key){
                    $fac=$db->prepare("insert into facturation_tmp(ref, date_vente, marchand) values (?,now(),?)");
                    $fac->execute(array($ref,$marchand));
                    foreach ($panier as $pan)
                    {
                        $name=$pan["name"];
                        $price=$pan["price"];
                        $qte=$pan["quantity"];
                        $sql=$db->prepare("insert into produit_tmp(ref_vente, libelle, prix, quantity) values (?,?,?,?)");
                        $sql->execute(array($ref,$name,$price,$qte));
                    }

                    $req=$db->prepare("insert into infos_tmp(ref_id, urlReturn, urlSuccess, currency, frais, tax, mode, intent, description, ID_trans,subtotal) VALUES (?,?,?,?,?,?,?,?,?,?,?)");
                    $req->execute(array($ref,$faild_url,$success_url,$currency,$frais,$tax,$type_paiement,$intention,$description,$id_transaction,$subtotal));

                    return $response->withJson(array("ID"=>$id_transaction),200);
                }else{
                    return $response->withJson(array("message"=>"You are not authorized to use this service. Check your server key and try again"),203);
                }
            }else{
                return $response->withJson($acces,203);
            }
        }else{
            return $response->withJson("Your basket is empty !",201);
        }
    }else{
        return $response->withJson($errors,203);
    }




});
//----------------------------------------------------------


