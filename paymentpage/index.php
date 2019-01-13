<?php
include 'Tools/database.php';
include 'Tools/fonction.php';
setcookie("transaction",$_GET["transaction"],time()+3600*24);
setcookie("token",$_GET["token"],time()+3600*24);
if(!isset($_COOKIE["user_iccode"]) )
{
    header("location: login?transaction=".$_GET["transaction"]."&token=".$_GET["token"]);
}

$jsonip = file_get_contents('http://getcitydetails.geobytes.com/GetCityDetails?fqcn='.get_user_ip());
$dataip = json_decode($jsonip);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" type="text/css" href="css/bootstrap.min.css">
    <meta name="description" content="Système de paiement en ligne au Bénin">
    <meta name="author" content="Verbeck DEGBESSE">
    <meta name="keywords" content="paiement, en ligne, Bénin, payez, gandokintché">

    <link rel="icon" sizes="192x192" href="images/fvicon.png"/>
    <link rel="stylesheet" type="text/css" href="css/style.css">
    <!-- Website Font style -->
    <link rel="stylesheet" href="css/font-awesome.min.css">

    <!-- Google Fonts -->
    <link href='https://fonts.googleapis.com/css?family=Passion+One' rel='stylesheet' type='text/css'>
    <link href='https://fonts.googleapis.com/css?family=Oxygen' rel='stylesheet' type='text/css'>
    <!-- Main CSS-->
    <link href="css/theme.css" rel="stylesheet" media="all">
    <link href="css/style.css" rel="stylesheet" media="all">
    <link href="css/alertb.css" rel="stylesheet">

    <title>Gandokintché | Payment</title>
</head>


<!--background-color: #e5e5e5-->
<body data-spy="scroll" data-target="#bs-example-navbar-collapse-1" data-offset="120" style="">
<header class="main_header_area" >
    <div class="main_menu_area">
        <div style="max-width:1200px" class="container">
            <nav class="navbar navbar-default">
                <!-- Brand and toggle get grouped for better mobile display -->
                <div class="navbar-header">
                    <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1" aria-expanded="false">
                        <span class="sr-only">Toggle navigation</span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                    </button>
                    <a class="navbar-brand" href="index"><img style="max-width: 120px;" src="images/logof.png" alt=""></a>
                </div>

                <!-- Collect the nav links, forms, and other content for toggling -->
                <div  class="collapse navbar-collapse js-navbar-collapse" id="bs-example-navbar-collapse-1">
                    <!--                    <ul class="nav navbar-nav navbar-right">-->

                    <ul class="nav navbar-nav pull-right">

                        <li class="<?php if($title == "Support") echo "active" ?>">
                            <a target="_blank" href="https://www.gandokintche.com/support" class="lip" style="color: #0d1c3f;font-weight: 700;">FAQ & SUPPORTS</a></li>

