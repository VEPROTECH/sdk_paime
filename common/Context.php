<?php
require 'ApiContext.php';
class Context
{
        public function create($credential,$basket,$amount="",$description="",$config="")
        {

            //add basket in json
            $tem=array();
            $i=0;
            foreach ($basket as $ca)
            {
                $tem[$i]["name"]=$ca->getName();
                $tem[$i]["price"]=$ca->getPrice();
                $tem[$i]["quantity"]=$ca->getQuantity();
                $i++;
            }

            //les configurations
            $appkey=$credential->getAppKey();
            $serverKey=$credential->getServerKey();

            $mode=$config->getMode();
            $urlfailed=$config->getUrlReturn();
            $urlsucess=$config->getUrlSuccess();
            $intent=$config->getIntent();


            //description
            $descrip=$description->getDescribe();

            //amount data
            $subtotal=$amount->getSubtotal();
            $tax=$amount->getTax();
            $frais=$amount->getFrais();
            $currency=$amount->getCurrency();


//            //Les elements a envoyer sur le serveur
            $mes_elements=array(
                'app_key' => $appkey,
                'serveur_key' => $serverKey,
                'url' => array(
                    "urlReturn" => $urlfailed,
                    "urlSuccess" => $urlsucess
                ),
                'basket' => $tem,
                'intent' => $intent,
                'config' => array(
                    "mode" => $mode
                ),
                'transaction' => array(
                    "description" => $descrip
                ),
                'amount' => array(
                    "subtotal" => $subtotal,
                    "currency" => $currency,
                    "frais" => $frais,
                    "tax" => $tax
                )
            );

            $content_elements=json_encode($mes_elements);


           //url sur lequel on envoi les données
            $url = ApiContext::getUrl();

            //envoi POST par curl
            $curl = curl_init();
            curl_setopt($curl,CURLOPT_URL,$url);
            curl_setopt($curl, CURLOPT_POST, true);
            curl_setopt($curl, CURLOPT_HTTPHEADER, array("Content-type: application/json","APIKEY:".$serverKey));
            curl_setopt($curl, CURLOPT_HEADER,false);
            curl_setopt($curl, CURLOPT_FAILONERROR,true);
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($curl, CURLOPT_POSTFIELDS, $content_elements);


            // execution de la requete
            $json_response = curl_exec($curl);
            $status = curl_getinfo($curl,CURLINFO_HTTP_CODE);

            if($status != 200)
            {
                echo '<div style="color:red" >';
                  die($json_response);
                echo '</div>';
            }else{
                $result =  json_decode($json_response);



                header("location: http://localhost/sdk_paime/paymentpage/payment.php?transaction=".$result->ID."&token=".$appkey);


            }


        }


}