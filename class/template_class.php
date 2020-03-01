<?php

require_once dirname(__FILE__,2) . "/include/init/constant.php";
require_once LIB.'helpers.php';

/* Ein Dank geht an den Autor Corvin Gr�ning mit seinem Artikel "Ein eigens Template System entwickeln" (Link: http://www.webmasterpro.de/coding/article/php-ein-eigenes-template-system.html). Es war eine wunderbar erkl�rte Grundlage f�r dieses System! */

class template {
	private $templateDir = "template/";
	private $templateFile = "";
	private $templateName = "";
	private $template = "";
	private $leftDelimiter = '{$';
	private $rightDelimiter = '}';
	private $leftDelimiterF = '{';
	private $rightDelimiterF = '}';
	private $leftDelimiterC = '\{\*';
	private $rightDelimiterC = '\*\}';

	/**
	 * 
	 * @param string $path Path to either a template file (all that end with .html) or a template directory.
	 */
	public function __construct(string $path = "") {
		if(endsWith($path, '.html'))
			$this->load ($path);
		elseif(!empty($path))
			$this->templateDir = $path;
	}

	public function load($file): template {
		$this->templateName = $file;
		$this->templateFile = $this->templateDir.$file;

		if (!empty($this->templateFile))
		{
			if (file_exists($this->templateFile))
			{
				$this->template = file_get_contents($this->templateFile);
			} else {
				return false;
			}
		} else {
			return false;
		}

		$this->parseFunctions();
		return $this;
	}

	public function assign(string $placeholder, string $replacement): template {
		$this->template = str_replace($this->leftDelimiter .$placeholder.$this->rightDelimiter,$replacement, $this->template);             

		return $this;
	}

	/**
	 * Applies a whole array of replacements.
	 * 
	 * May either be a single assoc array of replacements or even multiple rows of such arrays. In the latter case the template will be copied multiple times.
	 * 
	 * In case multiple rows are given, all placeholders that are present in the template should be replaced, otherwise they will be present multiple times afterwards.
	 *
	 * @param array $data An assoc array of replacements or even multiple rows of such arrays.
	 * @param bool clear_if_empty If true: if an empty 2D array is given, this template will be empty as a result. If false it will be unchanged.
	 * @return template
	 */
	public function assign_array(array $data, bool $clear_if_empty = true): template
	{
		if(is_multi($data))
			return $this->assign_array_2D($data, $clear_if_empty);
		return $this->assign_array_1D ($data);
	}
	
	/**
	 * Applies multiple assignments from an assoc array at once
	 *
	 * @param array $assignments An assoc array of assignments (placeholder => replacement)
	 * @return template
	 */
	protected function assign_array_1D(array $assignments): template
	{
		foreach($assignments as $placeholder => $replacement)
			$this->assign($placeholder, $replacement);
		return $this;
	}
	
	/**
	 * Copies the template multiple times, once for each row in the given array, and then applies the given replacements.
	 * 
	 * If not all placeholders are replaced, the result will have the same placeholder multiple times. As a result it will never be useful to do any more assignmenst after this.
	 *
	 * @param array $rows An array of rows (which are associative arrays containing placeholder => replacement).
	 * @param bool clear_if_empty If true: if an empty array is given, this template will be empty as a result. If false it will be unchanged.
	 * @return template
	 */
	protected function assign_array_2D(array $rows, bool $clear_if_empty = true): template
	{
		if(count($rows) > 0 || $clear_if_empty)
		{
			$template = clone $this;
			$this->template = "";
			foreach($rows as $row)
			{
				$tmp = clone $template;
				$tmp->assign_array_1D($row);
				$this->template .= $tmp->r_display();
			}
		}
		return $this;
	}

	public function assign_subtemplate(string $placeholder, $replacement): template
	{
		if(is_string($replacement))
		{
			$tmp = new template();
			$tmp->load($replacement);
			$this->assign($placeholder, $tmp->r_display());
		}
		elseif(get_class($replacement) == "template")
			$this->assign($placeholder, $replacement->r_display());
		return $this;
	}

	public function parseFunctions() {

		$this->template = preg_replace_callback("/" .$this->leftDelimiterF ."include file=(.*)\.(.*)" .$this->rightDelimiterF."/isU",
		function($matches){
			foreach($matches as $match)
			{
				return file_get_contents($this->templateDir . substr(rtrim($match,"}"),14));
			}
		},
		$this->template);

		$this->template = preg_replace("/" .$this->leftDelimiterC . "(.*)" . $this->rightDelimiterC ."/isU","",$this->template);
	}

	public function display() {
		echo $this->template;
	}

	public function r_display() {
			return $this->template;
	}
}
?>