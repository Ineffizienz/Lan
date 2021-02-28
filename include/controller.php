<?php
function run_controller(template $tpl, $player)
{
	global $con;
	if (isset($_REQUEST["page"]))
	{
		switch ($_REQUEST["page"]) {
			case 'wow_server':
				global $con_wow, $con_char;
				$tpl->assign_subtemplate('content', "wow_server.html");
				$tpl->assign("wow_account",selectWowAccount($con,$con_wow,$con_char,$player));
				$tpl->assign("realm",getRealmName($con_wow));
				$tpl->assign("server_on",displayServerStatus($con_wow));
			break;
			case 'teams':
				$tpl->assign_subtemplate('content', "teams.html");
				$tpl->assign("teams",members($con));
			break;
			case 'c_team':
				$tpl->assign_subtemplate('content', "create_team.html");
			break;
			case 'tm':
				$tpl->assign_subtemplate('content', "turnier.html");
				$tpl->assign("vote_option",generateVoteOption($con));
				$tpl->assign("running_votes",displayRunningVotes($con));
				$tpl->assign("tournaments",displayTournaments($con));
			break;
			case 'single_tm':
				$tpl->assign_subtemplate('content', "tournament_view.html");
				$tpl->assign("tournament_view",displayTournamentTree($con));
				$tpl->assign_subtemplate("result_popup","part/popup/result_popup.html");
			break;
			case 'conf':
				$tpl->assign_subtemplate('content', "settings.html");
			break;
			case 'tschedule':
				$tpl->assign_subtemplate('content', "time_schedule.html");
			break;
			default:
				$tpl->assign_subtemplate('content', 'key_generate.html');
				$tpl->assign("games",generate_options($con));
		}
	} else {
		$tpl->assign_subtemplate('content', 'key_generate.html');
		$tpl->assign("games",generate_options($con));
	}
	
	if (isset($_REQUEST["subpage"]))
	{
		switch ($_REQUEST["subpage"]) {
			case 'team_conf':
				$tpl->assign_subtemplate('settings', "team_settings.html");
				$tpl->assign("team",displaySinglePlayerTeam($con, $player_id));
				$tpl->assign("captain",displayCaptain($con, $player_id));
				$tpl->assign("t_members",displayPlayerTeamMember($con, $player_id));
			break;
			case 'own':
				$tpl->assign_subtemplate('settings', "own_settings.html");
				$tpl->assign("ip",$player->getPlayerIp());
				$tpl->assign("name",$player->getPlayerUsername());
				$tpl->assign("real_name",$player->getPlayerRealname());
				$tpl->assign_subtemplate("profil_image",displayProfilImage($con, $player));
				$tpl->assign("pref",displayPlayerPrefs($con, $player));
				$tpl->assign_subtemplate("checkbox_container",createCheckbox($con, $player));
			break;
			case 'achieve':
				$tpl->assign_subtemplate('settings', "achievement_list.html");
				$tpl->assign("player_achievements",displayPlayerAchievements($con, $player));
				$tpl->assign("ac_small",displayAvailableAchievements($con, $player));
			break;
		}
	}
}
?>