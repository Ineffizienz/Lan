<?php //add to index.php --> include("inlude/create_team.php");
include($_SERVER["DOCUMENT_ROOT"] . "/Lan_Git/include/init/constant.php");
include(CL . "message_class.php");
include(CL . "progress_class.php");
include(INC . "connect.php");
include(INC . "function.php");


	$message = new message();
	$achievement = new Progress();

	if(isset($_REQUEST["team"]))
	{
				$t_name = $_REQUEST["team"];
				if ($t_name == "")
				{
					$message->getMessageCode("ERR_NO_TEAM_NAME");
					$achievement->getTrigger($con,IP,"Sir Brummel");
					echo json_encode(array("message" => $message->displayMessage(),"achievement" => $achievement->showAchievement()));

				} else {
					$teams = getTeamNames($con);

					if (!empty($teams) && in_array($t_name,$teams))
					{
						
						$message->getMessageCode("ERR_TEAM_NAME_EXISTS");
						$achievement->getTrigger($con,IP,"Sir Brummel");
						echo json_encode(array("message" => $message->displayMessage(),"achievement" => $achievement->showAchievement()));

					} else {
						mysqli_query($con,"INSERT INTO tm_teamname (name,color) VALUES ('$t_name',NULL)");

						$message->getMessageCode("SUC_CREATE_TEAM");
						echo json_encode(array("message" => $message->displayMessage()));

					}
				}
	}
?>