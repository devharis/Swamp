<?php
namespace Model;
class AdminHandler {
	private $m_db = NULL;
	
	public function __construct (Database $db) {
		$this->m_db = $db;
	}
	
		
	/**
	 * Sends a sqlquery to Database.php->GetUsers returns the users in a UserArray.
	 * @return UserArray
	 * 
	 */
	public function GetUsers() {
		return $this->m_db->GetAllInstances(User::CLASS_NAME, "SELECT * FROM " . User::TABLE_NAME);
	}
	
	/**
	 * Returns the last value of a string, which will be the id of the user.
	 * @return string id
	 * 
	 */
	public function ExplodeString($string) {
		$content = explode("=", $string);
		$ret = end($content);
	}
	
	/**
	 * Sends a sqlquery to Database.php->RunPreparedQuery that deletes the user from the database.
	 * @param int $UserID
	 */
	public function DeleteUser($userID) {
		$sql = "DELETE FROM " . User::TABLE_NAME . " WHERE m_id = ?";
		return $this->m_db->RunPreparedQuery($sql,"i", array($userID));
	}
	
	public function Test(Database $db, NewUserHandler $nu_h) {
		$sut = new AdminHandler($db);
		
		$usersAtStart = count($sut->GetUsers());
		
		
		
		if (count($sut->GetUsers()) === 0) {
			echo "GetUsers returnerade 0 användare trots att det finns min en användare";
			return FALSE;
		} 
		
		
		$testUser = new User();
		$testUser = $testUser->Create("", "testare", "testiiiiing");
		$testUser = $nu_h->AddUser($testUser);
		
		foreach ($sut->GetUsers() as $user) {
			if ($user->getUsername() === "testare") {
				$id = $user->getId();
			}
		}
		
		if (count($sut->GetUsers()) === $usersAtStart + 1) {
			$sut->DeleteUser($id);
			if (count($sut->GetUsers()) != $usersAtStart) {
				echo "Antalet användare är fel efter delete";
				return FALSE;
			}
		}
		
		return TRUE;
	}
}
?>