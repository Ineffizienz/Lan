<?php
    include(dirname(__FILE__,2) . "/include/connect.php");

    $sql = "CREATE TABLE pref_temp (ID INT(11) PRIMARY KEY AUTO_INCREMENT NOT NULL, player_id INT(11) NOT NULL, game_id INT(11) NOT NULL, UNIQUE (player_id,game_id))";
    if(mysqli_query($con,$sql))
    {
        echo "Die Tabelle <i>pref_temp</i> wurde erfolgreich erstellt.<br>";
        $result = mysqli_query($con, "SELECT user_id, preferences FROM pref");
        while($row=mysqli_fetch_array($result))
        {
            $prefs[] = $row;
        }

        foreach ($prefs as $pref)
        {
            $player_id = $pref["user_id"];
            $single_prefs = explode(",",$pref["preferences"]);
            foreach ($single_prefs as $step_pref)
            {
                $sql = "INSERT INTO pref_temp (player_id,game_id) VALUES ('$player_id','$step_pref')";
                if(mysqli_query($con,$sql))
                {
                    echo "Dem Spieler <i>" . $player_id . "</i> wurde erfolgreich die das Spiel mit der ID <i>" . $step_pref . "</i> zugeordnet.<br>";
                } else {
                    echo "Bei der Zuordnung <i>" . $player_id . " - " . $step_pref . "</i> ist ein Fehler aufgetreten:<b> " . mysqli_error($con) . "</b><br>";
                }
            }
        }
        $delete_sql = "DROP TABLE pref";
        if(mysqli_query($con,$delete_sql))
        {
            echo "Die Tabelle <i>pref</i> wurde erfolgreich gelöscht.<br>";
            $rename_sql = "RENAME TABLE pref_temp TO pref";
            if(mysqli_query($con,$rename_sql))
            {
                echo "Die Tabelle <i>pref_temp</i> wurde erfolgreich in <b>pref</b> umbenannt.<br>";
            } else {
                echo "Beim Umbenennen der Tabelle <i>pref_temp</i> ist ein Fehler aufgetreten: <b>" . mysqli_error($con) . "</b><br>";
            }
        } else {
            echo "Beim Löschen der Tabelle <i>pref</i> ist ein Fehler aufgetreten: <b>" . mysqli_error($con) . "</b><br>";
        }
    } else {
        echo "Beim Erstellen der Tabelle <i>pref_temp</i> ist ein Fehler aufgetreten: <b>" . mysqli_error($con) . "</b><br>";
    }
?>