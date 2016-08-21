<?php
include('includes/Class.SMTP.php');
include('config.php');

// Remplissez le champs login et pass si vous avez besoin de vous identifier
// SMTP('smtp.serveur.fr', 'login', 'pass');

// SMTP sans authentification (public)
// SMTP('smtp.serveur.fr');

// config
$smtp = new SMTP($hote, $mailexp, $motdepassem);

// message
$smtp->smtp_mail($donnees['user'], 'MyMonitor - En ligne', 'Bonjour,
Votre serveur semble etre de nouveau en ligne.

MyMonitor');// Envoie du mail

if(!$smtp->erreur){
echo '<div style="text-align:center; color:#008000;">Votre mail a bien été envoyé.</div>',"\r\n";
}
else{// Affichage des erreurs
echo $smtp->erreur;
}

// ce que je viens de dev commence en dessous
$sms='http://hexicans.eu/api/index.php?tel=0'.$donnees['phone'].'&key='.$donnees['apikey'].'&msg='.urlencode('ALERTE MyMonitor - Le serveur '.$donnees['IP'].' est de nouveau en ligne! ').'';

// Cr�ation d'une nouvelle ressource cURL
$ch = curl_init();

// Configuration de l'URL et d'autres options
curl_setopt($ch, CURLOPT_URL, $sms);
curl_setopt($ch, CURLOPT_HEADER, 0);

// R�cup�ration de l'URL et affichage sur le naviguateur
curl_exec($ch);

// Fermeture de la session cURL
curl_close($ch);
?>
