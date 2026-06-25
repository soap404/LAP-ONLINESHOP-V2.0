<?php
require_once('templates/header.php');
require_once('middleware/IsAdmin.php');
require_once('controller/ProductController.php');

if (!IsAdmin::check()) {
    header('Location: index.php');
    exit();
}

if (isset($_POST['delete'])) {
    $id = $_POST['id'];
    ProductController::delete($id);
    header('Location: admin_products.php');
    exit();
}

$products = ProductController::getAll();

?>

    <a href="admin_create_product.php" class="btn btn-primary">Create Product</a>
    <hr>
    <table class="table">
        <thead>
        <tr>
            <th scope="col">#</th>
            <th scope="col">Image</th>
            <th scope="col">Name</th>
            <th scope="col">Description</th>
            <th scope="col">Price</th>
            <th scope="col">Stock</th>
            <th scope="col">Active</th>
            <th scope="col">Actions</th>
        </tr>
        </thead>
        <tbody>

        <?php foreach ($products as $product): ?>
            <tr>
                <th scope="row"><?= htmlspecialchars($product['id']) ?></th>
                <td>
                    <img src="img/<?= $product['img_name'] ?? 'default.png' ?>"
                         alt="<?= htmlspecialchars($product['name']) ?>" width="100px" height="100px">
                </td>
                <td><?= htmlspecialchars($product['name']) ?></td>
                <td><?= htmlspecialchars($product['description']) ?></td>
                <td><?= htmlspecialchars($product['price']) ?> $</td>
                <td><?= htmlspecialchars($product['stock']) ?></td>
                <td>
                    <?php if (htmlspecialchars($product['is_active']) == 1): ?>
                        <span class="badge text-bg-success">Active</span>
                    <?php else: ?>
                        <span class="badge text-bg-danger">Not Active</span>
                    <?php endif; ?>

                </td>
                <td>
                    <div class="d-flex flex-row gap-2">
                        <a href="admin_edit_product.php?id=<?= $product['id'] ?>" class="btn btn-primary">Edit</a>
                        <form action="" method="POST">
                            <input type="hidden" name="id" value="<?= htmlspecialchars($product['id']) ?>">
                            <button type="submit" class="btn btn-danger" name="delete">Delete</button>
                        </form>
                    </div>
                </td>
            </tr>
        <?php endforeach; ?>

        </tbody>
    </table>

<?php require_once('templates/footer.php'); ?>