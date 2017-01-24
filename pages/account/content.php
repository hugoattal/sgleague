<?php

include_once("./class/Database.class.php");

$database = new Database();

$check_steamid = 0;
$error_steamid = '';

$check_btag = 0;
$error_btag = '';

$check_pass = 0;
$error_pass = '';

if (isset($_POST["sent"]))
{
	$form_steamid =	isset($_POST['steamid']) ?	$_POST['steamid'] : '';
	$form_btag =	isset($_POST['btag']) ?		$_POST['btag'] : '';
	$form_smnr =	isset($_POST['summoner']) ?	$_POST['summoner'] : '';

	if (strlen($form_steamid) != 0)
	{
		if (!preg_match("/^STEAM_[0-5]:[0-1]:[0-9]+$/", $form_steamid))
		{
			$check_steamid = -1;
			$error_steamid = "Attention, ça doit être un truc du style STEAM_0:1:11539914.";
			$form_steamid = '';
		}
	}

	if ($check_steamid < 0)
	{
		$error_steamid = "<div class=\"error\"><i class=\"fa fa-exclamation-triangle\" aria-hidden=\"true\"></i>".$error_steamid."</div>";
	}

	if (strlen($form_btag) != 0)
	{
		if (!preg_match("/#[0-9]{4}$/", $form_btag))
		{
			$check_btag = -1;
			$error_btag = "Attention, vous avez pas oubliez la partie après le '#' par hasard ?";
			$form_btag = '';
		}
	}

	if ($check_btag < 0)
	{
		$error_btag = "<div class=\"error\"><i class=\"fa fa-exclamation-triangle\" aria-hidden=\"true\"></i>".$error_btag."</div>";
	}
	
	$form_mail =	isset($_POST['mail']) ?		$_POST['mail'] : '';
	$form_school =	isset($_POST['school']) ?	$_POST['school'] : '';
	$form_first =	isset($_POST['first']) ?	$_POST['first'] : '';
	$form_name =	isset($_POST['name']) ?		$_POST['name'] : '';
	$form_gender =	isset($_POST['gender']) ? 	$_POST['gender'] : '';
	$form_bday =	isset($_POST['bday']) ?		$_POST['bday'] : '';
	$form_bmonth =	isset($_POST['bmonth']) ?	$_POST['bmonth'] : '';
	$form_byear =	isset($_POST['byear']) ?	$_POST['byear'] : '';

	$form_rankcs =	isset($_POST['rankcs']) ?	$_POST['rankcs'] : '';
	$form_ranklol =	isset($_POST['ranklol']) ?	$_POST['ranklol'] : '';
	$form_rankow =	isset($_POST['rankow']) ?	$_POST['rankow'] : '';

	$form_gender = intval($form_gender);
	$form_gender = in_array($form_gender, array(0,1,2,3))?$form_gender:0;

	$database->req('UPDATE sgl_users SET school="'.addslashes($form_school).'", gender="'.$form_gender.'", first="'.addslashes($form_first).'", name="'.addslashes($form_name).'",
		birth="'.mktime(0, 0, 0, intval($form_bmonth), intval($form_bday), intval($form_byear)).'",
		rankcs = "'.intval($form_rankcs).'", ranklol = "'.intval($form_ranklol).'", rankow = "'.intval($form_rankow).'",
		steamid="'.addslashes($form_steamid).'", battletag="'.addslashes($form_btag).'", summoner="'.addslashes($form_smnr).'" WHERE id='.$_SESSION["sgl_id"]);

	$form_oldpass =	isset($_POST['oldpass']) ?	$_POST['oldpass'] : '';
	$form_newpass =	isset($_POST['newpass']) ?	$_POST['newpass'] : '';

	$temp = $database->req('SELECT id, login, pass, salt, type FROM sgl_users WHERE id="'.$_SESSION["sgl_id"].'"');
	$data = $temp->fetch();

	$hash = sha1($data["salt"].$form_oldpass.CONFIG_SALT);

	if ($hash == $data["pass"])
	{
		if (strlen($form_newpass) != 0)
		{
			if (strlen($form_newpass) >= 8)
			{
				if (!(preg_match('/[A-Za-z]/', $form_newpass) && preg_match('/[0-9]/', $form_newpass)))
				{
					$check_pass = -3;
					$error_pass = "On a dit au moins une lettre et un chiffre ! Si vous m'écoutez pas aussi :( ...";
				}
				else
				{
					include_once("./generic/randomstr.php");
					$salt = random_str(100);
					$hash = sha1($salt.$form_newpass.CONFIG_SALT);

					$database->req('UPDATE sgl_users SET salt="'.$salt.'", pass="'.$hash.'", resetpass="" WHERE id="'.$_SESSION["sgl_id"].'"');
				}
			}
			else
			{
				$check_pass = -2;
				$error_pass = "On a dit au moins 8 caractères ! C'est pour que la NSA puisse pas le décrypter è_é !";
			}
		}
		else
		{
			$check_pass = -1;
			$error_pass = "Non vraiment, c'est plus sécuritaire si vous en mettez un :/";
		}
	}
	else
	{
		$check_pass = -4;
		$error_pass = "Votre mot de passe actuel n'est pas bon !";
	}

	if ($check_pass < 0)
	{
		$error_pass = "<div class=\"error\"><i class=\"fa fa-exclamation-triangle\" aria-hidden=\"true\"></i>".$error_pass."</div>";
	}
}

$temp = $database->req('SELECT login, mail, steamid, battletag, summoner, school, first, name, gender, birth, rankcs, ranklol, rankow FROM sgl_users WHERE id='.$_SESSION["sgl_id"]);
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
					<tr><td><h3>Ancien :</h3></td><td><input type="password" name="oldpass" /><br />
					<div class="smallquote">Juste pour être sûr que c'est bien vous et pas votre copine qui essaie de vous empecher de venir jouer.</div></td></tr>
					<tr><td><h3>Nouveau :</h3></td><td><input type="password" name="newpass" /><br />
					<?=$error_pass?>
					<div class="smallquote">On va dire au moins 8 caractères chiffres + lettres. 100% incraquable par la NSA.</div></td></tr>
				</table>

				<p><table class="line_table"><tr><td><hr class="line" /></td><td>Comptes de jeux et rangs</td><td><hr class="line" /></td></tr></table></p>
				<table class="form_table">
					<tr><td><h3>Steam ID :</h3></td><td><input type="text" name="steamid" value="<?=htmlspecialchars($data["steamid"])?>" /><br />
					<?=$error_steamid?>
					<div class="smallquote">Votre Steam ID pour Counter Strike (ex : STEAM_0:1:11539914). Pour vous aider : <a target="_blank" href="http://steamidfinder.com/">SteamIDFinder.com</a></div></td></tr>
					<tr><td><h3>BattleTag :</h3></td><td><input type="text" name="btag" value="<?=htmlspecialchars($data["battletag"])?>" /><br />
					<?=$error_btag?>
					<div class="smallquote">Votre BattleTag pour Hearthstone et Overwatch. On oublie pas la partie après le "#" !</div></td></tr>
					<tr><td><h3>Invocateur :</h3></td><td><input type="text" name="summoner" value="<?=htmlspecialchars($data["summoner"])?>" /><br />
					<div class="smallquote">Votre nom d'invocateur pour League of Legends.</div></td></tr>
					<tr><td colspan="2"><br /></td></tr>
					<tr><td><h3>Rang CSGO :</h3></td><td>
					<select name="rankcs">
						<option value="0">Non classé</option>
						<option <?=($data["rankcs"] == 1)?'selected="selected"':''?> value="1">Silver 1</option>
						<option <?=($data["rankcs"] == 2)?'selected="selected"':''?> value="2">Silver 2</option>
						<option <?=($data["rankcs"] == 3)?'selected="selected"':''?> value="3">Silver 3</option>
						<option <?=($data["rankcs"] == 4)?'selected="selected"':''?> value="4">Silver 4</option>
						<option <?=($data["rankcs"] == 5)?'selected="selected"':''?> value="5">Silver Elite</option>
						<option <?=($data["rankcs"] == 6)?'selected="selected"':''?> value="6">Silver Elite Master</option>
						<option <?=($data["rankcs"] == 7)?'selected="selected"':''?> value="7">Gold Nova 1</option>
						<option <?=($data["rankcs"] == 8)?'selected="selected"':''?> value="8">Gold Nova 2</option>
						<option <?=($data["rankcs"] == 9)?'selected="selected"':''?> value="9">Gold Nova 3</option>
						<option <?=($data["rankcs"] == 10)?'selected="selected"':''?> value="10">Gold Nova Master</option>
						<option <?=($data["rankcs"] == 11)?'selected="selected"':''?> value="11">Master Guardian 1</option>
						<option <?=($data["rankcs"] == 12)?'selected="selected"':''?> value="12">Master Guardian 2</option>
						<option <?=($data["rankcs"] == 13)?'selected="selected"':''?> value="13">Master Guardian Elite</option>
						<option <?=($data["rankcs"] == 14)?'selected="selected"':''?> value="14">Distinguished Master Guardian</option>
						<option <?=($data["rankcs"] == 15)?'selected="selected"':''?> value="15">Legendary Eagle</option>
						<option <?=($data["rankcs"] == 16)?'selected="selected"':''?> value="16">Legendary Eagle Master</option>
						<option <?=($data["rankcs"] == 17)?'selected="selected"':''?> value="17">Supreme Master First Class</option>
						<option <?=($data["rankcs"] == 18)?'selected="selected"':''?> value="18">The Global Elite</option>
					</select>
					<br />
					<div class="smallquote">Votre rang Counter Strike : Global Offensive</div></td></tr>
					<tr><td><h3>Rang LOL :</h3></td><td>
					<select name="ranklol">
						<option value="0">Non classé</option>
						<option <?=($data["ranklol"] == 1)?'selected="selected"':''?> value="1">Bronze</option>
						<option <?=($data["ranklol"] == 2)?'selected="selected"':''?> value="2">Argent</option>
						<option <?=($data["ranklol"] == 3)?'selected="selected"':''?> value="3">Or</option>
						<option <?=($data["ranklol"] == 4)?'selected="selected"':''?> value="4">Platine</option>
						<option <?=($data["ranklol"] == 5)?'selected="selected"':''?> value="5">Maitre</option>
						<option <?=($data["ranklol"] == 6)?'selected="selected"':''?> value="6">Challenger</option>
					</select>
					<br />
					<div class="smallquote">Votre rang League of Legends</div></td></tr>
					<tr><td><h3>Rang OW :</h3></td><td>
					<select name="rankow">
						<option value="0">Non classé</option>
						<option <?=($data["rankow"] == 1)?'selected="selected"':''?> value="1">1850 et moins</option>
						<option <?=($data["rankow"] == 2)?'selected="selected"':''?> value="2">1851-2200</option>
						<option <?=($data["rankow"] == 3)?'selected="selected"':''?> value="3">2201-2450</option>
						<option <?=($data["rankow"] == 4)?'selected="selected"':''?> value="4">2451-2700</option>
						<option <?=($data["rankow"] == 5)?'selected="selected"':''?> value="5">2701-3000</option>
						<option <?=($data["rankow"] == 6)?'selected="selected"':''?> value="6">3001-3500</option>
						<option <?=($data["rankow"] == 7)?'selected="selected"':''?> value="7">3501 et plus</option>
					</select>
					<br />
					<div class="smallquote">Votre nombre de points Overwatch</div></td></tr>
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