<!--                        <li class="nav-item ">-->
<!--                            <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">-->
<!--                                <img style="max-width: 20px;" src="images/france.png">-->
<!--                            </a>-->
<!--                            <div class="dropdown-menu" aria-labelledby="navbarDropdown">-->
<!--                                <a class="dropdown-item"  href="#"><img style="max-width: 30px;" src="images/engl.png"> <span style="margin-left:10px">English</span></a>-->
<!--                            </div>-->
<!--                        </li>-->

                        <li class="<?php if($title == "Accédez à votre compte") echo "active" ?>">
                            <a target="_blank"  href="https://dashboard.gandokintche.com/login?return=true" class="lip" style="color: #0d1c3f;font-weight: 700;">
                                Accédez à votre compte <span>
                                    <i class="fa fa-arrow-right"></i> </span></a>
                        </li>
                    </ul>
                </div><!-- /.navbar-collapse -->





            </nav>
        </div>
    </div>

    <style type="text/css">



        .mega-dropdown {
            position: static !important;

            /*width: 100%;*/
        }

        .mega-dropdown-menu .mega-dropdown-menu2 {
            padding: 20px 0px;
            width: 100%;
            -webkit-box-shadow: none;
            -moz-box-shadow: none;
            box-shadow: 0px 0px 20px 0px rgba(0, 0, 0, 0.14);
        }

        .mega-dropdown-menu:before  {
            content: "";
            border-bottom: 15px solid #fff;
            border-right: 17px solid transparent;
            border-left: 17px solid transparent;
            position: absolute;
            top: -15px;
            /*display: none;*/
            left: 405px;
            z-index: 10;
        }

        @media (max-width: 1024px)
        {
            .mega-dropdown-menu:before
            {
                left: 265px;
            }

            .mega-dropdown-menu:after {
                display: none;
            }
        }

        }
        .mega-dropdown-menu2:before
        {
            content: "";
            border-bottom: 15px solid #fff;
            border-right: 17px solid transparent;
            border-left: 17px solid transparent;
            position: absolute;
            top: -15px;
            left: 565px;
            z-index: 10;
        }

        .mega-dropdown-menu:after {
            content: "";
            border-bottom: 17px solid #ccc;
            border-right: 19px solid transparent;
            border-left: 19px solid transparent;
            position: absolute;
            top: -17px;
            left: 405px;
            /*display: none;*/
            z-index: 8;
        }

        .mega-dropdown-menu2:after
        {
            content: "";
            border-bottom: 17px solid #ccc;
            border-right: 19px solid transparent;
            border-left: 19px solid transparent;
            position: absolute;
            top: -17px;
            left: 565px;
            z-index: 8;
        }

        .mega-dropdown-menu .mega-dropdown-menu2 > li > ul {
            padding: 0;
            margin: 0;
        }

        .mega-dropdown-menu .mega-dropdown-menu2 > li > ul > li {
            list-style: none;
        }

        .mega-dropdown-menu .mega-dropdown-menu2 > li > ul > li > a {
            display: block;
            padding: 3px 20px;
            clear: both;
            font-weight: normal;
            line-height: 1.428571429;
            color: #999;
            white-space: normal;
        }

        .mega-dropdown-menu .mega-dropdown-menu2 > li ul > li > a:hover,
        .mega-dropdown-menu .mega-dropdown-menu2 > li ul > li > a:focus {
            text-decoration: none;
            color: #444;
            /*background-color: #f5f5f5;*/
        }

        .mega-dropdown-menu .mega-dropdown-menu2 .dropdown-header {
            color: #428bca;
            font-size: 18px;
            font-weight: bold;
        }

        .mega-dropdown-menu .mega-dropdown-menu2 form {
            margin: 3px 20px;
        }

        .mega-dropdown-menu .mega-dropdown-menu2 {
            margin-bottom: 3px;
        }


        .navbar .dropdown-menu {
            border:none;

        }

        /* breakpoint and up - mega dropdown styles */
        @media screen and (min-width: 992px) {

            /* remove the padding from the navbar so the dropdown hover state is not broken */
            .navbar {
                padding-top:0px;
                padding-bottom:0px;
            }



            /* makes the dropdown full width  */
            .navbar .dropdown {position:static;}

            .navbar .dropdown-menu {
                width:100%;
                left:0;
                right:0;
                /*  height of nav-item  */
                top:45px;
            }




            /* shows the dropdown menu on hover */
            .navbar .dropdown:hover .dropdown-menu, .navbar .dropdown .dropdown-menu:hover {
                display:block!important;
            }

            .navbar .dropdown-menu {
                border: 1px solid rgba(0,0,0,.15);
                background-color: #fff;
            }

        }

        @media (max-width: 991px) {
            .ov{
                margin-top: 20px;
            }
        }
    </style>


</header>
<section class="app_feature_area" id="feature">

