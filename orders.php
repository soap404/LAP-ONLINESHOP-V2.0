<?php

require_once('templates/header.php');
require_once('middleware/IsUser.php');
require_once('controller/OrderController.php');

if (!IsUser::check()) {
    header('Location: index.php');
    exit();
}

$orders = OrderController::user_index();

?>
    <table class="table">
        <thead>
        <tr>
            <th scope="col">#</th>
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
                    <a class="btn btn-primary" href="order.php?id=<?= $order['order_id'] ?>">Show</a>
                </td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
<?php require_once('templates/footer.php'); ?>