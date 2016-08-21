<?php
/*******************************************************************************
*
* Nom de la source :
*       Class SMTP
* Nom du fichier par dï¿½faut :
*       Class.SMTP.php
* Auteur :
*       Nuel Guillaume alias Immortal-PC
* Site Web :
*       http://immortal-pc.info/
*
*******************************************************************************/

class SMTP {
      // Nom du domaine ou nom du serveur
    var $NomDuDomaine = '';

    // De Qui
    var $From = 'contact@mymonitor.com';// Adresse de l' expï¿½diteur
    var $FromName = 'MyMonitoring';// Nom de l' expï¿½diteur
    var $ReplyTo = 'contact@mymonitor.com';// Adresse de retour
    var $org = 'Localhost'; // Organisation

    // A Qui
    var $To = '';
    // Utilisation : $Bcc = 'mail1,mail2,....';
    var $Bcc = '';// Blind Carbon Copy, c'est ï¿½ dire que les adresses qui sont contenue ici seront invisibles pour tout le monde
    var $Cc = '';

    // Prioritï¿½
    var $Priority = 3;// Prioritï¿½ accordï¿½e au mail (valeur allant de 1 pour Urgent ï¿½ 3 pour normal et 6 pour bas)

    // Encodage
    var $ContentType = 'html';//Contenu du mail (texte, html...) (txt , html, txt/html)
    var $Encoding = '8bit'; // Ancienne valeur quoted-printable
    var $ISO = 'iso-8859-15';
    var $MIME = '1.0';// La version mime
    var $Encode = false;// Encodage necessaire ou pas
	var $CHARSET = '';

    // Confirmation de reception
    var $Confimation_reception = '';// Entrez l' adresse oï¿½ sera renvoyï¿½ la confirmation

    // Le mail
    var $Sujet = '';
    var $Body = '';
    var $Body_txt = '';

    // Fichier(s) joint(s)
    var $File_joint = array();

    // Nombre tour
    var $Tour = 0;


    //**************************************************************************
    // Paramï¿½tre de connection SMTP
    //**************************************************************************
    var $Authentification_smtp = false;

    var $serveur = '';// Serveur SMTP
    var $port = 25;// Port SMTP
    var $login_smtp = '';// Login pour le serveur SMTP
    var $mdp_smtp = '';// Mot de passe pour le serveur SMTP
    var $time_out = 10;// Durï¿½e de la connection avec le serveur SMTP
    var $tls = false;// Activation de la connection sï¿½curisï¿½e (anciennement ssl)


    //**************************************************************************
    // Variables temporaires
    //**************************************************************************
    var $smtp_connection = '';// Variable de connection
    var $erreur = '';
    var $debug = false;

//------------------------------------------------------------------------------

    //**************************************************************************
    // Fonction de dï¿½claration de connection SMTP
    //**************************************************************************
    function SMTP($serveur='', $user='', $pass='', $port=25, $NomDuDomaine='', $debug=false){
        if($serveur){
            $this->serveur = $serveur;
        }
        if($user){
            $this->Authentification_smtp = true;
            $this->login_smtp = $user;
            $this->mdp_smtp = $pass;
        }
        $this->port = $port;
        if($NomDuDomaine){
            $this->NomDuDomaine = $NomDuDomaine;
        }
        $this->debug = $debug;
    }


