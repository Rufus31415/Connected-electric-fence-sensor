<?php
// Affiche les différent écrand permettant à l'utilisateur d'ajouter un appareil par son ID
function show_step1($error=null){
	if($error != null) echo "<p style='color:red'>$error</p>";
?>
<form method="post" action="">
	<label for="new_sigfox_id">
		Identifiant Sigox
		<br/>
		<input type="text" name="new_sigfox_id" id="new_sigfox_id" />
	</label>
	<br>
	<input type="submit" value="Suivant" />
	
</form>
<?php
}

function show_step2($id, $type_name, $example_name){
?>
<form method="post" action="">
	<label for="new_sigfox_name">
		Donner un nom à ce <?php echo $type_name ?>. Par exemple : <i><?php echo $example_name ?></i>
		<br/>
		<input type="text" name="new_sigfox_name" id="new_sigfox_name" />
		<input type="hidden" name="new_sigfox_id" value="<?php echo $id ?>" />
	</label>
	<br>
	<input type="submit" value="Enregistrer" />
	
</form>
<?php
}

function show_add_device_form(){
try{
global $wpdb;
		
$user = wp_get_current_user();	

$isset_id = isset($_POST["new_sigfox_id"]);
$isset_name = isset($_POST["new_sigfox_name"]);

// Etape 2 : un ID a été envoyé
if($isset_id && !$isset_name){
	
	$id = strtoupper($_POST["new_sigfox_id"]);
	
	$next_form = false;
	$error="";
	if(preg_match('/^[A-Z0-9]{6,6}$/', $id)){
		$resultats = $wpdb->get_results("SELECT device_types.type_name, device_types.default_friendly_name FROM devices,device_types WHERE devices.type_id=device_types.ID AND devices.owner NOT IN (SELECT wor6142_users.ID FROM wor6142_users) AND devices.sigfox_id='$id'") ;
		$nb_resultats = count($resultats);
		
		if($nb_resultats==1){
			$next_form=true;
			show_step2($id, $resultats[0]->type_name, $resultats[0]->default_friendly_name);
		}
		elseif($nb_resultats==0){
			$error = "Aucun appareil portant l'identifiant ".$id." n'a été trouvé";
		}
		else{
			$error = $nb_resultats. " appareils trouvés. Impossible de continuer.";
		}
	}
	else{
		$error = "L'identifiant sigfox doit être composé de 6 caractères alphanumériques";
	}
	
	if(!$next_form) {
		show_step1($error);
	}
}
// Etape 3 : un ID et un nom ont été envoyés
elseif($isset_id && $isset_name){
	
}
// Etape 1 : aucune donnée envoyée
else{
	show_step1();
}
?>

<?php	
} catch (Exception $ex) {
echo "<pre>".var_dump($ex)."</pre>";
}
}



?>