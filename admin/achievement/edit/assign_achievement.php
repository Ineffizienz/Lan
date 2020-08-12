<?php
	include(dirname(__FILE__,4) . "/include/init/constant.php");
	include(dirname(__FILE__,3) . "/include/admin_func.php");
	include(CL . "achievement_class.php");
	include(CL . "message_class.php");
	include(CL . "player_class.php");
	include(INC . "connect.php");

	$message = new message();
	$ac = new Achievement($con);

	if(isset($_REQUEST["ac_id"]))
	{
		if(isset($_REQUEST["u_id"]))
		{
			$ac_id = $_REQUEST["ac_id"];
			$player = new Player($con,$_REQUEST["u_id"]);

			if (in_array($ac_id,$player->getPlayerAchievements()))
			{
				$message->getMessageCode("ERR_ADMIN_AC_ALLREADY_GIVEN");
				echo buildJSONOutput($message->displayMessage());
			} else {
				$message->getMessageCode($ac->assignNewAchievementAdmin($player->getPlayerId(),$ac_id));
			}

		} else {
			$message->getMessageCode("ERR_ADMIN_MISSING_PLAYER");
			echo buildJSONOutput($message->displayMessage());
		}
	} else {
		$message->getMessageCode("ERR_ADMIN_MISSING_AC");
		echo buildJSONOutput($message->displayMessage());
	}
?>