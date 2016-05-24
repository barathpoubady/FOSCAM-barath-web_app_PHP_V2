<?php
session_start();
/************************************************/
/* Inclusion de la classe Connexion. */  //Etablir la connexion a la BDD 
	require '../../common/config/connexion.php';
/************************************************/

//CONSTANTE CHEMIN DES IMAGES
define("REP_IMAGE", "../../../video_home/");


/* Inclusion de la condition Test Connexion. */  //Test l'utilisateur en qui est en session avec la BDD
	require 'php/Test_connexion.php';
	
/* Inclusion de la function afficheDate */  //Lecture des images et enregistrement des infos de l'image en BDD et dans un repertoire en FTP
	require 'php/afficheDate.php';
	
/* Inclusion de la function lireFichierImage */  //Lecture des images à la racine
	require 'php/lireFichierImage.php';
	
/* Inclusion de la function creationRepertoire */  //Creation d'un repertoire
	require 'php/creationRepertoire.php';
	
/* Inclusion de la function envoieNomFichier */  //Envoie les info de l'image en BDD
	require 'php/envoieNomFichier.php';
	
/* Inclusion de la function requeteDefaut */  //Requete qui recupere les dernière image capturé
	require 'php/requeteDefaut.php';

/* Inclusion de la function requeteSelection */  //Requete qui recupere les images par selection
	require 'php/requeteSelection.php';
	
/* Inclusion de la function supprime_image_defaut */  //Supprime les images qui date de moin de une semaine
	require 'php/supprime_image_defaut.php';
	
/* Inclusion de la function supprime_image_selection */  //Supprime les images par selection
	require 'php/supprime_image_selection.php';
	
/* Inclusion de la function demande_notification */  //AFFICHE LES INFOS de LA BDD EN AJAX
	require 'php/demande_notification.php';
	
/* Inclusion de la function envoieAJAX */  //AFFICHE LES DONNEES EN AJAX
	require 'php/envoieAJAX.php';
	
/* Inclusion de la function lecteur_jpeg */  //AFFICHE LES DONNEES JPEG EN VIDEO EN AJAX
	require 'php/lecteur_jpeg.php';
	
/* inclusion du fichier php */ // permet de rendre compatible les server < PHP5.2
require("php/jsonencode_for_php5.2.php");

/* SWITCH */
switch(isset($_POST))
{
	
/* DECONNEXION */
    case (isset($_POST["sessionAdmin"]) && $_POST["sessionAdmin"] == 1):
		
      	// Finally, destroy the session.
		session_destroy();
		die('LA SESSION EST DECONNECTE :');
    break;
		
/* ECOUTEUR DU CLIQUE BT REQUETE */
    case (isset($_POST['requeteAJAX']) && isset($_POST['premCharge']) && $_POST['requeteAJAX'] == "demandeDATA"):
			
		/* APPEL DE LA METHODE lireFichierImage  */ // -- Lecture des images du repertoire racine puis appel la methode recupDateduFichier() qui cree le repertoire avec la date de l'image et envoie les données en BDD
		lireFichierImage();
		
    break;
	
	/* ECOUTEUR DU CLIQUE BT REQUETE */
    case (isset($_POST['requeteAJAX']) && $_POST['requeteAJAX'] == "demandeAFFICHE_DATA"):
			
		/* APPEL DE LA METHODE envoieAJAX qui affiche  */ // -- elle prend un paramettre $sql qui est une requete retourné par requeteDefaut()
		envoieAJAX(requeteDefaut()); 

    break;
		
		/* ECOUTEUR DU CLIQUE BT VIDEO */
    case (isset($_POST['requeteAJAX']) && $_POST['requeteAJAX'] == "demandeAFFICHE_VIDEO"):
			
		/* APPEL DE LA METHODE mysqlRecupeURL() qui renvoi un JSON avec tout les lien des JPEG */ // 
		 mysqlRecupeURL();

    break;

/* ECOUTEUR DES SELECT POUR FILTRE LES IMAGES PAR date ET heure */
    case (isset($_POST['annee']) && isset($_POST['mois']) && isset($_POST['jour']) && isset($_POST['heure']) && isset($_POST['requeteAJAX']) && $_POST['requeteAJAX'] == "demandeDATA_SELECT"):
			
		/* APPEL DE LA METHODE envoieAJAX qui affiche  */ // -- elle prend un paramettre $sql qui est une requete retourné par requeteSelection()
		envoieAJAX(requeteSelection()); 

    break;
	
/* ECOUTEUR DU CLIQUE BT_SUPPRIME IMAGE */ // -- // GESTION DES SURPRESSIONS DES IMAGES MOIN DE 7 JOURS
    case (isset($_POST['requeteSUPP']) && $_POST['requeteSUPP'] == 1):
		
		//METHODE SUPPRIME IMAGE PAR DEFAUT
		supprimeImage_defaut();

    break;

/* ECOUTEUR DES SELECT POUR SUPPRIMER LES IMAGES PAR date ET heure */
    case (isset($_POST['requeteSUPP']) && $_POST['requeteSUPP'] == 2):
		
		//METHODE SUPPRIME IMAGE PAR SELECTION
		supprimeSelection();
		//print_r('OK_SUP-SELECT');
		/* APPEL DE LA METHODE envoieAJAX qui affiche  */ // -- elle prend un paramettre $sql qui est une requete retourné par requeteDefaut()
		//envoieAJAX(requeteDefaut()); 
		
    break;
		
/* DEMANDE INFO NOTIF APPEL PAR ALWAYS AJAX */ // -- // ENVOIE DES INFOS DANS NOTIFICATION
    case (isset($_POST['notification']) && $_POST['notification'] == "demandeNotif"):
		
		//METHODE REQUETE DE DONNEE DE LA BDD
		demande_infoNotification();

    break;
		
	
}


//METHODE SURPRIMER DOSSIER + FICHIER
function supprimeDossier($dossier) {
	
   if( file_exists ( $dossier )){
     @unlink( $dossier );
   }
   
}

//error_reporting(0); // DESACTIVE LES ERREURS

?>





