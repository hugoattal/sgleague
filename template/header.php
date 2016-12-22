<!DOCTYPE html>
<html>
	<head>
		<title>Student Gaming League</title>
		<meta content="text/html;charset=utf-8" http-equiv="Content-Type">
		<link rel="stylesheet" media="screen,print" href="./style/style.css" />
		<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.4.0/css/font-awesome.min.css">
		<script src="https://cdnjs.cloudflare.com/ajax/libs/d3/3.5.6/d3.min.js" charset="utf-8"></script>
		<?=isset($page_head)?$page_head:'';?>
		<?=isset($page_script)?'<script src="./pages/'.CURRENT_PAGE.'/script.js" charset="utf-8"></script>':''?>
	</head>
	<body>
		<div id="topmenu">
			<div class="page">
				<div class="left">
					<div id="social">
						<span class="media"><a href="https://www.facebook.com/pages/Student-Gaming-Network-SGN/1485021598479124"><i class="fa fa-facebook-square"></i></a></span>
						<span class="media"><a href="https://twitter.com/Student_GN"><i class="fa fa-twitter-square"></i></a></span>
						<span class="media"><a href="https://discord.gg/D7uxyrC"><img src="./style/img/icon/discord.png" alt="discord"></a></span>
						<span class="media"><a href="https://steamcommunity.com/groups/sgnw"><i class="fa fa-steam-square"></i></a></li></span>
					</div>
					<div id="meta">
<?php
if (isset($_SESSION["sgl_id"]))
{
?>
						<a href="index.php?page=account">
							<i class="fa fa-angle-right" aria-hidden="true"></i>
							<?=htmlspecialchars($_SESSION["sgl_login"])?>
						</a>
						<a href="index.php?page=connect&amp;disconnect=1">
							<i class="fa fa-angle-right" aria-hidden="true"></i>
							Déconnexion
						</a>
<?php
}
else
{
?>
						<a href="index.php?page=connect">
							<i class="fa fa-angle-right" aria-hidden="true"></i>
							Connexion
						</a>
						<a href="index.php?page=register">
							<i class="fa fa-angle-right" aria-hidden="true"></i>
							Inscription
						</a>
<?php
}
?>
					</div>
				</div>
				<div class="right">
					<div id="menu">
<?php

if (!isset($page_tab))
{
	$page_tab = "acc";
}

?>
						<a href="index.php" <?=$page_tab=="acc"?'class="selected"':''?>>Accueil</a>
						<a href="index.php?page=games" <?=$page_tab=="games"?'class="selected"':''?>>Jeux</a>
						<a href="#" <?=$page_tab=="x"?'class="selected"':''?>>Tournoi</a>
						<a href="#" <?=$page_tab=="x"?'class="selected"':''?>>Stream</a>
						<a href="#" <?=$page_tab=="x"?'class="selected"':''?>>Informations</a>
						<a href="#" <?=$page_tab=="x"?'class="selected"':''?>>Contact</a>
					</div>
				</div>
			</div>
		</div>
		<div class="page">
			<div id="header">
				<div id="logo" class="left">
					<a href="index.php">
						<img src="./style/img/logo.png" alt="logo" id="logoImg"/>
						<span class="logoTtl">Student Gaming</span>
						<span class="logoStl"><b>League</b> 2017</span>
					</a>
				</div>
				<div id="partners" class="right">
					<i class="fa fa-chevron-left" aria-hidden="true"></i>
					<img src="./style/img/partners/tf1.png" alt="tf1" />
					<img src="./style/img/partners/twitch.png" alt="twitch" />
					<img src="./style/img/partners/fnatic.png" alt="fnatic" />
					<i class="fa fa-chevron-right" aria-hidden="true"></i>
				</div>
			</div>