<?php
error_reporting(E_ALL);
session_set_cookie_params(3600*24*7); //set session cookie lifetime to 7 days. Don't forget to change the server config, too! - change session.gc_maxlifetime to 259200 secs
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

$tpl = new template();

$title = "Du nicht nehmen Kerze!";
$message = new message();


if(!isset($_SESSION["player_id"]))
{
	include 'include/auth/validate_ticket.php';
	
	list($success, $message) = validate_ticket($con);
	if(!$success)
	{
		$tpl->load("validate_ticket.html");
		$tpl->assign("headline",$title);

		$tpl->assign("sir_brummel",$message->displayMessage());

		$tpl->display();
	}
}
if(isset($_SESSION["player_id"])) //can be set by the validate_Ticket()-function
{
	$first_login = getFirstLoginById($con, $_SESSION["player_id"]);
	
	$success = false;
	if($first_login)
	{
		include 'include/auth/reg_name.php';
	
		list($success, $message) = reg_name($con, $message);
		if(!$success)
		{
			$tpl->load("reg_name.html");
			$tpl->assign("headline",$title);

			$tpl->assign("sir_brummel",$message->displayMessage());

			$tpl->display();
		}
	}
	
	if(!$first_login || $success)
	{
		$player_id = $_SESSION["player_id"];

		include(INC . "controller.php");

		$tpl->load("index.html");

		$tpl->assign("sir_brummel",$message->displayMessage());
		
		$tpl->assign("headline",$title);
		$tpl->assign("lantitle",$title);
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
		$tpl->assign("members",teamMembers($con,$player_id));
		$tpl->assign("status",getUserRelatedStatusColor($con,$player_id));
		$tpl->assign("status_option",getUserStatusOption($con,$player_id));

		/******************************WOW-Server **************************/
		$tpl->assign("wow_account",selectWowAccount($con,$con_wow,$con_char,$player_id));
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
}
?>