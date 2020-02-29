<?php

/* Ein Dank geht an den Autor Corvin Gr�ning mit seinem Artikel "Ein eigens Template System entwickeln" (Link: http://www.webmasterpro.de/coding/article/php-ein-eigenes-template-system.html). Es war eine wunderbar erkl�rte Grundlage f�r dieses System! */

class template {
         private $templateDir = "template/";
         private $templateFile = "";
         private $templateName = "";
         private $template = "";
         private $sub_templates = array();
         private $leftDelimiter = '{$';
         private $rightDelimiter = '}';
         private $leftDelimiterF = '{';
         private $rightDelimiterF = '}';
         private $leftDelimiterC = '\{\*';
         private $rightDelimiterC = '\*\}';

         public function __construct($tpl_dir = "") {
                 if (!empty($tpl_dir))
                 {
                         $this->templateDir = $tpl_dir;
                 }
         }

         public function load($file) {
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
         }

         public function assign(string $placeholder, string $replacement):template {
                 
                $temp = explode(".",$placeholder,2);
                
                if(count($temp) == 1)
                {
                        $this->template = str_replace($this->leftDelimiter .$placeholder.$this->rightDelimiter,$replacement, $this->template);
                } else {
                        list($placeholder_first,$placeholder_remainder) = $temp;
                        $this->sub_templates[$placeholder_first]->assign($placeholder_remainder,$replacement);
                }                

                return $this;
         }

         public function assign_subtemplate(string $placeholder, $replacement):template
         {
                $temp = explode(".",$placeholder,2);

                if(count($temp) == 1)
                {
                        if(is_string($replacement))
                        {
                                $this->sub_templates[$placeholder] = new template();
                                $this->sub_templates[$placeholder]->load($replacement);
                                return $this->sub_templates[$placeholder];
                        }

                        if(get_class($replacement) == "template")
                        {
                                $this->sub_template[$placeholder] = $replacement;
                                return $replacement;
                        }
                } else {
                        list($placeholder_first,$placeholder_remainder) = $temp;
                        $this->sub_templates[$placeholder_first]->assign_subtemplate($placeholder_remainder,$replacement);

                        return $this->sub_template[$placeholder_first];
                }
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
                foreach($this->sub_templates as $key=>$sub) 
                {
                        $this->assign($key,$sub->display());
                }
                echo $this->template;
         }

         public function r_display() {
                 return $this->template;
         }
}
?>