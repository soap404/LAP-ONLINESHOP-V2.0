<?php
require_once('controller/AccountController.php');
require_once('requests/UpdateUserRequest.php');
require_once('requests/UpdatePasswordRequest.php');
require_once('requests/CreateAddressRequest.php');
require_once('model/User.php');
require_once('model/Address.php');


class AccountController
{
    private static array $errors = [];

    public static function getUser()
    {
        return $_SESSION['user'];
    }

    public static function updateUser($data): bool|array
    {
        $id = $_SESSION['user']['id'];
        $userModel = new User();

        $user = $userModel->getUserById($id);

        if (!$user) {
            return false;
        }
        self::$errors = UpdateUserRequest::validate($data, $id);
        if (self::$errors) {
            return self::$errors;
        }

        $userModel->updateUser($data, $id);
        $_SESSION['user'] = $data;
        $_SESSION['user']['is_admin'] = $user['is_admin'];
        $_SESSION['user']['id'] = $id;
        return true;
    }

    public static function updatePassword($data): bool|array
    {
        self::$errors = UpdatePasswordRequest::validate($data);
        if (self::$errors) {
            return self::$errors;
        }

        $id = $_SESSION['user']['id'];
        $new_password = $data['new_password'];
        $old_password = $data['old_password'];

        $userModel = new User();
        $user = $userModel->getUserById($id);
        if (!$user) {
            return false;
        }

        if (!password_verify($old_password, $user['password'])) {
            self::$errors[] = 'Password does not match';
        }

        if (self::$errors) {
            return self::$errors;
        }
        $new_password = password_hash($new_password, PASSWORD_DEFAULT);
        $userModel->updateUserPassword($new_password, $id);
        return true;
    }

    public static function getUserAddresses(): bool|array
    {
        $user_id = $_SESSION['user']['id'];
        $userModel = new User();
        $user = $userModel->getUserById($user_id);
        if (!$user) {
            return false;
        }

        $addressModel = new Address();
        return $addressModel->getUserAddresses($user_id);
    }

    public static function createAddress($data): bool|array
    {
        self::$errors = CreateAddressRequest::validate($data);
        if (self::$errors) {
            return self::$errors;
        }

        $user_id = $_SESSION['user']['id'];
        $userModel = new User();
        $user = $userModel->getUserById($user_id);
        if (!$user) {
            return false;
        }

        $addressModel = new Address();
        return $addressModel->createAddress($data, $user_id);

    }
}