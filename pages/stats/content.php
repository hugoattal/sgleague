<div id="content">
	<div class="container">
		<h1><i class="fa fa-angle-right" aria-hidden="true"></i> Statistiques écoles</h1>
<?php

	include_once("./class/Database.class.php");
	$database = new Database();

	$temp = $database->req('SELECT COUNT(school) AS number, school FROM sgl_users GROUP BY school ORDER BY number DESC, school ASC');

	while ($data = $temp->fetch())
	{
		if ($data["number"] > 0)
		{
			echo '<div class="adm_team"><div class="title">'.htmlspecialchars($data["school"]).'</div>
			<span style="opacity: 0.3; padding: 10px 0px; display: inline-block;">'.$data["number"].' joueurs</span>';

			echo '<table>';

			$temp_m = $database->req('SELECT sgl_users.login, sgl_users.activmail, sgl_users.mail, sgl_users.school, sgl_users.birth, sgl_users.gender, sgl_users.discord, sgl_users.activation, sgl_users.type,
				(SELECT GROUP_CONCAT(DISTINCT sgl_teams.game) AS games FROM sgl_teams WHERE sgl_teams.user = sgl_users.id) AS games
				FROM sgl_users
				WHERE sgl_users.school = "'.addslashes($data["school"]).'"');

			while($data_m = $temp_m->fetch())
			{
				$verif = false;
				if ((strlen($data_m["login"]) > 1) && (strlen($data_m["activmail"]) > 1) && ($data_m["activation"] == ""))
				{
					$verif = true;
				}

				$game_ow = "";
				$game_lol = "";
				$game_cs = "";
				$game_hs = "";

				$games_array = explode(",", $data_m["games"]);

				foreach ($games_array AS $game)
				{
					switch (intval($game))
					{
						case 1:
							$game_ow = "OW";
							break;
						case 2:
							$game_lol = "LOL";
							break;
						case 3:
							$game_cs = "CS";
							break;
						case 4:
							$game_hs = "HS";
							break;
					}
				}

				echo '<tr>
				<td style="width:20px;text-align:center;">'.($verif?'<i class="fa fa-check" aria-hidden="true"></i>':'<i class="fa fa-times" style="color:#d00000" aria-hidden="true"></i>').'</td>
				<td style="width:20px;text-align:center;">'.($data_m["type"]==1?'<i class="fa fa-user-o" aria-hidden="true"></i>':($data_m["type"]==2?'<i class="fa fa-user" aria-hidden="true"></i>':'<i class="fa fa-user-plus" aria-hidden="true"></i>')).'</td>
				<td style="width:20px;text-align:center;">'.($data_m["gender"]==1?'<i class="fa fa-mars" aria-hidden="true"></i>':($data_m["gender"]==2?'<i class="fa fa-venus" aria-hidden="true"></i>':'<i class="fa fa-question-circle-o" style="opacity: 0.3;" aria-hidden="true"></i>')).'</td>
				<td style="width:15%">'.htmlspecialchars($data_m["login"]).'</td>
				<td style="width:60px;">'.($data_m["birth"]>0?floor((time()-$data_m["birth"])/(60*60*24*365.25)).' ans':'').'</td>
				<td>'.htmlspecialchars($data_m["school"]).'</td>

				<td style="width:30px;text-align:center;">'.$game_ow.'</td>
				<td style="width:30px;text-align:center;">'.$game_lol.'</td>
				<td style="width:30px;text-align:center;">'.$game_cs.'</td>
				<td style="width:30px;text-align:center;">'.$game_hs.'</td>

				<td style="width:160px;">'.htmlspecialchars($data_m["discord"]).'</td>
				<td style="width:15px;text-align:center;"><a href="mailto:'.htmlspecialchars($data_m["activmail"]!=""?$data_m["activmail"]:$data_m["mail"]).'"><i class="fa fa-envelope" aria-hidden="true"></i></a></td>
				<td style="width:15px;text-align:center;"><a href="javascript:void(0);" onclick="copyTextToClipboard(\''.htmlspecialchars($data_m["activmail"]!=""?$data_m["activmail"]:$data_m["mail"]).'\')"><i class="fa fa-clipboard" aria-hidden="true"></i></a></td>
				</tr>';
			}

			echo '</table>';

			echo '</div>';
		}
	}

?>

	</div>
</div>