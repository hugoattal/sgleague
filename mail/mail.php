<?php

include_once("../class/Database.class.php");
include_once("../class/Mail.class.php");

$database = new Database();

echo "Inscription confirm<br /><br/>";
$temp = $database->req('SELECT login, mail, activation FROM sgl_users WHERE activation!=""');

while($data = $temp->fetch())
{
	$subject = "Informations pour la Student Gaming League";
	$content = "Salut ".htmlspecialchars($data["login"])." !\n\n
Tu n'as toujours pas confirmé ton inscription à la Student Gaming League...
Voici ton lien d'activation si tu l'as perdu : <https://".SERVER_ADDR.SERVER_REP."/index.php?page=activation&mvp=".strtolower($data["login"])."&key=".$data["activation"].">\n
Si tu as un problème, n'hésite pas à nous poser des questions sur discord ! <https://discord.gg/SGL17>\n\n
A très vite en jeu !\n
Toute l'équipe du SGN";

	echo "Sending mail to ".$data["login"]." (".$data["mail"].")<br/>";
	
	//new Mail($data["mail"], $subject, $content);
}

echo "<br /><br/><br />";
echo "Team confirm<br /><br/>";
$temp = $database->req('SELECT login, mail FROM sgl_users WHERE id NOT IN (SELECT user FROM sgl_teams)');

while($data = $temp->fetch())
{
	$subject = "Informations pour la Student Gaming League";
	$content = "Salut ".htmlspecialchars($data["login"])." !\n\n
Tu es inscrit à la Student Gaming League, mais tu n'as toujours pas rejoint d'équipes... Tu peux soit te faire inviter par quelqu'un, soit créer ta propre équipe.\n
Si tu cherches encore des gens avec qui jouer, n'hésite pas à passer sur discord ! <https://discord.gg/SGL17>\n\n
A très vite en jeu !\n
Toute l'équipe du SGN";

	echo "Sending mail to ".$data["login"]." (".$data["mail"].")<br/>";
	
	//new Mail($data["mail"], $subject, $content);
}

