<?php

/* FUNCTION QUI SUPPRIME LES IMAGES SELON LA SELECTION */
function supprimeSelection(){
	
	
		if(isset($_POST['heureSup']) && isset($_POST['heureSup']) == 666){
			
			
			//REQUETE QUI RECUPERE TOUS LES CHAMPS OU DATE | HEURE EGALE A DATE | HEURE DE BDD
			$sql = mysql_query("SELECT * FROM `infoimage` WHERE `date` = '".$_POST['anneeSup'].'-'.$_POST['moisSup'].'-'.$_POST['jourSup']."' LIMIT ".$_POST["debut"].",".$_POST["fin"]);
			if (!$sql) {
			 die('Impossible d\'exécuter la requête 150 :' . mysql_error());
  	 	   }
		   
		   $i = 0;
		   $sqlNbImg = mysql_query("SELECT count(*) as toto FROM `infoimage` WHERE `date` = '".$_POST['anneeSup']."-".$_POST['moisSup']."-".$_POST['jourSup']."'"); 
		   $tailleData = mysql_fetch_array($sqlNbImg);
		
		   $maTailleData = $tailleData["toto"];
		   
		   while($aData=mysql_fetch_array($sql))
			{
				//print_r('<br/><br/>' .$aData['date']. ' | |' .$aData['heure'] .' | | '. $aData['lien_img'] . '<br/><br/>');
				supprimeDossier($aData['lien_img']);
				$i++;
			}
		   
			if($i>0)
			{
	
				 echo "<div id='tailleTableImg'>". $maTailleData . "</div>  <br/> <div id='nbImg'> ".$i." </div>" ;
				 exit();
				
			}else{
				
				//REQUETE QUI SUPPRIME TOUS LES CHAMPS OU DATE | HEURE EGALE A DATE | HEURE DE BDD
				$sqlRequeteSup = mysql_query("DELETE FROM `infoimage` WHERE `date` = '".$_POST['anneeSup'].'-'.$_POST['moisSup'].'-'.$_POST['jourSup']."'");
				if (!$sqlRequeteSup) {
					die('Impossible d\'exécuter la requête SURPPRIMER:' . mysql_error());
				}
			
				echo "fin";
				exit();
			}
		   

		}else{

			$heurePlusSup = $_POST['heureSup'] +1;
			
			//REQUETE QUI RECUPERE TOUS LES CHAMPS OU DATE | HEURE EGALE A DATE | HEURE DE BDD
			$sql = mysql_query("SELECT * FROM `infoimage` WHERE `date` = '".$_POST['anneeSup'].'-'.$_POST['moisSup'].'-'.$_POST['jourSup']."' && `heure` > '".$_POST['heureSup'].":00:00' && `heure` < '".$heurePlusSup.":00:00'");
			if (!$sql) {
			 die('Impossible d\'exécuter la requête SUP_HEURE:' . mysql_error());
  	 	   }
		   
		   //REQUETE QUI SUPPRIME TOUS LES CHAMPS OU DATE | HEURE EGALE A DATE | HEURE DE BDD
			$sqlRequeteSup = mysql_query("DELETE FROM `infoimage` WHERE `date` = '".$_POST['anneeSup'].'-'.$_POST['moisSup'].'-'.$_POST['jourSup']."' && `heure` > '".$_POST['heureSup'].":00:00' && `heure` < '".$heurePlusSup.":00:00'");
			if (!$sqlRequeteSup) {
				die('Impossible d\'exécuter la requête SURPPRIMER:' . mysql_error());
			}

		}

}


?>