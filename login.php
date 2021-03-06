<?php
include 'db_connect.php';
 ?>
<!DOCTYPE html>
<html>
  <head>
    <meta charset="UTF-8">
    <title>MyMonitor</title>
    <meta content='width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no' name='viewport'>
    <!-- Bootstrap 3.3.4 -->
    <link href="bootstrap/css/bootstrap.min.css" rel="stylesheet" type="text/css" />
    <!-- Font Awesome Icons -->
    <link href="https://maxcdn.bootstrapcdn.com/font-awesome/4.3.0/css/font-awesome.min.css" rel="stylesheet" type="text/css" />
    <!-- Theme style -->
    <link href="dist/css/AdminLTE.min.css" rel="stylesheet" type="text/css" />
    <!-- iCheck -->
    <link href="plugins/iCheck/square/blue.css" rel="stylesheet" type="text/css" />

    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
        <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
        <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
  </head>
  <body class="login-page">
    <div class="login-box">
      <div class="login-logo">
        <a href="index.php"><b>My</b>Monitor</a>
      </div><!-- /.login-logo -->
      <div class="login-box-body">
        <p class="login-box-msg">Connectez-vous ! </p>

        <?php
            // Alors, déjà. Pour hashé un mdp :
            // password_hash               ( $mdp, PASSWORD_DEFAULT (ou PASSWORD_BCRYPT) )
            // avec un troisième paramètre facultatif:
            // array [ cost => 'afecte le temps de génération, defaut 10', salt => 'ne pas spécifier. c'est random par default et mieux comme ça']
          // et pour vérifier. c'est un peu différent. vu que que salt change. deux hash du même mdp seront différents ok:)

            // on teste si le visiteur a soumis le formulaire de connexion
            if (!empty($_POST['mail']) && !empty($_POST['pass_md5']))
            {
              $req = $bdd->prepare('SELECT pass_md5 FROM admin WHERE mail = ?');
              $req->execute(array($_POST['mail']));

            //  die(password_hash($_POST['pass_md5'], PASSWORD_DEFAULT)); //go tenter de te login avec ton mdp, faut mettre à jour le hash
              while($row = $req->fetch()) { //oh nan wait.
                if(password_verify                 ($_POST['pass_md5'], $row['pass_md5'])) { //wow
                  session_start();
                  $_SESSION['login'] = $_POST['mail'];
                  header('Location: index.php');
                  exit();
                }
                else {
                  $erreur = '<div class="alert alert-danger alert-dismissable">
                             <button type="button" class="close" data-dismiss="alert" aria-hidden="true">Ãƒâ€”</button>
                            <h4><i class="icon fa fa-ban"></i> Error !</h4>
                            Utilisateur ou mot de passe incorrect !
                            </div>';
                  echo $erreur;
                }
              }
            }


        ?>
        <form action="login.php" method="post">
          <div class="form-group has-feedback">

            <input type="login" class="form-control" placeholder="mail" name="mail" value="<?php if (isset($_POST['mail'])) echo htmlentities(trim($_POST['mail'])); ?>"/>
            <span class="glyphicon glyphicon-envelope form-control-feedback"></span>
          </div>
          <div class="form-group has-feedback">
            <input type="password" class="form-control" placeholder="Password" name="pass_md5" value="<?php if (isset($_POST['pass_md5'])) echo htmlentities(trim($_POST['pass_md5'])); ?>"/>
            <span class="glyphicon glyphicon-lock form-control-feedback"></span>
          </div>
          <div class="row">
            <div class="col-xs-8">
              <div class="checkbox icheck">

              </div>
            </div><!-- /.col -->
            <div class="col-xs-4">
              <button type="submit" class="btn btn-primary btn-block btn-flat" name="connexion" type="submit" value="Connexion">Connexion</button>

          </div><!-- /.col -->
          </div>
        </form>
<a href="register.php">Se créer un compte</a>



      </div><!-- /.mail-box-body -->
    </div><!-- /.mail-box -->

    <!-- jQuery 2.1.4 -->
    <script src="plugins/jQuery/jQuery-2.1.4.min.js"></script>
    <!-- Bootstrap 3.3.2 JS -->
    <script src="bootstrap/js/bootstrap.min.js" type="text/javascript"></script>
    <!-- iCheck -->
    <script src="plugins/iCheck/icheck.min.js" type="text/javascript"></script>
    <script>
      $(function () {
        $('input').iCheck({
          checkboxClass: 'icheckbox_square-blue',
          radioClass: 'iradio_square-blue',
          increaseArea: '20%' // optional
        });
      });
    </script>
  </body>
</html>
