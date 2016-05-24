<?php

// CREATION DU REPERTOIRE AVEC LA DATE
function creationRepertoire($dateRep){

  $newfolder = "images/".$dateRep;
	 
	// CREATION D'UN REPERTOIRE SI LE DOSSIER N'EXISTE PAS
	if (!file_exists($newfolder)) {
		 mkdir ($newfolder, 0777);
	}
		
}


?>