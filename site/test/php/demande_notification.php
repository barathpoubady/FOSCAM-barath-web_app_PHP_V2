<?php


// FUNCTION REQUETE PAR SELECTION
function demande_infoNotification(){
	
	//REQUETE RECUPERE LES INFORMATIONS
	
	/* FAIT LA SOMME DU CHAMP 'taille_img' */
	$sql = mysql_query("SELECT SUM( taille_img ) AS 'somme' FROM `infoimage` ");
	$aDataSom = mysql_fetch_array($sql);
	
	/* RECUPERE LES INFO DE LA DERNIERE IMAGE */
	$sql_2 = mysql_query("SELECT `id`,`date`,`heure` FROM `infoimage` ORDER BY `id` DESC LIMIT 1");
	$aDataLastImg = mysql_fetch_array($sql_2);
	
	/* COMPTE LE NOMBRE D'IMAGE DANS LA BDD */
	$sql_3 = mysql_query("SELECT count(*) AS 'nombre_Image' FROM `infoimage`");
	$aDataNbImg = mysql_fetch_array($sql_3);
	
	/* TRAITEMENT DES INFOS */
	
	$espaceUtilise = $aDataSom['somme'] / 1000000;
			
	echo '<div id="espaceUse">'.round($espaceUtilise, 3).' GO / 10 GO</div> 
	<div id="date_derniereImgCharge">'.$aDataLastImg['date'].'</div> 
	<div id="heure_derniereImgCharge">'.$aDataLastImg['heure'].'</div> 
	<div id="nb_image_bdd">'.$aDataNbImg['nombre_Image'].'</div>';
	die();	

	//return $sql;
}

?>