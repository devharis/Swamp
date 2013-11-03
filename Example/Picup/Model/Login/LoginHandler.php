<?php
namespace Model;
class LoginHandler {
	private $objtype = "object";
	private $m_db = NULL;
	const CLASS_NAME = "Model\User";
	const USER_SESSION = "loggedin";
	
	public function __construct(Database $db) {
		$this->m_db = $db;
	}
	
	/**
	 * Sends a sqlquery to Database.php->GetUsers returns the users in a UserArray.
	 * @return UserArray
	 * 
	 */
	 public function Authenticate(User $user) {
	 	$uname = $user->getUsername();
	 	$pwd = $user->getPassword();
				
	 	$sql = "SELECT * FROM " . User::TABLE_NAME ." WHERE m_username = ? AND m_password = ?";
		$ret = $this->m_db->RunPreparedSelectQuery(LoginHandler::CLASS_NAME, $sql, "ss", array(&$uname, &$pwd));
		
		return $ret;
	 }
	
	/**
	 * Controlls the session self::USER_SESSION.
	 * @return obj User if logged in, NULL if not.
	 * 
	 */
	public function GetUserSession() {
		if (isset($_SESSION[self::USER_SESSION])) {
			if (gettype($_SESSION[self::USER_SESSION]) === $this->objtype) {
				return $_SESSION[self::USER_SESSION];
			}			
		}
		return NULL;
	}
	
	/**
	 * Puts the user in logged out-mode by putting the session to 0.
	 * 
	 */		
	public function DoLogOut() {
		$_SESSION[self::USER_SESSION] = 0;
	}	
	
	/**
	 * Checks so that the username and password is correct by controlling that the user is a object and not false.
	 * @param $username
	 * @param $password
	 * @return bool
	 * 
	 */
	public function DoLogIn($username, $password) {
		$user = new User();
		$user = $this->Authenticate($user->Create("", $username, $password));
		$user = array_values(array_filter($user));
		
		if (Count($user) > 0 && gettype($user[0]) === $this->objtype)  {
			$_SESSION[self::USER_SESSION] = $user[0];
			return TRUE;
		}
		return FALSE;
	}
	
	/**
	 * Test-function that tests the class's methods.
	 * @return bool
	 * 
	 */
	public function Test(Database $db, \View\LoginView $lv) {
	require_once 'Model\User.php';
	
		$sut = new LoginHandler($db);
		
		$sut->DoLogOut(); //Sätter läget till utloggad.
		
		if ($sut->IsLoggedIn() == TRUE) {
			echo "FEL! Användaren har läget: inloggad";
			return FALSE;
		} else if ($sut->DoLogIn("hej", $lv->hashPassword("då")) == TRUE) {
			echo "Inloggningen godkänndes trots fel användarnamn och lösenord.";
			return FALSE;
		} else if ($sut->DoLogIn("admin", $lv->hashPassword("då")) == TRUE) {
			echo "Inloggningen lyckades trots fel lösenord.";
			return FALSE;
		} else if ($sut->DoLogIn("william", $lv->hashPassword("password")) == TRUE) {
			echo "Inloggningen lyckades trots fel användarnamn.";
			return FALSE;
		} else if ($sut->DoLogIn("admin", $lv->hashPassword("password")) == FALSE) {
			echo "Inloggningen misslyckades trots rätt användarnamn och lösenord.";
			return FALSE;
		} else if ($sut->IsLoggedIn() == FALSE) {
			echo "Det gick inte logga in.";
			return FALSE;
		} else {
			$sut->DoLogOut();
			if ($_SESSION[self::USER_SESSION] == 0) {
				return TRUE;
			} else { //Om sessionen inte är i 0-läge har utloggningen misslyckats.
				echo "Användaren är inte utloggade trots utloggningsförsök.";
				return FALSE;
			}
		}		
	}
}
?>