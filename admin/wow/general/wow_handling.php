<?php

function deleteWowCharacter($con_wow,$con_char,$account_id,$guid)
{	
	if(!mysqli_query($con_char,"DELETE FROM characters.arena_team WHERE captainGuid = '$guid'")) 
	{
		return "ERR_ADMIN_DEL_WOW_CHAR_ARENATEAM";
	}

	if(!mysqli_query($con_char,"DELETE FROM characters.arena_team_member WHERE guid = '$guid'"))
	{
		return "ERR_ADMIN_DEL_WOW_CHAR_ARENATEAMMEMBER";
	}

	if(!mysqli_query($con_char,"DELETE FROM characters.auctionbidders WHERE bidderguid = '$guid'"))
	{
		return "ERR_ADMIN_DEL_WOW_CHAR_AUCTIONBIDDERS";
	}

	if(!mysqli_query($con_char,"DELETE FROM characters.auctionhouse WHERE itemowner = '$guid' OR buyguid = '$guid'"))
	{
		return "ERR_ADMIN_WOW_CHAR_AUCTIONHOUSE";
	}
	
	if(!mysqli_query($con_char,"DELETE FROM characters.battleground_deserters WHERE guid = '$guid'"))
	{
		return "ERR_ADMIN_WOW_CHAR_BATTLEGROUNDDESERTERS";
	}

	if(!mysqli_query($con_char,"DELETE FROM characters.calendar_events WHERE creator = '$guid'"))
	{
		return "ERR_ADMIN_WOW_CHAR_CALENDEREVENTS" . mysqli_error($con_char);
	}

	if(!mysqli_query($con_char,"DELETE FROM characters.calendar_invites WHERE invitee = '$guid' OR sender = '$guid'"))
	{
		return "ERR_ADMIN_WOW_CHAR_CALENDERINVITES";
	}

	if(!mysqli_query($con_char,"DELETE FROM characters.character_account_data WHERE guid = '$guid'"))
	{
		return "ERR_ADMIN_WOW_CHAR_CHARACTERACCOUNTDATA";
	}

	if(!mysqli_query($con_char,"DELETE FROM characters.character_achievement WHERE guid = '$guid'"))
	{
		return "ERR_ADMIN_WOW_CHAR_CHARACTERACHIEVEMENT";
	}

	if(!mysqli_query($con_char,"DELETE FROM characters.character_achievement_progress WHERE guid = '$guid'"))
	{
		return "ERR_ADMIN_WOW_CHAR_CHARACTERACHIEVEMENTPROGRESS";
	}

	if(!mysqli_query($con_char,"DELETE FROM characters.character_action WHERE guid = '$guid'"))
	{
		return "ERR_ADMIN_WOW_CHAR_CHARACTERAUCTION";
	}

	if(!mysqli_query($con_char,"DELETE FROM characters.character_arena_stats WHERE guid = '$guid'"))
	{
		return "ERR_ADMIN_WOW_CHAR_CHARACTERARENASTATS";
	}

	if(!mysqli_query($con_char,"DELETE FROM characters.character_aura WHERE guid = '$guid'"))
	{
		return "ERR_ADMIN_WOW_CHAR_CHARACTERAURA";
	}

	if(!mysqli_query($con_char,"DELETE FROM characters.character_banned WHERE guid = '$guid'"))
	{
		return "ERR_ADMIN_WOW_CHAR_CHARACTERBANNED";
	}

	if(!mysqli_query($con_char,"DELETE FROM characters.character_battleground_data WHERE guid = '$guid'"))
	{
		return "ERR_ADMIN_WOW_CHAR_CHARACTERBATTLEGROUNDDATA";
	}

	if(!mysqli_query($con_char,"DELETE FROM characters.character_battleground_random WHERE guid = '$guid'"))
	{
		return "ERR_ADMIN_WOW_CHAR_CHARACTERBATTLEGROUNDRANDOM";
	}

	if(!mysqli_query($con_char,"DELETE FROM characters.character_declinedname WHERE guid = '$guid'"))
	{
		return "ERR_ADMIN_WOW_CHAR_CHARACTERDECLINEDNAME";
	}

	if(!mysqli_query($con_char,"DELETE FROM characters.character_equipmentsets WHERE guid = '$guid'"))
	{
		return "ERR_ADMIN_WOW_CHAR_CHARACTEREQUIPMENTSETS";
	}

	if(!mysqli_query($con_char,"DELETE FROM characters.character_fishingsteps WHERE guid = '$guid'"))
	{
		return "ERR_ADMIN_WOW_CHAR_CHARACTERFISHINGSTEPS";
	}

	if(!mysqli_query($con_char,"DELETE FROM characters.character_gifts WHERE guid = '$guid'"))
	{
		return "ERR_ADMIN_WOW_CHAR_CHARACTERGIFTS";
	}

	if(!mysqli_query($con_char,"DELETE FROM characters.character_glyphs WHERE guid = '$guid'"))
	{
		return "ERR_ADMIN_WOW_CHAR_CHARACTERGLYPHS";
	}

	if(!mysqli_query($con_char,"DELETE FROM characters.character_homebind WHERE guid = '$guid'"))
	{
		return "ERR_ADMIN_WOW_CHAR_CHARACTERHOMEBIND";
	}

	if(!mysqli_query($con_char,"DELETE FROM characters.character_instance WHERE guid = '$guid'"))
	{
		return "ERR_ADMIN_WOW_CHAR_CHARACTERINSTANCE";
	}

	if(!mysqli_query($con_char,"DELETE FROM characters.character_inventory WHERE guid = '$guid'"))
	{
		return "ERR_ADMIN_WOW_CHAR_CHARACTERINVENTORY";
	}

	if(!mysqli_query($con_char,"DELETE FROM characters.character_pet WHERE owner = '$guid'"))
	{
		return "ERR_ADMIN_WOW_CHAR_CHARACTERPET";
	}

	if(!mysqli_query($con_char,"DELETE FROM characters.character_pet_declinedname WHERE owner = '$guid'"))
	{
		return "ERR_ADMIN_WOW_CHAR_CHARACTERPETDECLINEDNAME";
	}

	if(!mysqli_query($con_char,"DELETE FROM characters.character_queststatus WHERE guid = '$guid'"))
	{
		return "ERR_ADMIN_WOW_CHAR_CHARACTERQUESTSTATUS";
	}

	if(!mysqli_query($con_char,"DELETE FROM characters.character_queststatus_daily WHERE guid = '$guid'"))
	{
		return "ERR_ADMIN_WOW_CHAR_CHARACTERQUESTSTATUSDAILY";
	}

	if(!mysqli_query($con_char,"DELETE FROM characters.character_queststatus_rewarded WHERE guid = '$guid'"))
	{
		return "ERR_ADMIN_WOW_CHAR_CHARACTERQUESTSTATUSREWARDED";
	}

	if(!mysqli_query($con_char,"DELETE FROM characters.character_queststatus_seasonal WHERE guid = '$guid'"))
	{
		return "ERR_ADMIN_WOW_CHAR_CHARACTERQUESTSTATUSSEASONAL";
	}

	if(!mysqli_query($con_char,"DELETE FROM characters.character_queststatus_weekly WHERE guid = '$guid'"))
	{
		return "ERR_ADMIN_WOW_CHAR_CHARACTERQUESTSTATUSWEEKLY";
	}

	if(!mysqli_query($con_char,"DELETE FROM characters.character_reputation WHERE guid = '$guid'"))
	{
		return "ERR_ADMIN_WOW_CHAR_CHARACTERREPUTATION";
	}

	if(!mysqli_query($con_char,"DELETE FROM characters.character_skills WHERE guid = '$guid'"))
	{
		return "ERR_ADMIN_WOW_CHAR_CHARACTERSKILLS";
	}

	if(!mysqli_query($con_char,"DELETE FROM characters.character_social WHERE guid = '$guid'"))
	{
		return "ERR_ADMIN_WOW_CHAR_CHARACTERSOCIAL";
	}

	if(!mysqli_query($con_char,"DELETE FROM characters.character_spell WHERE guid = '$guid'"))
	{
		return "ERR_ADMIN_WOW_CHAR_CHARACTERSPELL";
	}

	if(!mysqli_query($con_char,"DELETE FROM characters.character_spell_cooldown WHERE guid = '$guid'"))
	{
		return "ERR_ADMIN_WOW_CHAR_CHARACTERSPELLCOOLDOWN";
	}

	if(!mysqli_query($con_char,"DELETE FROM characters.character_stats WHERE guid = '$guid'"))
	{
		return "ERR_ADMIN_WOW_CHAR_CHARACTERSTATS";
	}

	if(!mysqli_query($con_char,"DELETE FROM characters.character_talent WHERE guid = '$guid'"))
	{
		return "ERR_ADMIN_WOW_CHAR_CHARACTERTALENT";
	}

	if(!mysqli_query($con_char,"DELETE FROM characters.corpse WHERE guid = '$guid'"))
	{
		return "ERR_ADMIN_WOW_CHAR_CORPS";
	}

	if(!mysqli_query($con_char,"DELETE FROM characters.group_member WHERE memberGuid = '$guid'"))
	{
		return "ERR_ADMIN_WOW_CHAR_GROUPMEMBER";
	}

	if(!mysqli_query($con_char,"DELETE FROM characters.groups WHERE leaderGuid = '$guid' OR looterGuid = '$guid'"))
	{
		return "ERR_ADMIN_WOW_CHAR_GROUP";
	}

	if(!mysqli_query($con_char,"DELETE FROM characters.guild WHERE leaderguid = '$guid'"))
	{
		return "ERR_ADMIN_WOW_CHAR_GUILD";
	}

	if(!mysqli_query($con_char,"DELETE FROM characters.guild_member WHERE guid = '$guid'"))
	{
		return "ERR_ADMIN_WOW_CHAR_GUILDMEMBER";
	}

	if(!mysqli_query($con_char,"DELETE FROM characters.item_instance WHERE owner_guid = '$guid' OR creatorGuid = '$guid' OR giftCreatorGuid = '$guid'"))
	{
		return "ERR_ADMIN_WOW_CHAR_ITEMINSTANCE";
	}

	if(!mysqli_query($con_char,"DELETE FROM characters.item_refund_instance WHERE player_guid = '$guid'"))
	{
		return "ERR_ADMIN_WOW_CHAR_ITEMREFUNDINSTANCE";
	}

    if(handleSoulboundItems($con_char,$guid) == 1)
    {
        return "ERR_ADMIN_WOW_CHAR_ITEMSOULBOUNDTRADEDATA";
    }

	if(!mysqli_query($con_char,"DELETE FROM characters.lag_reports WHERE guid = '$guid'"))
	{
		return "ERR_ADMIN_WOW_CHAR_LAGREPORTS";
	}

	if(!mysqli_query($con_char,"DELETE FROM characters.log_mount WHERE character_guid = '$guid'"))
	{
		return "ERR_ADMIN_WOW_CHAR_LOGMOUNT";
	}

	if(!mysqli_query($con_char,"DELETE FROM characters.mail WHERE sender = '$guid' OR receiver = '$guid'"))
	{
		return "ERR_ADMIN_WOW_CHAR_MAIL";
	}

	if(!mysqli_query($con_char,"DELETE FROM characters.mail_items WHERE receiver = '$guid'"))
	{
		return "ERR_ADMIN_WOW_CHAR_MAILITEMS";
	}

	if(!mysqli_query($con_char,"DELETE FROM characters.pet_aura WHERE casterGuid = '$guid'"))
	{
		return "ERR_ADMIN_WOW_CHAR_PETAURA";
	}

	if(!mysqli_query($con_char,"DELETE FROM characters.petition WHERE ownerguid = '$guid'"))
	{
		return "ERR_ADMIN_WOW_CHAR_PETITION";
	}

	if(!mysqli_query($con_char,"DELETE FROM characters.petition_sign WHERE ownerguid = '$guid' OR playerguid = '$guid'"))
	{
		return "ERR_ADMIN_WOW_CHAR_PETITIONSIGN";
	}

	if(!mysqli_query($con_char,"DELETE FROM characters.pvpstats_players WHERE character_guid = '$guid'"))
	{
		return "ERR_ADMIN_WOW_CHAR_PVPSTATSPLAYER";
	}

	if(!mysqli_query($con_char,"DELETE FROM characters.quest_tracker WHERE character_guid = '$guid'"))
	{
		return "ERR_ADMIN_WOW_CHAR_QUESTTRACKER";
	}

    if(updateAccountData($con_wow,$account_id) == 0)
    {
        if(!mysqli_query($con_char,"DELETE FROM characters.characters WHERE guid = '$guid'"))
        {
            return "ERR_ADMIN_WOW_CHAR_CHARACTERS";
        } else {
            return "SUC_ADMIN_DELETE_WOW_CHAR";
        }
    } else {
        return "ERR_ADMIN_WOW_ACC_UPDATE";
    }


}

function updateAccountData($con_wow, $account_id)
{
    $sql = "UPDATE auth.realmcharacters SET numchars = numchars-1";
    if(mysqli_query($con_wow,$sql))
    {
        return 0;
    } else {
        return 1;
    }
}

function handleSoulboundItems($con_char,$guid)
{
    $result = mysqli_query($con_char,"SELECT * FROM characters.item_soulbound_trade_data");
    while($row=mysqli_fetch_assoc($result))
    {
        $items[] = $row;
    }

    foreach ($items as $soulbound_item)
    {

        $allowedPlayer = explode(" ",$soulbound_item["allowedPlayers"]);

        if(in_array($guid,$allowedPlayer))
        {
            $pos = array_search($guid,$allowedPlayer);
            unset($allowedPlayer[$pos]);

            $new_player_list = implode(" ",$allowedPlayer);
            
            $item = $soulbound_item["itemGuid"];
            
            $sql = "UPDATE characters.item_soulbound_trade_data SET allowedPlayers = '$new_player_list' WHERE itemGuid = '$item'";
            if(mysqli_query($con_char,$sql))
            {
                return 0;
            } else {
                return 1;
            }
        }
    }
}

?>