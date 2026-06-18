<?php

require_once ('requests/RegisterRequest.php');
require_once ('requests/LoginRequest.php');
require_once ('model/User.php');

class AuthController {

    private static array $errors = [];

    public static function register($data) : bool | array {
        self::$errors = RegisterRequest::validate($data);

        if (self::$errors) {
            return self::$errors;
        }

        $data['password'] = password_hash($data['password'], PASSWORD_DEFAULT);

        $userModel = new User();
        $userModel->register($data);
        return true;
    }

    public static function login($data) : bool | array {
        self::$errors = LoginRequest::validate($data);

        if (self::$errors) {
            return self::$errors;
        }

        $userModel = new User();
        $user = $userModel->getUserByEmail($data['email']);

        if(!$user) {
            self::$errors[] = 'Email or Password is incorrect';
            return self::$errors;
        }

        if(!password_verify($data['password'], $user['password'])) {
            self::$errors[] = 'Email or Password is incorrect';
            return self::$errors;
        }

        unset($user['password']);
        $_SESSION['user'] = $user;
        return true;
    }
}