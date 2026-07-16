<?php
require_once('templates/header.php');
require_once('middleware/IsUser.php');
require_once('controller/CartController.php');
require_once('controller/AccountController.php');
require_once('controller/OrderController.php');


if (!IsUser::check()) {
    header('location: index.php');
    exit();
}

if (isset($_POST['buy'])) {
    $delivery_id = $_POST['delivery_id'];
    $invoice_id = $_POST['invoice_id'];
    $result = OrderController::create($delivery_id, $invoice_id);
    if ($result) {
        header('location: index.php');
    } else {
        header('location: cart.php');
    }
}

$products = CartController::index();

if (!$products) {
    header('location: index.php');
    exit();
}

$addresses = AccountController::getUserAddressesSeparated();
$deliveryAddresses = $addresses['delivery'];
$invoiceAddresses = $addresses['invoice'];

function getCartSum(): float
{
    global $products;

    $sum = 0;
    foreach ($products as $product) {
        $sum += $product['price'] * $product['quantity'];
    }
    return $sum;
}

?>


    <table class="table" xmlns="http://www.w3.org/1999/html">
        <thead>
        <tr>
            <th scope="col">Image</th>
            <th scope="col">Name</th>
            <th scope="col">Price</th>
            <th scope="col">Quantity</th>
        </tr>
        </thead>
        <tbody>

        <?php foreach ($products as $product): ?>
            <tr>
                <td>
                    <img src="img/<?= $product['img_name'] ?? 'default.png' ?>"
                         alt="<?= htmlspecialchars($product['name']) ?>" width="100px" height="100px">
                </td>
                <td><?= htmlspecialchars($product['name']) ?></td>
                <td><?= htmlspecialchars($product['price']) * htmlspecialchars($product['quantity']) ?> $</td>
                <td><?= htmlspecialchars($product['quantity']) ?> </td>
            </tr>
        <?php endforeach; ?>

        </tbody>
    </table>

    <form action="" method="POST">
        <div class="row">
            <div class="mt-2 col-md-6">
                <h2>Delivery Addresses:</h2>
                <?php foreach ($deliveryAddresses as $address) : ?>
                    <div class="card mt-2">
                        <div class="card-body">
                            <input class="form-check-input" type="radio" name="delivery_id"
                                   id="delivery-<?= $address['id'] ?>" value="<?= $address['id'] ?>">
                            <label for="delivery-<?= $address['id'] ?>">
                                <span class="card-title"><?= $address['city'] ?> - <?= $address['zip'] ?></span>
                                <span class="card-text"><?= $address['address_line1'] ?>
                                    <?= $address['address_line2'] ? '- ' . $address['address_line2'] : '' ?></span>
                            </label>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>

            <div class="mt-2 col-md-6">
                <h2>Invoice Addresses:</h2>
                <?php foreach ($invoiceAddresses as $address) : ?>
                    <div class="card mt-2">
                        <div class="card-body">
                            <input class="form-check-input" type="radio" name="invoice_id"
                                   id="invoice-<?= $address['id'] ?>" value="<?= $address['id'] ?>">
                            <label for="invoice-<?= $address['id'] ?>">
                                <span class="card-title"><?= $address['city'] ?> - <?= $address['zip'] ?></span>
                                <span class="card-text"><?= $address['address_line1'] ?>
                                    <?= $address['address_line2'] ? '- ' . $address['address_line2'] : '' ?></span>
                            </label>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>


        <?php if (count($addresses) > 0): ?>
            <button class="btn btn-success mt-2" name="buy">Buy <?= getCartSum() ?> $</button>
        <?php else: ?>
            <div class="alert alert-warning mt-2" role="alert">
                Add Addresses to make a checkout <a href="account.php">Click me</a>
            </div>
        <?php endif; ?>

    </form>

<?php require_once('templates/footer.php'); ?>