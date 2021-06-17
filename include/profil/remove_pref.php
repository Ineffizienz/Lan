<?php
    include(dirname(__FILE__,2) . "/init/constant.php");
	require_once INC . 'session.php';
    include(INC . "connect.php");
    include(INIT . "get_parameters.php");
    include(CL . "message_class.php");
    include(CL . "player_class.php");

    $message = new message();
    $player = new Player($con, $_SESSION["player_id"]);
    $game_id = $_REQUEST["game_id"];
    $items = json_decode(stripslashes($_REQUEST["items"]));

    $message->getMessageCode($player->removePreference($game_id));
    echo json_encode(array("message" => $message->displayMessage(),"items" => $items));
?>