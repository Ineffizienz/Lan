<?php
    session_start();
    include(dirname(__FILE__,4) . "include/init/constant.php");
    include(INC. "connect.php");
    include(INC. "function.php");
    include(CL . "message_class.php");

    $message = new message();
    $player_id = $_SESSION["player_id"];
    $tm_id = $_REQUEST["tm_id"];

    $gamerslist_id = getGamerslistIdByPlayerId($con,$player_id);
    if(getPairIdByGamerslistId($con,$gamerslist_id,$tm_id))
    {
        $matches_id = getSingleMatchesIdFromPaarung($con,$gamerslist_id,$tm_id);
        $match_id = getMatchIdFromMatches($con,$matches_id);
        $result_1 = $_REQUEST["result_1"];
        $result_2 = $_REQUEST["result_2"];

        $sql = "UPDATE tm_match SET result_team1 = '$result_1' AND result_team2 = '$result_2' WHERE ID = '$match_id";
        if(mysqli_query($con,$sql))
        {
            $message->getMessageCode("SUC_ENTER_RESULT");
            echo json_encode($message->displayMessage());
        } else {
            $message->getMessageCode("ERR_DB");
            echo json_encode(array($message->displayMessage() . mysqli_error($con)));
        }
    } else {
        $message->getMessageCode("ERR_INCORRECT_MATCH");
        echo json_encode(array($message->displayMessage() . mysqli_error($con)));
    }
?>