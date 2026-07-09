<?php


class CreateAddressRequest
{
    private static array $errors = [];

    public static function validate(array $data): array
    {
        if (empty($data['city'])) {
            self::$errors[] = 'City is required';
        }
        if (empty($data['address_line1'])) {
            self::$errors[] = 'Address line 1 is required';
        }
        if (empty($data['zip'])) {
            self::$errors[] = 'Zip is required';
        }else if (strlen($data['zip']) != 4) {
            self::$errors[] = 'Zip must be 4 digits';
        }

        return self::$errors;
    }

}