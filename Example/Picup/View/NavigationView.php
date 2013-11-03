<?php
namespace View;

class NavigationView {
	const INSTALL = "InstallApplication";
	const INDEX = "index.php";
	const ADMIN = "Admin";
	const IMAGE = "Image";
	const USER = "User";
	
	/**
	 * Checks and returns the current controllerstate.
	 * @return string
	 * 
	 */
	public static function GetController () {
		if(isset($_GET[self::ADMIN])) {
			return self::ADMIN;
		} else if (isset($_GET[self::IMAGE])) {
			return self::IMAGE;
		} else if (isset($_GET[self::USER])) {
			return self::USER;
		} else if (isset($_GET[self::INSTALL])) {
			return self::INSTALL;
		}
		return self::INDEX;
	}
	
	public static function GetControllerVariable () {
		return $_GET[self::GetController()];
	}
	
	/**
	 * Refreshes current site
	 * 
	 */	
	public function Refresh() {
		$url = $_SERVER['REQUEST_URI'];
		header("location: $url");		
	}
	
	/**
	 * Sets the querystring to Admin
	 * 
	 */	
	public function SetAdmin() {
		$url = "?" . \View\NavigationView::ADMIN;
		header("location:  $url");	
	}	
	
	/**
	 * Sets the querystring to Index
	 * 
	 */	
	public function SetIndex() {
		$url = \View\NavigationView::INDEX;
		header("location:  $url");	
	}
}
?>