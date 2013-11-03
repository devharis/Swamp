<?php
/**
 * Created by PhpStorm.
 * User: haris
 * Date: 2013-11-03
 * Time: 17:28
 */
    class PageView{
        private $charset;
        private $styleTags = array();
        private $scriptTags = array();

        public function __construct($charset = "utf-8"){
            $this->$charset = $charset;
        }

        public function AddStyleSheet($href){
            $this->styleTags[] = "<link href='$href' rel='Stylesheet' type='text/css' />";
        }

        public function BuildStyleTags(){
            $retValue = "";

            $this->AddStyleSheet("css/main.css");

            foreach($this->styleTags as $mTag){
                $retValue .= $mTag;
            }

            return $retValue;
        }

        public function AddJavaScript($href) {
            $this->scriptTags[] = "<script type='text/javascript' src='$href'></script>";
        }

        public function BuildScriptTags() {
            $retValue = "";

            $this->AddJavaScript("JavaScript/jquery-1.8.2.js");

            foreach ($this->scriptTags as $tag) {
                $retValue .= $tag;
            }

            return $retValue;
        }

        public function GetHTML5Page($title, $body) {
            $head = $this->BuildStyleTags();
            $scripts = $this->BuildScriptTags();

            $html = "
			<!DOCTYPE html>
			<html>
				<head>
				<title>$title</title>
				<meta http-equiv='content-type' content='text/html; charset=$this->charset'>
				$head
				</head>
				<body>
					<div id='Container'>
						<div id='Content'>
						    $body
						</div>
				   	</div>
				   	$scripts
				</body>
			</html>
		    ";
            return $html;
        }

    }