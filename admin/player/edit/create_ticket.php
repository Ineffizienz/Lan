<?php
    include(dirname(__FILE__,4) . "/include/init/constant.php");
    include(INC . "connect.php");
    include(CL . "message_class.php");

    $message = new message();

    if (isset($_REQUEST["player_id"]))
	{
		$player_id = $_REQUEST["player_id"];
		$part_I = mt_rand(0,10) . mt_rand(0,10) . mt_rand(0,10);
		$part_II = substr(md5(microtime()),rand(0,26),3);
		$part_III = mt_rand(0,10) . mt_rand(0,10) . mt_rand(0,10);
		
		$ticket_idClear = $part_I . $part_II . $part_III;
		$ticket_id = sha1($ticket_idClear);
		
		$sql = "UPDATE player SET ticket_id = '$ticket_id' WHERE ID = '$player_id'";

		if(mysqli_query($con,$sql))
		{
			$sql = "UPDATE player SET ticket_active = NULL WHERE ID = '$player_id'";

			if(mysqli_query($con,$sql))
			{
                $message->getMessageCode("SUC_ADMIN_CREATE_TICKET_ID");
                echo json_encode(array("message"=>$message->displayMessage(),"player_id"=>$player_id, "ticket_id"=>$ticket_idClear));

			} else {
                $message->getMessageCode("ERR_ADMIN_DB");
                echo json_encode(array("message"=>$message->displayMessage() . mysqli_error($con)));
			}
		} else {
            $message->getMessageCode("ERR_ADMIN_DB");
            echo json_encode(array("message"=>$message->displayMessage() . mysqli_error($con)));
		}
	}
?>