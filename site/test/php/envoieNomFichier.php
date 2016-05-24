<?php



// FUNCTION ENREGISTRE EN BDD ET ENVOIE L'IMAGE DANS LE REPERTOIRE D'IMAGE+DATE
function envoieNomFichier($tab_image,$j,$dateRep,$oBdd){

	/* Recuperation des infos de l'image*/
	$taille_de_img = filesize(REP_IMAGE.$tab_image[$j]); 
	$taille_de_img_Ko = intval($taille_de_img / 1000) . " KO";
	$lien_de_img = "images/".$dateRep."/".$tab_image[$j];
	$heure_img = date("H:i:s",filemtime(REP_IMAGE.$tab_image[$j]));

	/* Requete mySQL QUI ENVOIE LES DONNEES EN BDD */			 
	$aRequete = mysql_query("INSERT INTO `infoimage` (`nom_img`, `taille_img`, `lien_img`, `date`, `heure`) VALUES ('".$tab_image[$j]."', '".$taille_de_img_Ko."', '".$lien_de_img."', '".$dateRep."', '".$heure_img."')");
	if (!$aRequete) {
		die('Impossible d\'exécuter la requête 1 :' . mysql_error());
	}


	/************* DEPLACE L'IMAGE VERS SONT REPERTOIRE ***********/
		$dossier_nouveau = "images/".$dateRep;
		rename(REP_IMAGE.$tab_image[$j], $dossier_nouveau.'/'.$tab_image[$j]);
	
	/********************** COPIE L'index et la DEPLACE ********************/
	$file = 'images/fileIndex/index.php';
	$newfile = 'images/'.$dateRep.'/index.php';

	if (!copy($file, $newfile)) {
		echo "La copie $file du fichier a échoué...\n";
	}

}




?>