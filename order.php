<?php require_once('templates/header.php');
require_once('controller/OrderController.php');
require_once('middleware/IsUser.php');

if (!isset($_GET['id'])) {
    header('location: index.php');
    exit();
}

if (!IsUser::check()) {
    header('location: index.php');
    exit();
}

$order_id = $_GET['id'];

$products = OrderController::order_details($order_id);
$order = OrderController::show($order_id);

if (!$order || !$products) {
    header('location: index.php');
    exit();
}

function getOrderSum(): float
{
    global $products;

    $sum = 0;
    foreach ($products as $product) {
        $sum += $product['price'] * $product['quantity'];
    }
    return $sum;
}

?>

<h2>Order Details:</h2>
<table class="table">
    <thead>
    <tr>
        <th scope="col">Date</th>
        <th scope="col">Status</th>
        <th scope="col">Sum</th>
    </tr>
    </thead>
    <tbody>
    <tr>
        <td><?= $order['datetime'] ?></td>
        <td>
            <?= match ((int)$order['status']) {
                1 => '<span class="text-warning">Pending</span>',
                2 => '<span class="text-danger">Cancel</span>',
                default => '<span class="text-success">Done</span>',
            } ?>
        </td>
        <td><?= getOrderSum() ?> $</td>
    </tr>
    </tbody>
</table>

<hr>

<h2>Products Details:</h2>
<table class="table">
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
            <td><?= htmlspecialchars($product['name'] ?? 'Not Found') ?></td>
            <td><?= htmlspecialchars($product['price']) * htmlspecialchars($product['quantity']) ?> $</td>
            <td>
                <?= htmlspecialchars($product['quantity']) ?>
            </td>
        </tr>
    <?php endforeach; ?>

    </tbody>
</table>


<?php require_once('templates/footer.php'); ?>
