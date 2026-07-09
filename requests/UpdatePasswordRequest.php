<?php

require_once('model/User.php');


class UpdatePasswordRequest
{

    private static array $errors = [];

    public static function validate(array $data): array
    {
        if (empty($data['old_password'])) {
            self::$errors[] = 'Old password is required';
        }
        if (empty($data['new_password'])) {
            self::$errors[] = 'New password is required';
        }

        if (empty(self::$errors)) {
            if ($data['new_password'] === $data['old_password']) {
                self::$errors[] = 'New password and old password should not match';
            }
            if (strlen($data['new_password']) < 8) {
                self::$errors[] = 'New password must be at least 8 characters';
            }
        }


        return self::$errors;
    }

}