<?php
ini_set('max_execution_time', '700');
include(dirname(__FILE__,2) . "/include/connect.php");

echo "Konvertiere Key-Tabellenformat Rejected keys werden wieder als valide gesehn, da Fehler durch wiederholte keys aufgetreten sein könnten.<br>";

$result = mysqli_query($con,"SELECT * FROM information_schema.tables WHERE table_schema = 'project_ziphon' AND table_name = 'gamekeys' LIMIT 1;");
if(mysqli_num_rows($result) == 0)
{
	mysqli_query($con, "CREATE TABLE `gamekeys` (`ID` INT NOT NULL AUTO_INCREMENT, `game_id` INT NOT NULL, `gamekey` VARCHAR(128) NOT NULL, `player_id` INT NULL DEFAULT NULL, `rejected` BOOLEAN NOT NULL DEFAULT FALSE, PRIMARY KEY (`ID`), INDEX (`game_id`), INDEX (`player_id`)) ENGINE = InnoDB;");
	
	//jeder key darf pro spiel nur einmal vorkommen. Das stellt nun die datenbank selber sicher.
	mysqli_query($con, "ALTER TABLE `gamekeys` ADD UNIQUE (`gamekey`, `game_id`);");
	
	//zwei schöne constraints, um auf Updates der player- bzw gametabelle reagieren zu können.
	mysqli_query($con, "ALTER TABLE `gamekeys` ADD FOREIGN KEY (`player_id`) REFERENCES `player`(`ID`) ON DELETE SET NULL ON UPDATE CASCADE;");
	mysqli_query($con, "ALTER TABLE `gamekeys` ADD FOREIGN KEY (`game_id`) REFERENCES `games`(`ID`) ON DELETE CASCADE ON UPDATE CASCADE;");
	
	
	$result_games = mysqli_query($con, "SELECT ID, raw_name FROM games WHERE (has_table ='1');");
	while ($row=mysqli_fetch_array($result_games))
	{
		$game_id = $row["ID"];
		$raw_name = $row["raw_name"];
		
		//zuerst die keys, die schon spielern zugeteilt sind. Damit duplikate mit noch nicht zugeteilnten keys den spieler nicht beeinträchtigen
		$result_keys = mysqli_query($con, "SELECT game_key, player_id FROM $raw_name WHERE (player_id IS NOT NULL);");
		$duplicate_keys = 0;
		while ($row=mysqli_fetch_array($result_keys))
		{
			$gamekey = trim($row["game_key"]);
			$player_id = $row["player_id"];
			$result_duplicate_key = mysqli_query($con, "SELECT id FROM gamekeys WHERE (game_id = '$game_id') AND (gamekey = '$gamekey');");
			if(mysqli_num_rows($result_duplicate_key) > 0)
			{
				$duplicate_keys++;
			}
			else
			{
				$result_player_has_key = mysqli_query($con, "SELECT id FROM gamekeys WHERE (game_id = '$game_id') AND (player_id = '$player_id');");
				if(mysqli_num_rows($result_player_has_key) == 0)
					mysqli_query($con, "INSERT INTO `gamekeys` (`game_id`, `gamekey`, `player_id`) VALUES ('$game_id', '$gamekey', '$player_id');");
				else
					mysqli_query($con, "INSERT INTO `gamekeys` (`game_id`, `gamekey`) VALUES ('$game_id', '$gamekey');");
			}
		}
		
		//nun die keys, die noch keinem spieler zugeordnet sind.
		$result_keys = mysqli_query($con, "SELECT game_key FROM $raw_name WHERE (player_id IS NULL);");
		while ($row=mysqli_fetch_array($result_keys))
		{
			$gamekey = trim($row["game_key"]);
			$result_duplicate_key = mysqli_query($con, "SELECT id FROM gamekeys WHERE (game_id = '$game_id') AND (gamekey = '$gamekey');");
			if(mysqli_num_rows($result_duplicate_key) > 0)
				$duplicate_keys++;
			else
				mysqli_query($con, "INSERT INTO `gamekeys` (`game_id`, `gamekey`) VALUES ('$game_id', '$gamekey');");
		}
		
		if($duplicate_keys == 0)
			echo "$raw_name erfolgreich konvertiert.<br>";
		else
			echo "$raw_name konvertiert. Dabei wurden $duplicate_keys Mehrfacheinträge von Keys gefunden.<br>";
	}
	
	//nun die alten tabellen löschen. Zur sicherheit erst jetzt, falls das vorher abbricht wird die erneute ausführeung sonst frickelig.
	
	$result_games = mysqli_query($con, "SELECT raw_name FROM games WHERE (has_table ='1');");
	while ($row=mysqli_fetch_array($result_games))
	{
		$raw_name = $row["raw_name"];
		mysqli_query($con, "DROP TABLE $raw_name;");
	}
	mysqli_query($con, "DROP TABLE rejected_key;");
	
	echo "deleted old key tables.<br>";
}


//TODO: kurznamenstabellespalte löschen? oder zumindest den namen von starcraft fixen

