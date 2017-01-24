<?php

$check_data = 1;
$error_data = '';

if ((isset($_GET["key"])) AND (isset($_GET["mvp"])))
{
	if ((!(preg_match("/[^A-Za-z0-9\!\?\.\-\#_]/", $_GET["key"]))) && (strlen($_GET["key"]) == 20))
	{
		include_once("./class/Database.class.php");

		$database = new Database();

		$temp = $database->req('SELECT id, activation, mail FROM sgl_users WHERE login="'.addslashes($_GET["mvp"]).'"');
		$data = $temp->fetch();

		if ($data["activation"] == $_GET["key"])
		{
			$database->req('UPDATE sgl_users SET activation="", activmail="'.addslashes($data["mail"]).'" WHERE id="'.$data["id"].'"');
		}
		else
		{
			if (strlen($data["activation"]) == 20)
			{
				$check_data = -4;
				$error_data = "Ce n'est pas la bonne clé d'activation !";
			}
			else
			{
				$check_data = -3;
				$error_data = "Hum... Vous avez pas déjà activé votre compte ?";
			}
			
		}
	}
	else
	{
		$check_data = -2;
		$error_data = "La clé d'activation n'est pas du bon format... Qu'est ce que vous avez trafiqué ?";
	}
}
else if ((isset($_GET["key"])) AND  (isset($_GET["hitc"])))
{
	if ((!(preg_match("/[^A-Za-z0-9\!\?\.\-\#_]/", $_GET["key"]))) && (strlen($_GET["key"]) == 20))
	{
		include_once("./class/Database.class.php");

		$database = new Database();

		$temp = $database->req('SELECT id, mail, resetpass FROM sgl_users WHERE login="'.addslashes($_GET["hitc"]).'"');
		$data = $temp->fetch();

		if ($data["resetpass"] == $_GET["key"])
		{
			include_once("./generic/randomstr.php");
			$rand_pass = random_str(10);

			$salt = random_str(100);
			$hash = sha1($salt.$rand_pass.CONFIG_SALT);

			$database->req('UPDATE sgl_users SET salt="'.$salt.'", pass="'.$hash.'", resetpass="" WHERE id="'.$data["id"].'"');

			$subject = "Votre nouveau mot de passe !";
			$content = "Et le voici, tout beau, tout neuf : ".$rand_pass."\n
Ne le perdez pas celui là ! Vous pouvez vous connecter ici : <https://".SERVER_ADDR.SERVER_REP."/index.php?page=connect>\n\nL'équipe de la Student Gaming League 2017";

			include_once("./class/Mail.class.php");
			new Mail($data["mail"], $subject, $content);
		}
		else
		{
			if (strlen($data["resetpass"]) == 20)
			{
				$check_data = -4;
				$error_data = "Ce n'est pas la bonne clé !";
			}
			else
			{
				$check_data = -3;
				$error_data = "Hum... Vous avez vraiment perdu votre mot de passe ?";
			}
			
		}
	}
	else
	{
		$check_data = -2;
		$error_data = "La clé n'est pas du bon format... Qu'est ce que vous avez trafiqué ?";
	}
}
else
{
	$check_data = -1;
	$error_data = "Comment vous avez atteri là en fait ? Il manque des choses dans l'URL...";
}

?>

<div id="content">
	<div class="container">
		<h1><i class="fa fa-angle-right" aria-hidden="true"></i> Activation</h1>
		<div class="quote">
			<span class="qcontent">
				<i>&ldquo;</i>Il a activé ! Reported hax.<br/>Good VACation...<i>&rdquo;</i>
			</span>
			<span class="qauthor">
				- Un joueur de la SGL 2016
			</span>
		</div>
		<?=($check_data<0)?'<div class="error" style="text-align:center;"><i class="fa fa-exclamation-triangle" aria-hidden="true"></i>'.$error_data.'</div>':
		'<p style="text-align:center;">'.isset($_GET["mvp"])?'C\'est bon, vous êtes activés :D ! Vous pouvez vous connecter !':'Votre nouveau mot de passe a été envoyé par mail ;)'.'</p>'?>
		<br />
		<p style="text-align: center;"><a href="index.php" class="button">Revenir à la page d'accueil</a></p>
		<br />
	</div>
</div>