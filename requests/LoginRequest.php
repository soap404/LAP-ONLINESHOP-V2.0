<?php

require_once ('model/User.php');


class LoginRequest
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
        }

        return self::$errors;
    }

}