<?php
/**
 * Created by PhpStorm.
 * User: haris
 * Date: 2013-11-03
 * Time: 20:44
 */
    class LoginController{
        public function DoLoginControl(LoginView $view){
            $loginForm = $view -> DoLoginBox();

            return $loginForm;
        }
    }