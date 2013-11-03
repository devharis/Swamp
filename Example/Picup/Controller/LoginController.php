<?php
namespace Controller;
class LoginController {
	private $uSession = "loggedin";
	private $admin = "Administrator";
	private $location = "location: ";	
	
	/**
	 * Renders the controllpanel which contains the loginform, registrationform and uploadform.
	 * @param $handler LoginHandler
	 * @param $view LoginView
	 * @param $registrationForm HTML 
	 * @param $uploadForm HTML
	 * @param $validate Validation
	 * @param $nav_view NavigationView
	 * @return string HTML
	 * 
	 */
	public function DoLoginControl (\Model\LoginHandler $handler, \View\LoginView $view, $registrationForm, $uploadForm, \Model\Validation $validate, \View\NavigationView $nav_view) {
		$loginForm = \View\LoginView::LOGIN_HEADER;
		if ($view -> TriedToLogOut()) { // Tyckt på utloggningsknappen?
			$view->ForgetUser();
			$handler -> DoLogOut();
			$loginForm .= $view -> PrintUserMessage(\View\LoginView::USER_LOGGED_OUT); 
		} else if ($handler->GetUserSession() === NULL) {
			if ($view->CookieSet()) { // Finns inloggningen sparad?
				$login = $view->GetCookieInformation();
				$handler -> DoLogIn($login[0], $login[1]); // Logga in med de sparade uppgifterna.
			} else if ($view -> TriedToLogIn()) {
				$username =	$validate->StrValNonHTML($view -> GetUserName());
				$password = $view->hashPassword($validate->StrValNonHTML($view -> GetPassword()));
				if ($handler -> DoLogIn($username, $password)) { // Annars loggar vi in med det användaren har skrivit.					
					if ($view -> RemberMe()) { // Vill användaren bli ihågkommen?
						$view -> RememberUser($username, $password); // Spara ner inloggningsuppgifterna i en cookie.
					}
					
					$user = $_SESSION[$this->uSession];					
					if ($user->getRole() === $this->admin) {
						$nav_view->SetAdmin();
					} else {
						$nav_view->Refresh();
					}
				} else {
					$loginForm .= $view -> PrintUserMessage(\View\LoginView::USER_WRONG_PWD);
				}
			}
		}
		
		if ($handler->GetUserSession() != NULL){
			$user = $handler->GetUserSession();		
			$loginForm .= $view -> DoLogoutBox($user);				
			return $view->ReturnAsControlPanel($loginForm, $registrationForm, $uploadForm, TRUE);
		} else {			
			$loginForm .= $view -> DoLoginBox();
			return $view->ReturnAsControlPanel($loginForm, $registrationForm, $uploadForm, FALSE);
		}
		
	}
}
?>