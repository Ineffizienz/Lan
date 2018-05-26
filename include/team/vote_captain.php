<?php
	/* #UPDATE DB
		$sql = "CREATE TABLE tmp_voting (ID INT(11) PRIMARY KEY AUTO_INCREMENT NOT NULL, team_id INT(11) NOT NULL, user_id INT(11) NOT NULL, voted_user INT(11) NOT NULL)";
		if(mysqli_query($con,$sql))
		{
			echo "Die Tabelle <i>tmp_voting</i> wurde erfolgreich erstellt.<br>";
		} else {
			echo "Beim Erstellen der Tabelle <i>tmp_voting</i> ist ein Fehler aufgetreten: " . mysqli_error($con) . "<br>";
		}
	*/
	
	include($_SERVER["DOCUMENT_ROOT"] . "/Project_Ziphon/include/init/constant.php");
	include(INC . "connect.php");
	include(INC . "function.php");
	include(CL . "message_class.php");
	
	$message = new message();
	
	$team_id = $_REQUEST["teamID"];
	$user_id = $_REQUEST["userID"];
	$voted_user = $_REQUEST["votedID"];
	
		$result = mysqli_query($con,"SELECT user_id FROM tmp_voting");
		while($row=mysqli_fetch_array($result))
		{
			$user[] = $row["user_id"];
		}
		
		if(in_array($user_id,$user))
		{
			$message->getMessageCode("ERR_VOTED");
			echo json_encode(array("message" => $message->displayMessage()));
		} else {
			$sql = "INSERT INTO tmp_voting (team_id,user_id,voted_user) VALUES ('$team_id','$user_id','$voted_user')";
		if(mysqli_query($con,$sql))
		{
				$message->getMessageCode("SUC_VOTING_COMPLETE");
				echo json_encode(array("message" => $message->displayMessage()));
			} else {
				$message->getMessageCode("ERR_DB");
				echo json_encode(array("message" => $message->displayMessage()));
			}	
		}
	
		
?>