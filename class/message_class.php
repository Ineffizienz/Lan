<?php
class message {

	private $messageCode = "";
	private $messageText = "";
	private $message = "";
	private $sir_brummel = "";

	public function getMessageCode($messageCode)
	{

		$this->messageCode = $messageCode;

		$this->buildMessageText();

	}

	public function buildMessageText()
	{
		if (substr($this->messageCode,0,3) == "ERR")
		{
			if(substr($this->messageCode,4,5) == "ADMIN")
			{
				if(file_exists(dirname(__FILE__,2) . "/template/messages/error_msg_admin.txt"))
				{
					$lines = file(dirname(__FILE__,2) . "/template/messages/error_msg_admin.txt");
					$this->messageText = $this->searchMessageAdmin($lines);
				}
			} else {
				if (file_exists(dirname(__FILE__,2) . "/template/messages/error_msg.txt"))
				{
					$lines = file(dirname(__FILE__,2) . "/template/messages/error_msg.txt");
					$this->messageText = $this->searchMessageUser($lines);
				}	
			}
		} else {
			if (substr($this->messageCode,4,5) == "ADMIN")
			{
				if(file_exists(dirname(__FILE__,2) . "/template/messages/success_message_admin.txt"))
				{
					$lines = file(dirname(__FILE__,2) . "/template/messages/success_message_admin.txt");
					$this->messageText = $this->searchMessageAdmin($lines);
				}
				
			} else {
				if (file_exists(dirname(__FILE__,2) . "/template/messages/success_message.txt"))
		        {
		            $lines = file(dirname(__FILE__,2) . "/template/messages/success_message.txt");
		            $this->messageText = $this->searchMessageUser($lines);
		        }	
			}
		}

		$this->buildSirBrummel();
	}

	public function searchMessageAdmin($data)
	{
		/*
			Im vorliegenden Fall sind folgende Modifikatoren verwendet worden:
			- i = Buchstaben im vorgebenen Suchmuster können sowohl groß- als auch kleingeschrieben sein
			- den Rest hab ich nicht verstanden o.O
		*/
		
		foreach ($data as $message)
		{
			if(preg_match("/" . substr($this->messageCode,10) . "/isUe",$message)) // sogenannte PCRE-Modifikatoren
			{
				$output = ltrim($message,$this->messageCode . ":");
			}
		}
		
		return $output;
	}
	
	public function searchMessageUser($data)
	{
		foreach ($data as $message)
		{
			if(preg_match("/" . substr($this->messageCode,3) . "/isUe",$message))
			{
				$output = ltrim($message,$this->messageCode . ":");
			}
		}
		
		return $output;
	}

	public function buildSirBrummel()
	{

		if (file_exists(dirname(__FILE__,2) . "/template/sir_brummel.html"))
		{
			$this->sir_brummel = file_get_contents(dirname(__FILE__,2) . "/template/sir_brummel.html");

			$this->message = str_replace("--Message--", $this->messageText, $this->sir_brummel);
		} else {
			$this->message = "File not Found";
		}

	}

	public function displayMessage()
	{
		return $this->message;
	}

}
?>