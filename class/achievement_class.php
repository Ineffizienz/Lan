<?php
class Achievement {
	
	private $db_con;
	private $id;
	private $name;
	private $message;
	private $image;
	private $trigger;
	private $trig_array = array();
	private $category;
	private $cat_array = array();
	private $s_cat = array();
	private $visibility;
	private $ac_template;
	private $ac;
	private $imagePath;
	private $templatePath;
	private $templateAdminPath;
	private $adminArr = array("--ID--","--NAME--","--IMAGE_URL--","--MESSAGE--","--TRIGGER--","--CATEGORY--","--VISIBILITY--");
	private $singleArr = array("--IMAGE--","--HEADLINE--","--TEXT--");
	private $basicArr = array("--ID--","--HEADLINE--");

	public function __construct($con)
	{
		$this->db_con = $con;
		$this->imagePath = "images/achievements/";
		$this->templatePath = "template/part/";
		$this->templateAdminPath = "template/admin/part/";

		$this->getAchievementTriggerData();
		$this->getAchievementCategoriesData();
	}

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

	private function validateData($input)
	{
		if(empty($input) || $input == "")
		{
			return "";
		} else {
			return $input;
		}
	}

	public function getDetails($single_details)
	{
		$this->title = $single_details["title"];
		$this->message = $single_details["message"];

		if(empty($single_details["image_url"]))
		{
			$this->image = "NULL";
		} else {
			$this->image = $this->imagePath . $single_details["image_url"];
		}

		$this->buildAchievement();
	}

	public function getAdminDetails($admin_achievements,$catArray,$trigArray)
	{
		if (empty($admin_achievements))
		{
			$this->ac = "File not Found.";
		} else {
			$this->id = $admin_achievements["ID"];
			$this->title = $admin_achievements["title"];
			$this->message = $admin_achievements["message"];
			$this->trigger = $this->buildOption($trigArray,$admin_achievements["trigID"],$admin_achievements["trigger_title"]);
			$this->category = $this->buildOption($catArray,$admin_achievements["catID"],$admin_achievements["c_name"]);
			
			if (empty($admin_achievements["image_url"]))
			{
				$this->image = "NULL";
			} else {
				$this->image = $admin_achievements["image_url"];
			}

			$this->opt_visib = array(array("ID"=>"Unsichtbar","name"=>"Unsichtbar"),array("ID"=>"Sichtbar","name"=>"Sichtbar"));

			if($admin_achievements["ac_visibility"] == "Sichtbar")
			{
				$this->visib = $this->buildOption($this->opt_visib,$admin_achievements["ac_visibility"],"Sichtbar");
			} elseif ($admin_achievements["ac_visibility"] == "Unsichtbar") {
				$this->visib = $this->buildOption($this->opt_visib,$admin_achievements["ac_visibility"],"Unsichtbar");
			} else {
				$this->visib = $this->buildOption($this->opt_visib,$admin_achievements["ac_visibility"],"Wird gelöscht");
			}
			
			$this->buildAdminAchievement();
		}
	}
	
	public function getBasicDetails($basic)
	{
		$this->id = $basic["ID"];
		$this->title = $basic["title"];
		
		$this->buildBasicAchievement();
	}

	private function buildOption($optArr,$selected_id,$selected_name)
	{
		$optGUI = file_get_contents($this->templateAdminPath . "option.html");

		if(empty($selected_id))
		{
			$output = "<option value='default' selected>Kein Angabe";
		} else {
			$output = "<option value='" . $selected_id . "' selected>" . $selected_name;
		}

		foreach ($optArr as $option)
		{
			if($option["ID"] !== $selected_id)
			{
				$output .= str_replace(array("--VALUE--","--NAME--"), array($option["ID"],$option["name"]),$optGUI);
			}
		}

		return $output;
	}

	private function buildAchievement()
	{
		$this->ac_template = file_get_contents($this->templatePath . "single_achievement.html");

		if($this->image == "NULL")
		{
			$this->ac = str_replace($this->singleArr, array($this->imagePath . "keinbild.jpg",$this->title,$this->message),$this->ac_template);
		} else {	
			if(file_exists($this->image))
			{
				$this->ac = str_replace($this->singleArr, array($this->image,$this->title,$this->message), $this->ac_template);
			} else {
				echo $this->ac = $this->image;
				$this->ac = str_replace($this->singleArr, array("Hier oder?",$this->title,$this->message), $this->ac_template);
			}
		}
	}

	private function buildAdminAchievement()
	{
		$this->ac_template = file_get_contents($this->templateAdminPath . "ac_list.html");

		if($this->image == "NULL")
		{
			$this->ac .= str_replace($this->adminArr, array($this->id,$this->title,"KeinBild",$this->message,$this->trigger,$this->category,$this->visib), $this->ac_template);
		} else {
			if(file_exists($this->imagePath . $this->image))
			{
				$this->ac = str_replace($this->adminArr, array($this->id,$this->title,$this->image,$this->message,$this->trigger,$this->category,$this->visib), $this->ac_template);
			} else {
				$this->ac = str_replace($this->adminArr, array($this->id,$this->title,"Error",$this->message,$this->trigger,$this->category,$this->visib), $this->ac_template);
			}
		}
	}
	
	private function buildBasicAchievement()
	{
		$this->ac_template = file_get_contents($this->templatePath . "ac_small.html");
		
		$this->ac = str_replace($this->basicArr,array($this->id,$this->title),$this->ac_template);
	}

	public function displayAchievement()
	{
		return $this->ac;
	}
	
	public function getAdminAchievement($id)
	{
		$this->id = $id;
		$this->getAchievementData();
		
		return array("id" => $this->id, "name" => $this->name, "image" => $this->image, "message" => $this->message, "trigger" => $this->trigger, "category" => $this->category, "visibility" => $this->visibility);
	}

	public function getAcCategories()
	{
		return $this->cat_array;
	}

	public function getAcTrigger()
	{
		return $this->trig_array;
	}
}
	
?>