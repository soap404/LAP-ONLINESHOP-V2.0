<?php require_once('templates/header.php');
require_once('controller/cartController.php');


if (isset($_POST['remove'])) {
    $id = $_POST['id'];
    CartController::remove($id);
    header('Location: cart.php');
}

if (isset($_POST['update'])) {
    $id = $_POST['id'];
    $quantity = $_POST['quantity'];
    CartController::store($id, $quantity);
    header('Location: cart.php');
}

$products = CartController::index();

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

<?php if (count($products) > 0): ?>
    <table class="table">
        <thead>
        <tr>
            <th scope="col">Image</th>
            <th scope="col">Name</th>
            <th scope="col">Price</th>
            <th scope="col">Quantity</th>
            <th scope="col">Remove</th>
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
                <td>
                    <form method="POST">
                        <input type="hidden" name="id" value="<?= $product['id'] ?>">
                        <select name="quantity" class="form-select" aria-label="Quantity">
                            <?php for ($i = 1; $i <= 30 && $i <= $product['stock']; $i++): ?>
                                <option value="<?= $i ?>" <?= $i == $product['quantity'] ? 'selected' : '' ?>>
                                    <?= $i ?></option>
                            <?php endfor; ?>
                        </select>
                        <button name="update" class="btn btn-primary mt-2 ">Update</button>
                    </form>
                </td>
                <td>
                    <div class="d-flex flex-row gap-2">
                        <form action="" method="POST">
                            <input type="hidden" name="id" value="<?= htmlspecialchars($product['id']) ?>">
                            <button type="submit" class="btn btn-danger" name="remove">Remove</button>
                        </form>
                    </div>
                </td>
            </tr>
        <?php endforeach; ?>

        </tbody>
    </table>
    <a href="checkout.php" class="btn btn-success">CheckOut <?= getCartSum() ?> $</a>
<?php else: ?>
    <div class="alert alert-warning" role="alert">
        Opss your cart is empty.
    </div>
<?php endif; ?>


<?php require_once('templates/footer.php'); ?>
