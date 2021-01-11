<?php
    include(dirname(__FILE__,4) . "/include/init/constant.php");
    include(dirname(__FILE__,3) . "/include/admin_func.php");
    include(INC . "connect.php");
    include(CL . "message_class.php");

    $message = new message();

    $game_id = $_REQUEST["game_id"];

    if(getGameId($con,$game_id))
    {
        $has_table = getHasTableByGameID($con,$game_id);
        if($has_table == "1")
        {
            $sql = "DELETE FROM gamekeys WHERE game_id = '$game_id'";
            if(mysqli_query($con,$sql))
            {
                $sql = "DELETE FROM pref WHERE game_id = '$game_id'";
                if(mysqli_query($con,$sql))
                {
                    $sql = "DELETE FROM games WHERE ID = '$game_id'";
                    if(mysqli_query($con,$sql))
                    {
                        $message->getMessageCode("SUC_ADMIN_DELETE_GAME");
                        echo buildJSONOutput(array($message->displayMessage(),$_REQUEST["p_element"],$_REQUEST["c_element"],0));
                    } else {
                        $message->getMessageCode("ERR_ADMIN_DB");
                        echo buildJSONOutput($message->displayMessage());
                    }
                } else {
                    $message->getMessageCode("ERR_ADMIN_DB");
                    echo buildJSONOutput($message->displayMessage());
                }
            } else {
                $message->getMessageCode("ERR_ADMIN_DB");
                echo buildJSONOutput($message->displayMessage());
            }
        } else {
            $sql = "DELETE FROM games WHERE ID = '$game_id'";
            if(mysqli_query($con,$sql))
            {
                $message->getMessageCode("SUC_ADMIN_DELETE_GAME");
                echo buildJSONOutput(array($message->displayMessage(),$_REQUEST["p_element"],$_REQUEST["c_element"],0));
            } else {
                $message->getMessageCode("ER_ADMIN_DB");
                echo buildJSONOutput($message->displayMessage());
            }
        }
    } else {
        $message->getMessageCode("ERR_ADMIN_GAME_DOES_NOT_EXISTS");
        echo buildJSONOutput($message->displayMessage());
    }
?>