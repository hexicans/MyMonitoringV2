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
      <li><a href="index.php"><i class="fa fa-dashboard"></i> Accueil</a></li>
    </ol>
  </section>

  <!-- Main content -->
 <div class="container">



<!-- /.box-header -->

<br />

<div class="row">
<?php $user = $_SESSION['login']; ?>
<?php
$reponse = $bdd->prepare('SELECT * FROM `servers` WHERE `user`= ?');
$reponse->execute(array($user));
if (isset($_GET["del"])) {
    $req = $bdd->prepare('DELETE FROM servers WHERE id = :id');
    $req->execute(array('id' => $_GET["del"]));
    $suppru = 'Confirmer';
echo '<META http-equiv="refresh" content="0; URL=myservers.php">';
  }


// On affiche chaque entrée une à une

while ($donnees = $reponse->fetch())


{

?>












    <div class="col-md-4">



<br />
<?php
$ip = $donnees['IP'];
$port = $donnees['port'];
if (!$socket = @fsockopen($ip, $port, $errno, $errstr, 30))

{
 echo '

<div class="box box-solid box-danger">
  <div class="box-header">
    <h3 class="box-title"> '.$donnees['nom'].' </h3>
  </div>
  <div class="box-body">


 '.$donnees['IP'].'

<br />


Ping : <span class="label label-danger">Failed</span>';
}
else
{ echo '
<div class="box box-solid box-success">
  <div class="box-header">
    <h3 class="box-title"> '.$donnees['nom'].'</h3>
  </div>
  <div class="box-body">

'.$donnees['IP'].'

<br/>
Ping : <span class="label label-success">Ok</span>'; fclose($socket);
}



?>

<br /><br />
  <center>  <a href="view.php?id=<?php echo $donnees['id']; ?>" class="btn btn-info" role="button">Voir les détails</a>
   <a href="myservers.php?del=<?php echo $donnees['id']; ?>" class="btn btn-danger" role="button">Supprimer le serveur</a></center>
        </div><!-- /.box-body -->
      </div><!-- /.box -->
    </div>



































<?php
}
$reponse->closeCursor(); // Termine le traitement de la requête
?>
  </div>
<!-- /.box -->


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
<META http-equiv="refresh" content="60; URL=myservers.php">
