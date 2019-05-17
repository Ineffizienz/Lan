<?php

include(dirname(__FILE__,2) . "/include/connect.php");

// Update 1.1
/*$sql = "CREATE TABLE status_name (ID INT(11) PRIMARY KEY AUTO_INCREMENT NOT NULL, status_id INT(11) NOT NULL, status_name VARCHAR(255) NOT NULL)";
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

// Update 1.2
/*$sql = "CREATE TABLE pref (ID INT(11) PRIMARY KEY AUTO_INCREMENT NOT NULL, user_id INT(11) NOT NULL, preferences VARCHAR(1024) NULL)";
if(mysqli_query($con,$sql))
{
	echo "Die Tabelle <i>pref</i> wurde erfolgreich erstellt.<br>";
} else {
	echo "Beim Erstellen der Tabelle <i>pref</i> ist ein Fehler aufgetreten: " . mysqli_error($con) . "<br>";
}*/

// Update 1.3
/*$sql = "ALTER TABLE games ADD icon VARCHAR(255) NULL";
if(mysqli_query($con,$sql))
{
	echo "Die Spalte <i>icon</i> wurde erfolgreich der Tabelle <i>games</i> hinzugefügt.<br>";
} else {
	echo "Beim Erstellen der Tabelle <i>pref</i> ist ein Fehler aufgetreten: " . mysqli_error($con) . "<br>";
}

$sql = "ALTER TABLE games ADD has_table INT(11) NULL";
if(mysqli_query($con,$sql))
{
	echo "Die Spalte <i>has_table</i> wurde erfolgreich der Tabelle <i>games</i> hinzugefügt.<br>";
} else {
	echo "Beim Erstellen der Tabelle <i>pref</i> ist ein Fehler aufgetreten: " . mysqli_error($con) . "<br>";
}
	
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
// Update 1.4
/*$sql = "CREATE TABLE ticket_id (ID INT(11) PRIMARY KEY AUTO_INCREMENT NOT NULL, ticket_id VARCHAR(255) NOT NULL)";
if(mysqli_query($con,$sql))
{
	echo "Die Tabelle <i>ticket_id</i> wurde erfolgreich erstellt.<br>";
} else {
	echo "Beim Erstellen der Tabelle <i>ticket_id</i> ist ein Fehler aufgetreten: " . mysqli_error($con) . "<br>";
}*/

// Update 1.4.1
/*$sql = "ALTER TABLE games ADD short_title VARCHAR(255) NULL AFTER raw_name ";
if(mysqli_query($con,$sql))
{
	echo "Die Spalte <i>short_title</i> wurde erfolgreich hinzugefügt.";
} else {
	echo "Beim Hinzufügen der Spalte <i>short_title</i> ist ein Fehler aufgetreten: " . mysqli_error($con) . "<br>";
}*/

