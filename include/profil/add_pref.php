<?php
    include(dirname(__FILE__,2) . "/init/constant.php");
	require_once INC . 'session.php';
    include(INC . "connect.php");
    include(INIT . "get_parameters.php");
    include(CL . "message_class.php");
    include(CL . "progress_class.php");
    include(CL . "player_class.php");

    $message = new message();
    $achievement = new Progress();
    $player = new Player($con, $_SESSION["player_id"]);
    $game_id = $_REQUEST["checkedGame"];

    if($_REQUEST["state"] == "checked")
    {
        //INSERT new value
        $message->getMessageCode($player->setNewPreference($game_id));
        echo json_encode(array("message" => $message->displayMessage()));
    } else {
        $message->getMessageCode($player->removePreference($game_id));
        echo json_encode(array("message" => $message->displayMessage()));
    }
?>