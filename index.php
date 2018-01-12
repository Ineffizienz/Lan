<?php

/*************** NOTES ********************/
/*
	- Authentifizierung im Netzwerk prüfen
*/

header("Content-Type: text/html; charset=utf-8");
error_reporting(E_ALL);

include ("include/init/constant.php");
require_once(CL . "template_class.php");
require_once(CL . "message_class.php");
require_once(CL . "achievement_class.php");

require_once(INC . "connect.php");
require_once(INC . "function.php");

$fl = getFirstLoginByIp($con,IP);
$ticket_status = getTicketStatus($con,IP);
$message = new message();
if($fl == "1")
{
	//$message->getMessageCode("ERR_NO_USER_NAME");

	$tpl = new template();
	$tpl->load("reg_name.html");

	$tpl->assign("sir_brummel",$message->displayMessage());

	$tpl->display();

} elseif (empty($ticket_status) && ((IP !== "192.168.0.89") && (IP !== "192.168.0.95") && (IP !== "::1"))) {

	$tpl = new template();
	$tpl->load("validate_ticket.html");

	$tpl->assign("sir_brummel",$message->displayMessage());

	$tpl->display();

} else {

	include(INC . "controller.php");

	$tpl = new template();

	$tpl->load("index.html");



	$tpl->assign("headline","Wer zuletzt lacht, hat den höchsten Ping");
	$tpl->assign("lantitle","Wer zuletzt lacht, hat den höchsten Ping");
	$tpl->assign("default","Wähle eine Option.");
	$tpl->assign("menu",build_content("menu.html"));

	if (isset($content))
	{
		$tpl->assign("content",$content);
	}

	if (isset($settings))
	{
		$tpl->assign("settings",$settings); // controller.php
	}

	$tpl->assign("default","Bitte wähle ein Spiel aus");
	$tpl->assign("teams",members($con));
	$tpl->assign("games",generate_options($con));
	$tpl->assign("members",teamMembers($con,IP));
	$tpl->assign("status",getUserRelatedStatusColor($con,IP));
	$tpl->assign("status_option",getUserStatusOption($con,IP));

	/***************************** SETTING *****************************/
	if (getCaptainStatus($con,IP) == 0)
	{
		$tpl->assign("captain","Nein");
	} else {
		$tpl->assign("captain","Ja");
	}
	$tpl->assign("ip",IP);
	$tpl->assign("profil_image",displayProfilImage($con,IP));
	$tpl->assign("nickname",getSingleUsername($con,IP));
	$tpl->assign("team",displaySinglePlayerTeam($con,IP));
	$tpl->assign("player_achievements",displayPlayerAchievements($con,IP));
	$tpl->assign("ac_small",displayAvailableAchievements($con,IP));


	$tpl->display();
}
?>