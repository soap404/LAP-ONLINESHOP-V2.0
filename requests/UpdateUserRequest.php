<?php

require_once ('model/User.php');


class UpdateUserRequest
{

    private static array $errors = [];
    public static function validate(array $data, int $id) : array
    {
        if(empty($data['email'])) {
            self::$errors[] = 'Email is required';
        }else if(!filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
            self::$errors[] = 'Invalid email format';
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
            if ($user && $user['id'] != $id) {
                self::$errors[] = 'Email already exists';
            }
        }

        return self::$errors;
    }

}