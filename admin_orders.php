<?php

require_once('templates/header.php');
require_once('middleware/IsAdmin.php');
require_once('controller/OrderController.php');

if (!IsAdmin::check()) {
    header('Location: index.php');
    exit();
}

if (isset($_POST['status'])) {
    $status = $_POST['status'];
    $order_id = $_POST['order_id'];
    OrderController::update_status($order_id, $status);
}

$orders = OrderController::admin_index();

?>
    <table class="table">
        <thead>
        <tr>
            <th scope="col">#</th>
            <th scope="col">User Email</th>
            <th scope="col">Date</th>
            <th scope="col">Amount</th>
            <th scope="col">Status</th>
            <th scope="col">Actions</th>
        </tr>
        </thead>
        <tbody>
        <?php foreach ($orders as $order): ?>
            <tr>
                <th scope="row"><?= $order['order_id'] ?></th>
                <td><?= htmlspecialchars($order['user_email'] ?? 'Deleted User') ?></td>
                <td><?= $order['date'] ?></td>
                <td><?= $order['total_price'] ?> $</td>
                <td>
                    <?= match ((int)$order['status']) {
                        1 => '<span class="text-warning">Pending</span>',
                        2 => '<span class="text-danger">Cancel</span>',
                        default => '<span class="text-success">Done</span>',
                    } ?>
                </td>
                <td>
                    <?php if ($order['status'] == 1): ?>
                        <form action="" method="POST">
                            <input type="hidden" value="<?= $order['order_id'] ?>" name="order_id">
                            <button class="btn btn-danger" name="status" value="2">Cancel</button>
                            <button class="btn btn-success" name="status" value="3">Done</button>
                        </form>
                        <br>
                    <?php endif; ?>
                    <a class="btn btn-primary" href="order.php?id=<?= $order['order_id'] ?>">Show</a>
                </td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
<?php require_once('templates/footer.php'); ?>