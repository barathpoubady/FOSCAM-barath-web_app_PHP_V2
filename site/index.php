<?php
// Tout début du code PHP. Situé en haut de la page web
//ini_set("display_errors",0);error_reporting(0);

/************************************************/
/* Inclusion de la classe Connexion. */  //Etablir la connexion a la BDD 
require '../common/config/connexion.php';
/************************************************/

	/* condition au clique */
	if(isset($_POST['Enregistrer']) && $_POST['Enregistrer'] == 'Connexion'){
									  
           //variable  PHP				
					$sLogin = $_POST['login'];
					$sMdp =  $_POST['mdp'];
	
            /*var_dump( $bTestVar ); //equivalent de system.out.println
            die();//stop

            /* condition qui verifie si le login est un email */
            if (!$sLogin){

                         echo "Veuillez taper votre ID <br/>";

                 }else{


                 /* appel de methode */
                 //	requete($sLogin,$sMdp);

                     /* Requete mySQL de login*/			 
					 $aRequete = mysql_query('SELECT COUNT(id) AS nbId FROM admin WHERE login = "'.$sLogin.'"');
					 if (!$aRequete) {
						 die('Impossible d\'exécuter la requête :' . mysql_error());
					 }
				  // RECUPERE LES DONNEES
				  $aDonnee = mysql_fetch_array($aRequete);
                

                 /* Condition qui test si il y a ou pas un login et mdp */
                 if($aDonnee['nbId'] == 0 ){
									 
						 /* post --> redirection */
						 header('location:index.php?Test=errorLogin');
					     exit();
									 
                 }else{

					/* Requete mySQL de login*/			 
					 $aRequete2 = mysql_query('SELECT COUNT(id) AS nbId2 FROM admin WHERE mdp="'.$sMdp.'" AND login="'.$sLogin.'"');
					 if (!$aRequete2) {
						 die('Impossible d\'exécuter la requête :' . mysql_error());
					 }
					// RECUPERE LES DONNEES
				    $aDonnee2 = mysql_fetch_array($aRequete2);
                     
                     if($aDonnee2['nbId2'] == 0){
                        /* post --> redirection*/
                        header('location:index.php?Test2=errorMdp');
                        exit();
											 
											
                     }else{
                         
					/* Requete mySQL de login*/			 
					 $aRequete = mysql_query('SELECT * FROM `admin` WHERE login="'.$sLogin.'" AND mdp="'.$sMdp.'"');
					 if (!$aRequete) {
						 die('Impossible d\'exécuter la requête :' . mysql_error());
					 }
					// RECUPERE LES DONNEES
				   $aDonnee = mysql_fetch_array($aRequete);

					 session_start();

					 $_SESSION['loginAdmin'] = $sLogin;
					 $_SESSION['mdpAdmin'] =  $sMdp;
					 $_SESSION['connexionAdminID'] = $aDonnee['id'];
					
					/* post --> redirection*/
					header('location:index.php?nom='.$aDonnee['nom'].'&prenom='.$aDonnee['prenom'].'&id='.$aDonnee['id'].'&Test=succes');
					exit();
                         
                         
                     }// fin si

                 }//fin sinon
		
            }//fin si
					
	}//fin si

	/* methode test à trois paramettres */
	function test($sTest,$sTest2,$nom,$prenom,$id){
		
		if($sTest == 'succes'){

		 /* post --> redirection*/
		 header('location:test/index.html?&id='.$id.'');
		 exit();
				
			
		}else{
			
			if($sTest == 'errorLogin' && $sTest2 == 'errorMdp'){
			
				echo 'Le mot de passe et l\'id sont incorrectes';
			
			}else{
				
				if($sTest == 'errorLogin'){
			
					echo 'l\'id est incorrecte';
			
				}else{
					
					if($sTest2 == 'errorMdp'){
			
					echo 'Le mot de passe est incorrecte';
			
					}
					
				}
				
				
			}//fin si

		}//fin si
		
	}//fin function
	
	
	
	/* condition qui test */
	if(isset($_GET['Test']) || isset($_GET['Test2'])){
		
		test($_GET['Test'],$_GET['Test2'],$_GET['nom'],$_GET['prenom'],$_GET['id']);
		
	}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Connexion-admin</title>
</head>
<body>
<!-- FORMULAIRE -->
<FORM method="post">
  <!-- les methodes post/get/ -->
  <h1> CONNEXION BACK OFFICE</h1>
  <TABLE BORDER=0>
    <TR>
      <TD>Login</TD>
      <TD><INPUT type="text" name="login"></TD>
    </TR>
    <TR>
      <TD>Mot de passe</TD>
      <TD><INPUT type="password" name="mdp"></TD>
    </TR>
    <TR>
      <TD COLSPAN=2><INPUT type="submit" name="Enregistrer" value="Connexion"></TD>
    </TR>
  </TABLE>
</FORM>
</body>
</html>
