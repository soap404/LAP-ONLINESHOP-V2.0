<?php

require_once('model/User.php');


class CreateUpdateProductRequest
{
    private static array $errors = [];

    public static function validate(array $data, $files): array
    {
        if (empty($data['name'])) {
            self::$errors[] = 'Name is required';
        }

        if (empty($data['price'])) {
            self::$errors[] = 'Price is required';
        } else if (!is_numeric($data['price'])) {
            self::$errors[] = 'Price must be a number';
        }

        if (empty($data['stock'])) {
            self::$errors[] = 'Stock is required';
        } else if (!ctype_digit($data['stock'])) {
            self::$errors[] = 'Stock must be a number';
        }

        if ($files["img"]["tmp_name"]) {
            $allowed_image_extension = ['jpg', 'jpeg', 'png'];

            $file_extension = pathinfo($files["img"]["name"], PATHINFO_EXTENSION);

            if (!in_array($file_extension, $allowed_image_extension)) {
                self::$errors[] = 'Image format is not allowed';
            }
        }


        return self::$errors;
    }

}