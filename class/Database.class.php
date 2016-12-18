<?php
class Database
{
    private $database;
	
	public function __construct()
	{
		$hote = "localhost";
		$db = "sgleague";
		$login = "sgleague";
		$pass = ""; // Z'avez cru quoi ?
		
		try
		{
			$this->database = new PDO('mysql:host='.$hote.';dbname='.$db.';charset=UTF8', $login, $pass);
		}
		catch (Exception $e)
		{
			die('Erreur : ' . $e->getMessage());
		}
	}

	public function req($req)
	{
		$return = $this->database->query($req) or die(print_r($this->database->errorInfo()));
		return $return;
	}

	public function close_sql()
	{
		$this->database->closeCursor();
	}
}
?>