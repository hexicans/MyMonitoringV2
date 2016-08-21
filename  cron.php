<?php
include 'db_connect.php';
 ?>
<?php
$reponse = $bdd->prepare('SELECT * FROM `servers`');
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
$status = @fsockopen($ip, $port, $errno, $errstr, 30); // true si up, false si down.

  if (!$status) {
    $sql = 'UPDATE servers SET mail_send = ? WHERE IP = ?';
    $req = $bdd->prepare($sql);
    $req->execute(array(true, $donnees['IP']));
    echo '<span class="label label-danger">Hors ligne</span>'; fclose($socket);

    if(!$donnees['mail_send']){
      include 'includes/mailoff.php';
    }
  }
  else
  {
    $sql = 'UPDATE servers SET mail_send = ? WHERE IP = ?';
    $req = $bdd->prepare($sql);
    $req->execute(array(false, $donnees['IP']));
    echo '<span class="label label-success">En ligne</span>'; fclose($socket);

    if($donnees['mail_send']){
      include 'includes/mailon.php';
    };
  }

}

$reponse->closeCursor(); // Termine le traitement de la requête


?>
