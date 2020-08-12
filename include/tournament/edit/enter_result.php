<?php
    include(dirname(__FILE__,4) . "/include/init/constant.php");
	require_once INC . 'session.php';
    include(INC. "connect.php");
    include(INC. "function.php");
    include(CL . "message_class.php");
    include(CL . "player_class.php");

    $message = new message();
    $player = new Player($con,$_SESSION["player_id"]);
    $tm_id = $_REQUEST["tm_id"];
    $pair_id = $_REQUEST["pair_id"];

    $gamerslist_id = getGamerslistIdByPlayerId($con,$player->getPlayerId(),$tm_id);
    if(getGamerslistIdFromPair($con,$gamerslist_id,$pair_id))
    {
        $result_1 = $_REQUEST["result_1"];
        $result_2 = $_REQUEST["result_2"];
        
        $lock_time = getMatchLockTime($con,$pair_id);
        $current_time = date("Y-m-d H:i:s", strtotime("now"));

        if(!(empty($lock_time)) && (strtotime($current_time) > strtotime($lock_time)))
        {
            $message->getMessageCode("ERR_MATCH_LOCKED");
            echo json_encode(array("message"=>$message->displayMessage()));
        } elseif (empty($lock_time) || ($lock_time > $current_time)) {

            $message_code = matchResultHandling($con,$pair_id,$result_1,$result_2);
            $message->getMessageCode($message_code);
            echo json_encode(array("message"=>$message->displayMessage()));
        }
               
    } else {
        $message->getMessageCode("ERR_INCORRECT_MATCH");
        echo json_encode(array("message"=>$message->displayMessage() . mysqli_error($con)));
    }
?>