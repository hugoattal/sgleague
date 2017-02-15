<?php

include_once("../config.php");
include_once("../class/Database.class.php");
include_once("../class/HtmlMail.class.php");

$database = new Database();

echo "Loading...<br /><br/>";
$temp = $database->req('SELECT login, mail FROM sgl_users WHERE activation="" LIMIT 500,100');

while($data = $temp->fetch())
{
	$subject = "🏆 La SGL 2017 : confirmation de ton inscription 🎮";
	$html = "<b>Très cher ".htmlspecialchars($data["login"])."</b>,<br />
<br />
Tu fais partie des quelques centaines de prétendant(e)s au titre de la Student Gaming League 2017. Toute l'équipe organisatrice tient à te remercier, car sans toi, cette SGL17 serait vide de sens.<br />
<br />
TL;DR<br />
Pour t'assurer que ton inscription et celle de ton équipe a bien été prise en compte, ton nom doit figurer dans ces listes, en fonction du ou des jeux auxquels tu participes :<br />
League of Legends > <a href=\"https://goo.gl/00qzon\">https://goo.gl/00qzon</a><br />
Hearthstone > <a href=\"https://goo.gl/5Hx4iZ\">https://goo.gl/5Hx4iZ</a><br />
Counter-Strike: GO > <a href=\"https://goo.gl/Idc3F2\">https://goo.gl/Idc3F2</a><br />
Overwatch > <a href=\"https://goo.gl/1fv2k4\">https://goo.gl/1fv2k4</a><br />
<br />
S'il n'apparaît pas, c'est que les conditions de validation n'ont pas été remplie. Pas de panique ! On te les rappelle dans ce mail et sur <a href=\"https://discord.gg/SGL17\">notre serveur Discord</a>. Tu as jusqu'au 2 mars au soir pour le faire, n'attends plus :)<br />
<br />
Sache qu'au Student Gaming Network (SGN), nous ne sommes pas (trop) exigeants. Nous demandons simplement aux participants d'être étudiant(e)s, non lycéen(e)s, qu'ils déclarent sur leur honneur (même si à un moment on va vérifier ton honneur). Pour rappel, l'intégralité du règlement est consultable à cette adresse : <a href=\"https://docs.google.com/document/d/1WJXpUn2LAVkb0YCtqFDDdTvT_QTn4rt7I9_bTHv2vnY/edit?usp=sharing\">Règlement de la Student Gaming League 2017</a><br />
<br />
Pour s'assurer que la compétition se déroule au mieux, nous conférerons aux capitaines d'équipes (et joueurs/joueuses Hearthstone) quelques responsabilités que nous prendrons le temps de vous expliquer dans les prochains jours.<br />
<br />
<b>Première étape : la confirmation de votre inscription</b><br />
<br />
Tu t'es inscrit(e) sur https://league.sgnw.fr/, ça tu maîtrises. Mais savais-tu que tu as un profil que tu peux compléter ? Pour cela rien de plus simple, il suffit de te rendre <a href=\"https://league.sgnw.fr/index.php?page=account\">sur ta page de compte</a> et compléter les champs manquants.<br />
<br />
Certaines de ces informations sont cruciales pour ta participation, le reste c'est du bonus.<br />
Voici un récapitulatif des conditions requises à ta participation, les informations à fournir, et où les renseigner :<br />
<br />
<i>Pour <b>tous les joueurs de la SGL17</b>, dont tu fais partie</i><br />
- ton BattleTag/SteamID/Summoner (<a href=\"https://league.sgnw.fr/index.php?page=account\">sur ton profil</a>, selon le(s) jeu(x) au(x)quel(s) tu es inscrit(e))<br />
- ton école d'origine<br />
<br />
<i>Pour les capitaines d'équipe League of Legends, Counter-Strike: GO et Overwatch</i><br />
- le nom et le tag de ton équipe (à remplir par le capitaine sur la page du jeu)<br />
- une équipe d'au moins 5 membres (6 pour Overwatch)<br />
- ton ID sur Discord de la forme Pseudo#XXXX<br />
<br />
<i>Pour les joueurs Hearthstone</i><br />
- ton ID sur Discord de la forme Pseudo#XXXX<br />
- avoir envoyé un <u>screenshot</u> de tes 4 decklists à <a href=\"mailto:sgl17.hs@sgnw.fr\">sgl17.hs@sgnw.fr</a><br />
<br />
Si tout est conforme, alors nos admins tournoi s'empresseront de t'ajouter aux fameuses listes des participants, accompagné d'un email, l'un ou l'autre faisant foi de confirmation d'inscription.<br />
<br />
Si tu as la moindre question, rendez-vous dans le canal #help de <a href=\"https://discord.gg/SGL17\">notre serveur Discord</a>.<br />
<br />
Nous comptons sur toi ".htmlspecialchars($data["login"]).", déjà plus de 700 joueurs t'attendent (et ce n'est pas fini ;) )<br />
<br />
Pilou du SGN";

	echo "Sending mail to ".$data["login"]." (".$data["mail"].")<br/>";
	
	new Mail($data["mail"], $subject, $html);
}

?>