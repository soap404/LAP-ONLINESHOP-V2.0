<?php
require_once('model/Order.php');
require_once('model/Address.php');
require_once('model/Product.php');
require_once('middleware/IsAdmin.php');
require_once('middleware/IsUser.php');

class OrderController
{
    public static function create($delivery_id, $invoice_id): bool
    {
        if (count($_SESSION['cart']) < 1) {
            return false;
        }
        $user_id = $_SESSION['user']['id'];

        $addressModel = new Address();

        $addresses = $addressModel->getUserAddresses($user_id);
        $is_delivery = false;
        $is_invoice = false;
        foreach ($addresses as $address) {
            if ($address['type'] == '1') {
                $is_delivery = $address['id'] == $delivery_id;
                if ($is_delivery) {
                    break;
                }
            }
        }
        foreach ($addresses as $address) {
            if ($address['type'] == '2') {
                $is_invoice = $address['id'] == $invoice_id;
                if ($is_invoice) {
                    break;
                }
            }
        }

        if (!$is_delivery || !$is_invoice) {
            return false;
        }

        $data['delivery_id'] = $delivery_id;
        $data['invoice_id'] = $invoice_id;
        $data['products'] = [];
        foreach ($_SESSION['cart'] as $id => $quantity) {
            $data['products'][] = [
                'id' => $id,
                'quantity' => $quantity
            ];
        }


        $orderModel = new Order();
        $orderModel->create($data, $user_id);
        $_SESSION['cart'] = [];
        $productModel = new Product();
        foreach ($data['products'] as $product) {
            $product_id = $product['id'];
            $quantity = $product['quantity'];
            $productModel->reduce($product_id, $quantity);
        }
        return true;

    }

    public static function admin_index(): array
    {
        $orderModel = new Order();
        return $orderModel->admin_index();
    }

    public static function user_index(): bool|array
    {
        if (!IsUser::check()) {
            return false;
        }
        $orderModel = new Order();
        return $orderModel->user_index($_SESSION['user']['id']);
    }


    public static function update_status($order_id, $status): void
    {
        if ($status != 2 && $status != 3) {
            return;
        }

        $orderModel = new Order();
        $order = $orderModel->show($order_id);
        if (!$order || $order['status'] != 1) {
            return;
        }

        $orderModel->update_status($order_id, $status);
        // Rechnug Erstellen Wenn Status 2 ist

        // Email senden
    }

    public static function order_details($order_id): bool|array
    {
        $orderModel = new Order();
        $order = $orderModel->show($order_id);
        if (!$order) {
            return false;
        }
        if (!IsAdmin::check() && $order['user_id'] != $_SESSION['user']['id']) {
            return false;
        }

        return $orderModel->order_details($order_id);
    }

    public static function show($order_id): bool|array
    {

        $orderModel = new Order();
        $order = $orderModel->show($order_id);

        if (!$order) {
            return false;
        }
        if (!IsAdmin::check() && $order['user_id'] != $_SESSION['user']['id']) {
            return false;
        }

        return $order;
    }
}