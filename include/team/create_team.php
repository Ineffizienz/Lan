<?php //add to index.php --> include("inlude/create_team.php");
session_start();
include(dirname(__FILE__,2) . "/init/constant.php");
include(CL . "message_class.php");
include(CL . "progress_class.php");
include(INC . "connect.php");
include(INC . "function.php");


	$message = new message();
	$achievement = new Progress();
	$player_id = $_SESSION["player_id"];

	if(isset($_REQUEST["team"]))
	{
		$t_name = $_REQUEST["team"];

		if ($t_name == "")
		{
			$message->getMessageCode("ERR_NO_TEAM_NAME");
			$achievement->getTrigger($con,$player_id,"Sir Brummel");
			echo json_encode(array("message" => $message->displayMessage(),"achievement" => $achievement->showAchievement()));

		} else {
			if(strstr($t_name,"\\"))
			{
				$t_name = addcslashes($t_name, "\\");
			}
			$teams = getTeamNames($con);

			if (!empty($teams) && in_array($t_name,$teams))
			{
				
				$message->getMessageCode("ERR_TEAM_NAME_EXISTS");
				$achievement->getTrigger($con,$player_id,"Sir Brummel");
				echo json_encode(array("message" => $message->displayMessage(),"achievement" => $achievement->showAchievement()));

			} else {
				mysqli_query($con,"INSERT INTO tm_teamname (name,color) VALUES ('$t_name',NULL)");

				$message->getMessageCode("SUC_CREATE_TEAM");
				echo json_encode(array("message" => $message->displayMessage()));

			}
		}
	}
?>