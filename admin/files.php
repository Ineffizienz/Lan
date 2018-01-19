<?php
	// Konstanten für Lindas System
	
	define("ROOT_INCLUDE", $_SERVER["DOCUMENT_ROOT"] . "/Lan_Git/include/");
	define("ROOT_CLASS", $_SERVER["DOCUMENT_ROOT"] . "/Lan_Git/class/");

	// GENERAL
	require_once(ROOT_INCLUDE . "init/constant.php");
	require_once(ROOT_INCLUDE . "connect.php");
	require_once("include/admin_func.php");
	require_once(ROOT_INCLUDE . "init/get_parameters.php");
	require_once("admin/include/controller.php");
	require_once(ROOT_CLASS . "template_class.php");
	require_once(ROOT_CLASS . "message_class.php");
	require_once(ROOT_CLASS . "achievement_class.php");

	// OVERVIEW
	require_once("admin/overview/key_status.php");
	require_once("admin/overview/team_status.php");

	// PLAYER
	require_once("admin/player/view/player_settings_view.php");

?>