    //**************************************************************************
    // Fonction de connection SMTP
    //**************************************************************************
    function Connect_SMTP(){
		// Definition du charset
		if(!$this->CHARSET){ $this->CHARSET = mb_internal_encoding(); }

        // Connection au serveur SMTP
        $this->smtp_connection = fsockopen($this->serveur, // Serveur
                                     $this->port,          // Port de connection
                                     $num_erreur,    	   // Numï¿½ros de l' erreur
                                     $msg_erreur,    	   // Message d' erreur
                                     $this->time_out);     // Durï¿½e de la connection en secs
        if(!$this->smtp_connection){// Vï¿½rification de la connection
            $this->erreur = 'Impossible de se connecter au serveur SMTP !!!<br />'."\r\n"
            .'Numï¿½ro de l&#39; erreur: '.$num_erreur.'<br />'."\r\n"
            .'Message renvoyï¿½: '.$msg_erreur.'<br />'."\r\n";
            return false;
        }

        // Suppression du message d' accueil
        $reponce = $this->get_smtp_data();
        // Debug
        if($this->debug){
            echo '<div style="color:#993300;">Connection</div>',"\r\n",str_replace("\r\n", '<br />', $reponce['msg']);
        }

        // On rï¿½gle le timeout du serveur SMTP car parfois, le serveur SMTP peut ï¿½tre un peut lent ï¿½ rï¿½pondre
        // Windows ne comprend pas la fonction socket_set_timeout donc on vï¿½rifi que l' on travail sous Linux
        if(substr(PHP_OS, 0, 3) !== 'WIN'){
           socket_set_timeout($this->smtp_connection, $this->time_out, 0);
        }

        //**********************************************************************
        // Commande EHLO et HELO
        if($this->NomDuDomaine === ''){// On vï¿½rifit si le nom de domaine ï¿½ ï¿½tï¿½ renseignï¿½
            if($_SERVER['SERVER_NAME'] !== ''){
                $this->NomDuDomaine = $_SERVER['SERVER_NAME'];
            }else{
                $this->NomDuDomaine = 'localhost.localdomain';
            }
        }

        if(!$this->Commande('EHLO '.$this->NomDuDomaine, 250)){// Commande EHLO
            // Deusiï¿½me commande EHLO -> HELO
            if(!$this->Commande('HELO '.$this->NomDuDomaine, 250, 'Le serveur refuse l&#39; authentification (EHLO et HELO) !!!')){// Commande HELO
                return false;
            }
        }

        if($this->tls && !$this->Commande('STARTTLS', 220, 'Le serveur refuse la connection sï¿½curisï¿½e ( STARTTLS ) !!!')){// Commande STARTTLS
            return false;
        }

        if($this->Authentification_smtp){// On vï¿½rifi si l' on a besoin de s' authentifier
            //******************************************************************
            // Authentification
            //******************************************************************
            if(!$this->Commande('AUTH LOGIN', 334, 'Le serveur refuse l&#39; authentification (AUTH LOGIN) !!!')){
                return false;
            }


            //******************************************************************
            // Authentification : Login
            //******************************************************************
            $tmp = $this->Commande(base64_encode($this->login_smtp), 334, 'Login ( Nom d&#39; utilisateur ) incorrect !!!', 0);
            if(!$tmp['no_error']){
                return false;
            }
            // Debug
            if($this->debug){
                echo '<div style="color:#993300;">Envoie du login.</div>',"\r\n",str_replace("\r\n", '<br />', $tmp['msg']);
            }


            //******************************************************************
            // Authentification : Mot de passe
            //******************************************************************
            $tmp = $this->Commande(base64_encode($this->mdp_smtp), 235, 'Mot de passe incorrect !!!', 0);
            if(!$tmp['no_error']){
                return false;
            }
            // Debug
            if($this->debug){
                echo '<div style="color:#993300;">Envoie du mot de passe.</div>',"\r\n",str_replace("\r\n", '<br />', $tmp['msg']);
            }

        }

        //**********************************************************************
        // Connectï¿½ au serveur SMTP
        //**********************************************************************
        return true;
    }


    //**************************************************************************
    // Fonctons de set
    //**************************************************************************
    function set_from($name, $email='', $org='Localhost'){
		$this->FromName = $name;
		if($this->Encode){
			$this->FromName = $this->encode_mimeheader(mb_convert_encoding($this->FromName, $this->ISO, $this->CHARSET), $this->ISO);
		}
        if(!empty($email)){
            $this->From = $email;
        }
        $this->org = $org;
        unset($name, $email, $org);
    }

    function set_encode($ISO, $CHARSET=''){
		$this->Encode = true;
		$this->ISO = $ISO;
		$this->CHARSET = $CHARSET;
        unset($ISO, $CHARSET);
    }


    //**************************************************************************
    // System d' encodage par Pierre CORBEL
    //**************************************************************************
	function encode_mimeheader($string){
		$encoded = '';
		$CHARSET = mb_internal_encoding();
		// Each line must have length <= 75, including `=?'.$this->CHARSET.'?B?` and `?=`
		$length = 75 - strlen('=?'.$this->CHARSET.'?B?') - 2;
		$tmp = mb_strlen($string, $this->CHARSET);
		// Average multi-byte ratio
		$ratio = mb_strlen($string, $this->CHARSET) / strlen($string);
		// Base64 has a 4:3 ratio
		$magic = floor(3 * $length * $ratio / 4);
		$avglength = $magic;

		for($i=0; $i <= $tmp; $i+=$magic) {
			$magic = $avglength;
			$offset = 0;
			// Recalculate magic for each line to be 100% sure
			do{
				$magic -= $offset;
				$chunk = mb_substr($string, $i, $magic, $this->CHARSET);
				$chunk = base64_encode($chunk);
				$offset++;
			}while(strlen($chunk) > $length);
			if($chunk){
				$encoded .= ' '.'=?'.$this->CHARSET.'?B?'.$chunk.'?='."\r\n";
			}
		}
		// Chomp the first space and the last linefeed
		return substr($encoded, 1, -2);
	}


