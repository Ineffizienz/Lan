<?php

include(dirname(__FILE__,2) . "/include/connect.php");
include("db_ops.php");

defineEvents($con);

// Update 1.1
/*
if(mysqli_query($con,$sql))
{
	echo "Die Tabelle <i>status_name</i> wurde erfolgreich erstellt.<br>";
	$sql = "INSERT INTO status_name (status_id,status_name) VALUES ('1','Online')";
	if(mysqli_query($con,$sql))
	{
		echo "Status <i>Online</i> erfolgreich hinzugefügt.<br>";
		$sql = "INSERT INTO status_name (status_id,status_name) VALUES ('2','Schlafend')";
		if(mysqli_query($con,$sql))
		{
			echo "Status <i>Schlafend</i> erfolgreich hinzugefügt.<br>";
			$sql = "INSERT INTO status_name (status_id,status_name) VALUES ('3','Essen')";
			if(mysqli_query($con,$sql))
			{
				echo "Status <i>Essen</i> erfolgreich hinzugefügt.<br>";
				$sql = "INSERT INTO status_name (status_id,status_name) VALUES ('4','Zerstört')";
				if(mysqli_query($con,$sql))
				{
					echo "Status <i>Zerstört</i> erfolgreich hinzugefügt.<br>";
					$sql = "INSERT INTO status_name (status_id,status_name) VALUES ('5','Offline')";
					if(mysqli_query($con,$sql))
					{
						echo "Status <i>Offline</i> erfolgreich hinzugefügt.<br>";
					} else {
						echo "Beim Erstellen des Status <i>Offline</i> ist ein Fehler aufgetreten: " . mysqli_error($con) . "<br>";
					}
				} else {
					echo "Beim Erstellen des Status <i>Zerstört</i> ist ein Fehler aufgetreten: " . mysqli_error($con) . "<br>";
				}
			} else {
				echo "Beim Erstellen des Status <i>Essen</i> ist ein Fehler aufgetreten: " . mysqli_error($con) . "<br>";
			}
		} else {
			echo "Beim Erstellen des Status <i>Schlafend</i> ist ein Fehler aufgetreten: " . mysqli_error($con) . "<br>";
		}
	} else {
		echo "Beim Erstellen des Status <i>Online</i> ist ein Fehler aufgetreten: " . mysqli_error($con) . "<br>";
	}
} else {
	echo "Beim Erstellen der Tabelle <i>status_name</i> ist ein Fehler aufgetreten: " . mysqli_error($con) . "<br>";
}*/

// Update 1.3
/*
$result = mysqli_query($con,"SELECT raw_name FROM games");
while($row=mysqli_fetch_array($result))
{
	$table_games[] = $row["raw_name"];
}

foreach ($table_games as $game)
{
	$sql = "UPDATE games SET has_table = '1' WHERE raw_name = '$game'";
	if(mysqli_query($con,$sql))
	{
		echo "Daten erfolgreich aktualisiert.<br>";
	} else {
		echo "Bei den Daten des Spiels " . $game  . " ist ein Fehler aufgetreten: " . $mysqli_error($con);
	}
}*/

