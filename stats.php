<?php

include_once("./class/Database.class.php");

$database = new Database();

echo "<pre>";

$temp = $database->req('SELECT COUNT(*) AS nb_users FROM sgl_users');
$data = $temp->fetch();
echo "Nombre de joueurs inscrits :\n".$data["nb_users"];

echo "\n\n";

$temp = $database->req('SELECT COUNT(*) AS nb_users FROM sgl_users WHERE activation=""');
$data = $temp->fetch();
echo "Nombre de joueurs confirmés :\n".$data["nb_users"];

echo "\n\n";

$temp = $database->req('SELECT COUNT(DISTINCT lead) AS nb_teams FROM sgl_teams WHERE game = 1');
$data = $temp->fetch();
echo "Teams OverWatch :\n".$data["nb_teams"];

$temp = $database->req('SELECT COUNT(*) AS num_teams FROM (SELECT COUNT(user) AS nb_teams FROM sgl_teams WHERE game = 1 GROUP BY lead) AS nt WHERE nb_teams >= 6 ');
$data = $temp->fetch();
echo " (".$data["num_teams"]." full)";

echo "\n\n";

$temp = $database->req('SELECT COUNT(DISTINCT lead) AS nb_teams FROM sgl_teams WHERE game = 2');
$data = $temp->fetch();
echo "Teams League of Legends :\n".$data["nb_teams"];

$temp = $database->req('SELECT COUNT(*) AS num_teams FROM (SELECT COUNT(user) AS nb_teams FROM sgl_teams WHERE game = 2 GROUP BY lead) AS nt WHERE nb_teams >= 5 ');
$data = $temp->fetch();
echo " (".$data["num_teams"]." full)";

echo "\n\n";

$temp = $database->req('SELECT COUNT(DISTINCT lead) AS nb_teams FROM sgl_teams WHERE game = 3');
$data = $temp->fetch();
echo "Teams Counter Strike :\n".$data["nb_teams"];

$temp = $database->req('SELECT COUNT(*) AS num_teams FROM (SELECT COUNT(user) AS nb_teams FROM sgl_teams WHERE game = 3 GROUP BY lead) AS nt WHERE nb_teams >= 5 ');
$data = $temp->fetch();
echo " (".$data["num_teams"]." full)";

echo "\n\n";

$temp = $database->req('SELECT COUNT(DISTINCT lead) AS nb_teams FROM sgl_teams WHERE game = 4');
$data = $temp->fetch();
echo "Teams HearthStone :\n".$data["nb_teams"];

$temp = $database->req('SELECT COUNT(*) AS num_teams FROM (SELECT COUNT(user) AS nb_teams FROM sgl_teams WHERE game = 4 GROUP BY lead) AS nt WHERE nb_teams >= 1 ');
$data = $temp->fetch();
echo " (".$data["num_teams"]." full)";

echo "</pre>";

?>