<?php

global $csrf_check;

include_once("./class/Database.class.php");

$database = new Database();

// 1 - Overwatch
// 2 - League of Legends
// 3 - Counter Strike
// 4 - Hearthstone

// TODO array of objects OR read from database

$games = array(1, 2, 3, 4);

$games_team = array(6, 5, 5, 1);
$games_reps = array(2, 2, 2, 0);

$games_name = array(
	"Overwatch",
	"League of Legends",
	"Counter Strike",
	"Hearthstone");

$games_short = array(
	"ow",
	"lol",
	"csgo",
	"hs");
if (isset($_GET["game"]) AND $csrf_check)
{
	$form_game = intval($_GET["game"]);
	if (in_array($form_game, $games))
	{
		if (isset($_GET["del"]))
		{
			$database->req('DELETE FROM sgl_teams WHERE user="'.$_SESSION["sgl_id"].'" AND game="'.$form_game.'"');
		}
		else if(isset($_GET["accept"]))
		{
			$database->req('UPDATE sgl_teams SET register = "'.time().'" WHERE user = "'.$_SESSION["sgl_id"].'" AND game = "'.$form_game.'" AND lead = "'.intval($_GET["accept"]).'"');
			$database->req('DELETE FROM sgl_teams WHERE user = "'.$_SESSION["sgl_id"].'" AND game="'.$form_game.'" AND register = "0"');
		}
		else if(isset($_GET["remove"]))
		{
			$database->req('DELETE FROM sgl_teams WHERE user = "'.intval($_GET["remove"]).'" AND game="'.$form_game.'" AND lead = "'.$_SESSION["sgl_id"].'"');
		}
		else
		{
			$temp = $database->req('SELECT COUNT(*) as exist FROM sgl_teams WHERE user="'.$_SESSION["sgl_id"].'" AND game="'.$form_game.'"');
			$data = $temp->fetch();

			if ($data["exist"] == 0)
			{
				$database->req('INSERT INTO sgl_teams (user, game, lead, type, register) VALUES("'.$_SESSION["sgl_id"].'", "'.$form_game.'", "'.$_SESSION["sgl_id"].'", 1, '.time().')');
			}
		}
	}
}

// TODO automated generation
$games_in = array (1 => false, 2 => false, 3 => false, 4 => false);

$temp = $database->req('SELECT game FROM sgl_teams WHERE user="'.$_SESSION["sgl_id"].'" AND register > 0');

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

	echo '<p id="'.$games_short[$i].'"><table class="line_table"><tr><td><hr class="line" /></td><td><img src="./style/img/games/'.$games_short[$i].'.png" alt="'.$games_name[$i].'" /></td><td><hr class="line" /></td></tr></table></p><br />';
	
