<?php

require_once('model/Products.php');
require_once('requests/CreateUpdateProductRequest.php');


class ProductController
{
    private static array $errors = [];

    public static function getAllActive(): array
    {
        $productsModel = new Products();
        return $productsModel->allActive();
    }

    public static function getAll(): array
    {
        $productsModel = new Products();
        return $productsModel->all();
    }

    public static function getById(int $id): array| bool
    {
        $productsModel = new Products();
        return $productsModel->getById($id);
    }

    public static function delete(int $id): bool
    {
        $productsModel = new Products();
        $product = $productsModel->getById($id);
        if ($product) {
            unlink('img/' . $product["img_name"]);

            $productsModel->delete($id);
            return true;
        }
        return false;
    }

    public static function create(array $data, $files): array|bool
    {
        self::$errors = CreateUpdateProductRequest::validate($data, $files);

        if (self::$errors) {
            return self::$errors;
        }

        if (file_exists($files['img']['tmp_name'])) {
            $file_extension = pathinfo($files["img"]["name"], PATHINFO_EXTENSION);

            $img_name = bin2hex(random_bytes(16)) . "." . $file_extension;
            $targetDir = "img/" . $img_name;
            move_uploaded_file($files['img']['tmp_name'], $targetDir);
            $data["img_name"] = $img_name;
        }
        $data['is_active'] = $data["is_active"] ? 1 : 0;

        $productModel = new Products();
        $productModel->create($data);
        return true;

    }

    public static function edit(int $id, array $data, $files): array|bool
    {

        $productModel = new Products();

        $product = $productModel->getById($id);
        if (!$product) {
            return false;
        }

        self::$errors = CreateUpdateProductRequest::validate($data, $files);

        if (self::$errors) {
            return self::$errors;
        }


        if (file_exists($files['img']['tmp_name'])) {
            $file_extension = pathinfo($files["img"]["name"], PATHINFO_EXTENSION);

            $img_name = bin2hex(random_bytes(16)) . "." . $file_extension;
            $targetDir = "img/" . $img_name;
            move_uploaded_file($files['img']['tmp_name'], $targetDir);
            $data["img_name"] = $img_name;

            if(isset($product["img_name"])){
                unlink('img/' . $product["img_name"]);
            }

        }

        if($data["delete_img"]){
            unlink('img/' . $product["img_name"]);
        }
        $data['is_active'] = $data["is_active"] ? 1 : 0;

        $productModel->update($id, $data);
        return true;

    }
}