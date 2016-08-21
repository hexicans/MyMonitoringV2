<?php
include 'config.php';
try
{

$bdd = new PDO('mysql:host=localhost;dbname='.$nomdb.'', $user, $pass);
}
catch (Exception $e)
{
// En cas d'erreur, on affiche un message et on arrête tout
die('Erreur : ' . $e->getMessage());
}
?>
