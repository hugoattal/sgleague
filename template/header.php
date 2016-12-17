<!DOCTYPE html>
<html>
	<head>
		<title>Student Gaming League</title>
		<meta content="text/html;charset=utf-8" http-equiv="Content-Type">
		<link rel="stylesheet" media="screen,print" href="./style/style.css" />
		<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.4.0/css/font-awesome.min.css">
		<script src="https://cdnjs.cloudflare.com/ajax/libs/d3/3.5.6/d3.min.js" charset="utf-8"></script>
		<?php echo isset($page_head)?$page_head:''; ?>
		<!-- <script src="./script.js" charset="utf-8"></script> -->
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
						<a href="#">
							<i class="fa fa-angle-right" aria-hidden="true"></i>
							Connexion
						</a>
						<a href="index.php?page=register">
							<i class="fa fa-angle-right" aria-hidden="true"></i>
							Inscription
						</a>
					</div>
				</div>
				<div class="right">
					<div id="menu">
						<a href="#" class="selected">Accueil</a>
						<a href="#">Jeux</a>
						<a href="#">Tournoi</a>
						<a href="#">Stream</a>
						<a href="#">Informations</a>
						<a href="#">Contact</a>
					</div>
				</div>
			</div>
		</div>
		<div class="page">
			<div id="header">
				<div id="logo" class="left">
					<a href="#">
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