<?php
require_once("model/Product.php");

class CartController
{

    public static function index(): array
    {
        if (empty($_SESSION['cart'])) {
            return [];
        }
        $ids = array_keys($_SESSION['cart']);
        $productModel = new Product();
        $products = $productModel->getByIds($ids);
        $cartProducts = [];
        foreach ($_SESSION['cart'] as $id => $quantity) {
            $product = CartController::getProductById($products, $id);
            if (!$product || !$product['is_active']) {
                unset($_SESSION['cart'][$id]);
                continue;
            }
            $cartProducts[$id] = $product;

            if ($product['stock'] < $quantity) {
                $_SESSION['cart'][$id] = $product['stock'];
            }

            $cartProducts[$id]['quantity'] = $_SESSION['cart'][$product['id']];
        }
        return $cartProducts;
    }

    public static function store($id, $quantity): bool
    {
        $productModel = new Product();
        $product = $productModel->getById($id);

        if (!$product || !$product['is_active']) {
            return false;
        }
        if (!ctype_digit($quantity)) {
            return false;
        }
        if ($product['stock'] < $quantity) {
            return false;
        }
        $_SESSION['cart'][$id] = $quantity;
        return true;
    }

    public static function remove($id): void
    {
        unset($_SESSION['cart'][$id]);
    }


    private static function getProductById($products, $id)
    {
        foreach ($products as $product) {
            if ($product['id'] == $id) {
                return $product;
            }
        }
        return null;

    }
}