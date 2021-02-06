<?php
	function run_admin_controller(template $tpl)
	{
		global $con;
		global $con_wow;
		global $con_char;
		if (isset($_REQUEST["aa"]))
		{
			switch($_REQUEST["aa"]) {
				case "home":
					include("admin/overview/key_status.php");
					include("admin/overview/team_status.php");
					$tpl->assign_subtemplate("content","admin/overview.html");
					$tpl->assign_subtemplate("key_status",$output);
					$tpl->assign("team_status",$team_status);
				break;
				case "player":
					include("admin/player/view/player_settings_view.php");
					$tpl->assign_subtemplate("content","admin/skeleton/skeleton_player_settings.html");
					$tpl->assign_subtemplate("player_content","admin/settings_player.html");
					$tpl->assign_subtemplate("player",$output);
				break;
				case "games":
					$tpl->assign_subtemplate("content","admin/game_settings.html");
					$tpl->assign("admin_games",displaySingleGame($con));
				break;
				case "keys":
					include("admin/overview/key_status.php");
					$tpl->assign_subtemplate("content","admin/keys.html");
					$tpl->assign_subtemplate("keys",$output);
				break;
				case "turnier":
					$tpl->assign_subtemplate("content","admin/tm.html");
					$tpl->assign("games",displayTmGames($con));
					$tpl->assign("tournaments",displayTournaments($con));
				break;
				case "vote_tm":
					$tpl->assign_subtemplate("content","admin/vote_tm.html");
					$tpl->assign("votes",displayVotedTournaments($con));
					$tpl->assign("define_tm_popup",displayDefineTmPopup($con));
				case "achieve":
					$tpl->assign_subtemplate("content","admin/achievement_settings.html");
					$tpl->assign("achievements",displayAchievements($con));
					$tpl->assign("ac_cat",displayAcCategories($con));
					$tpl->assign("ac_trigger",displayAcTrigger($con));
				break;
				case "ac_action":
					$tpl->assign_subtemplate("content","admin/ac_action.html");
					$tpl->assign("username",addUsername($con));
					$tpl->assign("ac_cat",displayAcCategories($con));
					$tpl->assign("ac_trigger",displayAcTrigger($con));
				break;
				case "ticket":
					$tpl->assign_subtemplate("content","admin/ticket_status.html");
					$tpl->assign("ticket_status",displayTicketStatus($con));
				break;
				case "wow";
					$tpl->assign_subtemplate("content","admin/wow.html");
				break;
				case "wow_region":
					$tpl->assign_subtemplate("content","admin/wow_region.html");
					$tpl->assign("admin_wow_region",displayWowRegion($con));
				break;
				case "wow_accounts":
					$tpl->assign_subtemplate("content","admin/wow_accounts.html");
					$tpl->assign("admin_wow_accounts",displayWoWAccounts($con,$con_wow,$con_char));
				case "lan":
					$tpl->assign_subtemplate("content","admin/lan_tpl.html");
					$tpl->assign("lans",displayLans($con));
				break;
				default:
					include("admin/overview/key_status.php");
					include("admin/overview/team_status.php");
					$tpl->assign_subtemplate("content","admin/overview.html");
					$tpl->assign("key_status",$key_status);
					$tpl->assign("team_status",$team_status);
			}
		} else {
			include("admin/overview/key_status.php");
			include("admin/overview/team_status.php");
			$tpl->assign_subtemplate("content","admin/overview.html");
			$tpl->assign_subtemplate("key_status",$output);
			$tpl->assign("team_status",$team_status);
		}
	}
?>