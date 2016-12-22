<?php

include_once("./class/Database.class.php");

$database = new Database();

if (isset($_POST["sent"]))
{
	$form_mail =	isset($_POST['mail']) ?		$_POST['mail'] : '';
	$form_school =	isset($_POST['school']) ?	$_POST['school'] : '';
	$form_first =	isset($_POST['first']) ?	$_POST['first'] : '';
	$form_name =	isset($_POST['name']) ?		$_POST['name'] : '';
	$form_gender =	isset($_POST['gender']) ? 	$_POST['gender'] : '';
	$form_bday =	isset($_POST['bday']) ?		$_POST['bday'] : '';
	$form_bmonth =	isset($_POST['bmonth']) ?	$_POST['bmonth'] : '';
	$form_byear =	isset($_POST['byear']) ?	$_POST['byear'] : '';

	$form_gender = intval($form_gender);
	$form_gender = in_array($form_gender, array(0,1,2,3))?$form_gender:0;

	$database->req('UPDATE sgl_users SET school="'.addslashes($form_school).'", gender="'.$form_gender.'", first="'.addslashes($form_first).'", name="'.addslashes($form_name).'",
		birth="'.mktime(0, 0, 0, intval($form_bmonth), intval($form_bday), intval($form_byear)).'" WHERE id='.$_SESSION["sgl_id"]);
}

$temp = $database->req('SELECT login, mail, school, first, name, gender, birth FROM sgl_users WHERE id='.$_SESSION["sgl_id"]);
$data = $temp->fetch();

$birth_day = intval(date('d', $data["birth"]));
$birth_month = intval(date('m', $data["birth"]));
$birth_year = intval(date('Y', $data["birth"]));

?>

<div id="content">
	<div class="container">
		<h1><i class="fa fa-angle-right" aria-hidden="true"></i> Profil</h1>
		<div class="quote">
			<span class="qcontent">
				<i>&ldquo;</i>Glorious PC gaming master race<i>&rdquo;</i>
			</span>
			<span class="qauthor">
				- Un joueur de la SGL 2016
			</span>
		</div>
		<br />
		<div class="form">
			<form action="index.php?page=account" method="post">
				<table class="form_table">
					<tr><td><h3>Pseudo :</h3></td><td><input type="text" name="login" value="<?=htmlspecialchars($data["login"])?>" disabled="disabled" /><br />
					<div class="smallquote">Non, c'est même pas la peine d'essayer de le changer.</div></td></tr>
				</table>
				<p><table class="line_table"><tr><td><hr class="line" /></td><td>Modification du mot de passe</td><td><hr class="line" /></td></tr></table></p>
				<table class="form_table">
					<tr><td><h3>Ancien :</h3></td><td><input type="password" name="pass" /><br />
					<div class="smallquote">Juste pour être sûr que c'est bien vous et pas votre copine qui essaie de vous empecher de venir jouer.</div></td></tr>
					<tr><td><h3>Nouveau :</h3></td><td><input type="password" name="pass" /><br />
					<div class="smallquote">On va dire au moins 8 caractères chiffres + lettres. 100% incraquable par la NSA.</div></td></tr>
				</table>
				<p><table class="line_table"><tr><td><hr class="line" /></td><td>Informations personnelles</td><td><hr class="line" /></td></tr></table></p>
				<table class="form_table">
					<tr><td><h3>Mail :</h3></td><td><input type="mail" name="mail" value="<?=htmlspecialchars($data["mail"])?>"/><br />
					<div class="smallquote">Essayez de mettre votre mail étudiant, comme ça vous n'aurez pas à scanner votre carte étudiante.</div></td></tr>
					<tr><td><h3>Ecole :</h3></td><td><input type="text" name="school" value="<?=htmlspecialchars($data["school"])?>"/><br />
					<div class="smallquote">Pour ceux qui n'écoutent rien : on doit être étudiant pour participer à la SGL !</div></td></tr>
					<tr><td><h3>Pseudo IRL :</h3></td><td>
					<input style="width: 237px" type="text" placeholder="Prénom" name="first" value="<?=htmlspecialchars($data["first"])?>"/>
					<input style="width: 237px" type="text" placeholder="Nom" name="name" value="<?=htmlspecialchars($data["name"])?>"/><br />
					<div class="smallquote">Comme ça ou pourra faire des affichages stylés genre "Prénom (aka Pseudo) Nom"</div></td></tr>
					<tr><td><h3>Genre :</h3></td><td>
					<input type="radio" name="gender" id="radio_m" value="1" <?=($data["gender"] == 1)?'checked="checked"':''?>> <label for="radio_m">Homme</label> |
					<input type="radio" name="gender" id="radio_f" value="2" <?=($data["gender"] == 2)?'checked="checked"':''?>> <label for="radio_f">Femme</label> |
					<input type="radio" name="gender" id="radio_a" value="3" <?=($data["gender"] == 3)?'checked="checked"':''?>> <label for="radio_a">Hélicoptère Apache</label> |
					<input type="radio" name="gender" id="radio_o" value="0" <?=($data["gender"] == 0)?'checked="checked"':''?>> <label for="radio_o">Inconnu / Ne sais pas / Autres</label><br />
					<div class="smallquote">On me dit dans l'oreillette que c'est pour faire des statistiques.</div></td></tr>
					<tr><td><h3>Naissance :</h3></td><td>
					<select name="bday" style="width: 100px">
						<?php for ($i=1; $i<=31; $i++)
						{echo '<option value="'.$i.'" '.($i==$birth_day?'selected="selected"':'').'>'.str_pad($i, 2, '0', STR_PAD_LEFT).'</option>';}?>
					</select>
					<select name="bmonth" style="width: 200px">
						<?php $months = ["Janvier", "Février", "Mars", "Avril", "Mai", "Juin", "Juillet", "Août", "Septembre", "Octobre", "Novembre", "Décembre"];
						for ($i=0; $i<12; $i++)
						{echo '<option value="'.($i+1).'" '.(($i+1)==$birth_month?'selected="selected"':'').'>'.$months[$i].'</option>';}?>
					</select>
					<select name="byear" style="width: 100px">
						<?php for ($i=1970; $i<=2005; $i++)
						{echo '<option value="'.$i.'" '.($i==$birth_year?'selected="selected"':'').'>'.$i.'</option>';}?>
					</select><br />
					<div class="smallquote">C'est pour vous souhaiter le bon anniversaire le moment venu !</div></td></tr>
				</table>
				<br /><br />
				<input type="hidden" name="sent" value="sent">
				<button type="submit" value="Submit">Mettre à jour</button>
			</form>
		</div>
	</div>
</div>