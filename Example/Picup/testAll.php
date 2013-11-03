<?php
	//Startar PHP-Sessionen.
	session_start();
	
	require_once 'Controller\LoginController.php';
	require_once 'Model\Login\LoginHandler.php';
	require_once 'View\LoginView.php';
	require_once 'Model\Database\DBConfig.php';
	require_once 'Model\Database\Database.php';
	require_once 'Model\User\UserHandler.php';
	require_once 'Model\AdminHandler.php';
	require_once 'Model\Registration\NewUserHandler.php';
	require_once 'Model\Comment\CommentHandler.php';
	require_once 'Model\Comment\Comment.php';
	require_once 'Model\Image\ImageHandler.php';
	require_once 'Model\Image\Image.php';
	require_once 'Model\Image\ImageRating.php';
	require_once 'Model\RatingHandler.php';
	require_once 'Model\User\UserHandler.php';
    require_once 'View\PrintErrorMessage.php';
    require_once 'Model\Validation.php';

    $dbConfig = new \Model\DBConfig();
    $errorPrint = new \View\PrintErrorMessage();
	$db = new \Model\Database($errorPrint);
	$db->Connect($dbConfig);
    $controller = new \Controller\LoginController();
	$l_handler = new \Model\LoginHandler($db);
	$loginView = new \View\LoginView();
    $validator = new \Model\Validation();
	$u_handler = new \Model\UserHandler($db, $l_handler, $validator);
	$nu_handler = new \Model\NewUserHandler($db, $validator);
	$admin_handler = new \Model\AdminHandler($db);
	$comment_handler = new \Model\CommentHandler($db, $l_handler, $validator);
	$image_handler = new \Model\ImageHandler($db, $l_handler, $validator);
	$rating_handler = new \Model\RatingHandler($db, $l_handler, $validator);
	$user_handler = new \Model\UserHandler($db, $l_handler, $validator);
?>
<!DOCTYPE html>
<html>
	<head>
	<link href="../css/css.css" rel="Stylesheet" type="text/css">
	<title>Testning</title>
	</head>

	<body>	  
	   <?php
	   		echo "<h1>Enhetstester</h1>";
			
	   		//Test av LoginHandler.php
	   		echo "<h2>Test av LoginHandler.php</h2>";
			if ($l_handler->Test($db, $loginView)) {
				echo "<p>LoginHandler-testet lyckades</p>";
			} else {
				echo "<p>LoginHandler-testet misslyckades</p>";
			}
			
			echo "<h2>Test av AdminHandler.php</h2>";
			if ($admin_handler->Test($db, $nu_handler)) {
				echo "<p>AdminHandler-testet lyckades</p>";
			} else {
				echo "<p>AdminHandler-testet misslyckades</p>";
			}
			
			echo "<h2>Test av CommentHandler.php</h2>";
			if ($comment_handler->Test($db)) {
				echo "<p>CommentHandler-testet lyckades";
			} else {
				echo "<p>CommentHandler-testet misslyckades</p>";
			}
			
			echo "<h2>Test av ImageHandler.php</h2>";
			if ($image_handler->Test($db)) {
				echo "<p>ImageHandler-testet lyckades";
			} else {
				echo "<p>ImageHandlet-testet misslyckades";
			}
			
			echo "<h2>Test av NewUserHandler.php</h2>";
			if ($nu_handler->Test()) {
				echo "NewUserHandler-testet lyckades";
			} else {
				echo "NewUserHandler-testet lyckades inte";
			}
			
			echo "<h2>Test av RatingHandler.php</h2>";
			if ($rating_handler->Test($db)) {
				echo "RatingHandler-testet lyckades";
			} else {
				echo "RatingHandler-testet misslyckades";
			}
			
			echo "<h2>Test av UserHandler.php</h2>";
			if ($user_handler->Test($db)) {
				echo "UserHandler-testet lyckades";
			} else {
				echo "UserHandler-testet misslyckades";
			}
			
			echo "<h2>Databas-test</h2>";
			
			if ($db->Test($dbconfig)) {
				echo "Lyckades";
			} else {
				echo "Lyckades inte";
			}
			
			
			
			/*
			//Test av LoginController.php
			echo "<h2>Controller-test</h2>";
			if($controller->Test()) {
				echo "<p>Controller-testet lyckades</p>";
			} else {
				echo "<p>Controller-testet misslyckades</p>";
			}
			
			//Test av LoginView.php
			echo "<h2>View-test</h2>";
			if($view->Test()) {
				echo "<p>View-testet lyckades</p>";
			} else {
				echo "<p>View-testet misslyckades</p>";
			}
			*/
	   ?>	   
	</body>
</html>

