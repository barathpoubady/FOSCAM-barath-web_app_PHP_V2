<?php

	//mysql recupere les données
	function mysqlRecupeURL(){
		
	  //champs
	  $today = date("Y-m-d");
	  $tabJson = array();
	  $i = 0;
	  $j = 0;


		//REQUETE PAR DEFAUT
	  $sql = mysql_query("SELECT `date`,`lien_img`,`heure` FROM `infoimage` WHERE `date`='".$today."' ORDER BY `heure` ASC");
		
		//RECUPERE LES DONNEES
		if (!$sql) {
		 die('Impossible d\'exécuter la requête :' . mysql_error());
    }
		
		//AFFICHE LES DONNEES
		while($aData=mysql_fetch_array($sql))
		{
			$lienIMG=$aData['lien_img'];
			$date=$aData['date'];
			$heure=$aData['heure'];

			//echo $lienIMG ."<br />";
			$tabJson[$i] = $aData['lien_img'];
			$i++;
		}
		
		echo json_encode($tabJson);
	}
	



?>