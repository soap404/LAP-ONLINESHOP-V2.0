<?php

require_once ('model/User.php');


class RegisterRequest
{

    private static array $errors = [];
    public static function validate(array $data) : array
    {
        if(empty($data['email'])) {
            self::$errors[] = 'Email is required';
        }else if(!filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
            self::$errors[] = 'Invalid email format';
        }

        if(empty($data['password'])) {
            self::$errors[] = 'Password is required';
        }else if(strlen($data['password']) < 8) {
            self::$errors[] = 'Password must be at least 8 characters';
        }

        if(empty($data['first_name'])) {
            self::$errors[] = 'First name is required';
        }

        if(empty($data['last_name'])) {
            self::$errors[] = 'Last name is required';
        }

        if (!self::$errors) {
            $userModel = new User();
            $user = $userModel->getUserByEmail($data['email']);
            if ($user) {
                self::$errors[] = 'Email already exists';
            }
        }

        return self::$errors;
    }

}