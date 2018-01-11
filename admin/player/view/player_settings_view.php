<?php
	
	$players = getPlayerData($con);

	foreach ($players as $data)
	{
		$table_template = file_get_contents("template/admin/part/player_table.html");
		if (!isset($player))
		{
			$player = str_replace(array("--NAME--","--IP--","--TEAM--","--CAPTAIN--","--ID--"),array($data["name"],$data["ip"],$data["team_id"],$data["team_captain"],$data["ID"]),$table_template);
		} else {
			$player .= str_replace(array("--NAME--","--IP--","--TEAM--","--CAPTAIN--","--ID--"),array($data["name"],$data["ip"],$data["team_id"],$data["team_captain"],$data["ID"]),$table_template);
		}
	}

	//<i class='fa fa-pencil' aria-hidden='true'></i>
?>