<?php

/************************************************/
/* Inclusion de la classe Connexion. */  //Etablir la connexion a la BDD 
	require '../../common/config/connexion.php';
/************************************************/

if (isset($_GET["fileID"]))
{

	$sql = mysql_query("SELECT * FROM `infoimage` WHERE `id` = '".$_GET["fileID"]."'");
	if (!$sql) {
		 die('Impossible d\'exécuter la requête :' . mysql_error());
    }
	$aData = mysql_fetch_array($sql);
	

$nameFile = $aData['lien_img'];

$nomFichier = basename($nameFile);


//print_r($nameFile. " | " .$nomFichier);
if (file_exists($nameFile)) {
    header('Content-Description: File Transfer');
    header('Content-Type: application/octet-stream');
    header('Content-Disposition: attachment; filename='.$nomFichier);
    header('Content-Transfer-Encoding: binary');
    header('Expires: 0');
    header('Cache-Control: must-revalidate');
    header('Pragma: public');
    readfile($nameFile);
}else{
	
echo($nomFichier. " " .$nameFile);
	
}


}
?> 