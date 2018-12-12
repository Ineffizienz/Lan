<?php
	if (isset($_REQUEST["page"]))
	{
		switch ($_REQUEST["page"]) {
			case 'keygen':
				$content = build_content("key_generate.html");
			break;
			case 'wow_server':
				$content = build_content("wow_server.html");
			break;
			case 'teams':
				$content = build_content("teams.html");
			break;
			case 'tm':
				$content = build_content("generate_tournament.html");
			break;
			case 'c_team':
				$content = build_content("create_team.html");
			break;
			case 'conf':
				$content = build_content("settings.html");
			break;
			case 'tschedule':
				$content = build_content("time_schedule.html");
			break;
			default:
				$content = build_content("key_generate.html");
		}
	} else {
		$content = build_content("key_generate.html");
	}
	

	if (isset($_REQUEST["subpage"]))
	{
		switch ($_REQUEST["subpage"]) {
			case 'team_conf':
				$settings = build_content("team_settings.html");
			break;
			case 'own':
				$settings = build_content("own_settings.html");
			break;
			case 'achieve':
				$settings = build_content("achievement_list.html");
			break;
		}
	}
?>