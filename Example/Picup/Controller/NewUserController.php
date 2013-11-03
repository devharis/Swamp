<?php
namespace Controller;
class NewUserController {
	/**
	 * Controlls the flow of the registration-form.
	 * @param $handler UserHandler object.
	 * @param $view UserView object.
	 * @param $validator Validator object.
	 * @param $loginhandler LoginHandler object.
	 * @return string HTML
	 * 
	 */
	public function DoNewUserControl (\Model\NewUserHandler $handler, \View\NewUserView $view, 
										\Model\Validation $validator, \Model\LoginHandler $loginhandler, \View\NavigationView $nav_view) {
											
		$body = \View\NewUserView::NEW_USER_HEADER . $view->DoRegisterBox();
		if ($view->TriedToRegister()) { //Did user press the register-button.
			if (!$validator->IsValidUsername($view->GetUserName()) || !$validator->IsValidPassword($view->GetPassword())) {
				$body .= $view->PrintUserMessage(\View\NewUserView::NEW_USER_PWDINVALID);
			} else if (!$validator->MatchCaptcha($validator->StrValNonHTML($view->GetCaptchaString()))) {
				$body .= $view->PrintUserMessage(\View\NewUserView::NEW_USER_CAPTCHANOMATCH);
			} else if ($view->GetPassword() !== $view->GetConfPassword()) {
				$body .= $view->PrintUserMessage(\View\NewUserView::NEW_USER_PWDNOMATCH);
			} else {	
				$user = new \Model\User(); // Create a new user
				$username = $validator->StrValNonHTML($view->GetUsername());
				$password = $view->hashPassword($validator->StrValNonHTML($view->GetPassword()));
				$user = $user->Create("", $username, $password);	
												
				$ret = $handler->AddUser($user);
				if (is_numeric($ret)) {			
					$body .= $view->PrintUserMessage($ret);								
				} else {												
					$loginhandler->DoLogin($username, $password);								
					$nav_view->Refresh();
				}
					
			}		
								
							
		}		
		return $body;
	}
}
?>