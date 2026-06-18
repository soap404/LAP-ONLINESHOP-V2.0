<?php

require_once('model/Products.php');


class ProductController {

    public static function getAllActive() : array{
        $productsModel = new Products();
        return $productsModel->allActive();
    }
}