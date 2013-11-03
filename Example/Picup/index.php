<?php
	//Startar PHP-Sessionen.
	include './Model/User/User.php';
	session_start();
	
	// Install
	require_once './Model/InstallHandler.php';
	require_once './Controller/InstallController.php';
	require_once './View/InstallView.php';
	
	// Various
	require_once './Common/PageView.php';
	require_once './Model/Database/Database.php';
	require_once './Model/Database/DBConfig.php';
	require_once './Model/User/User.php';
	require_once './Model/Image/Image.php';
	require_once './Model/Validation.php';
	require_once './View/NavigationView.php';
	require_once './Model/Comment/Comment.php';
	require_once './Model/Image/ImageRating.php';
	require_once './View/PrintErrorMessage.php';
	
	//Login	
	require_once './Model/Login/LoginHandler.php';
	require_once './View/LoginView.php';
	require_once './Controller/LoginController.php';
	
	// NewUser
	require_once './Model/Registration/NewUserHandler.php';
	require_once './View/NewUserView.php';
	require_once './Controller/NewUserController.php';
	
	// User
	require_once './Model/User/UserHandler.php';
	require_once './View/UserView.php';
	require_once './Controller/UserController.php';
	
	// Image
	require_once './Model/Image/ImageHandler.php';
	require_once './View/ImageView.php';
	require_once './Controller/ImageController.php';
	
	// Comment
	require_once './Model/Comment/CommentHandler.php';
	require_once './View/CommentView.php';
	require_once './Controller/CommentController.php';
	
	// Admin
	require_once './Model/AdminHandler.php';
	require_once './View/AdminView.php';
	require_once './Controller/AdminController.php';
	
	// FileUpload	
	require_once './Model/Image/FileUploadHandler.php';
	require_once './View/FileUploadView.php';
	require_once './Controller/FileUploadController.php';
	
	// Rating
	require_once './Model/RatingHandler.php';
	require_once './View/RatingView.php';
	require_once './Controller/RatingController.php';
	
	class MasterController {
		const HEADER = "Picup";
		
		public static function DoControl() {
			
			// Various		
			$errorPrint = new \View\PrintErrorMessage();
			$pageView = new \Common\PageView();			
			$dbConfig = new \Model\DBConfig();
			$database = new \Model\Database($errorPrint);
			$database->Connect($dbConfig);
			$nav_view = new \View\NavigationView();			
			$validator = new \Model\Validation();
			$imageRating = new \Model\ImageRating();
			
			// Install
			$install_handler = new \Model\InstallHandler($database);
			$install_controller = new \Controller\InstallController();
			$install_view = new \View\InstallView();
			
			//Login	
			$l_handler = new \Model\LoginHandler($database);
			$l_view = new \View\LoginView();
			$l_controller = new \Controller\LoginController();
			
			// NewUser
			$nu_handler = new \Model\NewUserHandler($database, $validator);
			$nu_view = new \View\NewUserView();
			$nu_controller = new \Controller\NewUserController();
			
			// User
			$u_handler = new \Model\UserHandler($database, $l_handler, $validator);
			$u_view = new \View\UserView();
			$u_controller = new \Controller\UserController();
			
			// Image
			$i_handler = new \Model\ImageHandler($database, $l_handler, $validator);
			$i_view = new \View\ImageView();
			$i_controller = new \Controller\ImageController();
			
			// Comment
			$com_handler = new \Model\CommentHandler($database, $l_handler, $validator);
			$com_view = new \View\CommentView();
			$com_controller = new \Controller\CommentController();
			
			// Admin
			$a_handler = new \Model\AdminHandler($database);
			$a_view = new \View\AdminView();
			$a_controller = new \Controller\AdminController();
			
			// FileUpload	
			$fu_handler = new \Model\FileUploadHandler($database);
			$fu_view = new \View\FileUploadView();
			$fu_controller = new \Controller\FileUploadController();
			
			// Rating
			$r_handler = new \Model\RatingHandler($database, $l_handler, $validator);
			$r_view = new \View\RatingView();
			$r_controller = new \Controller\RatingController();
			
			if ($nav_view->GetController() == \View\NavigationView::INSTALL) {
				$body = $install_controller->DoInstall($install_handler, $install_view);				
			} else {			
				$registrationForm = $nu_controller->DoNewUserControl($nu_handler, $nu_view, $validator, $l_handler, $nav_view);
				$uploadForm = $fu_controller->DoUploadControl($l_handler, $fu_view, $fu_handler, $validator, $nav_view);
				$ratingForm = $r_controller->DoRatingControll($r_view, $r_handler, $imageRating, $l_handler, $nav_view);
				$commentDiv = $com_controller->DoControll($com_view, $com_handler, $nav_view, $l_handler, $validator);
				
				$body = $l_controller->DoLoginControl($l_handler, $l_view, $registrationForm, $uploadForm, $validator, $nav_view);
				
				if ($nav_view->GetController() == \View\NavigationView::ADMIN) {
					$body .= $a_controller->DoAdminControl($a_view, $a_handler, $nav_view, $i_handler, $l_handler);
				} else if ($nav_view->GetController() == \View\NavigationView::USER) {
					$body .= $u_controller->DoControll($u_view, $u_handler, $nav_view, $validator, $l_handler, $nu_view);
				} else {
					$body .= $i_controller->DoControl($i_view, $i_handler, $fu_handler, $nav_view, $l_handler, $validator, $ratingForm, $commentDiv);		
				}				
			}	
			
			$database->Close();
			return $pageView->GetHTMLPage(self::HEADER, $body);			
		}		
	}
	
	echo MasterController::DoControl();
?>
