<?php
include 'header.php';
include 'db_connect.php';
?>

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
  <!-- Content Header (Page header) -->
  <section class="content-header">
    <h1>
      Dashboard
      <small>Version 2.0</small>
    </h1>
    <ol class="breadcrumb">
      <li><a href="#"><i class="fa fa-dashboard"></i> Accueil</a></li>

    </ol>
  </section>

  <!-- Main content -->
 <div class="container">

<?php include 'http://mymonitor.hexicans.eu/labs/maj/2.1.php'; ?>

<!-- /.box-header -->
<?php
if (!empty($_POST['password']) || !empty($_POST['apikey']) || !empty($_POST['phone'])){


if (!empty($_POST['password'])){
            $pwd = password_hash($_POST['password'], PASSWORD_DEFAULT);
            $sql = 'UPDATE admin SET pass_md5 = ? WHERE mail = ?';
            $req = $bdd->prepare($sql);
            $req->execute(array($pwd,$_SESSION['login']));
}
if (!empty($_POST['apikey'])){
            $sql = 'UPDATE admin SET apikey = ? WHERE mail = ?';
            $req = $bdd->prepare($sql);
            $req->execute(array($_POST['apikey'],$_SESSION['login']));
            $sql = 'UPDATE servers SET apikey = ? WHERE user = ?';
            $req = $bdd->prepare($sql);
            $req->execute(array($_POST['apikey'],$_SESSION['login']));

}
if (!empty($_POST['phone'])){
          $sql = 'UPDATE admin SET phone = ? WHERE mail = ?';
          $req = $bdd->prepare($sql);
          $req->execute(array($_POST['phone'],$_SESSION['login']));
          $sql = 'UPDATE servers SET phone = ? WHERE user = ?';
          $req = $bdd->prepare($sql);
          $req->execute(array($_POST['phone'],$_SESSION['login']));
}
echo '<div class="callout callout-success">
    <h4>F&eacute;licitations !</h4>

    <p>Les modifications ont &eacute;t&eacute; prises en compte.</p>
  </div>';
}
 ?>
<?php $user = $_SESSION['login']; ?>
<?php
            $reponse = $bdd->prepare('SELECT * FROM `admin` WHERE `mail`= ?');
            $reponse->execute(array($user));
            while ($donnees = $reponse->fetch())
            {
?>
<div class="box box-info">
            <div class="box-header with-border">
              <h3 class="box-title">Profil</h3>
            </div>
            <!-- /.box-header -->
            <!-- form start -->
            <form class="form-horizontal" method="POST">
              <div class="box-body">
<!-- Email // disable -->
                <div class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label">Email</label>

                  <div class="col-sm-10">
                    <input class="form-control" id="inputEmail3" disabled="disabled" placeholder="<?php echo $donnees['mail']; ?>" type="email">
                  </div>
                </div>
<!-- Téléphone // -->
<div class="form-group">
  <label for="inputEmail3" class="col-sm-2 control-label">Téléphone</label>

  <div class="col-sm-10">
    <input class="form-control" name="phone" id="inputEmail3" name="phone" placeholder="<?php if ($donnees['phone'] == 'null') { }else{ echo $donnees['phone']; }; ?>" type="text">
  </div>
</div>
<!-- Mdp -->
          <div class="form-group">
                <label for="inputPassword3" class="col-sm-2 control-label">Mot de passe</label>

                  <div class="col-sm-10">
                    <input class="form-control" id="inputPassword3" name="password" placeholder="Mot de passe" type="password">
                  </div>
                </div>
<!-- apikey -->

              <div class="form-group">
                <label for="inputEmail3" class="col-sm-2 control-label">API KEY (SMS)</label>

                <div class="col-sm-10">
                  <input class="form-control" id="inputEmail3" name="apikey" placeholder="<?php if ($donnees['apikey'] == 'null') { }else{ echo $donnees['apikey']; }; ?>" type="text">
                </div>
              </div>
<!-- Submit -->
              <div class="box-footer">
                <button type="submit" class="btn btn-info pull-left">Mettre à jour</button>
              </div>
              <!-- /.box-footer -->
            </form>
          </div>
<?php

}


$reponse->closeCursor(); // Termine le traitement de la requête

?>


                  </table>
                </div><!-- /.box-body -->
              </div><!-- /.box -->
            </div>
          </div>
        </section><!-- /.content -->
      </div>
      </aside><!-- /.control-sidebar -->
      <!-- Add the sidebar's background. This div must be placed
           immediately after the control sidebar -->
      <div class='control-sidebar-bg'></div>

    </div><!-- ./wrapper -->

    <!-- jQuery 2.1.4 -->
    <script src="plugins/jQuery/jQuery-2.1.4.min.js"></script>
    <!-- Bootstrap 3.3.2 JS -->
    <script src="bootstrap/js/bootstrap.min.js" type="text/javascript"></script>
    <!-- FastClick -->
    <script src='plugins/fastclick/fastclick.min.js'></script>
    <!-- AdminLTE App -->
    <script src="dist/js/app.min.js" type="text/javascript"></script>
    <!-- Sparkline -->
    <script src="plugins/sparkline/jquery.sparkline.min.js" type="text/javascript"></script>
    <!-- jvectormap -->
    <script src="plugins/jvectormap/jquery-jvectormap-1.2.2.min.js" type="text/javascript"></script>
    <script src="plugins/jvectormap/jquery-jvectormap-world-mill-en.js" type="text/javascript"></script>
    <!-- SlimScroll 1.3.0 -->
    <script src="plugins/slimScroll/jquery.slimscroll.min.js" type="text/javascript"></script>
    <!-- ChartJS 1.0.1 -->
    <script src="plugins/chartjs/Chart.min.js" type="text/javascript"></script>

    <!-- AdminLTE dashboard demo (This is only for demo purposes) -->
    <script src="dist/js/pages/dashboard2.js" type="text/javascript"></script>

    <!-- AdminLTE for demo purposes -->
    <script src="dist/js/demo.js" type="text/javascript"></script>
  </body>
</html>
<META http-equiv="refresh" content="60; URL=index.php">
