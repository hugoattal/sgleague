<?php

include_once("./class/Database.class.php");

$database = new Database();

echo "<pre>";

$temp = $database->req('SELECT COUNT(*) AS nb_users FROM sgl_users');
$data = $temp->fetch();
$mydata["nb_subs"] = $data["nb_users"];
echo "Nombre de joueurs inscrits :\n".$data["nb_users"];

echo "\n\n";

$temp = $database->req('SELECT COUNT(*) AS nb_users FROM sgl_users WHERE activation=""');
$data = $temp->fetch();
$mydata["nb_conf"] = $data["nb_users"];
echo "Nombre de joueurs confirmés :\n".$data["nb_users"];

echo "\n\n";

echo "Pourcentage de joueurs confirmés : ".round(($mydata["nb_conf"]/$mydata["nb_subs"]*100),2)."%";

echo "\n\n";

$temp = $database->req('SELECT COUNT(DISTINCT lead) AS nb_teams FROM sgl_teams WHERE game = 1');
$data = $temp->fetch();
$mydata["tot_ow"] = $data["nb_teams"];
echo "Teams OverWatch :\n".$data["nb_teams"];

$temp = $database->req('SELECT COUNT(*) AS num_teams FROM (SELECT COUNT(user) AS nb_teams FROM sgl_teams WHERE game = 1 GROUP BY lead) AS nt WHERE nb_teams >= 6 ');
$data = $temp->fetch();
$mydata["full_ow"] = $data["num_teams"];
echo " (".$data["num_teams"]." full)";

echo "\n\n";

$temp = $database->req('SELECT COUNT(DISTINCT lead) AS nb_teams FROM sgl_teams WHERE game = 2');
$data = $temp->fetch();
$mydata["tot_lol"] = $data["nb_teams"];
echo "Teams League of Legends :\n".$data["nb_teams"];

$temp = $database->req('SELECT COUNT(*) AS num_teams FROM (SELECT COUNT(user) AS nb_teams FROM sgl_teams WHERE game = 2 GROUP BY lead) AS nt WHERE nb_teams >= 5 ');
$data = $temp->fetch();
$mydata["full_lol"] = $data["num_teams"];
echo " (".$data["num_teams"]." full)";

echo "\n\n";

$temp = $database->req('SELECT COUNT(DISTINCT lead) AS nb_teams FROM sgl_teams WHERE game = 3');
$data = $temp->fetch();
$mydata["tot_csgo"] = $data["nb_teams"];
echo "Teams Counter Strike :\n".$data["nb_teams"];

$temp = $database->req('SELECT COUNT(*) AS num_teams FROM (SELECT COUNT(user) AS nb_teams FROM sgl_teams WHERE game = 3 GROUP BY lead) AS nt WHERE nb_teams >= 5 ');
$data = $temp->fetch();
$mydata["full_csgo"] = $data["num_teams"];
echo " (".$data["num_teams"]." full)";

echo "\n\n";

$temp = $database->req('SELECT COUNT(DISTINCT lead) AS nb_teams FROM sgl_teams WHERE game = 4');
$data = $temp->fetch();
echo "Joueurs HearthStone :\n".$data["nb_teams"];

echo "\n\n";

echo "Pourcentage de complétion d'équipes : ".round((($mydata["full_ow"]+$mydata["full_lol"]+$mydata["full_csgo"])/($mydata["tot_ow"]+$mydata["tot_lol"]+$mydata["tot_csgo"])*100),2)."%";

echo "</pre>";

?>
