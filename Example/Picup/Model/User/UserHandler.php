<?php
namespace Model;
class UserHandler {
	private $m_db = NULL;
	private $m_user = NULL;
	private $m_validation = NULL;
	
	const USER_NOT_LOGGED_IN = 1;
	const NOT_VALID_USER = 2;
	const NOT_VALID_ID = 3;
	const UPDATE_FAILED = 4;
	const DELETE_FAILED = 5;
	const NOT_VALID_USERNAME = 6;
	
	public function __construct (Database $db, LoginHandler $lh, Validation $val) {
		$this->m_db = $db;
		$this->m_user = $lh->GetUserSession();
		$this->m_validation = $val;
	}
	
	
	/**
	 * Sends a sqlquery to Database.php to get an User.
	 * @param int $id
	 * @return User
	 * 
	 */
	public function GetUser($id) {
		if (!$this->m_validation->NumberValidation($id)) {
			return self::NOT_VALID_ID;
		}
		
		$sql = "SELECT * FROM " . User::TABLE_NAME . " WHERE m_id = ?";
		$user = $this->m_db->RunPreparedSelectQuery(User::CLASS_NAME, $sql,"i", array($id));
		$user = current($user);
		
		if (!$user) {
			return self::NOT_VALID_ID;
		}
		return $user;
	}
	
	/**
	 * Sends a sqlquery to Database.php to delete a user.
	 * @param int $userid
	 * @return Bool
	 * 
	 */
	public function DeleteUser($userid) {
		if (!$this->m_validation->NumberValidation($userid)) {
			return self::NOT_VALID_ID;
		} else if ($this->m_user === NULL) {
			return self::USER_NOT_LOGGED_IN;
		}
		
		$sql = "DELETE FROM " . User::TABLE_NAME . " WHERE m_id = ?";
		$ret = $this->m_db->RunPreparedQuery($sql,"i", array(&$userid));
		
		if (!$ret) {
			return self::DELETE_FAILED;
		}
		return $ret;
	}
	
	/**
	 * Sends a sqlquery to Database.php to edit an User.
	 * @param $user User
	 * @return Bool
	 * 
	 */  
  	public function EditUser(User $user) {
  		$userID = $user->getID();
		$username = $this->m_validation->StrValNonHTML($user->getUsername());
		$password = $user->getPassword();
  		
  		if ($this->m_user === NULL) {
  			return self::USER_NOT_LOGGED_IN;
  		} else if (!$this->m_validation->NumberValidation($userID)) {
  			return self::NOT_VALID_ID;	
		} else if (!$this->m_validation->IsValidUsername($username)) {
			return self::NOT_VALID_USERNAME;
		}
		
		
		$sql = "UPDATE " . User::TABLE_NAME . " U
        SET U.m_username = ?, U.m_password = ? WHERE U.m_id = ?";
		
        $ret = $this->m_db->RunPreparedQuery($sql, "ssi", array(&$username, &$password, &$userID));
		
		if (!$ret) {
			return self::UPDATE_FAILED;
		}
		return $ret;
    }
	
	/**
	 * Sends a sqlquery to Database.php to get the 5 most recent images by the user.
	 * @param $userID int
	 * @return Array with Images
	 * 
	 */  
	public function GetRecentImages($userID) {
		$sql = "SELECT * FROM " . Image::TABLE_NAME . " WHERE m_userid = ? ORDER BY m_dateadded DESC LIMIT 0 , 5";
		return $this->m_db->RunPreparedSelectQuery(Image::CLASS_NAME, $sql,"i", array($userID)); 
	}
	
	public function Test($db, $lh) {
		$sut = new UserHandler($db, $lh, null);
		
		$user = $sut->GetUser(1);
		if (gettype($user) != "object") {
			echo "Det gick inte att hämta en användare.";
			return FALSE;
		}
		
		$editedUser = new User();
		$editedUser = $editedUser->Create($user->getID(), "asdasad", $user->getPassword());
		
		if ($sut->EditUser($editedUser) === FALSE) {
			echo "Det gick inte att editera en användare";
			return FALSE;	
		}
		$sut->EditUser($user);
		
		return TRUE;
	}
}
?>