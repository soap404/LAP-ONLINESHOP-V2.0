<?php
require_once('templates/header.php');
require_once('controller/ProductController.php');

$products = ProductController::getAllActive();


?>

<?php if (count($products) > 0): ?>
    <div class="d-flex flex-wrap gap-3">

        <?php foreach ($products as $product): ?>
            <div class="card" style="width: 18rem;">
                <img src="img/<?= $product['img_name'] ?? 'default.png' ?>" class="card-img-top"
                     alt="<?= $product['name'] ?>">
                <div class="card-body">
                    <h5 class="card-title"><?= $product['name'] ?></h5>
                    <?php if ($product['description']): ?>
                        <p class="card-text"><?= $product['description'] ?></p>
                    <?php endif; ?>
                    <p class="card-text">Stock: <?= $product['stock'] ?></p>
                    <p class="card-text">Price: <?= $product['price'] ?>$</p>
                    <a href="#" class="btn btn-primary">Add to Cart</a>
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
