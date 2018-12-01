<!DOCTYPE html>
<html lang="en">
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" type="text/css" href="css/bootstrap.min.css">

    <link rel="stylesheet" type="text/css" href="css/style.css">
    <!-- Website Font style -->
    <link rel="stylesheet" href="css/font-awesome.min.css">

    <!-- Google Fonts -->
    <link href='https://fonts.googleapis.com/css?family=Passion+One' rel='stylesheet' type='text/css'>
    <link href='https://fonts.googleapis.com/css?family=Oxygen' rel='stylesheet' type='text/css'>

    <title>Gandokintché | Payment</title>
</head>
<body>
<div class="container">

    <div class="row main">
        <div class="panel-heading">
            <div class="panel-title text-center">
                <img style="max-width: 250px" src="images/logof.png">
                <hr />
            </div>
        </div>
        <div class="main-login main-center">
            <?php
            include 'Tools/_connect.php';
            include 'Tools/errors_files.php';
            ?>
            <form class="form-horizontal" method="post">

                <div class="form-group">
                    <label for="email" class="cols-sm-2 control-label">Email</label>
                    <div class="cols-sm-10">
                        <div class="input-group">
                            <span class="input-group-addon"><i class="fa fa-envelope fa" aria-hidden="true"></i></span>
                            <input value="<?php if(isset($_POST["valider"]) && isset($_SESSION["email"])) echo $_SESSION["email"];  ?>" type="email" class="form-control" name="email" id="email" />
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <label for="password" class="cols-sm-2 control-label">Mot de passe</label>
                    <div class="cols-sm-10">
                        <div class="input-group">
                            <span class="input-group-addon"><i class="fa fa-lock fa-lg" aria-hidden="true"></i></span>
                            <input type="password" class="form-control" name="password" id="password" />
                        </div>
                    </div>
                </div>

                <div class="form-group ">
                    <div class="call-to-action">
                        <button style="width: 319px; height: 50px" name="valider" type="submit" value="submit your quote" class="btn submint_btn form-control">
                            Se connecter
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<script type="text/javascript" src="js/jquery-2.2.4.js"></script>
<script type="text/javascript" src="js/bootstrap.min.js"></script>

<style type="text/css">
    body, html{
        /*background:  linear-gradient(rgba(0, 0, 0, 0.5), rgba(0, 0, 0, 0.5)), url('images/devop.jpg') no-repeat;*/

        font-family: 'Oxygen', sans-serif;
        background-size: cover;
    }

    .main{
        margin-top: 30px;

    }

    .main-login
    {

        box-shadow: 0 0 5px 0 rgb(230, 153, 30), 0 11px 6px -7px rgba(22, 33, 62, 0.49);
    }

    h1.title {
        font-size: 50px;
        font-family: 'Passion One', cursive;
        font-weight: 400;
    }

    hr{
        width: 10%;
        color: #fff;
    }

    .form-group{
        margin-bottom: 15px;
    }

    label{
        margin-bottom: 15px;
    }

    input,
    input::-webkit-input-placeholder {
        font-size: 11px;
        padding-top: 3px;
    }

    .main-login{
        background-color: #fff;
        /* shadows and rounded borders */
        -moz-border-radius: 2px;
        -webkit-border-radius: 2px;
        border-radius: 2px;
        /*-moz-box-shadow: 0px 2px 2px rgba(0, 0, 0, 0.3);*/
        /*-webkit-box-shadow: 0px 2px 2px rgba(0, 0, 0, 0.3);*/
        /*box-shadow: 0px 2px 2px rgba(0, 0, 0, 0.3);*/

    }

    .main-center{
        margin: 0 auto;
        max-width: 380px;
        padding: 40px 40px;

    }

    .login-button{
        margin-top: 5px;
    }

    .login-register{
        font-size: 11px;
        text-align: center;
    }
    li a{
        color: #fff;
    }
</style>
</body>
<div style="background: transparent; color: white" class="footer_copy_right">
    <div class="container">
        <div class="pull-left">
            <p style="color: #16213e" class="copyright">
                <!-- Link back to Colorlib can't be removed. Template is licensed under CC BY 3.0. -->
                Gandokintché Copyright &copy;<script>document.write(new Date().getFullYear());</script>

                <!-- Link back to Colorlib can't be removed. Template is licensed under CC BY 3.0. -->
            </p>
        </div>
        <div class="pull-right">
            <ul>
                <li><a href="#">Termes & Conditions d'utilisation</a></li>
                <li><a target="_blank" href="http://www.dashboard.gandokintche.com/balance">Mon Compte</a></li>
                <li><a target="_blank" href="http://www.gandokintche.com/tarif">Tarifs</a></li>
                <li><a target="_blank" href="http://www.gandokintche.com/developpeur">Développeurs</a></li>
                <li><a href="#">Nous contactez</a></li>
            </ul>
        </div>
    </div>
</div>
</html>