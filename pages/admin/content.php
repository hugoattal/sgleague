<div id="content">
	<div class="container">
		<h1><i class="fa fa-angle-right" aria-hidden="true"></i> Panneau d'administration</h1>
		<p style="text-align: center;">
<?php

if (isset($_GET["game"]))
{
	$get_game = intval($_GET["game"]);
?>
<br /><br />
<p style="text-align: center;">
	<a href="index.php?page=admin&amp;game=1" class="button<?=($get_game==1)?" selected":""?>">Overwatch</a>
	<a href="index.php?page=admin&amp;game=2" class="button<?=($get_game==2)?" selected":""?>">League of Legends</a>
	<a href="index.php?page=admin&amp;game=3" class="button<?=($get_game==3)?" selected":""?>">Counter Strike</a>
	<a href="index.php?page=admin&amp;game=4" class="button<?=($get_game==4)?" selected":""?>">Hearthstone</a>
</p>
<?php

	include_once("./class/Database.class.php");
	$database = new Database();

	$gameidType = "battletag";
	if ($get_game == 2)
	{
		$gameidType = "summoner";
	}
	else if ($get_game == 3)
	{
		$gameidType = "steamid";
	}

	$temp = $database->req('SELECT user, name, tag, nt.nb_teams FROM sgl_teams, (SELECT COUNT(user) AS nb_teams, lead FROM sgl_teams WHERE game = "'.$get_game.'" GROUP BY lead) AS nt
		WHERE sgl_teams.user=sgl_teams.lead AND sgl_teams.lead=nt.lead AND game="'.$get_game.'" ORDER BY nb_teams DESC, case when (name is null OR name="") then 1 else 0 end, name');
	while($data = $temp->fetch())
	{
		echo '<div class="adm_team">';


		if ($get_game != 4)
		{
			echo '<div class="title">';

			if ($data["name"] != null)
			{
				echo "[ ".$data["tag"]." ] ".$data["name"];
			}
			else
			{
				echo "Unnamed team";
			}

			echo '</div>';
			echo '<span style="opacity: 0.3; padding: 10px 0px; display: inline-block;">'.$data["nb_teams"]." joueurs</span><br />";

		}

		echo '<table>';

		$temp_m = $database->req('SELECT sgl_users.login, sgl_users.activmail, sgl_users.mail, sgl_users.school, sgl_users.birth, sgl_users.gender, sgl_users.discord, sgl_users.activation,
			sgl_users.'.$gameidType.' AS gameid, sgl_teams.register, sgl_teams.type
			FROM sgl_teams, sgl_users
			WHERE sgl_teams.user = sgl_users.id AND sgl_teams.lead = "'.$data["user"].'" AND sgl_teams.game ="'.$get_game.'" ORDER BY type');

		while($data_m = $temp_m->fetch())
		{
			$verif = false;
			if ((strlen($data_m["login"]) > 1) && (strlen($data_m["activmail"]) > 1) && (strlen($data_m["gameid"]) > 1) && ($data_m["activation"] == ""))
			{
				$verif = true;
			}

			echo '<tr>
			<td style="width:20px;text-align:center;">'.($verif?'<i class="fa fa-check" aria-hidden="true"></i>':'<i class="fa fa-times" style="color:#d00000" aria-hidden="true"></i>').'</td>
			<td style="width:20px;text-align:center;">'.($data_m["type"]==1?'<i class="fa fa-user-o" aria-hidden="true"></i>':($data_m["type"]==2?'<i class="fa fa-user" aria-hidden="true"></i>':'<i class="fa fa-user-plus" aria-hidden="true"></i>')).'</td>
			<td style="width:20px;text-align:center;">'.($data_m["gender"]==1?'<i class="fa fa-mars" aria-hidden="true"></i>':($data_m["gender"]==2?'<i class="fa fa-venus" aria-hidden="true"></i>':'<i class="fa fa-question-circle-o" style="opacity: 0.3;" aria-hidden="true"></i>
')).'</td>
			<td style="width:15%">'.htmlspecialchars($data_m["login"]).'</td>
			<td style="width:160px;">'.htmlspecialchars($data_m["gameid"]).'</td>
			<td style="width:60px;">'.($data_m["birth"]>0?floor((time()-$data_m["birth"])/(60*60*24*365.25)).' ans':'').'</td>
			<td>'.htmlspecialchars($data_m["school"]).'</td>
			<td style="width:160px;">'.htmlspecialchars($data_m["discord"]).'</td>
			<td style="width:15px;text-align:center;"><a href="mailto:'.htmlspecialchars($data_m["activmail"]!=""?$data_m["activmail"]:$data_m["mail"]).'"><i class="fa fa-envelope" aria-hidden="true"></i></a></td>
			<td style="width:15px;text-align:center;"><a href="javascript:void(0);" onclick="copyTextToClipboard(\''.htmlspecialchars($data_m["activmail"]!=""?$data_m["activmail"]:$data_m["mail"]).'\')"><i class="fa fa-clipboard" aria-hidden="true"></i></a></td>
			</tr>';
		}

		echo '</table>';

		echo '</div>';
	}
}
else
{
?>
<br /><br />
<p style="text-align: center;">
	<a href="index.php?page=admin&amp;game=1" class="button">Overwatch</a>
	<a href="index.php?page=admin&amp;game=2" class="button">League of Legends</a>
	<a href="index.php?page=admin&amp;game=3" class="button">Counter Strike</a>
	<a href="index.php?page=admin&amp;game=4" class="button">Hearthstone</a>
</p>

<?php
}

?>
		</p>
		<br />
	</div>
</div>