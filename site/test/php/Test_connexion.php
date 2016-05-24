<?php

/* TEST CONNEXION */
if(isset($_SESSION["connexionAdminID"])){
	
	// REQUETE + ERROR CONDITION
	$aRequeteAdmin = mysql_query('SELECT `login`,`mdp` FROM admin WHERE id="'.$_SESSION["connexionAdminID"].'"');
	if (!$aRequeteAdmin) {
		die('Impossible d\'exécuter la requête  :' . mysql_error());
	}
	
	// RECUPERE LES DONNEES
	$aDataAdmin = mysql_fetch_array($aRequeteAdmin); 

	if($aDataAdmin["login"] == $_SESSION["loginAdmin"] && $aDataAdmin["mdp"] == $_SESSION['mdpAdmin']){
		
		
		
	}else{
			
		 /* redirection*/
		 die('Vous devez vous connecter en <a href="../index.php">cliquez-ici</a>');
			
		}

}else{

	/* redirection*/
	 die('Vous devez vous connecter <a href="../index.php">cliquez-ici</a>');

}





?>