echo "<br /><br/><br />";
echo "Team finish confirm<br /><br />";
echo "OverWatch<br />";
$temp = $database->req('SELECT login, mail FROM sgl_users, (SELECT COUNT(user) AS nb_teams, lead FROM sgl_teams WHERE game = 1 GROUP BY lead) AS nt
	WHERE id=lead AND nb_teams < 6');

while($data = $temp->fetch())
{
	$subject = "Informations pour la Student Gaming League";
	$content = "Salut ".htmlspecialchars($data["login"])." !\n\n
Ton équipe n'est pas encore complète. N'oublie pas de trouver des joueurs pour atteindre le nombre minimum pour pouvoir participer.\n
Note : les remplaçants ne sont pas obligatoires, il peut y en avoir 0, 1 ou 2.\n
Si tu cherches encore des gens avec qui jouer, n'hésite pas à passer sur discord ! <https://discord.gg/SGL17>\n\n
A très vite en jeu !\n
Toute l'équipe du SGN";

	echo "Sending mail to ".$data["login"]." (".$data["mail"].")<br/>";
	
	//new Mail($data["mail"], $subject, $content);
}

echo "<br />";
echo "League of Legends<br />";
$temp = $database->req('SELECT login, mail FROM sgl_users, (SELECT COUNT(user) AS nb_teams, lead FROM sgl_teams WHERE game = 2 GROUP BY lead) AS nt
	WHERE id=lead AND nb_teams < 5');

while($data = $temp->fetch())
{
	$subject = "Informations pour la Student Gaming League";
	$content = "Salut ".htmlspecialchars($data["login"])." !\n\n
Ton équipe n'est pas encore complète. N'oublie pas de trouver des joueurs pour atteindre le nombre minimum pour pouvoir participer.\n
Note : les remplaçants ne sont pas obligatoires, il peut y en avoir 0, 1 ou 2.\n
Si tu cherches encore des gens avec qui jouer, n'hésite pas à passer sur discord ! <https://discord.gg/SGL17>\n\n
A très vite en jeu !\n
Toute l'équipe du SGN";

	echo "Sending mail to ".$data["login"]." (".$data["mail"].")<br/>";
	
	//new Mail($data["mail"], $subject, $content);
}

echo "<br />";
echo "Counter Strike<br />";
$temp = $database->req('SELECT login, mail FROM sgl_users, (SELECT COUNT(user) AS nb_teams, lead FROM sgl_teams WHERE game = 3 GROUP BY lead) AS nt
	WHERE id=lead AND nb_teams < 5');

while($data = $temp->fetch())
{
	$subject = "Informations pour la Student Gaming League";
	$content = "Salut ".htmlspecialchars($data["login"])." !\n\n
Ton équipe n'est pas encore complète. N'oublie pas de trouver des joueurs pour atteindre le nombre minimum pour pouvoir participer.\n
Note : les remplaçants ne sont pas obligatoires, il peut y en avoir 0, 1 ou 2.\n
Si tu cherches encore des gens avec qui jouer, n'hésite pas à passer sur discord ! <https://discord.gg/SGL17>\n\n
A très vite en jeu !\n
Toute l'équipe du SGN";

	echo "Sending mail to ".$data["login"]." (".$data["mail"].")<br/>";
	
	//new Mail($data["mail"], $subject, $content);
}

echo "<br /><br/><br />";
echo "Profil confirm<br/><br />";
echo "OverWatch<br />";
$temp = $database->req('SELECT login, mail FROM sgl_users, (SELECT COUNT(user) AS nb_teams, lead FROM sgl_teams WHERE game = 1 GROUP BY lead) AS nt, sgl_teams
	WHERE sgl_users.id=sgl_teams.user AND sgl_teams.lead=nt.lead AND nb_teams = 6 AND (battletag="" OR battletag IS NULL)');

while($data = $temp->fetch())
{
	$subject = "Informations pour la Student Gaming League";
	$content = "Salut ".htmlspecialchars($data["login"])." !\n\n
Tu es fin prêt pour la Student Gaming League :D !\n
Par contre, il te manque quelques informations sur ton profil (comme ton battletag), n'oublie pas de les compléter avant le début de la compétition : <https://".SERVER_ADDR.SERVER_REP."/index.php?page=account>\n
Si tu as des question, n'hésite pas à venir sur discord ! <https://discord.gg/SGL17>\n\n
A très vite en jeu !\n
Toute l'équipe du SGN";

	echo "Sending mail to ".$data["login"]." (".$data["mail"].")<br/>";
	
	//new Mail($data["mail"], $subject, $content);
}

echo "<br />";
echo "League of Legends<br />";
$temp = $database->req('SELECT login, mail FROM sgl_users, (SELECT COUNT(user) AS nb_teams, lead FROM sgl_teams WHERE game = 2 GROUP BY lead) AS nt, sgl_teams
	WHERE sgl_users.id=sgl_teams.user AND sgl_teams.lead=nt.lead AND nb_teams = 5 AND (summoner="" OR summoner IS NULL)');

while($data = $temp->fetch())
{
	$subject = "Informations pour la Student Gaming League";
	$content = "Salut ".htmlspecialchars($data["login"])." !\n\n
Tu es fin prêt pour la Student Gaming League :D !\n
Par contre, il te manque quelques informations sur ton profil (comme ton nom d'invocateur), n'oublie pas de les compléter avant le début de la compétition : <https://".SERVER_ADDR.SERVER_REP."/index.php?page=account>\n
Si tu as des question, n'hésite pas à venir sur discord ! <https://discord.gg/SGL17>\n\n
A très vite en jeu !\n
Toute l'équipe du SGN";

	echo "Sending mail to ".$data["login"]." (".$data["mail"].")<br/>";
	
	//new Mail($data["mail"], $subject, $content);
}

echo "<br />";
echo "Counter Strike<br />";
$temp = $database->req('SELECT login, mail FROM sgl_users, (SELECT COUNT(user) AS nb_teams, lead FROM sgl_teams WHERE game = 3 GROUP BY lead) AS nt, sgl_teams
	WHERE sgl_users.id=sgl_teams.user AND sgl_teams.lead=nt.lead AND nb_teams = 5 AND (steamid="" OR steamid IS NULL)');

while($data = $temp->fetch())
{
	$subject = "Informations pour la Student Gaming League";
	$content = "Salut ".htmlspecialchars($data["login"])." !\n\n
Tu es fin prêt pour la Student Gaming League :D !\n
Par contre, il te manque quelques informations sur ton profil (comme ton steam id), n'oublie pas de les compléter avant le début de la compétition : <https://".SERVER_ADDR.SERVER_REP."/index.php?page=account>\n
Si tu as des question, n'hésite pas à venir sur discord ! <https://discord.gg/SGL17>\n\n
A très vite en jeu !\n
Toute l'équipe du SGN";

	echo "Sending mail to ".$data["login"]." (".$data["mail"].")<br/>";
	
	//new Mail($data["mail"], $subject, $content);
}

echo "<br />";
echo "HearhStone<br />";
$temp = $database->req('SELECT login, mail FROM sgl_users, sgl_teams WHERE id=user AND game=4 AND (battletag="" OR battletag IS NULL)');

while($data = $temp->fetch())
{
	$subject = "Informations pour la Student Gaming League";
	$content = "Salut ".htmlspecialchars($data["login"])." !\n\n
Tu es fin prêt pour la Student Gaming League :D !\n
Par contre, il te manque quelques informations sur ton profil (comme ton battletag), n'oublie pas de les compléter avant le début de la compétition : <https://".SERVER_ADDR.SERVER_REP."/index.php?page=account>\n
Si tu as des question, n'hésite pas à venir sur discord ! <https://discord.gg/SGL17>\n\n
A très vite en jeu !\n
Toute l'équipe du SGN";

	echo "Sending mail to ".$data["login"]." (".$data["mail"].")<br/>";
	
	//new Mail($data["mail"], $subject, $content);
}


?>