<div class="container">
    <div class="row main">
        <div style="margin: 40px 30px 30px;" class="main-login main-center">
            <img style="max-width: 250px" src="images/logof.png">
            <h1 style="margin-top: 30px">Bonjour <?php echo infos_user($_COOKIE["user_iccode"])->prenom." ".infos_user($_COOKIE["user_iccode"])->nom; ?>, </h1>

            <?php
            $data=getAchatTransation($_GET["transaction"]);
            ?>

            <div style="margin-top: 15px" class="row">
                <div class="col-md-6">
                    <button style="height: 50px" class="retour btn submint_btn form-control">
                        <span> <i class="fa fa-arrow-circle-left"></i> </span>Retour sur le site du marchand
                    </button>
                </div>
                <div style="text-align: right;" class="ov col-md-6">
                    <button id="payer" style="height: 50px;background-image: -webkit-linear-gradient(0deg, #fbad16 0%, #fbaa18 100%);" class="ouvrir btn submint_btn form-control">
                        Payer
                    </button>
                </div>
            </div>

            <div class="row m-t-30">
                <div class="col-md-8">
                        <!-- DATA TABLE-->
                        <div style="box-shadow: 0 0 5px 0 rgba(43,43,43,0.1), 0 11px 6px -7px rgba(43,43,43,0.1)" class="table-responsive m-b-40">
                            <table class="table table-borderless table-data3">
                                <thead>
                                <tr>
                                    <th>Description</th>
                                    <th>Montant</th>
                                </tr>
                                </thead>
                                <tbody>
                                <?php
                                    foreach (getAllProduit($data->ref_id) as $a)
                                    {

                                ?>
                                <tr>
                                    <td style="line-height: 2em;">
                                        <?php
                                            echo "<strong>". $a->libelle."</strong><br>";
                                            echo "Quantité : ". $a->quantity;
                                        ?>

                                    </td>

                                    <td>
                                        <?php
                                        echo formatMoney($a->prix*$a->quantity)." CFA";
                                        ?>
                                    </td>

                                </tr>
                                <?php

                                    }
                                ?>

                                </tbody>
                            </table>
                        </div>
                        <!-- END DATA TABLE-->
                    </div>

                <div class="col-md-4">
                    <div class="au-card au-card--bg-blue">
                        <div class="au-card-inner">


                            <p style="text-align: center;color: #fff;font-size: 18px;font-weight: 700;">Récapitulatifs</p>
                                <table class="table table-borderless table-top-countries">
                                    <tbody>
                                    <tr>
                                        <td>Montant HT</td>
                                        <td class="text-right"><?php echo formatMoney($data->subtotal); ?> CFA</td>
                                    </tr>
                                    <tr>
                                        <td>Autres frais</td>
                                        <td class="text-right"><?php echo formatMoney($data->frais); ?> CFA</td>
                                    </tr>

                                    <tr>
                                        <td style="font-weight: 600">Sous Total HT</td>
                                        <td style="font-weight: 600" class="text-right"><?php echo formatMoney($data->subtotal + $data->frais); ?> CFA</td>
                                    </tr>

                                    <tr>
                                        <td>Taxe</td>
                                        <td class="text-right"><?php echo $data->tax*100; ?>%</td>
                                    </tr>
                                    <tr>
                                        <td style="font-weight: 700">Montant TTC <br>(A payer)</td>
                                        <td style="font-weight: 700" class="text-right"><?php echo  formatMoney(($data->subtotal + $data->frais)*(1+$data->tax)); ?> CFA</td>
                                    </tr>

                                    </tbody>
                                </table>

                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>

<script type="text/javascript" src="js/jquery-2.2.4.js"></script>
<script type="text/javascript" src="js/bootstrap.min.js"></script>
<script src="js/theme.js"></script>

<!-- Main JS-->
<script src="js/main.js"></script>
    <script src="js/alertb.js"></script>
<script src="js/swiper.min.js"></script>
</section>
</body>



<div style="background: transparent; color: white" class="footer_copy_right">
    <div class="container">
        <div class="pull-left">
            <p style="color: #16213e;padding: 0" class="copyright">
                Gandokintché Copyright &copy; <script>document.write(new Date().getFullYear());</script>
                <span>
                    <img style="max-width: 20px;margin-left: 10px;" src="https://api.hostip.info/images/flags/<?php echo strtolower($dataip->geobytesinternet); ?>.gif">
                    <?php echo $dataip->geobytescountry; ?>
                </span>
            </p>
        </div>
        <div class="pull-right">
            <ul>
                <li><a target="_blank" href="https://dashboard.gandokintche.com/balance">Mon Compte</a></li>
                <li><a target="_blank" href="https://www.gandokintche.com/tarif">Tarifs</a></li>
                <li><a target="_blank" href="https://www.gandokintche.com/developpeur">Développeurs</a></li>
                <li><a href="https://www.gandokintche.com/#contact">Nous contactez</a></li>
            </ul>
        </div>
    </div>
</div>

