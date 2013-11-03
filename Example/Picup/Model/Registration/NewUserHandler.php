<?php 
namespace Model;
class NewUserHandler {
	private $m_db = NULL;
	private $m_user = NULL;
	private $m_validation = NULL;
	
	const NOT_VALID_USERNAME = 1;
	const ADD_USER_FAILED = 2;
	const USERNAME_EXIST = 3;
	
	public function __construct(Database $db, Validation $val) {
		$this->m_db = $db;
		$this->m_validation = $val;
	}
	
	/**
	 * Inserts a user into the database.
	 * @return bool
	 * 
	 */
	public function AddUser (User $user) {		
		$username = $this->m_validation->StrValNonHTML($user->getUsername());
		$password = $user->getPassword();		
		$role = User::STANDARD_USER_ROLE;
		
		if(!$this->m_validation->IsValidUsername($username)) {
			return self::NOT_VALID_USERNAME;
		} else if (!$this->CheckUsername($username)) {
			return self::USERNAME_EXIST;
		}
		
		$sql = "INSERT INTO " . User::TABLE_NAME . " (m_username, m_password, m_role) VALUES(?, ?, ?)";
		
		$ret = $this->m_db->RunPreparedQuery($sql, "sss", array(&$username,
																&$password,
																&$role));
											
		if (!$ret) {
			return self::ADD_USER_FAILED;
		}
		$ret;
	}
	
	/**
	 * Checks if a username already exists in the database.
	 * @return bool
	 * 
	 */
	public function CheckUsername ($username) {	
	 	$sql = "SELECT * FROM " . User::TABLE_NAME . " WHERE m_username = ?";
		if (count($this->m_db->RunPreparedSelectQuery(User::CLASS_NAME, $sql, "s", array(&$username))) === 0) {
			return TRUE;
		}
		return FALSE;
	}
		
	public function Test() {
		$user = new User();
		if ($this->CheckUsername($user->Create("", "admin", "asdsad")) == TRUE) {
			echo "Användarnamnet godkänndes trots att det finns i databasen.";
			return FALSE;
		} else if ($this->CheckUsername($user->Create("", "Apan123", "Tombs")) == FALSE) {
			echo "Användarnamnet godkänndes inte trots att det inte finns i databasen";
			return FALSE;
		}
		return TRUE;		
	}
}
?>