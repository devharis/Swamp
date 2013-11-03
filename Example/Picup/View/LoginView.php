<?php
namespace View;
class LoginView {
	private $loginname = "loginname";
	private $password = "password";
	private $submitbutton = "loginbutton";
	private $rememberme = "rememberme";
	private $logoutbutton = "logoutbutton";
	private $cookiepass = "password";
	private $cookielogin = "username";
	private $uSession = "loggedin";
	private $objtype = "object";
	
	const LOGIN_HEADER = "<h1>Member Login</h1>";
	const USER_LOGGED_IN = 1;
	const USER_LOGGED_OUT = 2;
	const USER_WRONG_PWD = 3;
	
	/**
	 * Render a LoginBox.
	 * @return string HTML
	 * 
	 */
	public function DoLoginBox(){ // Returnerar ett inloggninsformulär.
		return 
			"<form action='' method='POST' class='clearfix'>
				<label class='grey' for=$this->loginname>Username:</label> 
				<input class='inputfield' type='text' id='log' name=$this->loginname maxlength='40' />
				<label class='grey' for=$this->password>Password:</label>
				<input class='inputfield' type='password' id='pwd' name=$this->password />
				<label>
					<input type='checkbox' id='rememberme' name=$this->rememberme />
					Remember me						
				</label>
				<div class='clear'></div>
				<input type='submit' name=$this->submitbutton value='Login' class='formButton' />
			</form>";
	}
	
	/**
	 * Render a Logoutbox.
	 * @return string HTML
	 * 
	 */
	public function DoLogoutBox($user){
		return
			"
			<form action='" . $_SERVER['PHP_SELF'] . "' method='POST'>
				<a class='UserProfile' href='" . \View\NavigationView::INDEX . "?" . \View\NavigationView::USER . "=" . $user->getID() . "'>" . $user->getUsername() . "</a>
				<div class='clear'></div>
				<img src='Upload/ProfilePic/UnknownProfile.png' id='ProfilePic' />
				<div class='clear'></div>
				<input type='submit' value='Logout' name=$this->logoutbutton class='formButton' />
			</form>";
			
	}	
	
	/**
	 * Hashes the password to sha256 after adding a salt
	 * @param string $password
	 */
	public static function hashPassword($password) {
		//TODO: Generate unique salt for each user.
		$salt = "hej123";
	    return hash("sha256", $salt.$password.$salt);
	}
	
	/**
	 * Renders a controllpanel
	 * @param html $loginform containing html for a loginform
	 * @param html $registrationForm html for a registrationform.
	 * @param bool $loggedIn true if user is logged in.
	 * @return string HTML
	 * 
	 */
	public function ReturnAsControlPanel ($loginForm, $registrationForm, $uploadForm, $loggedIn) {
		$ret = NULL;
		$user = $loggedIn;
		
		$ret = "<div id='toppanel'>
				<div id='panel'>
				<a href='index.php' id='LogoType'><img src='Stylesheet/images/Logo.png' /></a>
					<div class='content clearfix'>";		
		$ret .=			"<div class='left'>
							$loginForm
						</div>";			
		if ($user == NULL) {
			$ret .=			"<div class='left'>
								$registrationForm
							</div>";			
		} else {
			$ret .=			"<div class='left'>
								$uploadForm
							</div>";			
		}
		$ret .=		"</div>
				</div>
				<div class='tab'>
					<ul class='login'>
						<li class='left'> </li>";
		if ($user == NULL) {		
			$ret .=			"<li>Hello Guest!</li>";			
		} else {		
			$ret .=			"<li>Profile</li>";				
		}			
		$ret .=			"<li class='sep'></li>
						<li id='toggle'>";
		if ($user == NULL) {			
			$ret .=			"<a id='open' class='open' href='#'>Log In | Register</a>";
		} else {
			$ret .=			"<a id='open' class='open' href='#'>Log Out | Upload</a>";			
		}
			$ret .=			"<a id='close' class='close' style='display: none;' href='#'>Close Panel</a>
						</li>
						<li class='right'> </li>
					</ul>
				</div>
			</div>";
		
		return $ret;
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
	 * Returns the password from passwordbox "$this->password".
	 * @return string HTML
	 * 
	 */
	public function GetPassword(){
		if (isset($_POST[$this->password])) {
			return $_POST[$this->password];
		}
		return NULL;		
	}
	
	/**
	 * Detects if the user presses the loginbutton.
	 * @return bool
	 * 
	 */
	public function TriedToLogIn(){
		if (isset($_POST[$this->submitbutton])) {
			return TRUE;
		}
		return FALSE;
	}
	
	/**
	 * Detects if the user presses the logoutbutton.
	 * @return bool
	 * 
	 */
	public function TriedToLogOut(){
		if (isset($_POST[$this->logoutbutton])) {
			return TRUE;
		}
		return FALSE;
	}
	
	/**
	 * Detects if a cookie with username and password is set.
	 * @return bool
	 * 
	 */
	public function CookieSet () {
		if (isset($_COOKIE[$this->cookielogin]) && isset($_COOKIE[$this->cookiepass])) {
			return TRUE;
		} else {
			return FALSE;
		}
	}
	
	/**
	 * Detects if the remember me checkbox is enabled.
	 * @return bool
	 * 
	 */
	public function RemberMe () {
		if (isset($_POST[$this->rememberme])) {
			return TRUE;
		}
		return FALSE;
	}
	
	public function GetCookieInformation() {
		$information = array();
		
		$information[0] = $_COOKIE[$this->cookielogin];
		$information[1] = $_COOKIE[$this->cookiepass];
		
		return $information;
	}
	
	/**
	 * Sets a cookie with the username and password.
	 * 
	 */
	public function RememberUser ($username, $password) {
		setcookie($this->cookielogin, $username, time()+60*60*24*365);
		$_COOKIE[$this->cookielogin] = $username;
		setcookie($this->cookiepass, $password, time()+60*60*24*365);
		$_COOKIE[$this->cookiepass] = $password;
	}
	
	/**
	 * Removes the logincookie.
	 * 
	 */
	public function ForgetUser () {
		setcookie($this->cookielogin, "", time()-600000);
		unset($_COOKIE[$this->cookielogin]);
		setcookie($this->cookiepass, "", time()-600000);
		unset($_COOKIE[$this->cookiepass]);
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
	 * Prints various messages based on a state.
	 * @param int $messageId - is the state of the message.
	 * @return string HTML
	 */
	public function PrintUserMessage($messageId) {
		$user = NULL;
		if (isset($_SESSION[$this->uSession]) && gettype($_SESSION[$this->uSession]) == $this->objtype) {
			$user = $_SESSION[$this->uSession];
		}				
		switch ($messageId) {
			case self::USER_LOGGED_IN:
				return "Welcome " . $user->GetUserName() . "";
				break;
			case self::USER_LOGGED_OUT;
				return "<p id='LogoutSucess'>You're logged out.</p>";
				break;
			case self::USER_WRONG_PWD;
				return "<p class='ui-state-error' id='LoginError'>Wrong username or password.</p>";
				break;
		}
	}
}
?>