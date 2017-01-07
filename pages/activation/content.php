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
		<?=($check_data<0)?'<div class="error" style="text-align:center;"><i class="fa fa-exclamation-triangle" aria-hidden="true"></i>'.$error_data.'</div>':'<p style="text-align:center;">C\'est bon, vous êtes activés :D ! Vous pouvez vous connecter !</p>'?>
		<br />
		<p style="text-align: center;"><a href="index.php" class="button">Revenir à la page d'accueil</a></p>
		<br />
	</div>
</div>