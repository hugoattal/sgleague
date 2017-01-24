<?php

global $csrf_check;

if ((isset($_GET["disconnect"])) AND $csrf_check)
{
	session_destroy();
?>
<div id="content">
	<div class="container">
		<h1><i class="fa fa-angle-right" aria-hidden="true"></i> Déconnexion</h1>
		<div class="quote">
			<span class="qcontent">
				<i>&ldquo;</i>A summoner has left the game<i>&rdquo;</i>
			</span>
			<span class="qauthor">
				- Un joueur de la SGL 2016
			</span>
		</div>
		<br />
		<p style="text-align: center;"><a href="index.php" class="button">Revenir à la page d'accueil</a></p>
		<br />
	</div>
</div>
<?php
}
else if (isset($_GET["recover"]))
{

// TODO : mail + regénération de mot de passe

	if (isset($_POST["sent"]))
	{
		$form_login = isset($_POST['login']) ? $_POST['login'] : '';

		include_once("./class/Database.class.php");
		$database = new Database();

		include_once("./generic/randomstr.php");
		$resetsalt = random_str(20);

		$database->req('UPDATE sgl_users SET resetpass="'.$resetsalt.'" WHERE LOWER(login)=LOWER("'.addslashes($form_login).'") AND activation=""');

		$temp = $database->req('SELECT mail FROM sgl_users WHERE LOWER(login)=LOWER("'.addslashes($form_login).'") AND activation=""');
		$data = $temp->fetch();

		if (isset($data["mail"]))
		{
			$subject = "Regénération de votre mot de passe";
			$content = "Alors comme ça on a oublié son mot de passe ?\n\n
Pas de soucis, il suffit de cliquer sur ce lien pour en recevoir un nouveau : <https://".SERVER_ADDR.SERVER_REP."/index.php?page=activation&hitc=".strtolower($form_login)."&key=".$resetsalt.">\n
Si vous avez des problèmes de connexion, n'hésitez pas à passer sur discord ! <https://discord.gg/SGL17>\n\nL'équipe de la Student Gaming League 2017";

			include_once("./class/Mail.class.php");
			new Mail($data["mail"], $subject, $content);
		}

		$flag_recover = true;
	}

	?>
<div id="content">
	<div class="container">
		<h1><i class="fa fa-angle-right" aria-hidden="true"></i> Oubli de mot de passe</h1>
		<div class="quote">
			<span class="qcontent">
				<i>&ldquo;</i>Mundo say his own name a lot,<br />or else he forget! Has happened before.<i>&rdquo;</i>
			</span>
			<span class="qauthor">
				- Un joueur de la SGL 2016
			</span>
		</div>
		<?php if(isset($flag_recover)){?>
		<br /><p style="text-align:center; font-weight: bold">C'est bon, <b>tout est réglé</b> ! On viens de vous envoyer un <b>mail</b> pour <b>regénérer un mot de passe</b>.</p><br />
		<?php }else{ ?>
		<p style="text-align: center;">La prochaine fois, faites comme mundo pour ne plus oublier votre mot de passe ! Quoique le dire à voix haute n'est peut être pas une super idée...</p>
		<?php } ?>
		<div class="form">
			<form action="index.php?page=connect&recover=1" method="post">
				<table class="form_table">
					<tr><td><h3>Login :</h3></td><td><input name="login" type="mail" /><br />
					<div class="smallquote">Pour regénérer un mot passe, il me faut votre login.</div></td></tr>
				</table>
				<br /><br />
				<input type="hidden" name="sent" value="sent">
				<button type="submit" value="Submit">Promis, je n'oublierai pas celui là</button>
			</form>
		</div>
		<br />
	</div>
</div>
	<?php

}
else
{
	$connect_flag = false;

	if (isset($_POST["sent"]))
	{
		include_once("./class/Database.class.php");

		$database = new Database();

		$form_login = isset($_POST['login']) ? $_POST['login'] : '';
		$form_pass = isset($_POST['pass']) ? $_POST['pass'] : '';


		$temp = $database->req('SELECT id, login, pass, salt, type FROM sgl_users WHERE LOWER(login)=LOWER("'.addslashes($form_login).'") AND activation=""');
		$data = $temp->fetch();

		$hash = sha1($data["salt"].$form_pass.CONFIG_SALT);

		if ($hash == $data["pass"])
		{
			$connect_flag = true;

			$_SESSION["sgl_id"] = $data["id"];
			$_SESSION["sgl_login"] = $data["login"];
			$_SESSION["sgl_type"] = $data["type"];
		}
	}

	if ($connect_flag)
	{
?>

<div id="content">
	<div class="container">
		<h1><i class="fa fa-angle-right" aria-hidden="true"></i> Connexion réussie</h1>
		<div class="quote">
			<span class="qcontent">
				<i>&ldquo;</i><?=htmlspecialchars($data["login"])?> used password.<br />It's super effective!<i>&rdquo;</i>
			</span>
			<span class="qauthor">
				- Un joueur de la SGL 2016
			</span>
		</div>
		<br />
		<p style="text-align: center;"><a href="index.php" class="button">Revenir à la page d'accueil</a></p>
		<br />
	</div>
</div>

<?php
	}
	else
	{

?>

<div id="content">
	<div class="container">
		<h1><i class="fa fa-angle-right" aria-hidden="true"></i> Connexion</h1>
		<div class="quote">
			<span class="qcontent">
				<i>&ldquo;</i>Welcome back, commander.<i>&rdquo;</i>
			</span>
			<span class="qauthor">
				- Un joueur de la SGL 2016
			</span>
		</div>
		<?=isset($_POST["sent"])?"<div class=\"error\" style=\"text-align:center;\"><i class=\"fa fa-exclamation-triangle\" aria-hidden=\"true\"></i>Hum... Vous êtes sûr que c'est le bon mot de passe ? Et que votre compte est activé ?</div>":""?>
		<div class="form">
			<form action="index.php?page=connect" method="post">
				<table class="form_table">
					<tr><td><h3>Pseudo :</h3></td><td><input type="text" name="login" /><br />
					<div class="smallquote">C'est pas le mail, c'est le login, hein ! 'fin votre pseudo quoi...</div></td></tr>
					<tr><td><h3>Password :</h3></td><td><input type="password" name="pass" /><br />
					<div class="smallquote">J'espère que vous l'avez pas oublié celui-là. Si ? <a href="index.php?page=connect&amp;recover=1">Ne vous inquiétez pas, on va le retrouver.</a></div></td></tr>
				</table>
				<br /><br />
				<input type="hidden" name="sent" value="sent">
				<button type="submit" value="Submit">Se connecter</button>
			</form>
		</div>
		<br />
	</div>
</div>

<?php

	}
}

?>