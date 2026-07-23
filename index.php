<?php
require_once('templates/header.php');
require_once('controller/ProductController.php');
require_once('controller/CartController.php');

$products = ProductController::getAllActive();

if (isset($_POST['add_to_cart'])) {
    $id = $_POST['id'];
    $quantity = $_POST['quantity'];
    $result = CartController::store($id, $quantity);
    if ($result === true) {
        header('location: cart.php');
    } else {
        header('location: index.php');
    }
}


?>

<?php if (count($products) > 0): ?>
    <div class="d-flex flex-wrap gap-3">

        <?php foreach ($products as $product): ?>
            <div class="card" style="width: 18rem;">
                <img src="img/<?= $product['img_name'] ?? 'default.png' ?>" class="card-img-top"
                     alt="<?= htmlspecialchars($product['name']) ?>">
                <div class="card-body">
                    <h5 class="card-title"><?= $product['name'] ?></h5>
                    <?php if (htmlspecialchars($product['description'])): ?>
                        <p class="card-text"><?= htmlspecialchars($product['description']) ?></p>
                    <?php endif; ?>
                    <p class="card-text">Stock: <?= htmlspecialchars($product['stock']) ?></p>
                    <p class="card-text">Price: <?= htmlspecialchars($product['price']) ?>$</p>
                    <form method="POST">
                        <input type="hidden" name="id" value="<?= $product['id'] ?>">
                        <select name="quantity" class="form-select" aria-label="Quantity">
                            <?php for ($i = 1; $i <= 30 && $i <= $product['stock']; $i++): ?>
                                <option value="<?= $i ?>">Quantity: <?= $i ?></option>
                            <?php endfor; ?>
                        </select>
                        <button name="add_to_cart" class="btn btn-primary mt-2 ">Add to Cart</button>
                    </form>
                </div>
            </div>
        <?php endforeach; ?>

    </div>
<?php else : ?>
    <div class="alert alert-primary" role="alert">
        Ops! we do not have any product!
    </div>
<?php endif; ?>


<?php require_once('templates/footer.php'); ?>