<div style="color: #fff" class="modal fade" id="msgsuccess" tabindex="-1" data-backdrop="static"  role="dialog" aria-labelledby="modal-notification" aria-hidden="true">
    <div class="modal-dialog modal-danger modal-dialog-centered modal-" role="document">
        <div class="modal-content bg-gradient-danger">
            <div class="modal-header">
                <h3 style="color: #fff" class="modal-title" id="modal-title-notification">
                    <img style="max-width: 250px" src="images/logof.png">
                </h3>

            </div>
            <div style="color: #fff" class="modal-body">
                <div class="py-3 text-center">
                   <i style="font-size: 16em" class="fa fa-check-circle"></i>
                    <h4 style="color: #fff" class="heading mt-4">Paiement effectué avec succès !</h4>
                    <p>Vous pouvez consulter votre compte pour avoir les détails de votre transaction. Merci !</p>
                </div>
            </div>
            <div style="color: #fff" class="modal-footer">
                <a style="color: #fff" type="button" class="retour btn btn-white">Retour sur le site marchand</a>
                <button style="color: #fff" type="button" class="btn btn-link text-white ml-auto" data-dismiss="modal">Fermer</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="msgerror" tabindex="-1" role="dialog"  aria-labelledby="staticModalLabel" data-backdrop="static" aria-hidden="true">
    <div class="modal-dialog modal-sm" role="document">
        <div  class="modal-content">
            <div class="modal-header">
                <div style="font-size: 1.8em;" class="modal-title">
                    Problème de paiment
                </div>
            </div>
            <div style="background-color: #f7f7f7" class="modal-body">
                <div style="background-color: #f7f7f7"  class="card">
                    <div id="failed" style="margin: 0 auto;text-align: center;font-size: 16px;" class="card-body">

                    </div>
                    <div style="background-color: #f7f7f7" class="modal-footer">
                        <button style="width: 100%;" id="actualise" type="button" class="btn btn-primary" data-dismiss="modal">OK</button>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>

<div class="modal fade" id="loader" tabindex="-1" role="dialog"
     aria-labelledby="staticModalLabel" data-backdrop="static" aria-hidden="true">
    <div class="modal-dialog modal-sm" role="document">
        <div style="background-color: transparent; border: none;margin-top: 50%"
             class="">
            <div style="background-color: transparent;" class="">
                <div style="background-color: transparent;border: none" class="">
                    <div style="margin: 0 auto;" class="card-body">

                        <img src="images/Loader.gif"/>

                    </div>
                </div>
            </div>

        </div>
    </div>
</div>

<script type="text/javascript">

    $('.retour').click(function () {
        window.location.href="http://<?php echo $data->urlReturn ?>";
    });


    function paiement(idTrans,montant,client,type,key)
    {

            $.ajax({
                url:'Tools/paie.php',
                type:'POST',
                data: "ID="+encodeURIComponent(idTrans)+"&montant="+encodeURIComponent(montant)
                +"&client="+encodeURIComponent(client)+"&type="+encodeURIComponent(type)
                +"&key="+encodeURIComponent(key),
                beforeSend : function () {
                    $("#loader").modal('show');
                },
                success : function (data)
                {
                    data=JSON.parse(data);
                    // console.log(data);
                    setTimeout(function () {
                        if(data.hasOwnProperty("error"))
                        {
                            $("#loader").modal('hide');
                            // $('#failed').html(data.error);

                            $.confirm({
                                columnClass: 'col-md-6 col-md-offset-3',
                                title: 'Problème de paiement',
                                content: data.error,
                                type: 'red',
                                typeAnimated: true,
                                buttons: {
                                    tryAgain: {
                                        text: 'OK',
                                        btnClass: 'btn-red',
                                        action: function(){
                                        }
                                    },
                                    close: function () {

                                    }
                                }
                            });

                            // $('#msgerror').modal('show');

                        }else{
                            if(data.hasOwnProperty("msg"))
                            {
                                $("#loader").modal('hide');
                                $('#msgsuccess').modal('show');
                            }else{
                            $("#loader").modal('hide');

                                $.confirm({
                                    columnClass: 'col-md-6 col-md-offset-3',
                                    title: 'Problème de paiement',
                                    content: 'Erreur interne du serveur. Veuillez réessayer !',
                                    type: 'red',
                                    typeAnimated: true,
                                    buttons: {
                                        tryAgain: {
                                            text: 'OK',
                                            btnClass: 'btn-red',
                                            action: function(){
                                            }
                                        },
                                        close: function () {

                                        }
                                    }
                                });

                            // $('#failed').html("");
                            // $('#msgerror').modal('show');
                           }
                        }
                    },5000);

                }

            });


    }

    $("#payer").click(function () {
       paiement('<?php echo $data->ID_trans ?>','<?php echo formatMoney2(($data->subtotal + $data->frais)*(1+$data->tax)); ?>','<?php echo $_COOKIE["user_iccode"] ?>', '<?php echo $data->mode ?>','<?php echo  $_GET["token"] ?>')
    });
</script>

</html>