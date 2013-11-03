<?php 
namespace View;
	class InstallView {
		private $installbutton = "InstallApplication";
		private $uninstallbutton = "UninstallApplication";
		private $adminusr = "AdminUsername";
		private $adminpwd = "AdminPassword";
		
		const CREATE_USERTABLE_FAILED = 1;
		const CREATE_IMAGETABLE_FAILED = 2;
		const CREATE_COMMENTTABLE_FAILED = 3;
		const CREATE_IMAGERATINGTABLE_FAILED = 4;
		const CREATE_RELATIONS_FAILED = 5;
		const CREATE_SUCCESS = 6;
		const UNINSTALL_SUCCESS = 7;
		const UNINSTALL_FAILED = 8;
		
		public function RenderInstallForm () {
			return
				"<form action='' method='POST' enctype='multipart/form-data'>
					<label for=$this->adminusr>Admin username:</label> 
					<input type='text' name=$this->adminusr maxlength='40'/>
					<label for=$this->adminpwd>Admin password:</label> 
					<input type='password' name=$this->adminpwd />
					<input type='submit' value='Install application' name=$this->installbutton />
					<input type='submit' value='Uninstall application' name=$this->uninstallbutton />
				</form>";	
		}
		
		public function GetAdminUsername() {
			if (isset($_POST[$this->adminusr])) {
				return $_POST[$this->adminusr];
			}
			return NULL;
		}
		
		public function GetAdminPwd() {
			if (isset($_POST[$this->adminpwd])) {
				return $_POST[$this->adminpwd];
			}
			return NULL;
		}
		
		public function TriedToInstall() {
			if (isset($_POST[$this->installbutton])) {
				return TRUE;
			}
			return FALSE;
		}
		
		public function TriedToUninstall() {
			if (isset($_POST[$this->uninstallbutton])) {
				return TRUE;
			}
			return FALSE;
		}
		
		/**
		 * Hashes the password to sha256 after adding a salt
		 * @param string $password
		 */
		public static function hashPassword($password) {
			$salt = "hej123";
		    return hash("sha256", $salt.$password.$salt);
		}
		
		/**
		 * Prints various messages based on a state.
		 * @param int $messageId - is the state of the message.
		 * @return string HTML
		 */
		public function PrintUserMessage($messageId) {			
			switch ($messageId) {
				case self::CREATE_USERTABLE_FAILED:
					return "<p>Something happend when creating the user table</p>";
					break;
				case self::CREATE_IMAGETABLE_FAILED;
					return "<p>Something happend when creating the image table</p>";
					break;
				case self::CREATE_COMMENTTABLE_FAILED;
					return "<p>Something happend when creating the comment table</p>";
					break;
				case self::CREATE_IMAGERATINGTABLE_FAILED;
					return "<p>Something happend when creating the imagerating table</p>";
					break;
				case self::CREATE_RELATIONS_FAILED;
					return "<p>Something happend when creating the relations between the tables</p>";
					break;
				case self::CREATE_SUCCESS;
					return "<p>Install success!</p>";
					break;
				case self::UNINSTALL_SUCCESS;
					return "<p>Uninstall success!</p>";
					break;
				case self::UNINSTALL_FAILED;
					return "<p>Uninstall failed!</p>";
					break;
			}
		}
	}
?>