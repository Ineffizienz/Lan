<?php

// NOTES:
// - Team löschen beenden
header("Content-Type: text/html; charset=utf-8");
error_reporting(E_ALL);

include("admin/files.php");

if(IP == "::1" || "192.168.0.89")
{
	$tpl = new template();
	$tpl->load("admin/admin_skeleton.html");

	$tpl->assign_subtemplate("content","admin/home.html");
	$tpl->assign_subtemplate("menu","admin/admin_menu.html");

	require_once("admin/include/controller.php");
	run_admin_controller($tpl);	

	$tpl->display();
} else {

	$message = new message();
	$message->getMessageCode("ERR_NO_ADMIN");
	echo $message->displayMessage();

}

?>