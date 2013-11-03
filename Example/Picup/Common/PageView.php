<?php
 namespace Common;
 
 class PageView {
	private $metaTags = array();
	private $scriptTags = array();
	private $charset;
	
	/**
	 * Called when creating new object of PageView()
	 * @param string $charset
	 */
	public function __construct($charset = "utf-8") {
		$this->charset = $charset;
	}
	
	/**
	 * Adds a stylesheet to the head of the document
	 * @param urlstring $href(url to stylesheet)
	 */
	public function AddStyleSheet($href) {
		$this->metaTags[] = "<link href='$href' rel='Stylesheet' type='text/css' />";
	}
	
	/**
	 * Builds metatags and csstags as HTML string
	 * @return string
	 */
	public function BuildHeadTags() {		
		$this->AddStyleSheet("Stylesheet/jquery-ui-1.9.0.custom.css");
		$this->AddStyleSheet("Stylesheet/page.css");
		$this->AddStyleSheet("Stylesheet/slide.css");
		$this->AddStyleSheet("Stylesheet/jquery.horizontal.scroll.css");
		$this->AddStyleSheet("http://fonts.googleapis.com/css?family=Signika:400,700");
		
		$retValue = "";
		
		foreach ($this->metaTags as $tag) {
			$retValue .= $tag;
		}
		
		return $retValue;
	}
	
	/**
	 * Adds a scripttag to the document
	 * @param urlstring $href(url to javascript)
	 */
	public function AddJavaScript($href) {
		$this->scriptTags[] = "<script type='text/javascript' src='$href'></script>";
	}
	
	public function BuildScriptTags() {
		$this->AddJavaScript("JavaScript/jquery-1.8.2.js");
		$this->AddJavaScript("JavaScript/jquery-ui-1.9.0.custom.js");
		$this->AddJavaScript("JavaScript/page.js");
		$this->AddJavaScript("JavaScript/slide.js");
		$this->AddJavaScript("JavaScript/jquery.horizontal.scroll.js");
		$this->AddJavaScript("JavaScript/jquery.ae.image.resize.min.js");
		
		$retValue = "";
		
		foreach ($this->scriptTags as $tag) {
			$retValue .= $tag;
		}
		
		return $retValue;
	}
	
	/**
	 * Returns a HTML page
	 * @param string $title
	 * @param string $body
	 * @return string html
	 */
	public function GetHTMLPage($title, $body) {
		$head = $this->BuildHeadTags(false);
		$scripts = $this->BuildScriptTags(false);
		
		$html = "
			<!DOCTYPE html>
			<html>
				<head>
				<title>$title</title>
				<meta http-equiv='content-type' content='text/html; charset=$this->charset'>
				$head
				</head>			
				<body>
					<div id='wrapper'>
						<a href='index.php' id='LogoType'><img src='Stylesheet/images/Logo.png' /></a>
						<div id='dimmer'></div>
				   		$body
				   		$scripts
				   	</div>
				</body>
			</html>
		";
		return $html;
	}
 }
?>