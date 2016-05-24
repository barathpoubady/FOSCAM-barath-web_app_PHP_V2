<?php


/* FUNCTION QUI RECUPERE LES DONNEES DES IMAGES DU REPERTOIRE VIDEO_CAM */
function lireFichierImage(){
		
	//nom du répertoire contenant les images à afficher
	$nom_repertoire = REP_IMAGE;
	 
	//on ouvre le repertoire
	$pointeur = opendir($nom_repertoire);
	$i = 0;
	 
	//on les stocke les noms des fichiers des images trouvées, dans un tableau
	while (false !== ($fichier = readdir($pointeur)))
	//for($j = 0; $j <=50; $j++)
	{
		
	if (substr($fichier, -3) == "gif" || substr($fichier, -3) == "jpg" || substr($fichier, -3) == "png"
	|| substr($fichier, -4) == "jpeg" || substr($fichier, -3) == "PNG" || substr($fichier, -3) == "GIF"
	|| substr($fichier, -3) == "JPG")
	{
	$tab_image[$i] = $fichier;
	$i++;
	}
	
	if(isset($_POST['premCharge']) && $_POST['premCharge'] != 0){
		
		if($i == 1000){
			
			break;
			
		}
		
	}

	}
	 
	//on ferme le répertoire
	closedir($pointeur);
	 
	
	if(!$tab_image){
		echo('fin_repertoire_vide');//. mysql_error()
		exit();
		
	}else{
		//on trie le tableau par ordre alphabétique
		array_multisort($tab_image, SORT_ASC);
		afficheDate($tab_image,$oBdd);
	}

	

}




?>