// TODO check for leader invitation to accept / decline

	if ($games_in[$games[$i]])
	{
		echo '<p style="text-align:center;">Vous êtes inscrit à ce tournoi !</p>
		<p style="text-align: center;" class="smallquote">Plus qu\'à hard train jusqu\'à début Février... [ <a href="index.php?page=games&amp;game='.$games[$i].'&amp;del=1">Se désinscrire du tournoi</a> ]</p><br />';

		$temp = $database->req('SELECT sgl_users.login, sgl_teams.type, sgl_teams.register, sgl_teams.user
			FROM sgl_users, sgl_teams LEFT JOIN sgl_teams AS my_team ON sgl_teams.lead = my_team.lead AND sgl_teams.game = my_team.game
			WHERE my_team.user="'.$_SESSION["sgl_id"].'" AND my_team.game="'.$games[$i].'" AND sgl_teams.user = sgl_users.id ORDER BY type ASC');

		$type = array("Aucun", "Leader", "Joueur", "Remplaçant");

		$nplayer = 0;
		$nreps = 0;

// TODO check stand-by players

		echo '<p style="text-align: center">';

		$lasttype = 1;
		$lead = false;

		while($data = $temp->fetch())
		{
			if ($data["type"] == 1)
			{
				if ($_SESSION["sgl_id"] == $data["user"])
				{
					$lead = true;
				}
			}

			if ($data["type"] != $lasttype)
			{
				if ($lasttype == 2)
				{
					for ($j=0; $j<($games_team[$i]-$nplayer); $j++)
					{
						if ($lead)
						{
							echo '<span class="buttoncard" onclick="morphIntoTextField(this, '.$games[$i].')()" data-type="2" data-game="'.$games[$i].'">Ajouter un joueur</span><br />';
						}
						else
						{
							echo '<span class="playercard"></span><br />';
						}
					}
					echo "<br /><br/>";
				}
			}

			if ($data["type"] < 3)
			{
				$nplayer++;
			}
			else if ($data["type"] == 3)
			{
				$nreps++;
			}

			if ($lead && ($_SESSION["sgl_id"] != $data["user"]))
			{
				$dlstr = '<span class="cardoption"><a href="index.php?page=games&amp;game='.$games[$i].'&remove='.$data["user"].'"><i class="fa fa-times" aria-hidden="true"></i></a></span>';
			}
			else
			{
				$dlstr = '';
			}

			if ($data["register"] == 0)
			{
				echo '<span class="playercard" style="opacity:0.5;"><span class="playername">'.htmlspecialchars($data["login"]).'</span><span class="playertype">('.$type[$data["type"]].')</span>'.$dlstr.'</span><br />';
			}
			else
			{
				echo '<span class="playercard"><span class="playername">'.htmlspecialchars($data["login"]).'</span><span class="playertype">('.$type[$data["type"]].')</span>'.$dlstr.'</span><br />';
			}

			$lasttype = $data["type"];
		}

		if ($lasttype <= 2)
		{
			for ($j=0; $j<($games_team[$i]-$nplayer); $j++)
			{
				if ($lead)
				{
					echo '<span class="buttoncard" onclick="morphIntoTextField(this, '.$games[$i].')()" data-type="2" data-game="'.$games[$i].'">Ajouter un joueur</span><br />';
				}
				else
				{
					echo '<span class="playercard"></span><br />';
				}
			}
			echo "<br /><br />";
			$lasttype = 2;
		}

		for ($j=0; $j<($games_reps[$i]-$nreps); $j++)
		{
			if ($lead)
			{
				echo '<span class="buttoncard" onclick="morphIntoTextField(this, '.$games[$i].')()" data-type="3" data-game="'.$games[$i].'">Ajouter un remplaçant</span><br />';
			}
			else
			{
				echo '<span class="playercard"></span><br />';
			}
		}

		echo '</p>';

// TODO confirm delete participation

		//echo '<p style="text-align: center;"><a href="" class="button">Mettre à jour les changements</a></p>';
	}
	else
	{
		if ($games_team[$i] > 1)
		{
			echo '<p style="text-align: center;" class="smallquote">Si vous souhaitez rejoindre une équipe, votre chef d\'équipe doit d\'abord vous inviter.</p><br /<br />';

			$temp = $database->req('SELECT sgl_users.login, sgl_users.id FROM sgl_teams, sgl_users WHERE sgl_teams.user="'.$_SESSION["sgl_id"].'" AND sgl_teams.lead = sgl_users.id AND game="'.$games[$i].'" AND sgl_teams.register = 0');

			while($data = $temp->fetch())
			{
				echo '<p style="text-align: center;"><a href="index.php?page=games&amp;game='.$games[$i].'&accept='.$data["id"].'" class="button">Accepter l\'invitation de '.htmlspecialchars($data["login"]).'</a></p><br />';
			}
		}
		echo '<p style="text-align: center;"><a href="index.php?page=games&amp;game='.$games[$i].'" class="button">S\'inscrire au tournoi</a></p>';
	}
	
	echo '<br /><br /><br /><br />';

}
?>
		<br />
	</div>
</div>