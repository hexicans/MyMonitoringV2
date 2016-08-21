
	<?php
include 'db_connect.php';
    // on teste si le visiteur a soumis le formulaire
    if (isset($_POST['inscription']) && $_POST['inscription'] == 'Inscription') {
    	// on teste l'existence de nos variables. On teste également si elles ne sont pas vides
    	if ((isset($_POST['login']) && !empty($_POST['login'])) && (isset($_POST['pass_md5']))) {
    	// on teste les deux mots de passe
    	if ($_POST['pass_md5'] != $_POST['pass_md5']) {
    		$erreur = 'Les 2 mots de passe sont différents.';
    	}
    	else {

				$sql = 'SELECT COUNT(*) FROM admin WHERE mail = ?';
				$req = $bdd->prepare($sql);
				$req->execute(array($mail));
				while($row = $req->fetchColumn()){
				$nb = $row;
				}


    		// on recherche si ce login est déjà utilisé par un autre membre

    		if ($nb == 0) {
				$tab = array(
					            "nom" => $_POST['nom'],
					            "prenom" => $_POST['prenom'],
					            "mail" => $_POST['mail'],
					            "login" => $_POST['login'],
											"pass_md5" => (password_hash($_POST['pass_md5'], PASSWORD_DEFAULT)),
											"apikey" => 'null',
											"phone" => 'null'
					);
				$sql = 'INSERT INTO admin (nom,prenom,mail,login,pass_md5,apikey,phone) VALUES (:nom, :prenom, :mail, :login, :pass_md5, :apikey, :phone)';
				$req = $bdd->prepare($sql);
				$result = $req->execute($tab);

    		session_start();
    		$_SESSION['login'] = $_POST['login'];
    		header('Location: login.php');
    		exit();
    		}
    		else {
    		$erreur = 'Un membre possède déjà ce login.';
    		}
    	}
    	}
    	else {
    	$erreur = 'Au moins un des champs est vide.';
    	}
    }
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
  <body class="register-page">
    <div class="register-box">
      <div class="register-logo">
        <a href="index.php"><b>My</b>Monitor</a>
      </div>

      <div class="register-box-body">
        <p class="login-box-msg">Inscription</p>





	 <?php
    if (isset($erreur)) echo '<br />',$erreur;
    ?>
	<form class="form-horizontal" action="register.php" method="post">
  <div class="form-group">
    <label for="inputEmail3" class="col-sm-2 control-label">E-mail</label>
    <div class="col-sm-10">
     <input type="text" class="form-control" name="mail" value="<?php if (isset($_POST['mail'])) echo htmlentities(trim($_POST['mail'])); ?>">
    </div>
  </div>
  <div class="form-group">
    <label for="inputEmail3" class="col-sm-2 control-label">Nom</label>
    <div class="col-sm-10">
     <input type="text" class="form-control" name="nom" value="<?php if (isset($_POST['nom'])) echo htmlentities(trim($_POST['nom'])); ?>">
    </div>
  </div>
  <div class="form-group">
    <label for="inputEmail3" class="col-sm-2 control-label">Prénom</label>
    <div class="col-sm-10">
     <input type="text" class="form-control" name="prenom" value="<?php if (isset($_POST['prenom'])) echo htmlentities(trim($_POST['prenom'])); ?>">
    </div>
  </div>
  <div class="form-group">
    <label for="inputEmail3" class="col-sm-2 control-label">Entreprise</label>
    <div class="col-sm-10">
     <input type="text" class="form-control" name="login" value="<?php if (isset($_POST['login'])) echo htmlentities(trim($_POST['login'])); ?>">
    </div>
  </div>
      <div class="form-group">
    <label for="inputEmail3" class="col-sm-2 control-label">Mot de passe</label>
    <div class="col-sm-10">
     <input type="password" class="form-control" name="pass_md5" value="<?php if (isset($_POST['pass_md5'])) echo htmlentities(trim($_POST['pass_md5'])); ?>">
    </div>
  </div>




  <div class="form-group">
    <div class="col-sm-offset-2 col-sm-10">
      <input type="submit" class="btn btn-primary" name="inscription" value="Inscription">
    </div>
  </div>
</form>



        <a href="login.php" class="text-center">J'ai déja un compte</a>
      </div><!-- /.form-box -->
    </div><!-- /.register-box -->

    <!-- jQuery 2.1.4 -->
    <script src="../../plugins/jQuery/jQuery-2.1.4.min.js"></script>
    <!-- Bootstrap 3.3.2 JS -->
    <script src="../../bootstrap/js/bootstrap.min.js" type="text/javascript"></script>
    <!-- iCheck -->
    <script src="../../plugins/iCheck/icheck.min.js" type="text/javascript"></script>
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
