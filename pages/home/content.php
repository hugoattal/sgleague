<div id="head">
	<div id="intro">
		<span class="title">Prêt à <b>rejoindre</b> la première league <b>esport</b> étudiante ?</span><br />
		<div id="subscription">
			<img src="./style/img/arrow_down.png" alt="down" /><br />
			<span class="title"><i class="fa fa-chevron-right" aria-hidden="true"></i>
<?php
if (isset($_SESSION["sgl_id"]))
{
?>
			<a href="index.php?page=games">Choisir un jeu</a>
<?php
}
else
{
?>
			<a href="index.php?page=register">S'inscrire</a>
<?php
}
?>
			<i class="fa fa-chevron-left" aria-hidden="true"></i></span><br />
			<img src="./style/img/arrow_up.png" alt="down" />
		</div>
		<span class="title">Venez <b>défendre</b> les couleurs de votre école jusqu'au <b>podium</b> !<br />
	</div>
	<table id="games">
		<tr>
			<td style="background-image: url('./style/img/ban/ban_ow.png" ');">
				<span class="name"><a href="index.php?page=games&gpage=1"><i class="fa fa-angle-right" aria-hidden="true"></i> Overwatch</a></span>
			</td>
			<td style="background-image: url('./style/img/ban/ban_lol.png"');">
				<span class="name"><a href="index.php?page=games&gpage=2"><i class="fa fa-angle-right" aria-hidden="true"></i> League of Legends</a></span>
			</td>
			<td style="background-image: url('./style/img/ban/ban_csgo.png');">
				<span class="name"><a href="index.php?page=games&gpage=3"><i class="fa fa-angle-right" aria-hidden="true"></i> Counter Strike</a></span>
			</td>
			<td style="background-image: url('./style/img/ban/ban_hs.png" ');">
				<span class="name"><a href="index.php?page=games&gpage=4"><i class="fa fa-angle-right" aria-hidden="true"></i> Hearthstone</a></span>
			</td>
		</tr>
	</table>
</div>
<br />
<div id="content">
	<div class="leftcol">
		<h1><i class="fa fa-angle-right" aria-hidden="true"></i> Derniers matchs</h1>
	</div>
	<div class="rightcol">
		<h1><i class="fa fa-angle-right" aria-hidden="true"></i> Calendrier</h1>
	</div>
	<hr class="break" />
</div>