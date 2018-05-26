<?php
    include("init/constant.php");
    include("connect.php");
    include("init/get_parameters.php");

    if(isset($_REQUEST["function"]))
    {
        if($_REQUEST["function"] == "displayPrefs")
        {
            $player_pref = getSinglePlayerPref($con,IP);

            if(empty($player_pref))
            {
                $output = "<i>Du hast deine Präferenzen noch nicht festgelegt.</i>";

                echo $output;
            } else {
                $part = file_get_contents("../template/part/single_pref.html");

                foreach ($player_pref as $pref)
                {
                    $gameInfo = getGameInfoById($con,$pref);
                    if (!isset($output))
                    {
                        $output = str_replace(array("--GAME_ID--","--ICON--","--PREF--"), array($pref,$gameInfo[0]["icon"],$gameInfo[0]["name"]), $part);
                    } else {
                        $output .= str_replace(array("--GAME_ID--","--ICON--","--PREF--"), array($pref,$gameInfo[0]["icon"],$gameInfo[0]["name"]), $part);
                    }
                    
                }

                echo $output;
            }
        }
    }
?>