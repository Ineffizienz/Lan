<?php
	
	define("ROOT",dirname(__FILE__,3));

	define("INC",ROOT . "/include/");
	define("AUTH", INC . "/auth/");
	define("INIT",INC . "/init/");

	define("CL",ROOT . "/class/");


	define("IP",$_SERVER["REMOTE_ADDR"]);
?>