// Update 1.5 /Lan 2020
// Das array ist aufgebaut nach dem Muster: Tabellenname, alter Tabellenname, Spaltenname, alter Spaltenname, SQL-Statement
$sql_statements = array(
	### - CREATE-STATMENTS - ###
		// Update 1.1
		array("tbl_name"=>"status_name","tbl_old"=>"0","clm_name"=>"0","clm_old"=>"0","statement"=>"CREATE TABLE status_name (ID INT(11) PRIMARY KEY AUTO_INCREMENT NOT NULL, status_id INT(11) NOT NULL, status_name VARCHAR(255) NOT NULL)"),
		// Update 1.2
		array("tbl_name"=>"pref","tbl_old"=>"0","clm_name"=>"0","clm_old"=>"0","statement"=>"CREATE TABLE pref (ID INT(11) PRIMARY KEY AUTO_INCREMENT NOT NULL, user_id INT(11) NOT NULL, preferences VARCHAR(1024) CHARSET utf8mb4 NULL)"),

		// Update 1.4
		array("tbl_name"=>"ticket_id","tbl_old"=>"0","clm_name"=>"0","clm_old"=>"0","statement"=>"CREATE TABLE ticket_id (ID INT(11) PRIMARY KEY AUTO_INCREMENT NOT NULL, ticket_id VARCHAR(255) CHARSET utf8mb4 NOT NULL)"),

		// Update 1.5
		array("tbl_name"=>"tm","tbl_old"=>"0","clm_name"=>"0","clm_old"=>"0","statement"=>"CREATE TABLE tm (ID INT(11) PRIMARY KEY AUTO_INCREMENT NOT NULL, game_id INT(11) NOT NULL, mode INT(11) NOT NULL, mode_details INT(11) NULL, player_count INT (11) NULL, tm_period_id INT(11) NOT NULL, tm_end_register DATETIME NOT NULL, tm_winner_team_id INT(11) NOT NULL, lan_id INT(11) NOT NULL)"),
		array("tbl_name"=>"tm_paarung","tbl_old"=>"0","clm_name"=>"0","clm_old"=>"0","statement"=>"CREATE TABLE tm_paarung (ID INT(11) PRIMARY KEY AUTO_INCREMENT NOT NULL, team_1 INT(11) NULL, team_2 INT(11) NULL, tournament INT(11) NOT NULL, result INT(11) NULL, period INT(11) NOT NULL, successor INT(11) NULL, ressource INT(11) NOT NULL, matches_id INT(11) NULL)"),
		array("tbl_name"=>"tm_period","tbl_old"=>"0","clm_name"=>"0","clm_old"=>"0","statement"=>"CREATE TABLE tm_period (ID INT(11) PRIMARY KEY AUTO_INCREMENT NOT NULL, time_from DATETIME NOT NULL, time_to DATETIME NOT NULL)"),
		array("tbl_name"=>"tm_team","tbl_old"=>"0","clm_name"=>"0","clm_old"=>"0","statement"=>"CREATE TABLE tm_team (ID INT(11) PRIMARY KEY AUTO_INCREMENT NOT NULL, gamerslist_id INT(11) NOT NULL, team_name VARCHAR(255) CHARSET utf8mb4 NOT NULL, tournament_id INT(11) NOT NULL, lan_id INT(11) NOT NULL)"),
		array("tbl_name"=>"tm_game","tbl_old"=>"0","clm_name"=>"0","clm_old"=>"0","statement"=>"CREATE TABLE tm_game (ID INT(11) PRIMARY KEY AUTO_INCREMENT NOT NULL, game_id INT(11) NOT NULL, ressource_id INT(11) NOT NULL)"),
		array("tbl_name"=>"tm_ressources","tbl_old"=>"0","clm_name"=>"0","clm_old"=>"0","statement"=>"CREATE TABLE tm_ressources (ID INT(11) PRIMARY KEY AUTO_INCREMENT NOT NULL, ressource_name VARCHAR(255) CHARSET utf8mb4 NOT NULL, server_ip VARCHAR(255) CHARSET utf8mb4 NOT NULL)"),
		array("tbl_name"=>"tm_matches","tbl_old"=>"0","clm_name"=>"0","clm_old"=>"0","statement"=>"CREATE TABLE tm_matches (ID INT(11) PRIMARY KEY AUTO_INCREMENT NOT NULL, match_id INT(11) NOT NULL)"),
		array("tbl_name"=>"tm_match","tbl_old"=>"0","clm_name"=>"0","clm_old"=>"0","statement"=>"CREATE TABLE tm_match (ID INT(11) PRIMARY KEY AUTO_INCREMENT NOT NULL, result_team1 VARCHAR(255) CHARSET utf8mb4 NULL, result_team2 VARCHAR(255) NULL)"),
		array("tbl_name"=>"tm_gamerslist","tbl_old"=>"0","clm_name"=>"0","clm_old"=>"0","statement"=>"CREATE TABLE tm_gamerslist (ID INT(11) PRIMARY KEY AUTO_INCREMENT NOT NULL, player_id INT(11) NOT NULL)"),
		array("tbl_name"=>"tm_vote","tbl_old"=>"0","clm_name"=>"0","clm_old"=>"0","statement"=>"CREATE TABLE tm_vote (ID INT(11) PRIMARY KEY AUTO_INCREMENT NOT NULL, game_id INT(11) NOT NULL, user_id INT(11) NOT NULL, vote_count INT(11) NOT NULL, starttime DATETIME NOT NULL, endtime DATETIME NOT NULL)"),
		array("tbl_name"=>"tm_vote_player","tbl_old"=>"0","clm_name"=>"0","clm_old"=>"0","statement"=>"CREATE TABLE tm_vote_player (ID INT(11) PRIMARY KEY AUTO_INCREMENT NOT NULL, tm_vote_id INT(11) NOT NULL, player_id INT(11) NOT NULL, UNIQUE (tm_vote_id,player_id))"),
		array("tbl_name"=>"log_trigger_error","tbl_old"=>"0","clm_name"=>"0","clm_old"=>"0","statement"=>"CREATE TABLE log_trigger_error (ID INT(11) PRIMARY KEY AUTO_INCREMENT NOT NULL, error_type VARCHAR(45) NOT NULL, error_statement(255) NOT NULL)"),

	
	### - ALTER STATEMENTS - ###

		## RENAME TABLE
		// Beispiel: array("tbl_name"=>"test_table_3","tbl_old"=>"test_table_2","clm_name"=>"0","clm_old"=>"0","statement"=>"RENAME TABLE test_table_2 TO test_table_3"),
		## ADD COLUMN
			// Update 1.3
			array("tbl_name"=>"games","tbl_old"=>"0","clm_name"=>"has_table","clm_old"=>"0","statement"=>"ALTER TABLE games ADD has_table INT(11) NULL"),
			array("tbl_name"=>"games","tbl_old"=>"0","clm_name"=>"icon","clm_old"=>"0","statement"=>"ALTER TABLE games ADD icon VARCHAR(255) CHARSET utf8mb4 NULL"),
			array("tbl_name"=>"tm_vote","tbl_old"=>"0","clm_name"=>"vote_closed","clm_old"=>"0","statement"=>"ALTER TABLE tm_vote ADD vote_closed INT(11) NOT NULL AFTER endtime"),

			// Update 1.4.1
			array("tbl_name"=>"games","tbl_old"=>"0","clm_name"=>"short_title","clm_old"=>"0","statement"=>"ALTER TABLE games ADD short_title VARCHAR(255) CHARSET utf8mb4 NULL AFTER raw_name"),

			// Update 1.5
			array("tbl_name"=>"games","tbl_old"=>"0","clm_name"=>"addon","clm_old"=>"0","statement"=>"ALTER TABLE games ADD addon INT(11) NULL AFTER short_title"),
			array("tbl_name"=>"games","tbl_old"=>"0","clm_name"=>"banner","clm_old"=>"0","statement"=>"ALTER TABLE games ADD banner VARCHAR(255) CHARSET utf8mb4 NULL AFTER icon"),
			array("tbl_name"=>"tm_gamerslist","tbl_old"=>0,"clm_name"=>"tm_id","clm_old"=>"0","statement"=>"ALTER TABLE tm_gamerslist ADD tm_id INT(11) AFTER ID"),
			array("tbl_name"=>"tm","tbl_old"=>0,"clm_name"=>"max_player","clm_old"=>"0","statement"=>"ALTER TABLE tm ADD max_player INT(11) AFTER player_count"),
			array("tbl_name"=>"tm","tbl_old"=>0,"clm_name"=>"tm_period_id","clm_old"=>"0","statement"=>"ALTER TABLE tm ADD tm_period_id INT(11) AFTER player_count"),
			array("tbl_name"=>"tm","tbl_old"=>0,"clm_name"=>"tm_end_register","clm_old"=>"0","statement"=>"ALTER TABLE tm ADD tm_end_register INT(11) AFTER tm_period_id"),
			array("tbl_name"=>"tm","tbl_old"=>0,"clm_name"=>"tm_winner_team_id","clm_old"=>"0","statement"=>"ALTER TABLE tm ADD tm_winner_team_id INT(11) AFTER tm_end_register"),
			array("tbl_name"=>"tm","tbl_old"=>0,"clm_name"=>"tm_locked","clm_old"=>"0","statement"=>"ALTER TABLE tm ADD tm_locked INT(11) AFTER tm_winner_team_id"),
			array("tbl_name"=>"pref","tbl_old"=>"0","clm_name"=>"game_id","clm_old"=>"0","statement"=>"ALTER TABLE pref ADD game_id INT(11) AFTER 'user_id'"),
			array("tbl_name"=>"tm_paarung","tbl_old"=>"0","clm_name"=>"stage","clm_old"=>"0","statement"=>"ALTER TABLE tm_paarung ADD stage INT(11) AFTER tournament"),

		## RENAME COLUMN
			// Update 1.5
			array("tbl_name"=>"ac","tbl_old"=>"0","clm_name"=>"ac_category","clm_old"=>"ac_categorie","statement"=>"ALTER TABLE ac CHANGE `ac_categorie` `ac_category` INT(11)"),
			array("tbl_name"=>"tm_vote","tbl_old"=>"0","clm_name"=>"vote_count","clm_old"=>"player_id","statement"=>"ALTER TABLE tm_vote CHANGE `player_id` `vote_count` INT(11) NOT NULL"),
			array("tbl_name"=>"tm_vote","tbl_old"=>"0","clm_name"=>"game_id","clm_old"=>"game","statement"=>"ALTER TABLE tm CHANGE `game` `game_id` INT(11) NOT NULL"),
			array("tbl_name"=>"pref","tbl_old"=>"0","clm_name"=>"player_id","clm_old"=>"user_id","statement"=>"ALTER TABLE pref CHANGE `user_id` `player_id` INT(11) NOT NULL"),
		
		## MODIFY COLUMN
			// clm_old = 2 --> Änderung des Charsets
			// clm_old = 3 --> Änderung zu ENUM
			// Update 1.5
			array("tbl_name"=>"ac","tbl_old"=>"0","clm_name"=>"ac_visibility","clm_old"=>"3","statement"=>"ALTER TABLE ac MODIFY ac_visibility ENUM('Sichtbar','Unsichtbar')"),
			array("tbl_name"=>"tm_teamname","tbl_old"=>"0","clm_name"=>"name","clm_old"=>"2","statement"=>"ALTER TABLE tm_teamname MODIFY name VARCHAR(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_bin"),
			array("tbl_name"=>"status_name","tbl_old"=>"0","clm_name"=>"status_name","clm_old"=>"2","statement"=>"ALTER TABLE status_name MODIFY status_name VARCHAR(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_bin"),
			array("tbl_name"=>"ac","tbl_old"=>"0","clm_name"=>"message","clm_old"=>"2","statement"=>"ALTER TABLE ac MODIFY message VARCHAR(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_bin"),
			array("tbl_name"=>"ac","tbl_old"=>"0","clm_name"=>"title","clm_old"=>"2","statement"=>"ALTER TABLE ac MODIFY title VARCHAR(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_bin"),
			array("tbl_name"=>"player","tbl_old"=>"0","clm_name"=>"name","clm_old"=>"2","statement"=>"ALTER TABLE player MODIFY name VARCHAR(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_bin"),
	
	### - DELETE STATEMENTS - ###
		## DELETE TABLE
		// Beispiel: array("tbl_name"=>"0","tbl_old"=>"table_to_delete","clm_name"=>"0","clm_old"=>"0","statement"=>"DROP TABLE table_to_delete"),

		## DELETE COLUMN
		// Beispiel: array("tbl_name"=>"0","tbl_old"=>"0","clm_name"=>"0","clm_old"=>"column_to_delete","statement"=>"ALTER TABLE table DROP COLUMN column_to_delete"),
		array("tbl_name"=>"0","tbl_old"=>"0","clm_name"=>"0","clm_old"=>"max_player","statement"=>"ALTER TABLE tm DROP COLUMN max_player"),
		array("tbl_name"=>"0","tbl_old"=>"0","clm_name"=>"0","clm_old"=>"start_time","statement"=>"ALTER TABLE tm DROP COLUMN starttime"),
		array("tbl_name"=>"0","tbl_old"=>"0","clm_name"=>"0","clm_old"=>"end_date","statement"=>"ALTER TABLE tm DROP COLUMN end_date"),
);

