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
			if (file_exists(dirname(__FILE__,2) . "/template/messages/error_msg.txt"))
			{
				$lines = file(dirname(__FILE__,2) . "/template/messages/error_msg.txt");
				foreach ($lines as $line)
				{
					/*
						Im vorliegenden Fall sind folgende Modifikatoren verwendet worden:
						- i = Buchstaben im vorgebenen Suchmuster können sowohl groß- als auch kleingeschrieben sein
						- den Rest hab ich nicht verstanden o.O
					*/
					if(preg_match("/" . substr($this->messageCode,3) . "/isUe",$line)) // sogenannte PCRE-Modifikatoren
					{
						$this->messageText = ltrim($line,$this->messageCode . ":");
					}
				}
			}
		} else {
			if (file_exists(dirname(__FILE__,2) . "/template/messages/success_message.txt"))
	        {
	            $lines = file(dirname(__FILE__,2) . "/template/messages/success_message.txt");
	            foreach ($lines as $line)
	            {
	            	if (strpos($line, $this->messageCode) == $this->messageCode)
	            	{
	            		$this->messageText = ltrim($line,$this->messageCode . ":");
	            	}
	            }
	        }
		}

		$this->buildSirBrummel();
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