<?php

include_once("./class/Database.class.php");

$database = new Database();

// 1 - Overwatch
// 2 - League of Legends
// 3 - Counter Strike
// 4 - Hearthstone

// TODO array of objects OR read from database

$games = array(1, 2, 3, 4);

$games_team = array(6, 5, 5, 1);

$games_name = array(
	"Overwatch",
	"League of Legends",
	"Counter Strike",
	"Hearthstone");

$games_logo = array(
	"ow.png",
	"lol.png",
	"csgo.png",
	"hs.png");

if(isset($_GET["game"]))
{
	$form_game = intval($_GET["game"]);
	if (in_array($form_game, $games))
	{
		$temp = $database->req('SELECT COUNT(*) as exist FROM sgl_teams WHERE user="'.$_SESSION["sgl_id"].'" AND game="'.$form_game.'"');
		$data = $temp->fetch();

		if ($data["exist"] == 0)
		{
			$database->req('INSERT INTO sgl_teams (user, game, lead, type, register) VALUES("'.$_SESSION["sgl_id"].'", "'.$form_game.'", "'.$_SESSION["sgl_id"].'", 1, '.time().')');
		}
	}
}

// TODO automated generation
$games_in = array (1 => false, 2 => false, 3 => false, 4 => false);

$temp = $database->req('SELECT game FROM sgl_teams WHERE lead="'.$_SESSION["sgl_id"].'"');

while($data = $temp->fetch())
{
	$games_in[$data["game"]] = true;
}

?>

<div id="content">
	<div class="container">
		<h1><i class="fa fa-angle-right" aria-hidden="true"></i> Jeux</h1>
		<div class="quote">
			<span class="qcontent">
				<i>&ldquo;</i>Keep calm and blame it on the lag<i>&rdquo;</i>
			</span>
			<span class="qauthor">
				- Un joueur de la SGL 2016
			</span>
		</div>
		<br />
<?php
for ($i=0; $i<count($games); $i++)
{

	echo '<p><table class="line_table"><tr><td><hr class="line" /></td><td><img src="./style/img/games/'.$games_logo[$i].'" alt="'.$games_name[$i].'" /></td><td><hr class="line" /></td></tr></table></p><br />';
	
// TODO check for leader invitation to accept / decline

	if ($games_in[$games[$i]])
	{
		echo '<p>Vous êtes inscrit à ce tournoi !</p>';
		echo '<p style="text-align: center;"><a href="index.php?page=games&amp;game='.$games[$i].'&amp;del=1" class="button">Se désinscire du tournoi</a></p>';
	}
	else
	{
		if ($games["team"] > 1)
		{
			echo '<p>Si vous souhaitez rejoindre une équipe, votre chef d\'équipe doit d\'abord vous inviter.</p>';
		}
		echo '<p style="text-align: center;"><a href="index.php?page=games&amp;game='.$games[$i].'" class="button">S\'inscire au tournoi</a></p>';
	}
	
	echo '<br /><br /><br /><br />';

}
?>
		<br />
	</div>
</div>