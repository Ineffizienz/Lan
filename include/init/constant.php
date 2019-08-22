<?php
	
	define("ROOT",dirname(__FILE__,3));

	define("INC",ROOT . "/include/");
	define("AUTH", INC . "/auth/");
	define("INIT",INC . "/init/");

	define("CL",ROOT . "/class/");
	
	define("IMG",ROOT . "/images/");
	define("ICON",IMG . "game_icon/");
	define("BANNER",IMG . "game_banner/");
	define("AC",IMG . "achievements/");

	define("TMP",ROOT . "/template/");
	define("KEY_FOLDER",ROOT . "/key_list/");

	define("IP",$_SERVER["REMOTE_ADDR"]);
?>