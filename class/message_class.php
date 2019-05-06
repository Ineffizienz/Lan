<?php
class message {

	private $messageCode = "";
	private $messageText = "";
	private $message = "";
	private $sir_brummel = "";
	protected static $_filepath = null;

	
	static public function filepath() // es ist nicht möglich dirname als Konstante in den Variablen zu definieren. Deshalb brauch ich eine extra Funktion. "self" ist  nur der Bezug zu der Datei selbst.
	{
		if(is_null(self::$_filepath))
		{
			self::$_filepath = dirname(__FILE__,2);
		}

		return self::$_filepath;
	}

	public function messageFile()
	{
		$this->messageFile = $this->filepath() . "/template/messages/";

		return $this->messageFile;
	}

	public function messageTemplate()
	{
		$this->messageTemplate = $this->filepath() . "/template/sir_brummel.html";

		return $this->messageTemplate;
	}
	
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
				if(file_exists($this->messageFile() . "error_message_admin.txt"))
				{
					$lines = file($this->messageFile() . "error_message_admin.txt");
					$this->messageText = $this->searchMessageAdmin($lines);
				} else {
					$this->messageText = "<i>error_msg_admin</i> nicht gefunden. " . $this->messageFile();
				}
			} else {
				if (file_exists($this->messageFile() . "error_msg.txt"))
				{
					$lines = file($this->messageFile() . "error_msg.txt");
					$this->messageText = $this->searchMessageUser($lines);
				} else {
					$this->messageText = "<i>error_msg</i> nicht gefunden. " . $this->messageFile();
				}	
			}
		} elseif (substr($this->messageCode,0,4) == "WARN") {
			if(file_exists($this->messageFile() . "warn_msg_admin.txt"))
			{
				$lines = file($this->messageFile() . "warn_msg_admin.txt");
				$this->messageText = $this->searchMessageAdmin($lines);
			} else {
				$this->messageText = "<i>warn_msg_admin</i> nicht gefunden. " . $this->messageFile();
			}
		} elseif (substr($this->messageCode,0,3) == "SUC") {
			if (substr($this->messageCode,4,5) == "ADMIN")
			{
				if(file_exists($this->messageFile() . "success_message_admin.txt"))
				{
					$lines = file($this->messageFile() . "success_message_admin.txt");
					$this->messageText = $this->searchMessageAdmin($lines);
				} else {
					$this->messageText = "<i>success_message_admin</i> nicht gefunden. " . $this->messageFile();
				}
			} else {
				if (file_exists($this->messageFile() . "success_message.txt"))
		        {
		            $lines = file($this->messageFile() . "success_message.txt");
		            $this->messageText = $this->searchMessageUser($lines);
		        } else {
					$this->messageText = "<i>succes_message</i> nicht gefunden. " . $this->messageFile();
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
		$i = 0;
		$check = false;
		
		foreach ($data as $elem){
			$elem_parts = explode(":", $elem);
			if (strcmp($elem_parts[0], $this->messageCode) == 0) //array[0] ist der MessageCode --> Bsp: ERR_ADMIN_...
			{
				$message_line = $elem_parts[1];
				$check = true;
				break;
			}
		} 
		
		/*while ($check == false || ($i < count($data) )){
			$array = explode(":", $data[$i]);
			
			if (strcmp($array[0], $this->messageCode) == 0) //array[0] ist der MessageCode --> Bsp: ERR_ADMIN_...
			{
				$check = true;
				$message_line = $array[1];
			}

						$i++;
		}*/
		
		if ($check == true)
		{
			$output = ltrim($message_line,$this->messageCode . ":");
		}
		else{
			$output = "Der Fehlercode existiert nicht: " . $this->messageCode;
		}

		
		return $output;
	}
	
	public function searchMessageUser($data)
	{
		foreach ($data as $message_line)
		{
			if(preg_match("/" . substr($this->messageCode,3) . "/isUe",$message_line))
			{
				$output = ltrim($message_line,$this->messageCode . ":");
			} else {
				$output = "Den Fehlercode hab ich nicht gefunden: " . $this->messageCode;
			}
		}
		
		return $output;
	}

	public function buildSirBrummel()
	{

		if (file_exists($this->messageTemplate()))
		{
			$this->sir_brummel = file_get_contents($this->messageTemplate());

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