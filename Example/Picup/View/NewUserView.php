<?php
namespace View;
class NewUserView {	
	private $loginname = "loginname";
	private $password = "password";
	private $password2 = "password2";
	private $register = "registerbutton";
	private $human = "antibot";
	
	const NEW_USER_HEADER = "<h1>New user</h1>";
	const NEW_USER_PWDNOMATCH = 4;
	const NEW_USER_USEREXIST = 5;
	const NEW_USER_PWDINVALID = 6;
	const NEW_USER_CAPTCHANOMATCH = 7;
	const ADD_FAILED = 8;
	
	/**
	 * Render a Register-form
	 * @return string HTML
	 * 
	 */
	public function DoRegisterBox(){ // Returnerar ett inloggninsformulär.
		return 
			"<form action='' method='POST' class='clearfix'>
				<label class='grey' for=$this->loginname>Name:</label>
				<input class='inputfield' type='text' id='signupName' name=$this->loginname maxlength='40' />
				<label class='grey' for=$this->password>Password:</label>
				<input class='inputfield' type='password' name=$this->password id='signupPwd' />
				<label class='grey' for=$this->password2>Password again:</label>
				<input class='inputfield' type='password' name=$this->password2 id='signupPwd2' />
				<img src='Model/AntiBot.php' alt='Confirm Image' title='Confirm Image' id='confirmimage' />
				<input class='inputfield' type='text' name=$this->human id='antibot' maxlength='6' />
				<div class='clear'></div>
				<input type='submit' name=$this->register value='Register' class='formButton' />
			</form>
			"; 
	}
	
	/**
	 * Detects if the user presses the loginbutton.
	 * @return bool
	 * 
	 */
	public function TriedToRegister(){
		if (isset($_POST[$this->register])) {
			return TRUE;
		}
		return FALSE;
	}
	
	/**
	 * Returns the username from inputbox "$this->loginname".
	 * @return string
	 * 
	 */
	public function GetUserName(){
		if (isset($_POST[$this->loginname])) {
			return $_POST[$this->loginname];
		}
		return NULL;
	}
	
	/**
	 * Returns the password from inputbox "$this->password".
	 * @return string
	 * 
	 */
	public function GetPassword(){
		if (isset($_POST[$this->password])) {
			return $_POST[$this->password];
		}
		return NULL;
	}
	
	/**
	 * Returns the confirmation password from inputbox "$this->password2".
	 * @return string
	 * 
	 */
	public function GetConfPassword(){
		if (isset($_POST[$this->password2])) {
			return $_POST[$this->password2];
		}
		return NULL;
	}
	
	/**
	 * Returns the "captcha" from inputbox "$this->human".
	 * @return string
	 * 
	 */
	public function GetCaptchaString() {
		if (isset($_POST[$this->human])) {
			return $_POST[$this->human];
		}
		return NULL;
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
				case self::NEW_USER_PWDNOMATCH:
					return "<p class='ui-state-error'>Passwords didn't match.</p>";
				
				case self::NEW_USER_PWDINVALID:
					return "<p class='ui-state-error'>Invalid username or password(6-18 characters of the type: 'a-z A-Z 0-9').</p>";
				
				case self::NEW_USER_CAPTCHANOMATCH:
					return "<p class='ui-state-error'>You must enter the characters on the image correctly</p>";
				
				case self::ADD_FAILED:
					return "<p class='ui-state-error'>Something went wrong when the user was to be added to the database</p>";
				
				case \Model\NewUserHandler::NOT_VALID_USERNAME:
					return "<p class='ui-state-error'>Username is invalid.</p>";
				
				case \Model\NewUserHandler::ADD_USER_FAILED:
					return "<p class='ui-state-error'>User couldn't be stored in the database.</p>";
				
				case \Model\NewUserHandler::USERNAME_EXIST:
					return "<p class='ui-state-error'>Username is taken.</p>";
					
			}
	}
}
?>