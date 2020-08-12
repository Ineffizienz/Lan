<?php
class Achievement {
	
	private $db_con;
	private $id;
	private $name;
	private $name_array = array();
	private $message;
	private $image;
	private $trigger;
	private $trig_array = array();
	private $category;
	private $cat_array = array();
	private $visibility;
	private $available_ac = array();

	public function __construct($con)
	{
		$this->db_con = $con;

		$this->getAchievementTriggerData();
		$this->getAchievementCategoriesData();
		$this->getAchievementNameData();
	}

	/************************************************************************************
	 *	QUERY DATA
	*************************************************************************************/

	private function getAchievementData()
	{
		$result = mysqli_query($this->db_con,"SELECT title, image_url, message, ac_trigger, ac_category, ac_visibility FROM ac WHERE ID = '$this->id'");
		while($row=mysqli_fetch_array($result))
		{
			$this->name = $row["title"];
			$this->image = $this->validateData($row["image_url"]);
			$this->message = $row["message"];
			$this->trigger = $row["ac_trigger"];
			$this->category = $row["ac_category"];
			$this->visibility = $this->validateData($row["ac_visibility"]);
		}
	}

	private function getAchievementNameData()
	{
		$result = mysqli_query($this->db_con,"SELECT ID, title FROM ac");
		while($row=mysqli_fetch_array($result))
		{
			if(!empty($row))
			{
				array_push($this->name_array,array("id"=>$row["ID"],"name"=>$row["title"]));
			}
		}
	}

	private function getAchievementCategoriesData()
	{
		$result = mysqli_query($this->db_con,"SELECT ID, c_name FROM ac_category");
		while($row=mysqli_fetch_assoc($result))
		{
			if(!empty($row))
			{
				array_push($this->cat_array,array("id" => $row["ID"], "name" => $row["c_name"]));
			} else {
				array_push($this->cat_array,"Es sind keine Kategorien hinterlegt.");
			}
		}
	}

	private function getAchievementTriggerData()
	{
		$result = mysqli_query($this->db_con,"SELECT ID, trigger_title FROM ac_trigger");
		while($row=mysqli_fetch_assoc($result))
		{
			if(!empty($row))
			{
				array_push($this->trig_array,array("id" => $row["ID"], "name" => $row["trigger_title"]));
			} else {
				array_push($this->trig_array,"Es sind keine Trigger hinterlegt");
			}
		}
	}

	/************************************************************************************
	 *	DATA VALIDATION
	*************************************************************************************/

	private function validateData($input)
	{
		if(empty($input) || $input == "")
		{
			return "";
		} else {
			return $input;
		}
	}

	/************************************************************************************
	 *	SET ACHIEVEMENTS/COMPONENTS
	*************************************************************************************/

	public function setNewAchievement($title, $path, $category, $trigger, $visibility)
	{
		$sql = "INSERT INTO ac (title,image_url,message,ac_category,ac_trigger,ac_visibility) VALUES ('$title','$path','$text','$category','$trigger','$visibility')";
		if(mysqli_query($this->db_con,$sql))
		{
			return "SUC_ADMIN_CREATE_AC";
		} else {
			return "ERR_ADMIN_DB";
		}
	}
	
	public function assignNewAchievementAdmin($player_id,$new_achievement)
	{
		$sql = "INSERT ac_player (player_id,ac_id) VALUES ('$player_id','$new_achievement')";
		if(mysqli_query($this->db_con,$sql))
		{
			return "SUC_ADMIN_ASSIGN_AC";
		} else {
			return "ERR_ADMIN_DB";
		}
	}

	public function setNewAcImage($id,$path)
	{
		$sql = "UPDATE ac SET image_url = '$path' WHERE ID = '$acid'";
		if(mysqli_query($this->db_con,$sql))
		{
			return "SUC_ADMIN_CHANGE_AC_IMAGE";
		} else {
			return "ERR_ADMIN_DB";
		}
	}
	

	/************************************************************************************
	 *	GET DATA
	*************************************************************************************/

	public function getAdminAchievement($id)
	{
		$this->id = $id;
		$this->getAchievementData();
		
		return array("id" => $this->id, "name" => $this->name, "image" => $this->image, "message" => $this->message, "trigger" => $this->trigger, "category" => $this->category, "visibility" => $this->visibility);
	}

	public function getAllAchievementByName()
	{
		return $this->name_array;
	}

	public function getAcCategories()
	{
		return $this->cat_array;
	}

	public function getAcTrigger()
	{
		return $this->trig_array;
	}

	public function getPlayerAchievement($id)
	{
		$this->id = $id;
		$this->getAchievementData();

		return array("name"=>$this->name,"image"=>$this->image,"message"=>$this->message);
	}

	public function getAvailableAchievement($id)
	{
		$this->id = $id;
		$this->getAchievementData();

		return array("id"=>$this->id,"name"=>$this->name);
	}
}
	
?>