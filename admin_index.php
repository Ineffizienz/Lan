<?php

// NOTES:
// - Team löschen beenden
header("Content-Type: text/html; charset=utf-8");
error_reporting(E_ALL);

include("admin/files.php");

if(IP == "::1" || "192.168.0.89")
{
	$tpl = new template();
	$tpl->load("admin/home.html");

	$tpl->assign("menu",buildContent("admin/admin_menu.html"));
	$tpl->assign("content",$content);
	$tpl->assign("key_status",$key_status);
	$tpl->assign("team_status",$team_status);
	$tpl->assign("player",$player);
	$tpl->assign("keys",$key_status);
	$tpl->assign("exist_teams",displayTeams($con));
	$tpl->assign("achievements",displayAchievements($con));
	$tpl->assign("username",addUsername($con));
	$tpl->assign("ticket_status",displayTicketStatus($con));
	$tpl->assign("ac_cat",displayAcCategories($con));
	$tpl->assign("ac_trigger",displayAcTrigger($con));
	$tpl->assign("admin_games",displaySingleGame($con));

	// Turniere
	$tpl->assign("games",displayTmGames($con));
	//$tpl->assign("tournaments",displayTournaments($con));
	$tpl->assign("votes",displayVotedTournaments($con));

	$tpl->display();
} else {

	$message = new message();
	$message->getMessageCode("ERR_NO_ADMIN");
	echo $message->displayMessage();

}

?>