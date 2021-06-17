<?php
    include(dirname(__FILE__,2) . "/init/constant.php");
	require_once INC . 'session.php';
    include(INC . "connect.php");
    include(INIT . "get_parameters.php");
    include(CL . "message_class.php");
    include(CL . "player_class.php");

    $message = new message();
    $player = new Player($con, $_SESSION["player_id"]);
    $game_id = $_REQUEST["checkedGame"];
    $items = json_decode(stripslashes($_REQUEST["items"]));

    if($_REQUEST["state"] == "checked")
    {
        $message->getMessageCode($player->setNewPreference($game_id));
        echo json_encode(array("message" => $message->displayMessage(),"items" => $items));
    } else {
        $message->getMessageCode($player->removePreference($game_id));
        echo json_encode(array("message" => $message->displayMessage(),"items" => $items));
    }
?>