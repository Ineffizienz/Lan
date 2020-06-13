<?php

function addRealNameToPlayer($con)
{
    if(mysqli_num_rows(mysqli_query($con, "SHOW COLUMNS FROM `player` LIKE 'real_name';")) == 1)
	echo "Spalte bereits vorhanden!<br>";	
    else
    {
        if(mysqli_query($con, "ALTER TABLE `player` ADD `real_name` TEXT CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL AFTER `name`;"))
            echo "Spalte erfolgreich hinzugefügt!<br>";	
    }
}

function changePlayerCountDataType($con)
{
    $sql = "ALTER TABLE tm CHANGE `player_count` `player_count` INT(11) NOT NULL";

    if(mysqli_query($con,$sql))
    {
        echo "Die Spalte <i>player_count</i> wurde erfolgreich auf <i>NOT NULL</i> umgestellt.<br>";
    } else {
        echo mysqli_error($con) . $sql . "<br>";
    }
}

function addMatchResultToPaarung($con)
{
    if(mysqli_num_rows(mysqli_query($con, "SHOW COLUMNS FROM `tm_paarung` LIKE 'result_team1';")) == 1)
    {
        echo "Spalte bereits vorhanden.<br>";
    } else {
        if(mysqli_query($con, "ALTER TABLE `tm_paarung` ADD `result_team1` INT(11) NULL AFTER `ressource`"))
        {
            echo "Die Spalte <i>result_team1</i> wurde erfolgreich hinzugefügt.<br>";
            if(mysqli_query($con,"ALTER TABLE `tm_paarung` ADD `result_team2` INT(11) NULL AFTER `result_team1`"))
            {
                echo "Die Spalte <i>result_team2</i> wurde erfolgreich hinzugefügt.<br>";
                if(mysqli_query($con,"ALTER TABLE `tm_paarung` DROP COLUMN `result`"))
                {
                    echo "Die Spalte <i>result</i> wurde erfolgreich entfernt.<br>";
                } else {
                    echo mysqli_error($con) . $sql . "<br>";
                }
            } else {
                echo mysqli_error($con) . $sql . "<br>";
            }
        } else {
            echo mysqli_error($con) . $sql . "<br>";
        }
    }
}

function transferMatchData($con)
{
    if(mysqli_num_rows(mysqli_query($con,"SHOW COLUMNS FROM `tm_paarung` LIKE 'matches_id';")) == 1)
    {
        $result = mysqli_query($con,"SELECT ID FROM tm WHERE tm_locked = '1'");
        while($row=mysqli_fetch_assoc($result))
        {
            $tm_id[] = $row["ID"];
        }

        foreach ($tm_id as $tm)
        {
            $result = mysqli_query($con,"SELECT tm_matches.match_id FROM tm_matches INNER JOIN tm_paarung ON tm_paarung.matches_id = tm_matches.ID WHERE tm_paarung.tournament = '$tm'");
            while($row=mysqli_fetch_assoc($result))
            {
                $match_ids[] = $row["match_id"];
            }
        }

        foreach ($match_ids as $match_id)
        {
            $result = mysqli_query($con,"SELECT tm_matches.ID, tm_match.result_team1, tm_match.result_team2 FROM tm_match INNER JOIN tm_matches ON tm_matches.match_id = tm_match.ID WHERE tm_match.ID = '$match_id'");
            while($row=mysqli_fetch_assoc($result))
            {
                $results[] = $row;
            }
        }

        foreach ($results as $result)
        {
            $result_team1 = $result["result_team1"];
            $result_team2 = $result["result_team2"];
            $matches_id = $result["ID"];
            if(mysqli_query($con,"UPDATE tm_paarung SET result_team1 = '$result_team1', result_team2 = '$result_team2' WHERE matches_id = '$matches_id'"))
            {
                echo "Daten des Matches " . $matches_id . " erfolgreich eingetragen.<br>";
            } else {
                echo mysqli_error($con);
            }
        }

        if(mysqli_query($con,"ALTER TABLE `tm_paarung` DROP COLUMN `matches_id`"))
        {
            echo "Spalte <i>matches_id</i> erfolgreich gelöscht.<br>";
        } else {
            echo mysqli_error($con);
        }
    } else {
        echo mysqli_error($con);
    }
}

function deleteTmMatches($con)
{
    if(mysqli_query($con,"SHOW TABLES LIKE 'tm_matches'"))
    {
        if(mysqli_query($con,"DROP TABLE tm_matches"))
        {
            echo "Tabelle <i>tm_matches</i> erfolgreich gelöscht.<br>";
        } else {
            echo mysqli_error($con);
        }
    } else {
        echo "Tabelle bereits gelöscht.<br>";
    }
}

function deleteTmMatch($con)
{
    if(mysqli_query($con,"SHOW TABLES LIKE 'tm_match'"))
    {
        if(mysqli_query($con,"DROP TABLE tm_match"))
        {
            echo "Tabelle <i>tm_match</i> erfolgreich gelöscht.<br>";
        } else {
            echo mysqli_error($con);
        }
    } else {
        echo "Tabelle bereits gelöscht.<br>";
    }
}

function addMatchLockedToPaarung($con)
{
    if(mysqli_num_rows(mysqli_query($con,"SHOW COLUMNS FROM `tm_paarung` LIKE 'match_locked';")) == 1)
    {
        echo "Die Spalte <i>match_locked</i> existiert bereits.<br>";
    } else {
        if(mysqli_query($con,"ALTER TABLE `tm_paarung` ADD `match_locked` DATETIME NULL AFTER `result_team2`"))
        {
            echo "Die Spalte <i>match_locked</i> wurde erfolgreich hinzugefügt.<br>";
        } else {
            echo mysqli_error($con);
        }
    }
}

function setTournamentPlayerIdUnique($con)
{
    if(mysqli_query($con,"ALTER TABLE tm_gamerslist CHANGE `tm_id` `tm_id` INT(11) NOT NULL"))
    {
        echo "Die Spalte <i>tm_id</i> kann nicht mehr NULL gesetzt werden.<br>";
        if(mysqli_query($con,"DELETE FROM tm_gamerslist WHERE ID = '15'")
        {
            if(mysqli_query($con,"ALTER TABLE tm_gamerslist ADD CONSTRAINT UC_Gamer UNIQUE (tm_id,player_id)"))
            {
                echo "Die Spalten <i>tm_id</i> und <i>player_id</i> sind jetzt UNIQUE.<br>";
            } else {
                echo mysqli_error($con);
            }
        }
        
    } else {
        echo mysqli_error($con);
    }
}


?>