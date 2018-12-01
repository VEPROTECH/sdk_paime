<?php

require '../callback.php';

$item_list=new ItemList();
$total=new Total();


 //add basket data
$cart=new Cart();
    $item=new Item();
    $item->setName("Tomate");
    $item->setPrice(1500);
    $item->setQuantity(2);
    $cart->addProduct($item);

    $item=new Item();
    $item->setName("Mangue");
    $item->setPrice(3000);
    $item->setQuantity(3);
    $cart->addProduct($item);



//Amount data
$amount = new Amount();
$amount->setCurrency('FCFA');
$amount->setSubtotal(12000);
$amount->setTax(0.12);
$amount->setFrais(2000);


//add description data
$description=new Description();
$description->setDescribe('Achat en ligne');


//add config data
$config=new Config();
$config->setMode('test');
$config->setUrlReturn('www.am');
$config->setUrlSuccess('www.fal');
$config->setIntent('sale');

//add credential
$credential=new Credential();
$credential->setAppKey("02c426fa1f4d510bbd0b978f4a7277d302f893e6b4b4106c27af1dc0ef5281b1");
$credential->setServerKey("2abd2ef0781dba47e21dcee51aa8fdcb1ba8d5879772303ad72a84fe5e5c360f");






try{
        $context=new Context();
        $context->create($credential,$cart->getProducts(),$amount,$description,$config);
}catch (Exception $ex){

}



