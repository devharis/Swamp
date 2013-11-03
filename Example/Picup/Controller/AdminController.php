<?php
namespace Controller;
class AdminController {
	private $uSession = "loggedin";
	private $objtype = "object";

	/**
	 * Controlls the flow of the administration.
	 * @param $view AdminView
	 * @param $handler AdminHandler
	 * @param $navview NavigationView
	 * @param $imagehandler ImageHandler
	 * @return string HTML
	 * 
	 */
	public function DoAdminControl(\View\AdminView $view, \Model\AdminHandler $handler, 
										\View\NavigationView $navview, \Model\ImageHandler $imagehandler, \Model\LoginHandler $l_handler) {
												
		$user = $l_handler->GetUserSession();
		
		if ($user != NULL && $user->GetRole() == \View\AdminView::ADMIN_ROLE) { //Is the user an Administrator?
			$body = \View\AdminView::ADMIN_HEADER . $view->DoUserTable($handler->GetUsers()) . $view->DoImageTable($imagehandler->GetImages());				
		} else {
			$body = $view->PrintUserMessage(\View\AdminView::NO_ADMIN_ACCESS);
		}
		return $body;
	}
}
?>