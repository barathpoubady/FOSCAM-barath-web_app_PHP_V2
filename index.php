<?php
session_start();
if(isset($_SESSION["connexionAdmin"]) && $_SESSION["connexionAdmin"] == $_GET["id"]){
	
	echo("Bonjour " . $_GET["id"] . "<br/>"  );
	include ('site/test/site-galerie.php');
}else{
	
	/* post --> redirection*/
	header('location:site/index.php?id=3');
	exit();

	
}



?>