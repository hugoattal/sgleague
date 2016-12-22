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
			<
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
		<p>La prochaine fois, faites comme mundo pour ne plus oublier votre mot de passe ! Quoique le dire à voix haute n'est peut être pas une super idée...</p>
		<div class="form">
			<form action="index.php?page=connect" method="post">
				<table class="form_table">
					<tr><td><h3>Pseudo :</h3></td><td><input type="text" name="login" /><br />
					<div class="smallquote">Pour regénérer un mot passe, il me faut votre pseudo.</div></td></tr>
					<tr><td><h3>Mail :</h3></td><td><input type="password" name="pass" /><br />
					<div class="smallquote">Et le mail associé à votre compte (pour recevoir le mot de passe).</div></td></tr>
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


		$temp = $database->req('SELECT id, login, pass, salt, type FROM sgl_users WHERE LOWER(login)=LOWER("'.addslashes($form_login).'")');
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
				<i>&ldquo;</i><?=htmlspecialchars($data["login"])?> use password.<br />It's super effective!<i>&rdquo;</i>
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
		<?=isset($_POST["sent"])?"<div class=\"error\" style=\"text-align:center;\"><i class=\"fa fa-exclamation-triangle\" aria-hidden=\"true\"></i>Hum... Vous êtes sûr que c'est le bon mot de passe ?</div>":""?>
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