// Update 1.4.2 /Post-Lan 2019
$sql = "CREATE TABLE tm (ID INT(11) PRIMARY KEY AUTO_INCREMENT NOT NULL, game VARCHAR(255) NOT NULL, mode INT(11) NOT NULL)";
if(mysqli_query($con,$sql))
{
	echo "Tabelle <i>tm</i> erfolgreich erstellt.<br>";
} else {
	echo "Beim erstellen der Tabelle ist ein Fehler aufgetreten: <b>" . mysqli_error($con) . "</b><br>";
}
$sql = "ALTER table tm ADD mode INT(11) NOT NULL AFTER game";
if(mysqli_query($con,$sql))
{
	echo "Die Spalte <i>mode</i> wurde erfolgreich hinzugefügt.<br>";
} else {
	echo "Beim Hinzufügen der Spalte <i>mode</i> ist ein Fehler aufgetreten: <b>" . mysqli_error($con) . "</b><br>";
}
$sql = "ALTER table tm ADD banner VARCHAR(255) NULL AFTER mode";
if(mysqli_query($con,$sql))
{
	echo "Die Spalte <i>banner</i> wurde erfolgreich hinzugefügt.<br>";
} else {
	echo "Beim Hinzufügen der Spalte <i>banner</i> ist ein Fehler aufgetreten: <b>" . mysqli_error($con) . "</b><br>";
}
$sql = "ALTER TABLE tm ADD min_player INT(11) NOT NULL AFTER banner";
if(mysqli_query($con,$sql))
{
	echo "Die Spalte <i>min_player</i> wurde erfolgreich hinzugefügt.<br>";
} else {
	echo "Beim Hinzufügen der Spalte <i>min_player</i> ist ein Fehler aufgetreten: <b>" . mysqli_error($con) . "</b><br>";
}
$sql = "ALTER TABLE tm ADD starttime DATETIME NOT NULL AFTER min_player";
if(mysqli_query($con,$sql))
{
	echo "Die Spalte <i>starttime</i> wurde erfolgreich hinzugefügt.<br>";
} else {
	echo "Beim Hinzufügen der Spalte <i>starttime</i> ist ein Fehler aufgetreten: <b>" . mysqli_error($con) . "</b><br>";
}
$sql = "ALTER TABLE tm ADD end_date VARCHAR(255) NULL AFTER starttime";
if(mysqli_query($con,$sql))
{
	echo "Die Spalte <i>end_date</i> wurde erfolgreich hinzugefügt.<br>";
} else {
	echo "Beim Hinzufügen der Spalte <i>end_date</i> ist ein Fehler aufgetreten: <b>" . mysqli_error($con) . "</b><br>";
}
$sql = "ALTER TABLE tm ADD player_count INT(11) NULL AFTER min_player";
if(mysqli_query($con,$sql))
{
	echo "Die Spalte <i>player_count</i> wurde erfolgreich hinzugefügt.<br>";
} else {
	echo "Beim Hinzufügen der Spalte <i>player_count</i> ist ein Fehler aufgetreten: <b>" . mysqli_error($con). "</b><br>";
}
$sql = "ALTER TABLE games ADD addon INT(11) NULL AFTER short_title";
if(mysqli_query($con,$sql))
{
	echo "Die Spalte <i>addon</i> wurde erfolgreich hinzugefügt.<br>";
} else {
	echo "Beim Hinzufügen der Spalte <i>addon</i> ist ein Fehler aufgetreten: <b>" . mysqli_error($con). "</b><br>";
}

// Charset-Änderungen
$sql = "ALTER TABLE player MODIFY name VARCHAR(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_bin";
if(mysqli_query($con,$sql))
{
	echo "Die Spalte <i>name</i> in der Tabelle <i>player</i> ist jetzt in <b>utf8mb4_bin</b><br>";
} else {
	echo "Beim Ändern des charsets in <i>player->name</i> ist ein Fehler aufgetreten: <b>" . mysqli_error($con) . "</b><br>";
}
$sql = "ALTER TABLE ac MODIFY title VARCHAR(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_bin";
if(mysqli_query($con,$sql))
{
	echo "Die Spalte <i>title</i> in der Tabelle <i>ac</i> ist jetzt in <b>utf8mb4_bin</b><br>";
} else {
	echo "Beim Ändern des charsets in <i>ac->title</i> ist ein Fehler aufgetreten: <b>" . mysqli_error($con) . "</b><br>";
}
$sql = "ALTER TABLE ac MODIFY message VARCHAR(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_bin";
if(mysqli_query($con,$sql))
{
	echo "Die Spalte <i>message</i> in der Tabelle <i>ac</i> ist jetzt in <b>utf8mb4_bin</b><br>";
} else {
	echo "Beim Ändern des charsets in <i>ac->message</i> ist ein Fehler aufgetreten: <b>" . mysqli_error($con) . "</b><br>";
}
$sql = "ALTER TABLE status_name MODIFY status_name VARCHAR(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_bin";
if(mysqli_query($con,$sql))
{
	echo "Die Spalte <i>status_name</i> in der Tabelle <i>status_name</i> ist jetzt in <b>utf8mb4_bin</b><br>";
} else {
	echo "Beim Ändern des charsets in <i>status_name->status_name</i> ist ein Fehler aufgetreten: <b>" . mysqli_error($con) . "</b><br>";
}
$sql = "ALTER TABLE tm_teamname MODIFY name VARCHAR(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_bin";
if(mysqli_query($con,$sql))
{
	echo "Die Spalte <i>name</i> in der Tabelle <i>tm_teamname</i> ist jetzt in <b>utf8mb4_bin</b><br>";
} else {
	echo "Beim Ändern des charsets in <i>tm_teamname->name</i> ist ein Fehler aufgetreten: <b>" . mysqli_error($con) . "</b><br>";
}

?>