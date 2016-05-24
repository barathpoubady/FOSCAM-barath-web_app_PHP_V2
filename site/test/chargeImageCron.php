<?php

/************************************************/
/* Inclusion de la classe Connexion. */  //Etablir la connexion a la BDD 
	require '../../common/config/connexion.php';
/************************************************/
	
//CONSTANTE CHEMIN DES IMAGES
define("REP_IMAGE", "../../../video_home/");	

/* Inclusion de la function afficheDate */  //Lecture des images et enregistrement des infos de l'image en BDD et dans un repertoire en FTP
	require 'php/afficheDate.php';
	
/* Inclusion de la function lireFichierImage */  //Lecture des images à la racine
	require 'php/lireFichierImage.php';
	
/* Inclusion de la function creationRepertoire */  //Creation d'un repertoire
	require 'php/creationRepertoire.php';
	
/* Inclusion de la function envoieNomFichier */  //Envoie les info de l'image en BDD
	require 'php/envoieNomFichier.php';

	//APPEL DE LA METHODE QUI LI les images
  lireFichierImage();


?>





