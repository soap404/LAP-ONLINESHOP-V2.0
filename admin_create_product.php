<?php
require_once('templates/header.php');
require_once('controller/ProductController.php');
require_once('middleware/IsAdmin.php');

if(!IsAdmin::check()){
    header('Location: index.php');
    exit();
}

$errors = [];

if(isset($_POST['create'])) {
    $result = ProductController::create($_POST, $_FILES);
    if($result === true) {
        header('Location: admin_products.php');
    }else {
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
            <input type="text" class="form-control" id="name" name="name" value="<?= $_POST['name'] ?? '' ?>">
        </div>
        <div class="mb-3">
            <label for="description" class="form-label">Description</label>
            <textarea class="form-control" id="description" name="description"><?= $_POST['description'] ?? '' ?></textarea>
        </div>
        <div class="mb-3">
            <label for="price" class="form-label">Price</label>
            <input type="text" class="form-control" id="price" name="price" value="<?= $_POST['price'] ?? '' ?>">
        </div>
        <div class="mb-3">
            <label for="stock" class="form-label">Stock</label>
            <input type="text" class="form-control" id="stock" name="stock" value="<?= $_POST['stock'] ?? '' ?>">
        </div>
        <div class="mb-3">
            <label for="img" class="form-label">Product Image</label>
            <input class="form-control" type="file" id="img" name="img">
        </div>
        <div class="mb-3">
            <input type="checkbox" class="btn-check" id="is_active" autocomplete="off" name="is_active" checked>
            <label class="btn btn-outline-primary" for="is_active">Active</label>
        </div>
        <button type="submit" class="btn btn-primary" name="create">Create</button>
    </form>

<?php require_once('templates/footer.php');?>