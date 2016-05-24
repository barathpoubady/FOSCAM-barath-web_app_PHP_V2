<?php

/************************************************/
/* Inclusion de la classe Connexion. */  //Etablir la connexion a la BDD 
	require '../../common/config/connexion.php';
/************************************************/
	
/* Inclusion de la function supprimeImage_CRON */  //Lecture en BDD et les supprimes si <7
	require 'php/supprime_image_defaut_Cron.php';


	// TRAITEMENT MANUEL EN APPELANT LA PAGE http://strigidae.agence.free.fr/video_cam/site/test/ajaxSupImageCron.html
	if(isset($_POST["supImgCron"]) && $_POST["supImgCron"] == "okSuppImgCron"){
	
		supprimeImage_CRON($_POST['debut'], $_POST['fin']);
		
	}else{
		
		supprimeImage_CRON(0, 300);
		
	}
	
	

	//METHODE SURPRIMER DOSSIER + FICHIER
	function supprimeDossier($dossier) {
	
		 if( file_exists ( $dossier )){
			 @unlink( $dossier );
		 }
	 
	}

?>





