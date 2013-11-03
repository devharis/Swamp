<?php 
namespace Model;

class User {
	public $m_id;
	public $m_username;
	public $m_password;
	public $m_role;	
	
	const TABLE_NAME = "picup_user";
	const CLASS_NAME = "Model\User";
	const STANDARD_USER_ROLE = "User";
	const STANDARD_ADMIN_ROLE = "Administrator";
	
  /**
   * This is used instead of a constuctor to let the objects be created with different paramentrs
   * because I use fetch_object to create objects.
   * 
   * @param int $id
   * @param string $username
   * @param string $password
   * @param string $role
   * @return UserObject
   */
	public static function Create($id, $username, $password) {
		$ret = new User();
		
		$ret->m_id = $id;
		$ret->m_username = $username;
		$ret->m_password = $password;
		
		return $ret;
	}
	
	public function getID() {
		return $this->m_id;
	}
	
	public function getUsername() {
		return $this->m_username;
	}
	
	public function getPassword() {
		return $this->m_password;
	}
	
	public function getRole() {
		return $this->m_role;
	}
}
?>