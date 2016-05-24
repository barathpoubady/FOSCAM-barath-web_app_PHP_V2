<?php

/* FUNCTION QUI RECUPERE LA DATE DE CHAQUE IMAGE ET APPEL 2 METHODE*/
function afficheDate($tab_image,$oBdd){
	
 $tailleTable =	count($tab_image);
 
	for ($j=0;$j<=$tailleTable-1;$j++)
	{
	
	   /* RECUPERE LA DATE DU FICHIER */
	   $dateIMG = date("Y-n-j",fileatime(REP_IMAGE.$tab_image[$j]));
	
		creationRepertoire($dateIMG);
		envoieNomFichier($tab_image,$j,$dateIMG,$oBdd);
		
		if($j == 999){
		  
		  //set_time_limit(150);
		  echo "<div id='tailleTableImg'>". $tailleTable ."</div>  <br/> <div id='nbImg'> ".$j." </div>" ;
		  exit();
		  
	   }
	   
	   if($tailleTable == 0){
		    
		  echo "fin_repertoire_vide2" ;
		  exit();
		   
	   }
		
	}
}


?>