<?php

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

         public function assign($replace,$replacement) {
                 $this->template = str_replace($this->leftDelimiter .$replace.$this->rightDelimiter,$replacement, $this->template);
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

                 /* Diese Zeile macht es m�glich innerhalb des HTML-Templates Kommentare zu verfassen. Da die Funktion preg_replace mit PHP 7 deaktiviert wurde, ist
                    preg_replace_callback die einzige Alternative. Da ich aber keine brauchbare Callback-Funktion habe, ist sie vorl�ufig deaktiviert. */

                 //$this->template = preg_replace_callback("/" .$this->leftDelimiterC . "(.*)" . $this->rightDelimiterC ."/isUe","",$this->template);
         }

         public function display() {
                 echo $this->template;
         }

         public function r_display() {
                 return $this->template;
         }
}
?>