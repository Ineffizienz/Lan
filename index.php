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

$tpl = new template("skeleton.html");

$title = "Press ALT+F4 to Ragequit!";
$tpl->assign("headline", $title);

$message = new message();

if(!isset($_SESSION["player_id"]))
{
	include 'include/auth/validate_ticket.php';
	
	list($success, $message) = validate_ticket($con);
	if(!$success)
	{
		$tpl->assign_subtemplate('content', 'validate_ticket.html');

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
			$tpl->assign_subtemplate('content', 'reg_name.html');

			$tpl->assign("sir_brummel",$message->displayMessage());

			$tpl->display();
		}
	}
	
	if(!$first_login || $success)
	{
		$player_id = $_SESSION["player_id"];

		$tpl->assign_subtemplate('content', 'index.html');
		$tpl->assign("lantitle",$title);
		$tpl->assign("sir_brummel",$message->displayMessage());
		
		$tpl->assign_subtemplate('menu', 'menu.html');
		$tpl->assign("status",getUserRelatedStatusColor($con,$player_id));
		$tpl->assign("status_option",getUserStatusOption($con,$player_id));
		
		include(INC . "controller.php");
		run_controller($tpl);
		
		$tpl->display();
	}
}
?>