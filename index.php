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
                <div class="box-body table-responsive no-padding">
                  <table class="table table-hover">
                    <tr>
                      <th>Nom</th>
                      <th>Status</th>
                      <th>Description</th>
                      <th>Détails</th>


                    </tr>
<?php $user = $_SESSION['login']; ?>
<?php
// On récupère tout le contenu de la table jeux_video
$reponse = $bdd->prepare('SELECT * FROM `servers` WHERE `user`= ?');
$reponse->execute(array($user));
while ($donnees = $reponse->fetch())
{
?>
                    <tr>
                      <td style="width:20%;"><?php echo $donnees['nom']; ?></td>
                      <td  style="width:50%;">
<?php
$ip = $donnees['IP'];
$port = $donnees['port'];
if (!$socket = @fsockopen($ip, $port, $errno, $errstr, 30))

{
 echo '<span class="label label-danger">Hors ligne</span>';


$destinataire = $donnees['user'];
// Pour les champs $expediteur / $copie / $destinataire, séparer par une virgule s'il y a plusieurs adresses
$expediteur = $donnees['user'];
$objet = 'MyMonitor - Serveur Offline'; // Objet du message
$headers  = 'MIME-Version: 1.0' . "\n"; // Version MIME
$headers .= 'Reply-To: '.$expediteur."\n"; // Mail de reponse
$headers .= 'From: "MyMonitor"<'.$expediteur.'>'."\n"; // Expediteur
$headers .= 'Delivered-to: '.$destinataire."\n"; // Destinataire
$headers .= 'Cc: '.$copie."\n"; // Copie Cc
$headers .= 'Bcc: '.$copie_cachee."\n\n"; // Copie cachée Bcc
$message = 'Bonjour,

Votre serveur '.$ip.' ne semble plus joignable.
Merci de regarder au plus vite.
MyMonitor';
if (mail($destinataire, $objet, $message, $headers)) // Envoi du message
{
    echo '';
}
else // Non envoyé
{
    echo "";
}


}
else
{ echo '<span class="label label-success">En ligne</span>'; fclose($socket);
}
?>





 </td>
                      <td  style="width:50%;"><?php echo $donnees['description']; ?> </td>
                      <td><a href="view.php?id=<?php echo $donnees['id']; ?>">Détails</a> </td>
</tr>
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
