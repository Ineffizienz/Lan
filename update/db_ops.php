<?php

function defineEvents($con)
{
    return t_setUpMatches($con);
}

function t_setUpMatches($con)
{
    $sql = "USE project_ziphon;

    DROP TRIGGER IF EXISTS t_setMatches;
    delimiter //
    CREATE TRIGGER t_setMatches AFTER UPDATE ON tm
    FOR EACH ROW
    thisTrigger: BEGIN
        DECLARE tm_id int;
        DECLARE player_id_gl int;
        DECLARE team_1 int;
        DECLARE team_2 int;
        DECLARE paarung int;
        DECLARE last_paarung_id int;
        DECLARE match_id int;
        DECLARE matches_id int;
        DECLARE trigger_disabled int;
        DECLARE count_var int;
        declare error_time varchar(255);
        DECLARE player_var varchar(255);
        DECLARE tm_var varchar(255);
        DECLARE msg_var varchar(255);
        DECLARE cursor_var varchar(255);
        DECLARE cursor_done int DEFAULT FALSE;
        DECLARE tm_id_table varchar(255);
		DECLARE player_cursor CURSOR FOR (SELECT ID FROM tm_gamerslist WHERE (tm_gamerslist.tm_id = NEW.ID) AND (NEW.tm_locked) ORDER BY RAND());
		DECLARE CONTINUE HANDLER FOR NOT FOUND SET cursor_done = TRUE;
		DECLARE EXIT HANDLER FOR SQLEXCEPTION
		BEGIN
            GET DIAGNOSTICS CONDITION 1 @sqlstate = RETURNED_SQLSTATE, 
				@errno = MYSQL_ERRNO, @text = MESSAGE_TEXT;
				SET @full_error = CONCAT("ERROR ", @errno, " (", @sqlstate, "): ", @text);
				select CURRENT_TIMESTAMP into error_time;
				Insert into log_trigger_error(error_type, error_statement) values (error_time, @full_error); 
                
		END; 
        DECLARE EXIT HANDLER FOR SQLWarning
		BEGIN
            GET DIAGNOSTICS CONDITION 1 @sqlstate = RETURNED_SQLSTATE, 
				@errno = MYSQL_ERRNO, @text = MESSAGE_TEXT;
				SET @full_error = CONCAT("Warning ", @errno, " (", @sqlstate, "): ", @text);
				select CURRENT_TIMESTAMP into error_time;
				Insert into log_trigger_error(error_type, error_statement) values (error_time, @full_error); 
                
		END;

           
        #SET trigger_disabled = (SELECT trigger_disabled FROM trigger_variables WHERE trigger_name = 't_setMatches');
        
        IF trigger_disabled = 1 THEN
			LEAVE thisTrigger;
		END IF;

        IF NEW.tm_locked != OLD.tm_locked THEN # Überprüft, ob sich die Spalte tm_locked geändert hat

            SET tm_id = (SELECT ID FROM tm ORDER BY ID DESC LIMIT 1);
            
            SET count_var = 1;
            
            OPEN player_cursor;
            
            cursor_loop: LOOP
				FETCH player_cursor INTO player_id_gl;

                IF cursor_done THEN
					LEAVE cursor_loop;
				END IF;
                                 
				SET last_paarung_id = (SELECT ID FROM tm_paarung ORDER BY tm_paarung.ID DESC LIMIT 1);
				
				IF ((count_var % 2) = 0) THEN # Nur, wenn die zweite team_id nicht vergeben ist, sollen entsprechende Spiele etc. aufgesetzt werden
					UPDATE tm_paarung SET tm_paarung.team_2 = player_id_gl WHERE tm_paarung.ID = last_paarung_id;
					
					INSERT INTO tm_match (result_team1, result_team2) VALUES (NULL, NULL);

					SET match_id = (SELECT ID FROM tm_match ORDER BY tm_match.ID DESC LIMIT 1);

					INSERT INTO tm_matches (match_id) VALUES (match_id);

					SET matches_id = (SELECT ID FROM tm_matches WHERE tm_matches.match_id = match_id);

					UPDATE tm_paarung SET tm_paarung.matches_id = matches_id WHERE tm_paarung.ID = last_paarung_id;

				ELSE # ansonsten erfolgt nur die Anlage einer neuen Paarung mit der ersten team_id
					INSERT INTO tm_paarung (team_1, tournament) VALUES (player_id_gl, tm_id);
				END IF;

                SET count_var = count_var + 1;
            END LOOP;
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