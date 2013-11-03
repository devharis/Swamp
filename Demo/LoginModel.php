<?php
/**
 * Created by PhpStorm.
 * User: haris
 * Date: 2013-11-03
 * Time: 18:01
 */
    class LoginModel{

        const USER_SESSION = "LoggedIn";

        public function Authenticate(User $user){

        }

        public function DoLogin(){

        }

        public function DoLogout(){
            $_SESSION[self::USER_SESSION] = 0;
        }


    }