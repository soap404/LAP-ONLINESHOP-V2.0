<?php
require_once('templates/header.php');
require_once('middleware/IsAdmin.php');

if(!IsAdmin::check()){
    header('Location: index.php');
    exit();
}
?>

    <a href="admin_products.php" class="btn btn-primary">Products</a>
    <a href="admin_orders.php" class="btn btn-primary">Orders</a>

<?php require_once('templates/footer.php');?>