    //**************************************************************************
    // Foncton d' ajout de piï¿½ce jointe
    //**************************************************************************
    function add_file($url_file){
    	if(!$url_file){
			$this->erreur = 'Champs manquant !!!<br />'."\r\n";
			return false;
		}
		if(!($fp = @fopen($url_file, 'a'))){
			$this->erreur = 'Fichier introuvable !!!<br />'."\r\n";
			return false;
		}
		fclose($fp);

		$file_name = explode('/', $url_file);
		$file_name = $file_name[count($file_name)-1];
		$mime = parse_ini_file('./mime.ini');
		$ext = explode('.', $file_name);
		$ext = $ext[count($ext)-1];

		if(IsSet($this->File_joint[$file_name])){
			$file_name = explode('_', str_replace('.'.$ext, '', $file_name));
			if(is_numeric($file_name[count($file_name)-1])){
				$file_name[count($file_name)-1]++;
				$file_name = implode('_', $file_name);
			}else{
				$file_name = implode('_', $file_name);
				$file_name .= '_1';
			}
			$file_name .= '.'.$ext;
		}
		$this->File_joint[$file_name] = array(
										'url' => $url_file,
										'mime' => $mime[$ext]
										);
		unset($file_name, $mime, $ext);
    }


    //**************************************************************************
    // Entï¿½tes (Headers)
    //**************************************************************************
    function headers(){
		// Id unique
		$Boundary1 = '------------Boundary-00=_'.substr(md5(uniqid(time())), 0, 7).'0000000000000';
		$Boundary2 = '------------Boundary-00=_'.substr(md5(uniqid(time())), 0, 7).'0000000000000';
		$Boundary3 = '------------Boundary-00=_'.substr(md5(uniqid(time())), 0, 7).'0000000000000';

        $header = '';
        $No_body = 0;

        // Adresse de l'expï¿½diteur (format : Nom <adresse_mail>)
        if(!empty($this->From)){
            $header .= 'X-Sender: '.$this->From."\n";// Adresse rï¿½elle de l'expï¿½diteur
        }
		// La version mime
        if(!empty($this->MIME)){
            $header .= 'MIME-Version: '.$this->MIME."\n";
        }
        $header .= sprintf("Message-ID: <%s@%s>%s", md5(uniqid(time())), $this->NomDuDomaine, "\n")
        .'Date: '.date('r')."\n"
        .'Content-Type: Multipart/Mixed;'."\n"
        .'  boundary="'.$Boundary1.'"'."\n"
        // Logiciel utilisï¿½ pour l' envoi des mails
		.'X-Mailer: PHP '.phpversion()."\n";
		// Adresse de l'expï¿½diteur (format : Nom <adresse_mail>)
        if(!empty($this->From)){
            if(!empty($this->FromName)){
                $header .= 'From: "'.$this->FromName.'"';
            }else{
                $header .= 'From: ';
            }
            $header .= '<'.$this->From.">\n";
		}
		$header .= 'X-FID: FLAVOR00-NONE-0000-0000-000000000000'."\n";

		// Prioritï¿½ accordï¿½e au mail (valeur allant de 1 pour Urgent ï¿½ 3 pour normal et 6 pour bas)
        if(!empty($this->Priority)){
            $header .= 'X-Priority: '.$this->Priority."\n";
        }
		// To
        if(!empty($this->To)){// A
            $header .= 'To: '.$this->To."\n";
        }else{
            $No_body++;// Personne
        }
        // Cc
        if(!empty($this->Cc)){// Copie du mail
            $header .= 'Cc: '.$this->Cc."\n";
        }else{
            $No_body++;// Personne
        }
        // Bcc
        if(empty($this->Bcc)){// Blind Carbon Copy, c' est ï¿½ dire que les adresses qui sont contenue ici seront invisibles pour tout le monde
            $No_body++;// Personne
        }
        // Sujet
        if(!empty($this->Sujet)){
            $header .= 'Subject: '.$this->Sujet."\n";
        }
        if(!empty($this->Confimation_reception)){// Adresse utilisï¿½e pour la rï¿½ponse au mail
            $header .= 'Disposition-Notification-To: <'.$this->Confimation_reception.'>'."\n";
        }
		// ReplyTo
		if(!empty($this->ReplyTo) && $this->ReplyTo !== $this->From && $this->ReplyTo !== 'root@localhost'){// Adresse utilisï¿½e pour la rï¿½ponse au mail
            $header .= 'Reply-to: '.$this->ReplyTo."\n"
            .'Return-Path: <'.$this->ReplyTo.">\n";
        }
        if(!IsSet($_SERVER['REMOTE_ADDR'])){$_SERVER['REMOTE_ADDR'] = '127.0.0.1';}
        if(!IsSet($_SERVER['HTTP_X_FORWARDED_FOR'])){$_SERVER['HTTP_X_FORWARDED_FOR'] = '';}
        if(!IsSet($_SERVER['HTTP_USER_AGENT'])){$_SERVER['HTTP_USER_AGENT'] = 'Internet Explorer';}
        if(!IsSet($_SERVER['HTTP_ACCEPT_LANGUAGE'])){$_SERVER['HTTP_ACCEPT_LANGUAGE'] = 'Fr-fr';}
        $host = 'localhost';
        if(function_exists('gethostbyaddr') && $_SERVER['REMOTE_ADDR'] !== '127.0.0.1'){$host = gethostbyaddr($_SERVER['REMOTE_ADDR']);}
        $header .= 'X-Client-IP: '.$_SERVER['REMOTE_ADDR']."\n"
		.'X-Client-PROXY: '.$_SERVER['HTTP_X_FORWARDED_FOR']."\n"
		.'X-Client-Agent: '.$_SERVER['HTTP_USER_AGENT']."\n"
		.'X-Client-Host: '.$host."\n"
		.'X-Client-Language: '.$_SERVER['HTTP_ACCEPT_LANGUAGE']."\n"
		.'Organization: '.$this->org."\n"
		."\n\n\n"
		.'--'.$Boundary1."\n"
		.'Content-Type: Multipart/Alternative;'."\n"
		.'  boundary="'.$Boundary3.'"'."\n"
		."\n\n"
		.'--'.$Boundary3."\n";
		if($this->ContentType === 'txt' || $this->ContentType === 'txt/html'){
			$header .= 'Content-Type: Text/Plain;'."\r\n"
			.'  charset="'.$this->ISO.'"'."\r\n"
			.'Content-Transfer-Encoding: '.$this->Encoding."\r\n"
			."\r\n";
			if($this->ContentType === 'txt'){
				$header .= $this->Body."\r\n";
			}else{
				$header .= $this->Body_txt."\r\n";
			}
		}elseif($this->ContentType === 'html' || $this->ContentType === 'txt/html'){
			if($this->ContentType === 'txt/html'){
				$header .= '--'.$Boundary3."\r\n";
			}
			$header .= 'Content-Type: Text/HTML;'."\r\n"
			.'  charset="'.$this->ISO.'"'."\r\n"
			.'Content-Transfer-Encoding: '.$this->Encoding."\r\n"
			."\r\n"
			.'<html><head>'."\r\n"
			.'<meta http-equiv="Content-LANGUAGE" content="French" />'."\r\n"
			.'<meta http-equiv="Content-Type" content="text/html; charset='.$this->ISO.'" />'."\r\n"
			.'</head>'."\r\n"
			.'<body>'."\r\n"
			.$this->Body."\r\n"
			.'</body></html>'."\r\n"
			.'--'.$Boundary3.'--'."\r\n";
		}else{
			$header .= 'Content-Type: '.$this->ContentType.';'."\r\n"
			.'  charset="'.$this->ISO.'"'."\r\n"
			.'Content-Transfer-Encoding: '.$this->Encoding."\r\n"
			."\r\n"
			.$this->Body."\r\n";
		}
		$header .= "\n";

		// On joint le ou les fichiers
		if($this->File_joint){
			foreach($this->File_joint as $file_name => $file){
		        $header .= '--'.$Boundary1."\n"
				.'Content-Type: '.$file['mime'].';'."\n"
				.'  name="'.$file_name.'"'."\n"
				.'Content-Disposition: attachment'."\n"
				.'Content-Transfer-Encoding: base64'."\n"
				."\n"
				.chunk_split(base64_encode(file_get_contents($file['url'])))."\n"
				."\n\n";
			}
		}
		$header .= '--'.$Boundary1.'--';

        if($No_body === 3){
            $this->erreur = 'Le mail n&#39; a pas de destinataire !!!';
            return false;
        }
        return $header;
    }


