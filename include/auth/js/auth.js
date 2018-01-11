$(document).ready(function(){

	function getName(event)
	{
		event.preventDefault();

		var username = $("#username").val();

		createUsername(username,reloadIntro);
	}

	function getTicketId(event)
	{
		event.preventDefault();
		
		var ticketId = $("#ticket_id").val();

		validateTicket(ticketId,reloadIntro);
	}

	function createUsername(username,fn)
	{
		return $.ajax({
			type: "post",
			dataType: "json",
			url: "include/auth/reg_name.php",
			data: {
				name:username
			},
			success: fn
		});
	}

	function validateTicket(ticketId,fn)
	{
		return $.ajax({
			type: "post",
			dataType: "json",
			url: "include/auth/validate_ticket.php",
			data: {
				id:ticketId
			},
			success: fn
		});
	}

	function reloadIntro(response)
	{
		$("#result").show();
		$("#result").html(response.message);

		if(response.step == "1")
		{
			$("#wrap").load("index.php");
		}

		if(response.step == "2")
		{
			$("#basic_wrap").load("index.php");
		}
	}

	$("#reg_username").on("click",getName);
	$("#validate_id").on("click",getTicketId);

});