<?php


// FUNCTION REQUETE PAR SELECTION
function requeteSelection(){

	$heurePlus = $_POST['heure'] +1;
	
	$sql = mysql_query("SELECT `id`,`lien_img` ,`date` ,`heure` FROM `infoimage` WHERE `date` = '".$_POST['annee'].'-'.$_POST['mois'].'-'.$_POST['jour']."'  && `heure` > '".$_POST['heure'].":00:00' && `heure` < '".$heurePlus.":00:00' ORDER BY `heure` "); //LIMIT ".$_POST['precedent']." , ".$_POST['suivant']."

	return $sql;

}
	
	

?>