    //**************************************************************************
    // Envoie du mail avec le serveur SMTP
    //**************************************************************************
    function smtp_mail($to, $subject, $message, $header=''){
        // Pas de dï¿½connection automatique
        $auto_disconnect = false;
        // On vï¿½rifit si la connection existe
        if(empty($this->smtp_connection)){
            if(!$this->Connect_SMTP()){// Connection
                $this->erreur .= 'Impossible d&#39; envoyer le mail !!!<br />'."\r\n";
                return false;
            }
            $auto_disconnect = true;// Dï¿½connection automatique activï¿½e
        }

        // On vï¿½rifit Que c' est le premier tour sinon on ï¿½fface les anciens paramï¿½tres
        if($this->Tour){
            if($this->Commande('RSET', 250, 'Envoie du mail impossible !!!')){
                $this->Tour = 0;
            }
        }

        //**********************************************************************
        // Variables temporairement modifiï¿½es
        if(!empty($to)){
            $this->To = $to;
        }
        if(!empty($subject)){
			if($this->Encode){
				$this->Sujet = $this->encode_mimeheader(mb_convert_encoding($subject, $this->ISO, $this->CHARSET), $this->ISO);
			}else{
				$this->Sujet = mb_encode_mimeheader($subject, $this->ISO);
			}
        }

        if(is_array($message)){
			$this->Body = $message[0];
			$this->Body_txt = $message[1];
			if($this->Encode){
				$this->Body = mb_convert_encoding($this->Body, $this->ISO, $this->CHARSET);
				$this->Body_txt = mb_convert_encoding($this->Body_txt, $this->ISO, $this->CHARSET);
			}
		}else{
        	$this->Body = $message;
			if($this->Encode){
				$this->Body = mb_convert_encoding($this->Body, $this->ISO, $this->CHARSET);
			}
        }

        //**********************************************************************
        // Y a t' il un destinataire
        if(empty($this->To) && empty($header) && empty($this->Bcc) && empty($this->Cc)){
            $this->erreur = 'Veuillez entrer une adresse de destination !!!<br />'."\r\n";
            return false;
        }

        //**********************************************************************
        // Envoie des informations
        //**********************************************************************

        //**********************************************************************
        // De Qui
        if(!empty($this->From) && !$this->Tour){
            if(!$this->Commande('MAIL FROM:<'.$this->From.'>', 250, 'Envoie du mail impossible car le serveur n&#39; accï¿½pte pas la commande MAIL FROM !!!')){
                return false;
            }
            $this->Tour = 1;
        }

        //**********************************************************************
        // A Qui
        $A = array();
        if(!empty($this->To)){
            $A[0] = $this->To;
        }
        if(!empty($this->Bcc)){
            $A[1] = $this->Bcc;
        }
        if(!empty($this->Cc)){
            $A[2] = $this->Cc;
        }
        foreach($A as $cle => $tmp_to){
            if(substr_count($tmp_to, ',')){
                $tmp_to = explode(',', $tmp_to);
                foreach($tmp_to as $cle => $tmp_A){
                    if(!$this->Commande('RCPT TO:<'.$tmp_A.'>', array(250,251), 'Envoie du mail impossible car le serveur n&#39; accï¿½pte pas la commande RCPT TO !!!')){
                        return false;
                    }
                }
            }else{
                if(!$this->Commande('RCPT TO:<'.$tmp_to.'>', array(250,251), 'Envoie du mail impossible car le serveur n&#39; accï¿½pte pas la commande RCPT TO !!!')){
                    return false;
                }
            }
        }

        //**********************************************************************
        // On crï¿½er les entï¿½tes ( headers ) si c' est pas fait
        if(empty($header)){
            if(!$header = $this->headers()){
                $this->erreur .= 'Impossible d&#39; envoyer le mail !!!<br />'."\r\n";
                return false;
            }
        }


        //**********************************************************************
        // On indique que l' on va envoyer des donnï¿½es
        if(!$this->Commande('DATA', 354, 'Envoie du mail impossible car le serveur n&#39; accï¿½pte pas la commande DATA!!!')){
            return false;
        }


        //**********************************************************************
        // Envoie de l' entï¿½te et du message
        fputs($this->smtp_connection, $header);
        fputs($this->smtp_connection, "\r\n.\r\n");

        $reponce = $this->get_smtp_data();
        // Debug
        if($this->debug){
            echo '<div style="color:#993300;">Entï¿½te et message :<br />',"\r\n",'<div style="padding-left:25px;">',str_replace(array("\r\n","\n"), '<br />', $header),'<br />',"\r\n",$message,'</div>',"\r\n",'</div>',"\r\n",str_replace("\r\n", '<br />', $reponce['msg']);
        }
        if($reponce['code'] !== 250 && $reponce['code'] !== 354){
            $this->erreur = 'Envoie du mail impossible !!!<br />'."\r\n"
            .'Numï¿½ro de l&#39; erreur: '.$reponce['code'].'<br />'."\r\n"
            .'Message renvoyï¿½: '.$reponce['msg'].'<br />'."\r\n";
            return false;
        }


        //**********************************************************************
        // Variables temporairement modifiï¿½es
        if($to === $this->To){
            $this->To = '';
        }
        if($subject === $this->Sujet){
            $this->Sujet = '';
        }

        //**********************************************************************
        // Dï¿½connection automatique
        //**********************************************************************
        if($auto_disconnect){// Auto dï¿½connection ?
            $this->Deconnection_SMTP();// Dï¿½connection
        }

        //**********************************************************************
        // Mail envoyï¿½
        //**********************************************************************
        return true;
    }


