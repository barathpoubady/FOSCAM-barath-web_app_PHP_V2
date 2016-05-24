<?php


// FUNCTION REQUETE PAR SELECTION
function requeteDefaut(){
	
	//REQUETE PAR DEFAUT
	$sql = mysql_query("SELECT * FROM `infoimage` ORDER BY `date` DESC , `heure` DESC LIMIT ".$_POST['precedent']." , ".$_POST['suivant']."");
		
	return $sql;
}

?>