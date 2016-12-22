<?php

session_start();

if (isset($_SESSION["sgl_id"]))
{
	$get_type = isset($_GET['type']) ? $_GET['type'] : '';

	switch($get_type)
	{
		case "search":

			include_once("./class/Database.class.php");

			$database = new Database();

			$get_search = isset($_GET['data']) ? $_GET['data'] : '';

			$temp = $database->req('SELECT id, login, school FROM sgl_users WHERE login LIKE "%'.addslashes($get_search).'%" OR mail LIKE "%'.addslashes($get_search).'%" LIMIT 0,10');

			while ($data = $temp->fetch(PDO::FETCH_ASSOC))
			{
				$result[] = $data;
			}

			header('Content-Type: application/json');
			echo json_encode($result);

		break;
	}
}

?>