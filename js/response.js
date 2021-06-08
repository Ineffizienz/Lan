function displayMessage(message) {
    $("#result").show();
    $("#result").html(message);
    $("#result").fadeOut(7000);
}

function refreshVoteItem(response)
{
    displayMessage(response.message);

    $("#vote_id_" + response.vote_id).load(location.href + " #playercount_" + response.vote_id);
}

function refreshMatchResult(response)
{
    displayMessage(response.message);

    $("#bracket").load(location.href + ' ' + ".container");
}

function refreshTournamentPlayerList(response)
{
    displayMessage(response.message);

    $("#player_list_container").load(location.href + " #tm_player_list");
}

function errorHandling()
{
    $(".error_container").fadeIn();
    $(".placeholder_container").hide();
}

/*
###########################################################
######################## TIME-EVENT #######################
###########################################################
*/


/*setInterval(autoRefreshMatchResult,20000);
function autoRefreshMatchResult()
{
    $("#tm_locked").load(location.href + " #tournament_tree");
}*/