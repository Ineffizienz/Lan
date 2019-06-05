<?php
class Achievement {
	
	private $DBC = "";
	private $id = "";
	private $title = "";
	private $message = "";
	private $image = "";
	private $trigger = "";
	private $category = "";
	private $s_cat = array();
	private $visib = "";
	private $ac_template = "";
	private $ac = "";
	private $imagePath = "";
	private $templatePath = "";
	private $templateAdminPath = "";
	private $adminArr = array("--ID--","--NAME--","--IMAGE_URL--","--MESSAGE--","--TRIGGER--","--CATEGORY--","--VISIBILITY--");
	private $singleArr = array("--IMAGE--","--HEADLINE--","--TEXT--");
	private $basicArr = array("--ID--","--HEADLINE--");


	public function templateFolder()
	{
		$this->templatePath = "template/part/";

		return $this->templatePath;
	}

	public function templateAdminFolder()
	{
		$this->templateAdminPath = "template/admin/part/";

		return $this->templateAdminPath;
	}

	public function imageFolder()
	{
		$this->imagePath = "images/achievements/";

		return $this->imagePath;
	}
	
	public function getDetails($single_details)
	{
		$this->title = $single_details["title"];
		$this->message = $single_details["message"];

		if(empty($single_details["image_url"]))
		{
			$this->image = "NULL";
		} else {
			$this->image = $this->imageFolder() . $single_details["image_url"];
		}

		$this->buildAchievement();
	}

	public function getAdminDetails($con,$admin_achievements,$catArray,$trigArray)
	{
		if (empty($admin_achievements))
		{
			$this->ac = "File not Found.";
		} else {
			$this->DBC = $con;
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

	public function buildOption($optArr,$selected_id,$selected_name)
	{
		$optGUI = file_get_contents($this->templateAdminFolder() . "option.html");

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

	public function buildAchievement()
	{
		$this->ac_template = file_get_contents($this->templateFolder() . "single_achievement.html");

		if($this->image == "NULL")
		{
			$this->ac = str_replace($this->singleArr, array($this->imageFolder() . "keinbild.jpg",$this->title,$this->message),$this->ac_template);
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

	public function buildAdminAchievement()
	{
		$this->ac_template = file_get_contents($this->templateAdminFolder() . "ac_list.html");

		if($this->image == "NULL")
		{
			$this->ac .= str_replace($this->adminArr, array($this->id,$this->title,"KeinBild",$this->message,$this->trigger,$this->category,$this->visib), $this->ac_template);
		} else {
			if(file_exists($this->imageFolder() . $this->image))
			{
				$this->ac = str_replace($this->adminArr, array($this->id,$this->title,$this->image,$this->message,$this->trigger,$this->category,$this->visib), $this->ac_template);
			} else {
				$this->ac = str_replace($this->adminArr, array($this->id,$this->title,"Error",$this->message,$this->trigger,$this->category,$this->visib), $this->ac_template);
			}
		}
	}
	
	public function buildBasicAchievement()
	{
		$this->ac_template = file_get_contents($this->templateFolder() . "ac_small.html");
		
		$this->ac = str_replace($this->basicArr,array($this->id,$this->title),$this->ac_template);
	}

	public function displayAchievement()
	{
		return $this->ac;
	}	
}
	
?>