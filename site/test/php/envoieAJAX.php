<?php

// FUNCTION QUI ENVOIE LES BALISE ET LES IMAGES EN AJAX
function envoieAJAX($sql){
	

	// TEST SI UNE RECHERHCE PAR DATE | HEURE SINON EXECUTE REQUETE PAR DEFAUT (les dernieres images)
 /*   if(isset($_POST['annee']) && isset($_POST['mois']) && isset($_POST['jour']) && isset($_POST['heure'])){ 
	
		$heurePlus = $_POST['heure'] +1;
	
		$sql = mysql_query("SELECT `id`,`lien_img` ,`date` ,`heure` FROM `infoimage` WHERE `date` = '".$_POST['annee'].'-'.$_POST['mois'].'-'.$_POST['jour']."'  && `heure` > '".$_POST['heure'].":00:00' && `heure` < '".$heurePlus.":00:00' ORDER BY `heure` LIMIT ".$_POST['precedent']." , ".$_POST['suivant'].""); 
	
	
	}else{
		
		//REQUETE PAR DEFAUT
		$sql = mysql_query("SELECT * FROM `infoimage` ORDER BY `date` DESC , `heure` DESC LIMIT ".$_POST['precedent']." , ".$_POST['suivant']."");
		
	}
	*/
	
	
	//RECUPERE LES DONNEES
	if (!$sql) {
		 die('Impossible d\'exécuter la requête :' . mysql_error());
    }
	
	echo ' <thead>
					<tr>
							<th>Rendering engine</th>
							<th>Browser</th>
							<th>Platform(s)</th>
							<th>Engine version</th>
							<th>CSS grade</th>
					</tr>
			</thead>
			 <tbody>
		 ';
	
	//AFFICHE LES DONNEES
	while($aData=mysql_fetch_array($sql))
	{
		$lienIMG=$aData['lien_img'];
		$date=$aData['date'];
		$heure=$aData['heure'];
		
		echo '
		<tr class="odd gradeX">
				<td> 
				<a href="javascript:PopupWindow(this,'."'".'/video_cam/site/test/'.$lienIMG.' '."'".');"><img src="'.$lienIMG.'" width="100px" height="100px"/></a>
				</td>
				<td>'.$date.'</td>
				<td>'.$heure.'</td>
				<td class="center"><a href="envoi_image.php?fileID='.$aData['id'].'">Cliquer ici pour télécharger l\'image </a> </td>
				<td class="center">'.$aData['id'].'</td>
		</tr>
		 ';
	}//http://poubady.barath.free.fr/video_cam/site/test/'.$lienIMG.'
	
	echo '</tbody>';
		
}



?>