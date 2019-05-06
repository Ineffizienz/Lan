<?php
error_reporting(E_ALL);
session_start();

/*************** NOTES ********************/
/*
	- Authentifizierung im Netzwerk prüfen
*/

header("Content-Type: text/html; charset=utf-8");

include ("include/init/constant.php");
require_once(CL . "template_class.php");
require_once(CL . "message_class.php");
require_once(CL . "achievement_class.php");

require_once(INC . "connect.php");
require_once(INC . "function.php");

//$fl = getFirstLoginByIp($con,IP);
$ticket_status = getTicketStatus($con,IP);
$message = new message();
if(!isset($_SESSION["player_id"]))
{
	//$message->getMessageCode("ERR_NO_USER_NAME");

	$tpl = new template();
	$tpl->load("validate_ticket.html");

	$tpl->assign("sir_brummel",$message->displayMessage());

	$tpl->display();

} elseif (empty($ticket_status) && ((IP !== "192.168.0.89") && (IP !== "192.168.0.95") && (IP !== "::1"))) {

	$tpl = new template();
	$tpl->load("reg_name.html");

	$tpl->assign("sir_brummel",$message->displayMessage());

	$tpl->display();

} else {
	// Online-Testing
	/*$ip = IP;
	mysqli_query($con,"UPDATE player SET ip = '$ip' WHERE ID = '38'");*/
	$player_id = $_SESSION["player_id"];
	
	include(INC . "controller.php");

	$tpl = new template();

	$tpl->load("index.html");



	$tpl->assign("headline","Du nicht nehmen Kerze!");
	$tpl->assign("lantitle","Du nicht nehmen Kerze!");
	$tpl->assign("menu",build_content("menu.html"));

	if (isset($content))
	{
		$tpl->assign("content",$content);
	}

	if (isset($settings))
	{
		$tpl->assign("settings",$settings); // controller.php
	}

	$tpl->assign("teams",members($con));
	$tpl->assign("games",generate_options($con));
	$tpl->assign("members",teamMembers($con,IP));
	$tpl->assign("status",getUserRelatedStatusColor($con,IP));
	$tpl->assign("status_option",getUserStatusOption($con,IP));

	/******************************WOW-Server **************************/
	$tpl->assign("wow_account",selectWowAccount($con,$con_wow,$con_char,IP));
	$tpl->assign("realm",getRealmName($con_wow));
	$tpl->assign("server_on",displayServerStatus($con_wow));


	/***************************** TUNRIERE *****************************/

	/***************************** SETTING *****************************/

	$tpl->assign("ip",IP);
	$tpl->assign("profil_image",displayProfilImage($con, $player_id));
	$tpl->assign("nickname",getSingleUsername($con, $player_id));
	$tpl->assign("pref",displayPlayerPrefs($con, $player_id));
	$tpl->assign("checkbox_container",createCheckbox($con, $player_id));
	$tpl->assign("team",displaySinglePlayerTeam($con, $player_id));
	$tpl->assign("captain",displayCaptain($con, $player_id));
	$tpl->assign("t_members",displayPlayerTeamMember($con, $player_id));
	$tpl->assign("player_achievements",displayPlayerAchievements($con, $player_id));
	$tpl->assign("ac_small",displayAvailableAchievements($con, $player_id));


	$tpl->display();
}
?>