<?php
namespace Controller;
class UserController {
	
	/**
	 * Renders the userdiv
	 * @param $u_view UserView
	 * @param $u_handler UserHandler
	 * @param $nav_view NavigationView
	 * @param $validator Validation
	 * @param $l_handler LoginHandler
	 * @param $nu_view NewUserView
	 * @return string HTML
	 * 
	 */
	public function DoControll(\View\UserView $u_view, \Model\UserHandler $u_handler, 
									\View\NavigationView $nav_view, \Model\Validation $validator, 
										\Model\LoginHandler $l_handler, \View\NewUserView $nu_view) {
											
		$userID = $nav_view->GetControllerVariable();
		$currentUser = $l_handler->GetUserSession();
		
			$ret = $u_handler->GetUser($userID);
			
			if (is_numeric($ret)) {
				return $u_view->PrintUserMessage($ret);
			}
			
			$user = $ret;
			$images = $u_handler->GetRecentImages($userID);
			$body = $u_view->ViewUser($user, $currentUser, $images);
			
			if ($currentUser) {
				if ($u_view->GetIdToDelete() != NULL) {
					$userId = $u_view->GetIdToDelete();
					$ret = $u_handler->DeleteUser($userId);
					if (is_numeric($ret)) {
						$u_view->PrintUserMessage($ret);
					} else {
						$l_handler->DoLogOut();
						$nav_view->SetIndex();						
					}
				}
				
				if ($u_view->TriedToEdit()) {
					$newUsername = $u_view->GetUserName();
					$oldpass = $u_view->GetOldPassword();
					$newpass = $u_view->GetNewPassword();
					$confpass = $u_view->GetNewConfPassword();
					
					if (!$validator->IsValidUsername($newUsername) || !$validator->IsValidPassword($newpass)) {		
						$body .= $u_view->PrintUserMessage(\View\UserView::INVALID_USERNAME_PWD);						
					} else if ($nu_view->hashPassword($oldpass) != $currentUser->getPassword()) {
						$body .= $u_view->PrintUserMessage(\View\UserView::INVALID_OLD_PASS);
					} else if ($newpass != $confpass) {
						$body .= $u_view->PrintUserMessage(\View\UserView::INVALID_CONF_PASS);						
					} else {
						$newpass = $nu_view->hashPassword($newpass);
						$editedUser = new \Model\User();
						$editedUser = $editedUser->Create($currentUser->getId(), $newUsername, $newpass);
						
						$ret = $u_handler->EditUser($editedUser);
						if (is_numeric($ret)) {
							$body .= $u_view->PrintUserMessage($ret);
						} else {
							$_SESSION[\Model\LoginHandler::USER_SESSION] = $editedUser;
							$nav_view->Refresh();
						}
					}								
				}				
			}
		return $body;
	}
}
?>