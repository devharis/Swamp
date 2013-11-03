<?php
/**
 * Created by PhpStorm.
 * User: haris
 * Date: 2013-11-03
 * Time: 19:22
 */
    class LoginView{

        private $loginName = "loginName";
        private $password = "password";
        private $submitButton = "loginButton";
        private $rememberMe = "rememberMe";
        private $logoutButton = "logoutButton";
        private $cookiepass = "password";
        private $cookielogin = "username";
        private $uSession = "loggedIn";
        private $objtype = "object";

        public function DoLoginBox(){
            return
                "<form action='' method='POST' class='loginForm'>
				<label class='label' for=$this->loginName>Username:</label>
				<input class='inputField' type='text' id='log' name=$this->loginName maxlength='40' />
				<label class='label' for=$this->password>Password:</label>
				<input class='inputField password' type='password' name=$this->password />
				<label class='label'>
					<input type='checkbox' id='rememberMe' name=$this->rememberMe />
					Remember me
				</label>
				<input type='submit' name=$this->submitButton value='Login' class='formButton' />
			</form>";
        }
    }