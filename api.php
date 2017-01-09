<?php

session_start();

include_once("./config.php");

if (isset($_SESSION["sgl_id"]))
{
	$get_type = isset($_GET['type']) ? $_GET['type'] : '';

	switch($get_type)
	{
		case "search":

			header('Content-Type: application/json');
			include_once("./class/Database.class.php");

			$database = new Database();

			$get_data = isset($_GET['data']) ? $_GET['data'] : '';
			$get_game = isset($_GET['game']) ? intval($_GET['game']) : '';

			$temp = $database->req('SELECT id, login, school FROM sgl_users WHERE (login LIKE "%'.addslashes($get_data).'%" OR mail LIKE "%'.addslashes($get_data).'%")
			AND NOT EXISTS (SELECT user FROM sgl_teams WHERE user = sgl_users.id AND game = "'.$get_game.'" AND (register > 0 OR lead="'.$_SESSION["sgl_id"].'")) AND sgl_users.activation="" LIMIT 0,10');

			while ($data = $temp->fetch(PDO::FETCH_ASSOC))
			{
				$result[] = $data;
			}

			if (isset($result))
			{
				echo json_encode($result);
			}
			else
			{
				echo "[]";
			}

		break;

		case "team_add":

			header('Content-Type: application/json');
			include_once("./class/Database.class.php");

			$database = new Database();

			$get_player = isset($_GET['player']) ? intval($_GET['player']) : '';

			$get_game = isset($_GET['game']) ? intval($_GET['game']) : '';
			$get_game = in_array($get_game, array(1,2,3,4))?$get_game:1;

			$get_ptype = isset($_GET['ptype']) ? intval($_GET['ptype']) : '';
			$get_ptype = ($get_ptype==3)?3:2;

//  ----- [ Check rights ] --------------------------------------------------

			$temp = $database->req('SELECT COUNT(*) as existuser FROM sgl_users WHERE id="'.$get_player.'" AND activation=""');
			$data = $temp->fetch();

			if ($data["existuser"] > 0)
			{
				$temp = $database->req('SELECT COUNT(*) as existplayer FROM sgl_teams WHERE user="'.$get_player.'" AND game="'.$get_game.'" AND register > 0');
				$data = $temp->fetch();

				if ($data["existplayer"] == 0)
				{
					$temp = $database->req('SELECT COUNT(*) as existteam FROM sgl_teams WHERE lead="'.$_SESSION["sgl_id"].'" AND game="'.$get_game.'"');
					$data = $temp->fetch();

					if ($data["existteam"] > 0)
					{
						$temp = $database->req('SELECT COUNT(*) as players FROM sgl_teams WHERE lead="'.$_SESSION["sgl_id"].'" AND game="'.$get_game.'" AND type="'.$get_ptype.'"');
						$data = $temp->fetch();

						$games_team = array(6, 5, 5, 1);
						$games_reps = array(2, 2, 2, 0);

						if ((($get_ptype==2) && ($data["players"]+1 < $games_team[$get_game-1])) || (($get_ptype==3) && ($data["players"] < $games_reps[$get_game-1])))
						{
							$database->req('INSERT INTO sgl_teams (user, game, lead, type, register) VALUES("'.$get_player.'", "'.$get_game.'", "'.$_SESSION["sgl_id"].'", "'.$get_ptype.'", 0)');

							echo '{"success": "Player added"}';
						}
						else
						{
							echo '{"error": "Team already full"}';
						}
					}
					else
					{
						echo '{"error": "Team do not exist"}';
					}
				}
				else
				{
					echo '{"error": "Player already in team"}';
				}
			}
			else
			{
				echo '{"error": "User do not exist"}';
			}

		break;

		case "mail_add":

			header('Content-Type: application/json');
			include_once("./class/Database.class.php");

			$database = new Database();

			$get_mail = isset($_GET['player']) ? $_GET['player'] : '';

			$get_game = isset($_GET['game']) ? intval($_GET['game']) : '';
			$get_game = in_array($get_game, array(1,2,3,4))?$get_game:1;

			$get_ptype = isset($_GET['ptype']) ? intval($_GET['ptype']) : '';
			$get_ptype = ($get_ptype==3)?3:2;

//  ----- [ Check rights ] --------------------------------------------------

			if (filter_var($get_mail, FILTER_VALIDATE_EMAIL) == true)
			{
				$temp = $database->req('SELECT COUNT(*) as existuser FROM sgl_users WHERE mail="'.addslashes($get_mail).'"');
				$data = $temp->fetch();

				if ($data["existuser"] == 0)
				{
					$temp = $database->req('SELECT COUNT(*) as existteam FROM sgl_teams WHERE lead="'.$_SESSION["sgl_id"].'" AND game="'.$get_game.'"');
					$data = $temp->fetch();

					if ($data["existteam"] > 0)
					{
						$temp = $database->req('SELECT COUNT(*) as players FROM sgl_teams WHERE lead="'.$_SESSION["sgl_id"].'" AND game="'.$get_game.'" AND type="'.$get_ptype.'"');
						$data = $temp->fetch();

						$games_team = array(6, 5, 5, 1);
						$games_reps = array(2, 2, 2, 0);

						if ((($get_ptype==2) && ($data["players"]+1 < $games_team[$get_game-1])) || (($get_ptype==3) && ($data["players"] < $games_reps[$get_game-1])))
						{
							$database->req('INSERT INTO sgl_users (mail, activation) VALUES("'.addslashes($get_mail).'", "PENDING")');

							$temp = $database->req('SELECT MAX(Id) AS player FROM sgl_users');
							// TODO : Not really safe...

							$data = $temp->fetch();

							$database->req('INSERT INTO sgl_teams (user, game, lead, type, register) VALUES("'.$data["player"].'", "'.$get_game.'", "'.$_SESSION["sgl_id"].'", "'.$get_ptype.'", 0)');

							$subject = "Invitation à la Student Gaming League";
							$content = $_SESSION["sgl_login"]." vous invite à rejoindre son équipe à la Student Gaming League !
Pour vous inscrire, cliquez sur le lien suivant : <https://".SERVER_ADDR.SERVER_REP."/index.php?page=register&mail=".strtolower($get_mail).">\n
Vous devrez ensuite accepter son invitation sur le site pour rejoindre son équipe.\n\nL'équipe de la Student Gaming League 2017";

							include_once("./class/Mail.class.php");
							new Mail($get_mail, $subject, $content);

							echo '{"success": "Player added"}';
						}
						else
						{
							echo '{"error": "Team already full"}';
						}
					}
					else
					{
						echo '{"error": "Team do not exist"}';
					}
				}
				else
				{
					echo '{"error": "Player already exist"}';
				}
			}
			else
			{
				echo '{"error": "Mail not valid"}';
			}

		break;
	}
}

?>