<?php

require_once ('requests/RegisterRequest.php');
require_once ('model/User.php');

class AuthController {

    private static array $errors = [];

    public static function register($data) : bool | array {
        self::$errors = RegisterRequest::validate($data);

        if (self::$errors) {
            return self::$errors;
        }

        $userModel = new User();
        $userModel->register($data);
        return true;
    }
}