transformArray($con,$sql_statements);

function transformArray($con,$sql_statements)
{
	foreach ($sql_statements as $statement)
	{
		checkExistingField($con,$statement["tbl_name"],$statement["tbl_old"],$statement["clm_name"],$statement["clm_old"],$statement["statement"]);
	}
}

function checkExistingField($con,$tbl_name,$tbl_old,$clm_name,$clm_old,$sql)
{
	if ($clm_name == "0")
	{
		$result = mysqli_query($con,"SELECT * FROM information_schema.tables WHERE table_schema = 'project_ziphon' AND table_name = '$tbl_name' LIMIT 1");
		if(mysqli_num_rows($result) !== 1)
		{
			if($tbl_old == "0")
			{
				execStatementTable($con,"c_tbl",$tbl_name,$tbl_old,$sql);
			} else {
				if($tbl_name == "0")
				{
					execStatementTable($con,"d_tbl",$tbl_name,$tbl_old,$sql);
				} else {
					execStatementTable($con,"a_tbl_rename",$tbl_name,$tbl_old,$sql);
				}
			}
		}
	} else {
		if($clm_old == "2")
		{
			$result = mysqli_query($con,"SELECT character_set_name FROM information_schema.`COLUMNS` WHERE table_schema='project_ziphon' AND table_name ='$tbl_name' AND column_name='$clm_name'");
			if(mysqli_num_rows($result) !== 1)
			{
				execStatementColumn($con,"a_clm_charset",$tbl_name,$clm_name,$clm_old,$sql);
			}
		} elseif ($clm_old == "3") {
			$result = mysqli_query($con,"SELECT character_set_name FROM information_schema.`COLUMNS` WHERE table_schema='project_ziphon' AND table_name ='$tbl_name' AND column_name='$clm_name' AND data_type='enum'");
			if(mysqli_num_rows($result) !== 1)
			{
				execStatementColumn($con,"a_clm_enum",$tbl_name,$clm_name,$clm_old,$sql);
			}
		} else {
			$result = mysqli_query($con,"SHOW COLUMNS FROM `$tbl_name` LIKE '$clm_name'");
			if(mysqli_num_rows($result) !== 1)
			{
				if($clm_old == "0")
				{
					execStatementColumn($con,"a_clm_add",$tbl_name,$clm_name,$clm_old,$sql);
				} else {
					if($clm_name == "0")
					{
						execStatementColumn($con,"d_clm",$tbl_name,$clm_name,$clm_old,$sql);
					} else {
						execStatementColumn($con,"a_clm_rename",$tbl_name,$clm_name,$clm_old,$sql);	
					}
				}
			}
		}
	}
}

