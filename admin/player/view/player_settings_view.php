<?php
	
	$players = getPlayerData($con);

	foreach ($players as $data)
	{
		$team_data = emptyText($data["team_id"]);
		if($team_data == "-")
		{
			$t_name = "-";
		} else {
			$t_name = getTeamName($con,$data["team_id"]);
		}

		$t_captain = emptyText($data["team_captain"]);

		$table_template = file_get_contents("template/admin/part/player_table.html");
		if (!isset($player))
		{
			$player = str_replace(array("--ID--","--NAME--","--IP--","--TEAM--","--T_NAME--","--CAPTAIN--","--ID--"),array($data["ID"],$data["name"],$data["ip"],$team_data,$t_name,$t_captain,$data["ID"]),$table_template);
		} else {
			$player .= str_replace(array("--ID--","--NAME--","--IP--","--TEAM--","--T_NAME--","--CAPTAIN--","--ID--"),array($data["ID"],$data["name"],$data["ip"],$team_data,$t_name,$t_captain,$data["ID"]),$table_template);
		}
	}

	//<i class='fa fa-pencil' aria-hidden='true'></i>
?>