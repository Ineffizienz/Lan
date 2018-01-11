<?php

	$games = getGames($con);

	$raw_name = getRawName($con);

	foreach ($raw_name as $table_head)
	{
		$result = mysqli_query($con,"SELECT COUNT(game_key) AS av_key FROM $table_head WHERE player_id IS NULL");
		while($row=mysqli_fetch_array($result))
		{
			$available_keys[] = $row["av_key"];
		}
	}

	foreach($games as $game)
	{
		$table_template = file_get_contents("template/part/table_overview.html");
		if(!isset($key_status))
		{
			$key_status = str_replace(array("--GAME--","--KEY--"),array($game,array_shift($available_keys)),$table_template);
		} else {
			$key_status .= str_replace(array("--GAME--","--KEY--"),array($game,array_shift($available_keys)),$table_template);
		}
	}
?>