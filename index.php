<?php
error_reporting(E_ALL);

/*************** NOTES ********************/
/*
	- Authentifizierung im Netzwerk prüfen
*/

header("Content-Type: text/html; charset=utf-8");

include ("include/init/constant.php");
require_once INC . 'session.php';
require_once(CL . "template_class.php");
require_once(CL . "message_class.php");
require_once(CL . "achievement_class.php");
require_once(CL . "player_class.php");

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
	$player = new Player($con,$_SESSION["player_id"]);

	$display_name_reg = $player->getPlayerFirstLogin() || $player->getPlayerRealname() == '';
	
	$success = false;
	if($display_name_reg)
	{
		include 'include/auth/reg_name.php';
	
		list($success, $message) = reg_name($con, $message, $player);
		if(!$success)
		{
			$tpl->assign_subtemplate('content', 'reg_name.html');
			$tpl->assign("name",$player->getPlayerUsername());
			$tpl->assign("realname",$player->getPlayerRealname());

			$tpl->assign("sir_brummel",$message->displayMessage());

			$tpl->display();
		}
	}
	
	if(!$display_name_reg || $success)
	{		
		
		$tpl->assign_subtemplate('content', 'index.html');
		$tpl->assign("lantitle",$title);
		$tpl->assign("sir_brummel",$message->displayMessage());
		
		$tpl->assign_subtemplate('menu', 'menu.html');
		$tpl->assign("status",getUserRelatedStatusColor($con,$player));
		$tpl->assign("status_option",getUserStatusOption($con,$player));
		
		include(INC . "controller.php");
		run_controller($tpl, $player);
		
		$tpl->display();
	}
}
?>