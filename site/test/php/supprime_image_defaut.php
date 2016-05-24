<?php

/* FUNCTION QUI RECUPERE LA DATE DE CHAQUE IMAGE ET APPEL 2 METHODE */
function supprimeImage_defaut(){
	
	$anneeSemaine = date("Y"); 
	$moisSemaine = date("n");
	$jourSemaine = date("j"); 
	
	
	if($jourSemaine>7 && isset($_POST['debut']) && isset($_POST['nbLigneBDD'])){
		
		$jourSemaine = $jourSemaine -7; 
		
		//REQUETE QUI RECUPERE TOUS LES CHAMPS OU DATE INFERIEUR A DATE DE AUJOURD'HUI - 7
		$sql = mysql_query("SELECT * FROM `infoimage` WHERE `date` <= '".$anneeSemaine."-".$moisSemaine."-".$jourSemaine."' LIMIT ".$_POST["debut"].",".$_POST["nbLigneBDD"]);  
		if (!$sql) {
			 die('Impossible d\'exécuter la requête selectionne les images à supprime:' . mysql_error());
		}
		
		$i = 0;
		$sqlNbImg = mysql_query("SELECT count(*) as toto FROM `infoimage` WHERE `date` <= '".$anneeSemaine."-".$moisSemaine."-".$jourSemaine."'"); 
		$tailleData = mysql_fetch_array($sqlNbImg);
		
		$maTailleData = $tailleData["toto"];
		
		while($aData=mysql_fetch_array($sql))
		{
			
			supprimeDossier($aData['lien_img']);
			$i++;
		}
	
		if($i>0)
		{
			/*$_GET["debut"] = $_GET["debut"] + 1000;
			$_GET["fin"] = $_GET["fin"] + 1000;
			//echo "site-galerie.php?requeteSUPP=1&debut=".$_GET["debut"]."&fin=".$_GET["fin"];
			echo '<html>
			<head>
			<META HTTP-EQUIV="Refresh" CONTENT="1; URL=http://poubady.barath.free.fr/video_cam/site/test/site-galerie.php?requeteSUPP=1&debut='.$_GET["debut"].'&fin='.$_GET["fin"].'">
			</head>
			<body> 
			'.$_GET["debut"].'/'.$maTailleData.' 
			</body>
			</html>';*/
			
			 echo "<div id='tailleTableImg'>". $maTailleData . "</div>  <br/> <div id='nbImg'> ".$i." </div>" ;
		   exit();
			
		}else{
			
			//REQUETE QUI SUPPRIME TOUS LES CHAMPS DE LA BDD OU DATE INFERIEUR A DATE DE AUJOURD'HUI - 7
			$sqlRequeteSup = mysql_query("DELETE FROM `infoimage` WHERE `date` <= '".$anneeSemaine."-".$moisSemaine."-".$jourSemaine."' ");
			if (!$sqlRequeteSup) {
				die('Impossible d\'exécuter la requête Delete:' . mysql_error());
			}
			
			echo "fin";
			exit();
		}
		
	}
}


?>