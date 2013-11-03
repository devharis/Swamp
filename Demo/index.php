<?php
/**
 * Created by PhpStorm.
 * User: haris
 * Date: 2013-11-03
 * Time: 17:24
 */
    require_once "PageView.php";
    require_once "LoginView.php";
    require_once "LoginController.php";

    class IndexController {
        public static function DoControl() {

            $loginView = new LoginView();
            $loginController = new LoginController();
            $pageView = new PageView();

            $body = $loginController->DoLoginControl($loginView);
            $body .= "Hello World!";

            return $pageView->GetHTML5Page("Demo", $body);
        }
    }
    echo IndexController::DoControl();