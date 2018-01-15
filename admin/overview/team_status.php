<?php
	
	$teams= getAllTeams($con);

	foreach ($teams as $team)
	{
		$member = getTeamMember($con,$team["ID"]);
		$table_template = file_get_contents("template/part/basic_table.html");

		$tm = implode(", ",$member);

		if (!isset($team_status))
		{
			$team_status = str_replace(array("--VALUE_1--","--VALUE_2--"),array($team["name"],$tm),$table_template);
		} else {
			$team_status .= str_replace(array("--VALUE_1--","--VALUE_2--"),array($team["name"],$tm),$table_template);
		}
	}

?>