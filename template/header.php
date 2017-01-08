<!DOCTYPE html>
<html>
	<head>
		<title>Student Gaming League</title>
		<meta content="text/html;charset=utf-8" http-equiv="Content-Type">
		<link rel="icon" type="image/png" href="./style/img/favicon.png" />
		<link rel="stylesheet" media="screen,print" href="./style/style.css" />
		<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
		<script src="https://cdnjs.cloudflare.com/ajax/libs/d3/3.5.6/d3.min.js" charset="utf-8"></script>
		<?=isset($page_head)?$page_head:'';?>
		<?=isset($page_script)?'<script src="./pages/'.CURRENT_PAGE.'/script.js" charset="utf-8"></script>':''?>
	</head>
	<body>
		<div id="topmenu">
			<div class="page">
				<div class="left">
					<div id="social">
						<span class="media"><a target="_blank" href="https://www.facebook.com/pages/Student-Gaming-Network-SGN/1485021598479124"><i class="fa fa-facebook-square"></i></a></span>
						<span class="media"><a target="_blank" href="https://twitter.com/Student_GN"><i class="fa fa-twitter-square"></i></a></span>
						<span class="media"><a target="_blank" href="https://discord.gg/D7uxyrC"><img src="./style/img/icon/discord.png" alt="discord"></a></span>
						<span class="media"><a target="_blank" href="https://steamcommunity.com/groups/sgnw"><i class="fa fa-steam-square"></i></a></li></span>
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

$gpage = 0;

if (!isset($page_tab))
{
	$page_tab = "acc";
}
else
{
	if(($page_tab == "games") && isset($_GET["gpage"]))
	{
		$gpage = intval($_GET["gpage"]);
	}
}

?>
						<a href="index.php" <?=$page_tab=="acc"?'class="selected"':''?>>Accueil</a>
						<a href="index.php?page=games&gpage=1" <?=$gpage==1?'class="selected"':''?>>O<i>ver</i>W<i>atch</i></a>
						<a href="index.php?page=games&gpage=2" <?=$gpage==2?'class="selected"':''?>>L<i>eague </i>O<i>f </i>L<i>egends</i></a>
						<a href="index.php?page=games&gpage=3" <?=$gpage==3?'class="selected"':''?>>C<i>ounter </i>S<i>trike</i></a>
						<a href="index.php?page=games&gpage=4" <?=$gpage==4?'class="selected"':''?>>H<i>earth</i>S<i>tone</i></a>
						<a href="#" <?=$page_tab=="x"?'class="selected"':''?>>FAQ</a>
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
					<a target="_blank" href="http://www.groupe-tf1.fr/"><img src="./style/img/partners/tf1.png" alt="tf1" /></a>
					<a target="_blank" href="https://www.twitch.tv/"><img src="./style/img/partners/twitch.png" alt="twitch" /></a>
					<a target="_blank" href="http://www.fnatic.com/"><img src="./style/img/partners/fnatic.png" alt="fnatic" /></a>
					<i class="fa fa-chevron-right" aria-hidden="true"></i>
				</div>
			</div>