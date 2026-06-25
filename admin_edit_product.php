<?php
require_once('templates/header.php');
require_once('controller/ProductController.php');
require_once('middleware/IsAdmin.php');

if (!IsAdmin::check()) {
    header('Location: index.php');
    exit();
}
if (!isset($_GET['id']) || !is_numeric($_GET['id']) || $_GET['id'] < 1) {
    header('Location: admin_products.php');
    exit();
}
$id = $_GET["id"];

$product = ProductController::getById($id);

if (!$product) {
    header('Location: admin_products.php');
    exit();
}

$errors = [];

if (isset($_POST['edit'])) {
    $result = ProductController::edit($id,$_POST, $_FILES);
    if ($result === true) {
        header('Location: admin_products.php');
    } else {
        $errors = $result;
    }
}

?>


<?php foreach ($errors as $error): ?>
    <div class="alert alert-danger" role="alert">
        <?= $error ?>
    </div>
<?php endforeach; ?>

    <form method="POST" enctype="multipart/form-data">
        <div class="mb-3">
            <label for="name" class="form-label">Name</label>
            <input type="text" class="form-control" id="name" name="name" value="<?= htmlspecialchars($product['name']) ?? '' ?>">
        </div>
        <div class="mb-3">
            <label for="description" class="form-label">Description</label>
            <textarea class="form-control" id="description"
                      name="description"><?= htmlspecialchars($product['description']) ?? '' ?></textarea>
        </div>
        <div class="mb-3">
            <label for="price" class="form-label">Price</label>
            <input type="text" class="form-control" id="price" name="price" value="<?= htmlspecialchars($product['price']) ?? '' ?>">
        </div>
        <div class="mb-3">
            <label for="stock" class="form-label">Stock</label>
            <input type="text" class="form-control" id="stock" name="stock" value="<?= htmlspecialchars($product['stock']) ?? '' ?>">
        </div>
        <div class="mb-3">
            <?php if ($product['img_name']) : ?>
                <img src="img/<?= $product['img_name'] ?>" width="100" height="100">
            <?php endif; ?>
            <label for="img" class="form-label">Product Image</label>
            <input class="form-control" type="file" id="img" name="img">
        </div>
        <?php if ($product['img_name']) : ?>
            <div class="mb-3">
                <input type="checkbox" class="btn-check" id="delete_img" autocomplete="off" name="delete_img">
                <label class="btn btn-outline-danger" for="delete_img">Delete Image</label>
            </div>
        <?php endif; ?>
        <div class="mb-3">
            <input type="checkbox" class="btn-check" id="is_active" autocomplete="off"
                   name="is_active" <?= $product['is_active'] ? 'checked' : '' ?>>
            <label class="btn btn-outline-primary" for="is_active">Active</label>
        </div>
        <button type="submit" class="btn btn-primary" name="edit">Edit</button>
    </form>

<?php require_once('templates/footer.php'); ?>