function execStatementTable($con,$param,$tbl_name,$tbl_name_old,$sql)
{
	if(mysqli_query($con,$sql))
	{
		switch($param) {
			case "c_tbl":
				echo "Die Tabelle <b>" . $tbl_name . "</b> wurde erfolgreich erstellt.<br>";
				break;
			case "a_tbl_rename":
				echo "Die Tabelle <b>" . $tbl_name_old . "</b> wurde erfolgreich in <b>" . $tbl_name . "</b> umbenannt.<br>";
				break;
			case "d_tbl":
				echo "Die Tabelle <b>" . $tbl_name_old . "</b> wurde erfolgreich gelöscht.<br>";
				break;
			default:
				echo "Keine Updates verfügbar.";
		}
	} else {
		echo mysqli_error($con) . $sql . "<br>";
	}
}

function execStatementColumn($con,$param,$tbl_name,$clm_name,$clm_name_old,$sql)
{
	if(mysqli_query($con,$sql))
	{
		switch($param) {
			case "a_clm_add":
				echo "Die Spalte <b>" . $clm_name . "</b> wurde erfolgreich hinzugefügt.<br>";
				break;
			case "a_clm_rename":
				echo "Die Spalte <b>" . $clm_name_old . "</b> wurde erfolgreich in <b>" . $clm_name . "</b> umbenannt.<br>";
				break;
			case "a_clm_charset":
				echo "Das Charset der Spalte <b>" . $clm_name . "</b> wurde erfolgreich auf uft8mb4 geändert.<br>";
				break;
			case "a_clm_enum":
				echo "Der Spalte <b>". $clm_name . "</b> wurde erfolgreich ein ENUM-Parameter hinzugefügt.<br>";
				break;
			case "d_clm":
				echo "Die Spalte <b>" . $clm_name_old . "</b> wurde erfolgreich gelöscht.<br>";
				break;
			default:
				echo "Keine Updates verfügbar.";
		}
	} else {
		echo mysqli_error($con) . $sql;
	}
}
?>