<?php
	
	define("ROOT",dirname(__FILE__,3));

	define("INC",ROOT . "/include/");
	define("AUTH", INC . "/auth/");
	define("INIT",INC . "/init/");

	define("CL",ROOT . "/class/");
	
	define("IMG",ROOT . "/images/");
	define("ICON",IMG . "game_icon/");
	define("AC",IMG . "achievements/");
	define("BANNER",IMG . "tm_banner/");


	define("IP",$_SERVER["REMOTE_ADDR"]);
?>