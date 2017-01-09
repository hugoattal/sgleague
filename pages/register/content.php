<?php

$error_login = '';
$error_pass = '';
$error_mail = '';
$error_school = '';
$error_captcha = '';

$register_flag = false;

$form_mail = isset($_GET['mail']) ? $_GET['mail'] : '';

if (isset($_POST["sent"]))
{
	include_once("./generic/recaptcha.php");
	include_once("./generic/randomstr.php");

	include_once("./class/Database.class.php");

	$database = new Database();

	$check_login = 1;
	$check_pass = 1;
	$check_mail = 1;
	$check_school = 1;
	$check_captcha = 1;

	$form_login = isset($_POST['login']) ? $_POST['login'] : '';
	$form_pass = isset($_POST['pass']) ? $_POST['pass'] : '';
	$form_mail = isset($_POST['mail']) ? $_POST['mail'] : '';

	$form_school = isset($_POST['school']) ? $_POST['school'] : '';
	$form_captcha = isset($_POST['g-recaptcha-response']) ? $_POST['g-recaptcha-response'] : '';

//  ----- [ Check login ] --------------------------------------------------

	if (strlen($form_login) != 0)
	{
		if (strlen($form_login) >= 3)
		{
			if (!(preg_match("/[^A-Za-z0-9\!\?\.\-\#_]/", $form_login)))
			{
				$temp = $database->req('SELECT COUNT(*) as exist FROM sgl_users WHERE login="'.addslashes($form_login).'"');
				$data = $temp->fetch();

				if ($data["exist"] != 0)
				{
					$check_login = -4;
					$error_login = "Désolé, quelqu'un est passé avant vous pour ce pseudo...";
				}
			}
			else
			{
				$check_login = -3;
				$error_login = "Pas de caractères chelous ! Les admins vous pas réussir à taper votre pseudo...<br />Vous avez droit aux chiffres, aux lettres (sans accent) et à . ? ! # _ -";
			}
		}
		else
		{
			$check_login = -2;
			$error_login = "Moins de 3 caractères le pseudo 0_o ? C'est une blague ?";
		}
	}
	else
	{
		$check_login = -1;
		$error_login = "Et ouais, il faut rentrer un pseudo... 'faut tout leur apprendre...";
	}

	if ($check_login < 0)
	{
		$error_login = "<div class=\"error\"><i class=\"fa fa-exclamation-triangle\" aria-hidden=\"true\"></i>".$error_login."</div>";
	}

//  ----- [ Check pass ] --------------------------------------------------

	if (strlen($form_pass) != 0)
	{
		if (strlen($form_pass) >= 8)
		{
			if (!(preg_match('/[A-Za-z]/', $form_pass) && preg_match('/[0-9]/', $form_pass)))
			{
				$check_pass = -3;
				$error_pass = "On a dit au moins une lettre et un chiffre ! Si vous m'écoutez pas aussi :( ...";
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

	if ($check_pass < 0)
	{
		$error_pass = "<div class=\"error\"><i class=\"fa fa-exclamation-triangle\" aria-hidden=\"true\"></i>".$error_pass."</div>";
	}

//  ----- [ Check mail ] --------------------------------------------------

	if (strlen($form_mail) != 0)
	{
		if (filter_var($form_mail, FILTER_VALIDATE_EMAIL) == true)
		{
			$temp = $database->req('SELECT COUNT(*) as exist FROM sgl_users WHERE mail="'.addslashes($form_mail).'" AND activation=""');
			$data = $temp->fetch();

			if ($data["exist"] != 0)
			{
				$check_mail = -3;
				$error_mail = "Vous êtes pas déjà inscrit avec ce mail ?<br />Non parce que si vous avez juste oublié votre mot de passe, on peut le récupérer hein ?";
			}
		}
		else
		{
			$check_mail = -2;
			$error_mail = "Comment on va vous envoyer des petits mots doux si votre mail ne marche pas :'( ?";
		}
	}
	else
	{
		$check_mail = -1;
		$error_mail = "Comment on va vous envoyer des petits mots d'amour si on a pas votre mail :'( ?";
	}

	if ($check_mail < 0)
	{
		$error_mail = "<div class=\"error\"><i class=\"fa fa-exclamation-triangle\" aria-hidden=\"true\"></i>".$error_mail."</div>";
	}

//  ----- [ Check school ] --------------------------------------------------

	if (strlen($form_school) == 0)
	{
		$check_school = -1;
		$error_school = "Et si ! On est fier de son école et on l'affiche !";
	}

	if ($check_school < 0)
	{
		$error_school = "<div class=\"error\"><i class=\"fa fa-exclamation-triangle\" aria-hidden=\"true\"></i>".$error_school."</div>";
	}

//  ----- [ Check captcha ] --------------------------------------------------

	if (!validCaptcha($form_captcha))
	{
		$check_captcha = -1;
		$error_captcha = "Roh, il suffit juste de cliquer sur la petite case...";
	}

	if ($check_captcha < 0)
	{
		$error_captcha = "<div class=\"error\" style=\"text-align:center;\"><i class=\"fa fa-exclamation-triangle\" aria-hidden=\"true\"></i>".$error_captcha."</div>";
	}

//  ----- [ Final check ] --------------------------------------------------

	if (($check_login > 0) && ($check_pass > 0) && ($check_mail > 0) && ($check_school > 0) && ($check_captcha > 0))
	{
		$register_flag = true;
	}
}

if ($register_flag)
{
	$salt = random_str(100);
	$activation = random_str(20);

	$hash = sha1($salt.$form_pass.CONFIG_SALT);

	$temp = $database->req('SELECT COUNT(*) as existuser FROM sgl_users WHERE mail="'.addslashes($form_mail).'"');
	$data = $temp->fetch();

	if ($data["existuser"] > 0)
	{
		$temp = $database->req('SELECT id FROM sgl_users WHERE mail="'.addslashes($form_mail).'"');
		$data = $temp->fetch();

		$database->req('UPDATE sgl_users SET login = "'.addslashes($form_login).'", pass = "'.$hash.'", salt = "'.$salt.'", mail = "'.addslashes($form_mail).'",
			activation = "'.$activation.'", school = "'.addslashes($form_school).'", register = '.time().' WHERE id = '.$data["id"]);
	}
	else
	{
		$database->req('INSERT INTO sgl_users (login, pass, salt, mail, activation, school, register)
			VALUES("'.addslashes($form_login).'", "'.$hash.'", "'.$salt.'", "'.addslashes($form_mail).'", "'.$activation.'", "'.addslashes($form_school).'", '.time().')');
	}

	

	$subject = "Confirmation d'inscription à la Student Gaming League";
	$content = "Bienvenue à la Student Gaming League !\n\n
Pour confirmer votre inscription, cliquez sur le lien suivant : <https://".SERVER_ADDR.SERVER_REP."/index.php?page=activation&mvp=".strtolower($form_login)."&key=".$activation.">\n
Vous pourrez ensuite créer ou rejoindre une équipe pour vos jeux préférés.\n\nL'équipe de la Student Gaming League 2017";

	include_once("./class/Mail.class.php");
	new Mail($form_mail, $subject, $content);
?>

<div id="content">
	<div class="container">
		<h1><i class="fa fa-angle-right" aria-hidden="true"></i> Inscription</h1>
		<div class="quote">
			<span class="qcontent">
				<i>&ldquo;</i>Easy peasy, lemon squeezy<i>&rdquo;</i>
			</span>
			<span class="qauthor">
				- Un joueur de la SGL 2016
			</span>
		</div>
		<p>Et voilà, la première étape vers la victoire et la domination mondiale ! Plus qu'à aller cliquer sur le lien d'activation qu'on vient de vous envoyer (à cette adresse si vous avez déjà oublié ce que vous aviez mis : <?=htmlspecialchars($form_mail)?>).<br /><br />PENSEZ A CHECKER VOS SPAMS !</p>
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
		<h1><i class="fa fa-angle-right" aria-hidden="true"></i> Inscription</h1>
		<div class="quote">
			<span class="qcontent">
				<i>&ldquo;</i>Are we rushin' in, or are we goin'<br />sneaky-beaky like?<i>&rdquo;</i>
			</span>
			<span class="qauthor">
				- Un joueur de la SGL 2016
			</span>
		</div>
		<p>La citation du dessus se traduit approximativement par "on y va jusqu'au bout, rapidement ou lentement", et comme vous êtes quelqu'un de bien, vous allez continuer l'inscription JUSQ'-AU-BOUT (et rapidement en plus) !</p>
		<br />
		<div class="form">
			<form action="index.php?page=register" method="post">
				<table class="form_table">
					<tr><td><h3>Pseudo <b>*</b> :</h3></td><td><input type="text" name="login" <?=isset($form_login)?'value="'.$form_login.'"':''?>/><br />
					<?=$error_login?>
					<div class="smallquote">C'est votre pseudo qui vous permettra de vous identifier, alors ne vous trompez pas !</div></td></tr>
					<tr><td><h3>Password <b>*</b> :</h3></td><td><input type="password" name="pass" /><br />
					<?=$error_pass?>
					<div class="smallquote">On va dire au moins 8 caractères chiffres + lettres. 100% incraquable par la NSA.</div></td></tr>
					<tr><td><h3>Mail <b>*</b> :</h3></td><td><input type="mail" name="mail" <?=isset($form_mail)?'value="'.$form_mail.'"':''?>/><br />
					<?=$error_mail?>
					<div class="smallquote">Essayez de mettre votre mail étudiant, comme ça vous n'aurez pas à scanner votre carte étudiante.</div></td></tr>
					<tr><td><h3>Ecole <b>*</b> :</h3></td><td><input type="text" name="school" <?=isset($form_school)?'value="'.$form_school.'"':''?>/><br />
					<?=$error_school?>
					<div class="smallquote">Pour ceux qui n'écoutent rien : on doit être étudiant pour participer à la SGL !</div></td></tr>
				</table>
				<br /><br />
				<p><b>*</b> : Oui oui, tout est obligatoire ! Vous choisirez vos jeux et vos équipes plus tard.</p>
				<br /><br />
				<div class="g-recaptcha" data-sitekey="<?=RECAPTCHA_PUBLIC;?>" data-theme="dark"></div>
				<?=$error_captcha?>
				<br /><br />
				<div class="smallquote">Je ne suis pas un robot ! Mes faux pas me collent à la peau... Je ne suis pas un robot ! Faut pas croire ce que disent les journaux...<br />Je ne suis pas un robot, un roboooooot ! (Bon ok, je sors =>[])</div>
				<br /><br /><br />
				<input type="hidden" name="sent" value="sent">
				<button type="submit" value="Submit">S'inscrire</button>
			</form>
		</div>
	</div>
</div>
<?php
}
?>