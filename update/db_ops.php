<?php

function defineEvents($con)
{
    return t_setUpMatches($con);
}

function t_setUpMatches($con)
{
    $sql = "USE project_ziphon;
    SET @active_trigger = 0;

    DROP TRIGGER IF EXISTS t_setMatches;
    delimiter //
    CREATE TRIGGER t_setMatches AFTER UPDATE ON tm
    FOR EACH ROW
    thisTrigger: BEGIN
        DECLARE tm_id int;
        DECLARE player_id int;
        DECLARE team_1 int;
        DECLARE team_2 int;
        DECLARE last_paarung_id int;
        DECLARE match_id int;
        DECLARE matches_id int;
        DECLARE cursor_done int DEFAULT 0;
        DECLARE player_cursor CURSOR FOR SELECT player_id FROM tm_gamerslist WHERE tm_id = @tm_id ORDER BY RAND();
        DECLARE CONTINUE HANDLER FOR NOT FOUND SET cursor_done = 1;
        
        IF @active_trigger = 1 THEN
			LEAVE thisTrigger;
		END IF;

        IF OLD.tm_locked <=> NEW.tm_locked THEN

            SET tm_id = (SELECT ID FROM tm);
            
            OPEN player_cursor;

            FETCH NEXT FROM player_cursor INTO player_id;
            WHILE NOT cursor_done DO

                SET paarung = (SELECT COUNT(*) FROM tm_paarung);

                IF paarung = 0 THEN
                    INSERT INTO tm_paarung (team_1, tournament) VALUES (@player_id, @tm_id);
                ELSE
                    
                    SET team_2 = (SELECT team_2 FROM tm_paarung ORDER BY ID DESC LIMIT 1);

                    IF NOT team_2 THEN # Nur, wenn die zweite team_id nicht vergeben ist, sollen entsprechende Spiele etc. aufgesetzt werden
                        UPDATE tm_paarung SET team_2 = @player_id WHERE tm_paarung = @last_paarung_id;
                        
                        INSERT INTO tm_match (result_team1, result_team2) VALUES (NULL, NULL);

                        SET match_id = (SELECT ID FROM tm_match ORDER BY ID DESC LIMIT 1);

                        INSERT INTO tm_matches (match_id) VALUES (@match_id);

                        SET matches_id = (SELECT ID FROM tm_matches WHERE match_id = @match_id);

                        UPDATE tm_paarung SET matches_id = @matches_id WHERE ID = @last_paarung_id;

                    ELSE # ansonsten erfolgt nur die Anlage einer neuen Paarung mit der ersten team_id
                        INSERT INTO tm_paarung (team_1, tournament) VALUES (@player_id, @tm_id);
                    END IF;
                END IF;

                FETCH player_cursor INTO player_id;
            END WHILE;
            CLOSE player_cursor;
        END IF;

        
    END; //
    
    delimiter ;";

    if(mysqli_query($con,$sql))
    {
        echo "Der Trigger <i>t_setMatches</i> wurde erfolgreich gesetzt.<br>";
    } else {
        echo "Es ist ein Fehler beim Trigger <i>t_setMatches</i> aufgetreten: " . mysqli_error($con) . "<br>";
    }
}
?>