    //**************************************************************************
    // Lecture des donnï¿½es renvoyï¿½es par le serveur SMTP
    //**************************************************************************
    function get_smtp_data(){
        $data = '';
        while($donnees = fgets($this->smtp_connection, 515)){// On parcour les donnï¿½es renvoyï¿½es
            $data .= $donnees;

            if(substr($donnees,3,1) == ' ' && !empty($data)){break;}// On vï¿½rifi si on a toutes les donnï¿½es
        }
        // Renvoie des donnï¿½es : array(Code, message complet)
        return array('code'=>(int)substr($data, 0, 3), 'msg'=>$data);
    }


    //**************************************************************************
    // Execution des commandes SMTP
    //**************************************************************************
    function Commande($commande, $bad_error, $msg_error='', $debug=1){
        if(!empty($this->smtp_connection)){
            fputs($this->smtp_connection, $commande."\n");
            $reponce = $this->get_smtp_data();
            // Debug
            if($this->debug && $debug){
                echo '<div style="color:#993300;">',htmlentities($commande),'</div>',"\r\n",str_replace("\r\n", '<br />', $reponce['msg']);
            }

            // Tableau de code valide
            if((is_array($bad_error) && !in_array($reponce['code'], $bad_error)) || (!is_array($bad_error) && $reponce['code'] !== $bad_error)){
                if($msg_error){
                    $this->erreur = $msg_error.'<br />'."\r\n"
                    .'Numï¿½ro de l&#39; erreur: '.$reponce['code'].'<br />'."\r\n"
                    .'Message renvoyï¿½: '.$reponce['msg'].'<br />'."\r\n";
                }
                if(!$debug){
                    return array('no_error'=>false, 'msg'=>$reponce['msg']);
                }else{
                    return false;
                }
            }

            if(!$debug){
                return array('no_error'=>true, 'msg'=>$reponce['msg']);
            }else{
                return true;
            }
        }else{
            $this->erreur = 'Impossible d&#39; ï¿½xecuter la commande <span style="font-weight:bolder;">'.$commande.'</span> car il n&#39; y a pas de connection !!!<br />'."\r\n";
            if(!$debug){
                return array('no_error'=>false, 'msg'=>'');
            }else{
                return false;
            }
        }
    }


    //**************************************************************************
    // Fonction de dï¿½connection SMTP
    //**************************************************************************
    function Deconnection_SMTP(){
        if(!empty($this->smtp_connection)){
            if(!$this->Commande('QUIT', 221, 'Impossible de se dï¿½connecter !!!')){
                return false;
            }

            @sleep(5);// On laisse 5 seconde au serveur pour terminer toutes les instructions
            if(!fclose($this->smtp_connection)){
                $this->erreur = 'Impossible de se dï¿½connecter !!!<br />'."\r\n";
                return false;
            }
            $this->smtp_connection = 0;
            return true;
        }
        $this->erreur = 'Impossible de se dï¿½connecter car il n&#39; y a pas de connection !!!<br />'."\r\n";
        return false;
    }
}
?>
