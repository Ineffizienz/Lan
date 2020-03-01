<?php

include(dirname(__FILE__,2) . "/include/connect.php");
if(mysqli_num_rows(mysqli_query($con, "SHOW COLUMNS FROM `player` LIKE 'real_name';")) == 1)
	echo "Spalte bereits vorhanden!";	
else
{
	if(mysqli_query($con, "ALTER TABLE `player` ADD `real_name` TEXT CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL AFTER `name`;"))
		echo "Spalte erfolgreich hinzugefügt!";	
}

